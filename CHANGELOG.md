# Changelog

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
