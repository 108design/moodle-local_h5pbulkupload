<?php
// This file is part of a 108design source-available software product.
//
// Copyright (C) 2026 Andreas Giesen <andreas@108design.com>
//
// Use and modification are permitted only under the Software License included
// with this distribution. Redistribution and circumvention of Pro feature or
// licensing restrictions are prohibited. See LICENSE.md for the full terms.

namespace local_h5pbulkupload\local;

defined('MOODLE_INTERNAL') || die();

/**
 * Imports H5P draft files through Moodle's content bank API.
 *
 * @package    local_h5pbulkupload
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    See LICENSE.md for the full terms.
 */
class importer {
    /** Maximum number of files in one submission. */
    public const MAX_FILES = 100;

    /**
     * Imports files into a course content bank.
     *
     * @param \context_course $context Target course context.
     * @param \stored_file[] $files Draft files to import.
     * @param int $userid Importing user ID.
     * @param bool $skipduplicates Whether matching names should be skipped.
     * @return array[] One result record per file.
     */
    public function import(
        \context_course $context,
        array $files,
        int $userid,
        bool $skipduplicates = true
    ): array {
        global $USER;

        require_capability('local/h5pbulkupload:upload', \context_system::instance());
        if ($userid !== (int)$USER->id) {
            throw new \moodle_exception('errormissingpermissions', 'local_h5pbulkupload');
        }
        if ($error = target_course::get_error($context->instanceid)) {
            throw new \moodle_exception($error, 'local_h5pbulkupload');
        }
        if (!$files || count($files) > self::MAX_FILES) {
            throw new \moodle_exception($files ? 'toomanyfiles' : 'errormissingfiles', 'local_h5pbulkupload', '', self::MAX_FILES);
        }
        $usercontext = \context_user::instance($userid);
        foreach ($files as $file) {
            if (!$file instanceof \stored_file || $file->is_directory()
                    || (int)$file->get_contextid() !== (int)$usercontext->id
                    || $file->get_component() !== 'user' || $file->get_filearea() !== 'draft') {
                throw new \moodle_exception('invaliddraftfile', 'local_h5pbulkupload');
            }
        }

        // Serialize this plugin's imports per course, including duplicate-name checks.
        $factory = \core\lock\lock_config::get_lock_factory('local_h5pbulkupload');
        $lock = $factory->get_lock('course:' . $context->instanceid, 10);
        if (!$lock) {
            throw new \moodle_exception('importbusy', 'local_h5pbulkupload');
        }
        try {
            return $this->import_files($context, $files, $userid, $skipduplicates);
        } finally {
            $lock->release();
        }
    }

    /**
     * Imports an authorised batch while holding the course lock.
     *
     * @param \context_course $context Target context.
     * @param \stored_file[] $files Draft files.
     * @param int $userid Current user ID.
     * @param bool $skipduplicates Skip existing names.
     * @return array[] Per-file results.
     */
    private function import_files(\context_course $context, array $files, int $userid, bool $skipduplicates): array {
        $contentbank = new \core_contentbank\contentbank();
        $results = [];

        foreach ($files as $file) {
            $filename = $file->get_filename();
            $result = [
                'filename' => $filename,
                'status' => 'error',
                'message' => '',
                'contentid' => 0,
            ];

            if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) !== 'h5p') {
                $result['message'] = get_string('invalidfiletype', 'local_h5pbulkupload');
                $results[] = $result;
                continue;
            }

            if ($skipduplicates && $this->has_duplicate($context, $filename)) {
                $result['status'] = 'skipped';
                $result['message'] = get_string('duplicate', 'local_h5pbulkupload');
                $results[] = $result;
                continue;
            }

            try {
                // Validate before creating a record. Core upload remains the final validator.
                // Collect validation messages per file instead of leaking them into the next import.
                $factory = new \core_h5p\factory();
                $valid = \core_h5p\api::is_valid_package($file, !\core_h5p\helper::can_update_library($file), false, $factory);
                $errors = $factory->get_framework()->getMessages('error');
                if (!$valid) {
                    $messages = array_map(static fn($error) => $error->message, $errors);
                    $result['message'] = get_string('invalidpackage', 'local_h5pbulkupload');
                    if ($messages) {
                        $result['message'] .= ' ' . implode(' ', $messages);
                    }
                    $results[] = $result;
                    continue;
                }
                $content = $contentbank->create_content_from_file($context, $userid, $file);
                $result['status'] = 'success';
                $result['contentid'] = $content->get_id();
            } catch (\moodle_exception $exception) {
                $result['message'] = $exception->getMessage();
            } catch (\Throwable $exception) {
                $result['message'] = get_string('importerror', 'local_h5pbulkupload');
                error_log('local_h5pbulkupload: draft file ' . $file->get_id() . ': ' . $exception->getMessage());
            }

            $results[] = $result;
        }

        return $results;
    }

    /**
     * Match exact file names independently of the database collation.
     *
     * @param \context_course $context Target context.
     * @param string $filename File name to compare.
     * @return bool Whether the exact name exists.
     */
    private function has_duplicate(\context_course $context, string $filename): bool {
        global $DB;

        $candidates = $DB->get_records('contentbank_content', [
            'contextid' => $context->id,
            'contenttype' => 'contenttype_h5p',
            'name' => $filename,
        ], '', 'id, name');
        foreach ($candidates as $candidate) {
            if ($candidate->name === $filename) {
                return true;
            }
        }
        return false;
    }
}
