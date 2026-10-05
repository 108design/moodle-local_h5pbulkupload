<p align="center">
  <img src="https://raw.githubusercontent.com/108design/moodle-local_h5pbulkupload/main/docs/branding/logo.svg" alt="h5p Bulk Upload logo" width="443" height="443">
</p>

# h5p Bulk Upload

Upload `.h5p` files in bulk from **one central administration page** and
distribute your batches across different course content banks. Select the
target course for each batch, without opening each course separately.
Supports Moodle 4.5 to 5.2.

## Screenshot

![Select a target course and add H5P files from the central administration page](https://raw.githubusercontent.com/108design/moodle-local_h5pbulkupload/main/docs/screenshots/h5pbu-files.jpg)

All features are permanently free to use. No activation, license key,
subscription or additional account is required.

## Features

- **One central upload tool for different courses:** choose the destination
  course for each batch and import its files directly into that course's content bank.
- Searchable target-course selector with course IDs.
- Multi-file selection and drag and drop, with up to 100 files per batch.
- Optional protection against duplicate file names, enabled by default.
- A result for each file, with links to successfully imported content.
- Moodle's normal H5P package validation and library permissions.

## Installation

1. Install the ZIP through Moodle's plugin installer, or extract it to
   `local/h5pbulkupload` in the Moodle installation.
2. Complete installation under **Site administration > Notifications**.
3. Open **Site administration > Plugins > Local plugins > h5p Bulk Upload**.

## Using the plugin

1. Search for and select the target course.
2. Drag your `.h5p` files into the file area, or use the file picker.
3. Leave **Skip files with an existing name** enabled to avoid importing a file
   whose exact name already exists in that course's H5P content bank.
4. Select **Upload H5P files** and review the result for each file.
5. Open imported content through the result links or the course content bank.
6. To populate another course, select that course and upload the next batch
   from the same central administration page.

The tool imports content-bank items. To use them in course activities, add
those activities separately in Moodle.

## Requirements and permissions

- Moodle 4.5, 5.0, 5.1 or 5.2, with its supported PHP and database versions.
- The core H5P content type must be enabled.
- The target must be a visible course; the site front page is excluded.
- Server upload limits and Moodle user file quotas still apply.

Only site administrators have access by default. The system capability
`local/h5pbulkupload:upload` can be granted to a system role when needed. That
role also needs Moodle's Content bank access/upload and H5P access/upload
permissions in the target courses. Installing new H5P libraries requires
`moodle/h5p:updatelibraries`.

## Import behaviour

Moodle validates each package. Invalid files are reported individually, so
other files in the batch can still be imported. Missing or disabled H5P
libraries follow Moodle's normal rules.

Duplicate detection compares exact, case-sensitive file names within the
target course's H5P content bank. It does not compare the package's internal
title or content. Turning duplicate protection off creates additional content
items; existing content is never overwritten.

## Privacy

Uploaded drafts, imported packages and their user attribution are managed by
Moodle's File and Content bank subsystems. The plugin keeps no separate
personal-data records and sends no files to an external service. Moodle's
native privacy controls apply to the data held by those subsystems.

## Support

Report bugs and feature requests through the
[GitHub issue tracker](https://github.com/108design/moodle-local_h5pbulkupload/issues),
or contact [108design](mailto:andreas@108design.com). Include the plugin and
Moodle versions and the per-file error message, without confidential content.

## License

All functionality in this distribution is designated as Free Features under
the license and remains available without payment.

This is a 108design source-available commercial software license, not an open-source license. See [LICENSE.md](https://github.com/108design/moodle-local_h5pbulkupload/blob/main/LICENSE.md) for the full terms.
