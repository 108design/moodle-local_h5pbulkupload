<?php
// This file is part of a 108design source-available software product.
//
// Copyright (C) 2026 Andreas Giesen <andreas@108design.com>
//
// Use and modification are permitted only under the Software License included
// with this distribution. Redistribution and circumvention of Pro feature or
// licensing restrictions are prohibited. See LICENSE.md for the full terms.

/**
 * AJAX function registration.
 *
 * @package    local_h5pbulkupload
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    See LICENSE.md for the full terms.
 */
defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_h5pbulkupload_search_courses' => [
        'classname' => 'local_h5pbulkupload\\external\\search_courses',
        'description' => 'Search courses eligible for H5P bulk upload.',
        'type' => 'read',
        'ajax' => true,
        'capabilities' => 'local/h5pbulkupload:upload',
    ],
];
