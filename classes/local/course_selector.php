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
 * Capability-filtered course lookup for the native autocomplete.
 *
 * @package    local_h5pbulkupload
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    See LICENSE.md for the full terms.
 */
final class course_selector {
    /** Maximum number of options returned to the browser. */
    public const LIMIT = 20;

    /**
     * Return only selected, currently eligible options for initial form rendering.
     *
     * @param int $courseid Selected course ID.
     * @return array<int,string> Plain-text labels indexed by ID.
     */
    public static function selected(int $courseid): array {
        global $DB;
        require_capability('local/h5pbulkupload:upload', \context_system::instance());
        if (target_course::get_error($courseid)) {
            return [];
        }
        $course = $DB->get_record('course', ['id' => $courseid], 'id,fullname,shortname', MUST_EXIST);
        return [$courseid => self::label($course)];
    }

    /**
     * Search on demand, retaining the import service's target checks.
     *
     * @param string $query Course name, short name, idnumber or numeric ID.
     * @return array<int,string> Plain-text labels indexed by ID.
     */
    public static function search(string $query): array {
        require_capability('local/h5pbulkupload:upload', \context_system::instance());
        $query = trim($query);
        if ($query === '' || \core_text::strlen($query) > 100) {
            return [];
        }
        $options = ctype_digit($query) ? self::selected((int)$query) : [];
        if (\core_text::strlen($query) < 2) {
            return $options;
        }
        $offset = 0;
        do {
            // Native search owns visibility/capability filtering and its course-search cache.
            $courses = \core_course_category::search_courses(['search' => $query], [
                'offset' => $offset,
                'limit' => self::LIMIT,
                'sort' => ['fullname' => 1, 'id' => 1],
            ], target_course::CAPABILITIES);
            foreach ($courses as $course) {
                $id = (int)$course->id;
                if (!isset($options[$id]) && !target_course::get_error($id)) {
                    $options[$id] = self::label($course);
                    if (count($options) === self::LIMIT) {
                        return $options;
                    }
                }
            }
            $offset += self::LIMIT;
            // Continue past filtered candidates, rather than losing later eligible courses.
        } while (count($courses) === self::LIMIT);
        return $options;
    }

    /**
     * Format a plain-text label; the form/AMD renderer owns HTML escaping.
     *
     * @param object $course Course record or native course-list element.
     * @return string Plain-text label.
     */
    private static function label(object $course): string {
        $context = \context_course::instance($course->id);
        $data = (object)[
            'fullname' => html_to_text(format_string($course->fullname, true, ['context' => $context]), 0, false),
            'shortname' => html_to_text(format_string($course->shortname, true, ['context' => $context]), 0, false),
            'id' => (int)$course->id,
        ];
        return get_string('courseoption', 'local_h5pbulkupload', $data);
    }
}
