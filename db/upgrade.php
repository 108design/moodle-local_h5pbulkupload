<?php
// This file is part of a 108design source-available software product.
// Copyright (C) 2026 Andreas Giesen <andreas@108design.com>
// Use and modification are permitted only under the included Software License.
// See LICENSE.md for the full terms.

/**
 * Upgrade entry point for h5p Bulk Upload.
 *
 * @package local_h5pbulkupload
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license See LICENSE.md for the full terms.
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Complete upgrades; this plugin has no schema changes to apply.
 *
 * @param int $oldversion Previously installed version.
 * @return bool
 */
function xmldb_local_h5pbulkupload_upgrade($oldversion) {
    return true;
}
