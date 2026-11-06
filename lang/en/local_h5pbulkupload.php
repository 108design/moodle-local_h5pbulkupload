<?php
// This file is part of a 108design source-available software product.
//
// Copyright (C) 2026 Andreas Giesen <andreas@108design.com>
//
// Use and modification are permitted only under the Software License included
// with this distribution. Redistribution and circumvention of Pro feature or
// licensing restrictions are prohibited. See LICENSE.md for the full terms.

/**
 * English language strings.
 *
 * @package    local_h5pbulkupload
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    See LICENSE.md for the full terms.
 */

$string['contentbanklink'] = 'Open the content bank';
$string['course'] = 'Target course';
$string['courseoption'] = '{$a->fullname} ({$a->shortname}, ID {$a->id})';
$string['duplicate'] = 'An H5P content item with this file name already exists.';
$string['error'] = 'Error';
$string['errormissingcontenttype'] = 'The H5P content type is not available in this course.';
$string['errormissingfiles'] = 'Add at least one H5P file.';
$string['errormissingpermissions'] = 'You do not have all permissions required to upload H5P content to this course.';
$string['filename'] = 'File';
$string['h5pbulkupload:upload'] = 'Upload multiple H5P files to content banks';
$string['import'] = 'Upload H5P files';
$string['importbusy'] = 'Another bulk import is running in this course. Please try again shortly.';
$string['importerror'] = 'The file could not be imported. Check the server logs or contact your administrator.';
$string['importfailed'] = '{$a->failed} of {$a->total} files could not be imported.';
$string['importpartial'] = '{$a->success} of {$a->total} files were imported; {$a->skipped} were skipped and {$a->failed} failed.';
$string['importsuccess'] = 'All {$a} H5P files were imported successfully.';
$string['invalidcourse'] = 'Select a valid, visible target course.';
$string['invaliddraftfile'] = 'Only files from your own upload draft area can be imported.';
$string['invalidfiletype'] = 'Only .h5p files can be imported.';
$string['invalidpackage'] = 'This file is not a valid, available H5P package.';
$string['nofiles'] = 'No files were submitted.';
$string['open'] = 'Open';
$string['packages'] = 'H5P files';
$string['packages_help'] = 'Drag multiple .h5p files into this field or select them using the file picker.';
$string['pluginname'] = 'h5p Bulk Upload';
$string['privacy:metadata'] = 'The h5p Bulk Upload plugin does not store personal data of its own. Imported content is stored by Moodle\'s content bank.';
$string['result'] = 'Result';
$string['skipduplicates'] = 'Skip files with an existing name';
$string['skipduplicates_help'] = 'If enabled, a file is not imported when an H5P content item with the exact same file name already exists in the target course.';
$string['skipped'] = 'Skipped';
$string['status'] = 'Status';
$string['success'] = 'Imported';
$string['toomanyfiles'] = 'Upload no more than {$a} files at a time.';
$string['uploadintro'] = 'Select a target course and add all H5P files that should be imported into its content bank. No course activities will be created.';

// Contextual administration help.
$string['adminhelp'] = 'Administration help';
$string['adminhelpmore'] = 'More in the help panel';
$string['adminhelptopics'] = 'Help Topics';
$string['adminhelp_overview'] = 'Importing H5P packages';
$string['adminhelp_overview_article'] = '<p>Select the target course, then upload the H5P packages. Importing creates content in the course content bank; it does not automatically create course activities.</p><p>You need the appropriate permissions in the target course. Moodle validates packages and required H5P libraries; unsupported or invalid files may be rejected.</p>';
$string['adminhelp_results'] = 'Duplicates and reviewing results';
$string['adminhelp_results_article'] = '<p>Check the selected duplicate handling before starting. Additional imports do not replace existing content.</p><p>Review the result for each file afterwards. A failed package does not mean that every other file failed too.</p>';

$string['coursesearch'] = 'Search by course name, short name or ID';
$string['course_help'] = 'Enter at least two characters of the course name, short name or ID number, or enter the numeric course ID. Results include only visible courses where you can upload H5P content. Up to 20 matching courses are shown; refine the search if needed.';

$string['adminhelp_overview_help'] = 'Choose an eligible target course and add your H5P packages. The help panel explains the import and its permissions.';
$string['adminhelp_results_help'] = 'Duplicate protection compares exact file names in the target content bank. The help panel explains results and retrying files.';


$string['updateguard_heading'] = 'Update stopped';
$string['updateguard_component'] = 'The package for {$a} was rejected.';
$string['updateguard_hint'] = 'Use an approved package for the installed edition. You can review the operation again.';
$string['updateguard_unchanged'] = 'This request did not replace plugin files.';
$string['updateguard_help'] = 'The installed plugin checks the publisher signature, package contents, permitted update channel and compatibility before replacement. A valid signature does not authorise a different edition. If one package is rejected, the whole batch stops.';
$string['updateguard_signature'] = 'The publisher signature or package contents could not be verified.';
$string['updateguard_policy'] = 'This package does not match the installed edition or permitted package profile.';
$string['updateguard_channel'] = 'Updates through this channel are not enabled for this plugin.';
$string['updateguard_metadata'] = 'The version or dependency information is inconsistent.';
$string['updateguard_dependency'] = 'A required plugin version is missing. Update the dependency together with this plugin or beforehand.';
$string['updateguard_approval'] = 'The packages or installed state have changed since the preview. Review the operation again.';
$string['updateguard_busy'] = 'Another installation is running. Try again after it finishes.';
$string['updateguard_installed'] = 'The installed update protection is incomplete. Restore the files of the last approved installed version.';
$string['updateguard_operation'] = 'This combination of installation actions is not supported. Start a single update operation again.';
$string['updateguard_failed'] = 'The update could not be verified. No installation was started.';
$string['updateguard_back'] = 'Return to plugin overview';
