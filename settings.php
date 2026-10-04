<?php
// This file is part of a 108design source-available software product.
//
// Copyright (C) 2026 Andreas Giesen <andreas@108design.com>
//
// Use and modification are permitted only under the Software License included
// with this distribution. Redistribution and circumvention of Pro feature or
// licensing restrictions are prohibited. See LICENSE.md for the full terms.

/**
 * Administration link for h5p Bulk Upload.
 *
 * @package    local_h5pbulkupload
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    See LICENSE.md for the full terms.
 */

defined('MOODLE_INTERNAL') || die();

$ADMIN->add('localplugins', new admin_externalpage(
    'local_h5pbulkupload',
    get_string('pluginname', 'local_h5pbulkupload'),
    new moodle_url('/local/h5pbulkupload/index.php'),
    'local/h5pbulkupload:upload'
));

