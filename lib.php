<?php
defined('MOODLE_INTERNAL') || die();

/** Contextual help for the explicitly registered administration pages only. */
function local_h5pbulkupload_render_navbar_output(\renderer_base $renderer): string {
    return \local_h5pbulkupload\local\admin_help::navbar($renderer);
}
