<?php
// This file is part of a 108design source-available software product.
//
// Copyright (C) 2026 Andreas Giesen <andreas@108design.com>
//
// Use and modification are permitted only under the Software License included
// with this distribution. Redistribution and circumvention of Pro feature or
// licensing restrictions are prohibited. See LICENSE.md for the full terms.

/**
 * Bulk H5P upload page.
 *
 * @package    local_h5pbulkupload
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    See LICENSE.md for the full terms.
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/filelib.php');

require_login();

$systemcontext = context_system::instance();
require_capability('local/h5pbulkupload:upload', $systemcontext);

$PAGE->set_context($systemcontext);
$PAGE->set_url(new moodle_url('/local/h5pbulkupload/index.php'));
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_h5pbulkupload'));
$PAGE->set_heading(get_string('pluginname', 'local_h5pbulkupload'));

$courseoptions = [];
foreach (get_courses('all', 'c.fullname ASC', 'c.id,c.fullname,c.shortname,c.visible') as $course) {
    if ((int)$course->id === (int)SITEID || !$course->visible) {
        continue;
    }
    $coursecontext = context_course::instance($course->id);
    if (\local_h5pbulkupload\local\target_course::get_error($course->id)) {
        continue;
    }
    $labeldata = (object)[
        'fullname' => format_string($course->fullname, true, ['context' => $coursecontext]),
        'shortname' => format_string($course->shortname, true, ['context' => $coursecontext]),
        'id' => $course->id,
    ];
    $courseoptions[$course->id] = get_string('courseoption', 'local_h5pbulkupload', $labeldata);
}

$maxbytes = $CFG->userquota;
$maxareabytes = $CFG->userquota;
if (has_capability('moodle/user:ignoreuserquota', $systemcontext)) {
    $maxbytes = USER_CAN_IGNORE_FILE_SIZE_LIMITS;
    $maxareabytes = -1;
}
$fileoptions = [
    'subdirs' => 0,
    'maxbytes' => $maxbytes,
    'maxfiles' => \local_h5pbulkupload\local\importer::MAX_FILES,
    'accepted_types' => ['.h5p'],
    'areamaxbytes' => $maxareabytes,
];
$draftitemid = file_get_submitted_draft_itemid('packages');
$mform = new \local_h5pbulkupload\form\upload_form(null, [
    'courseoptions' => $courseoptions,
    'fileoptions' => $fileoptions,
    'draftitemid' => $draftitemid,
]);

$selectedcourseid = optional_param('courseid', 0, PARAM_INT);
if ($selectedcourseid) {
    $mform->set_data((object)['courseid' => $selectedcourseid]);
}

if ($data = $mform->get_data()) {
    require_sesskey();
    \core_php_time_limit::raise(0);

    $course = get_course($data->courseid);
    $coursecontext = context_course::instance($course->id);
    $usercontext = context_user::instance($USER->id);
    $files = get_file_storage()->get_area_files(
        $usercontext->id,
        'user',
        'draft',
        $data->packages,
        'filename',
        false
    );

    $importer = new \local_h5pbulkupload\local\importer();
    $results = $importer->import($coursecontext, $files, $USER->id, !empty($data->skipduplicates));

    $SESSION->local_h5pbulkupload_result = [
        'courseid' => (int)$course->id,
        'coursename' => format_string($course->fullname, true, ['context' => $coursecontext]),
        'results' => $results,
    ];
    redirect(new moodle_url('/local/h5pbulkupload/index.php', [
        'courseid' => $course->id,
        'result' => 1,
    ]));
}

$resultdata = null;
if (optional_param('result', 0, PARAM_BOOL) && !empty($SESSION->local_h5pbulkupload_result)) {
    $resultdata = $SESSION->local_h5pbulkupload_result;
    unset($SESSION->local_h5pbulkupload_result);
}

echo $OUTPUT->header();

if ($resultdata) {
    $counts = ['success' => 0, 'skipped' => 0, 'error' => 0];
    foreach ($resultdata['results'] as $result) {
        $counts[$result['status']]++;
    }
    $total = count($resultdata['results']);
    if ($counts['success'] === $total) {
        echo $OUTPUT->notification(
            get_string('importsuccess', 'local_h5pbulkupload', $total),
            \core\output\notification::NOTIFY_SUCCESS
        );
    } else {
        $summary = (object)[
            'success' => $counts['success'],
            'skipped' => $counts['skipped'],
            'failed' => $counts['error'],
            'total' => $total,
        ];
        echo $OUTPUT->notification(
            get_string('importpartial', 'local_h5pbulkupload', $summary),
            $counts['error'] ? \core\output\notification::NOTIFY_WARNING : \core\output\notification::NOTIFY_INFO
        );
    }

    $table = new html_table();
    $table->head = [
        get_string('filename', 'local_h5pbulkupload'),
        get_string('status', 'local_h5pbulkupload'),
        get_string('result', 'local_h5pbulkupload'),
    ];
    foreach ($resultdata['results'] as $result) {
        if ($result['status'] === 'success') {
            $status = get_string('success', 'local_h5pbulkupload');
            $viewurl = new moodle_url('/contentbank/view.php', [
                'id' => $result['contentid'],
                'contextid' => context_course::instance($resultdata['courseid'])->id,
            ]);
            $detail = html_writer::link($viewurl, get_string('open', 'local_h5pbulkupload'));
        } else if ($result['status'] === 'skipped') {
            $status = get_string('skipped', 'local_h5pbulkupload');
            $detail = s($result['message']);
        } else {
            $status = get_string('error', 'local_h5pbulkupload');
            $detail = s($result['message']);
        }
        $table->data[] = [s($result['filename']), $status, $detail];
    }
    echo html_writer::table($table);

    $contentbankurl = new moodle_url('/contentbank/index.php', [
        'contextid' => context_course::instance($resultdata['courseid'])->id,
    ]);
    echo $OUTPUT->single_button($contentbankurl, get_string('contentbanklink', 'local_h5pbulkupload'), 'get');
}

$mform->display();
echo $OUTPUT->footer();
