<?php
// This file is part of a 108design source-available software product.
//
// Copyright (C) 2026 Andreas Giesen <andreas@108design.com>
//
// Use and modification are permitted only under the Software License included
// with this distribution. Redistribution and circumvention of Pro feature or
// licensing restrictions are prohibited. See LICENSE.md for the full terms.

namespace local_h5pbulkupload\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Form for importing multiple H5P packages into a course content bank.
 *
 * @package    local_h5pbulkupload
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    See LICENSE.md for the full terms.
 */
class upload_form extends \moodleform {
    /**
     * Defines the upload form.
     */
    public function definition(): void {
        $mform = $this->_form;
        $courseoptions = $this->_customdata['courseoptions'];
        $fileoptions = $this->_customdata['fileoptions'];
        $draftitemid = $this->_customdata['draftitemid'];

        $mform->addElement('html', \html_writer::div(
            get_string('uploadintro', 'local_h5pbulkupload'),
            'alert alert-info'
        ));

        $mform->addElement('autocomplete', 'courseid', get_string('course', 'local_h5pbulkupload'), $courseoptions, [
            'noselectionstring' => get_string('choosedots'),
        ]);
        $mform->addRule('courseid', get_string('required'), 'required', null, 'client');
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement('filemanager', 'packages', get_string('packages', 'local_h5pbulkupload'), null, $fileoptions);
        $mform->addHelpButton('packages', 'packages', 'local_h5pbulkupload');
        $mform->addRule('packages', get_string('required'), 'required', null, 'client');
        $mform->setDefault('packages', $draftitemid);

        $mform->addElement('advcheckbox', 'skipduplicates', get_string('skipduplicates', 'local_h5pbulkupload'));
        $mform->addHelpButton('skipduplicates', 'skipduplicates', 'local_h5pbulkupload');
        $mform->setDefault('skipduplicates', 1);
        $mform->setType('skipduplicates', PARAM_BOOL);

        $this->add_action_buttons(false, get_string('import', 'local_h5pbulkupload'));
    }

    /**
     * Validates course access and the uploaded draft files.
     *
     * @param array $data Submitted form data.
     * @param array $files Submitted files.
     * @return array Validation errors.
     */
    public function validation($data, $files): array {
        global $USER;

        $errors = parent::validation($data, $files);
        $courseid = (int)($data['courseid'] ?? 0);

        if ($error = \local_h5pbulkupload\local\target_course::get_error($courseid)) {
            $errors['courseid'] = get_string($error, 'local_h5pbulkupload');
        }

        $draftitemid = (int)($data['packages'] ?? 0);
        $usercontext = \context_user::instance($USER->id);
        $draftfiles = get_file_storage()->get_area_files(
            $usercontext->id,
            'user',
            'draft',
            $draftitemid,
            'filename',
            false
        );
        if (!$draftfiles) {
            $errors['packages'] = get_string('errormissingfiles', 'local_h5pbulkupload');
        } else if (count($draftfiles) > \local_h5pbulkupload\local\importer::MAX_FILES) {
            $errors['packages'] = get_string('toomanyfiles', 'local_h5pbulkupload', \local_h5pbulkupload\local\importer::MAX_FILES);
        }

        return $errors;
    }
}
