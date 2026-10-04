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
 * Shared target-course checks for the form and import service.
 *
 * @package    local_h5pbulkupload
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    See LICENSE.md for the full terms.
 */
class target_course {
    /** Required native course capabilities. */
    public const CAPABILITIES = [
        'moodle/contentbank:access',
        'moodle/contentbank:upload',
        'contenttype/h5p:access',
        'contenttype/h5p:upload',
    ];

    /**
     * Returns a plugin error string identifier, or null if import is allowed.
     *
     * @param int $courseid Target course ID.
     * @return string|null Validation error identifier.
     */
    public static function get_error(int $courseid): ?string {
        global $DB;

        if ($courseid === (int)SITEID || !$DB->record_exists('course', ['id' => $courseid, 'visible' => 1])) {
            return 'invalidcourse';
        }
        $context = \context_course::instance($courseid);
        if (!has_all_capabilities(self::CAPABILITIES, $context)) {
            return 'errormissingpermissions';
        }
        $contentbank = new \core_contentbank\contentbank();
        if (!in_array('h5p', $contentbank->get_enabled_content_types(), true)) {
            return 'errormissingcontenttype';
        }
        $contenttype = new \contenttype_h5p\contenttype($context);
        return $contenttype->can_upload() ? null : 'errormissingcontenttype';
    }
}
