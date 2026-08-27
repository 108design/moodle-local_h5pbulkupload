<?php
// This file is part of a 108design source-available software product.
//
// Copyright (C) 2026 Andreas Giesen <andreas@108design.com>
//
// Use and modification are permitted only under the Software License included
// with this distribution. Redistribution and circumvention of Pro feature or
// licensing restrictions are prohibited. See LICENSE.md for the full terms.

namespace local_h5pbulkupload\local;

/**
 * Imports H5P draft files through Moodle's content bank API.
 *
 * @package    local_h5pbulkupload
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    See LICENSE.md for the full terms.
 */
class importer {
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
        global $DB;

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

            if ($skipduplicates && $DB->record_exists('contentbank_content', [
                'contextid' => $context->id,
                'contenttype' => 'contenttype_h5p',
                'name' => $filename,
            ])) {
                $result['status'] = 'skipped';
                $result['message'] = get_string('duplicate', 'local_h5pbulkupload');
                $results[] = $result;
                continue;
            }

            try {
                $content = $contentbank->create_content_from_file($context, $userid, $file);
                $result['status'] = 'success';
                $result['contentid'] = $content->get_id();
            } catch (\Throwable $exception) {
                $result['message'] = $exception->getMessage();
            }

            $results[] = $result;
        }

        return $results;
    }
}
