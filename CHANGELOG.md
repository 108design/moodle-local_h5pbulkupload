# Changelog

## 1.0.2 - 2026-10-08

- Extend declared compatibility to Moodle 5.3 after native plugin checks.


## 1.0.1 — 2026-10-05

- Add the product logo above the README title at 125 by 125 pixels.
- Clarify current-release licence and availability.
- Plugin functionality and complete licence terms are unchanged.

## 1.0.0 - 2026-10-05

- Stable release for Moodle 4.5–5.2.
- Display name is exactly `h5p Bulk Upload` in English and German.
- Rechecks target-course visibility, native permissions and enabled H5P content type at import time.
- Restricts imports to the current user's draft files and enforces the 100-file limit server-side.
- Serializes bulk imports per course to protect duplicate-name detection.
- Reports H5P validation errors per file before creating content records.
- Adds native import, permission and validation regression tests, including PostgreSQL.

## 0.1.3 - 2026-08-19

- Limits the target-course selector to visible courses.
- Rejects hidden courses during server-side form validation as well.

## 0.1.2 - 2026-08-19

- Explicitly loads Moodle's File API before preparing the multi-file draft area.
- Fixes `Call to undefined function file_get_submitted_draft_itemid()` on the plugin page.

## 0.1.1 - 2026-08-19

- Fixed Moodle 4.5 compatibility when administrators can ignore file quotas.
- Replaced the unavailable `FILE_AREA_MAX_BYTES_UNLIMITED` constant with the supported unlimited value.

## 0.1.0 - 2026-08-19

- Initial Moodle 4.5 release.
- Multi-file drag-and-drop upload into a selected course content bank.
- Per-file validation results and duplicate-name protection.
- Dedicated system capability; administrators retain access by default.
