# H5P bulk upload (`local_h5pbulkupload`)

Bulk import of `.h5p` files into a course content bank for Moodle 4.5.

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
3. Open **Site administration > Plugins > Local plugins > H5P bulk upload**.

Only site administrators have access by default. The system capability
`local/h5pbulkupload:upload` can be granted to a system role when needed. Such a
role also needs Moodle's regular Content bank and H5P upload capabilities in the
target courses.

## Requirements

- Moodle 4.5 or later.
- The core H5P content type must be enabled.
- The server upload and user quota limits still apply.

## License

This is a 108design source-available commercial software license, not an open-source license. See [LICENSE.md](https://github.com/108design/moodle-local_h5pbulkupload/blob/main/LICENSE.md) for the full terms.
