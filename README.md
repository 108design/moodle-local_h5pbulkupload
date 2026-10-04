# h5p Bulk Upload (`local_h5pbulkupload`)

Bulk import of `.h5p` files into a course content bank for Moodle 4.5â€“5.2.

All features are permanently free to use. No activation, license key,
subscription or additional account is required.

## Features

- Searchable target-course selector with course IDs.
- Moodle file manager with multi-file drag and drop (up to 100 files).
- Imports through Moodle's Content bank and H5P validation APIs.
- Optional protection against duplicate file names, enabled by default.
- Per-file result list with links to successfully imported content.
- Does not create course activities.

## Installation

1. Extract the package to `local/h5pbulkupload` in the Moodle installation.
2. Complete the Moodle plugin upgrade as an administrator.
3. Open **Site administration > Plugins > Local plugins > h5p Bulk Upload**.

Only site administrators have access by default. The system capability
`local/h5pbulkupload:upload` can be granted to a system role when needed. Such a
role also needs Moodle's regular Content bank and H5P upload capabilities in the
target courses.

## Requirements

- Moodle 4.5, 5.0, 5.1 or 5.2, with its supported PHP and database versions.
- The core H5P content type must be enabled.
- The server upload and user quota limits still apply.

## Import behaviour

Each file is validated by Moodle. Invalid packages are reported individually;
other files in the batch can still be imported. Missing or disabled H5P libraries
follow Moodle's native rules. Installing new H5P libraries requires the importing
user's `moodle/h5p:updatelibraries` capability.

The target must be a visible course, excluding the site front page. Imports from
this plugin are serialized per target course to protect the duplicate-name check.
Duplicate detection uses the stored file name in that course, not the package's
internal title or content. Turning it off creates additional content items and
never overwrites existing content.

The plugin has no database tables or database-specific SQL. It uses Moodle's
portable database, file, lock and Content bank APIs, including on PostgreSQL.
It has no background tasks, external service calls, activation keys or additional
accounts. Uploaded drafts and imported files are managed by Moodle's core File
and Content bank subsystems; the plugin keeps no separate personal-data records.

For support, contact [108design](mailto:andreas@108design.com). Include the plugin
and Moodle versions and the per-file error, without sharing confidential content.

## License

All functionality in this distribution is designated as Free Features under
the license and remains available without payment.

This is a 108design source-available commercial software license, not an open-source license. See [LICENSE.md](https://github.com/108design/moodle-local_h5pbulkupload/blob/main/LICENSE.md) for the full terms.
