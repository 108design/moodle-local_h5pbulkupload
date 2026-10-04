<?php
// This file is part of a 108design source-available software product.
//
// Copyright (C) 2026 Andreas Giesen <andreas@108design.com>
//
// Use and modification are permitted only under the Software License included
// with this distribution. Redistribution and circumvention of Pro feature or
// licensing restrictions are prohibited. See LICENSE.md for the full terms.

namespace local_h5pbulkupload;

use local_h5pbulkupload\local\importer;
use local_h5pbulkupload\local\target_course;

defined('MOODLE_INTERNAL') || die();

/**
 * Native Content bank import and authorisation regressions.
 *
 * @package    local_h5pbulkupload
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    See LICENSE.md for the full terms.
 */
#[\PHPUnit\Framework\Attributes\CoversClass(importer::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(target_course::class)]
final class importer_test extends \advanced_testcase {
    /** Prepare isolated test data. */
    protected function setUp(): void {
        parent::setUp();
        $this->resetAfterTest();
        $this->setAdminUser();
    }

    /**
     * Create an actual draft file from a Moodle fixture or supplied bytes.
     *
     * @param string $name File name.
     * @param string|null $bytes Optional invalid payload.
     * @return \stored_file Draft file.
     */
    private function draft(string $name = 'greeting-card.h5p', ?string $bytes = null): \stored_file {
        global $CFG, $USER;
        require_once($CFG->libdir . '/filelib.php');
        $record = [
            'contextid' => \context_user::instance($USER->id)->id,
            'component' => 'user',
            'filearea' => 'draft',
            'itemid' => file_get_unused_draft_itemid(),
            'filepath' => '/',
            'filename' => $name,
            'userid' => $USER->id,
        ];
        if ($bytes !== null) {
            return get_file_storage()->create_file_from_string($record, $bytes);
        }
        return get_file_storage()->create_file_from_pathname($record, $CFG->dirroot . '/h5p/tests/fixtures/greeting-card.h5p');
    }

    /** Valid imports create native files, author metadata and upload events, but no activities. */
    public function test_native_import(): void {
        global $DB, $USER;
        $course = $this->getDataGenerator()->create_course();
        $context = \context_course::instance($course->id);
        $draft = $this->draft('Greeting.H5P');
        $sink = $this->redirectEvents();
        $results = (new importer())->import($context, [$draft], (int)$USER->id);
        $this->assertSame('success', $results[0]['status']);
        $content = (new \core_contentbank\contentbank())->get_content_from_id($results[0]['contentid']);
        $this->assertSame('Greeting.H5P', $content->get_name());
        $this->assertEquals($context->id, $content->get_content()->contextid);
        $this->assertEquals($USER->id, $content->get_content()->usercreated);
        $this->assertSame($draft->get_contenthash(), $content->get_file()->get_contenthash());
        $this->assertTrue($draft->get_filesize() > 0);
        $this->assertFalse($DB->record_exists('course_modules', ['course' => $course->id]));
        $events = array_filter($sink->get_events(), static fn($event) => $event instanceof \core\event\contentbank_content_uploaded);
        $this->assertCount(1, $events);
        $sink->close();
    }

    /** Duplicate checks apply to the target course and can be deliberately disabled. */
    public function test_duplicate_scope_and_opt_out(): void {
        global $DB, $USER;
        $context = \context_course::instance($this->getDataGenerator()->create_course()->id);
        $othercontext = \context_course::instance($this->getDataGenerator()->create_course()->id);
        $draft = $this->draft();
        $importer = new importer();
        $this->assertSame('success', $importer->import($context, [$draft], (int)$USER->id)[0]['status']);
        $this->assertSame('skipped', $importer->import($context, [$draft], (int)$USER->id)[0]['status']);
        $this->assertSame('success', $importer->import($othercontext, [$draft], (int)$USER->id)[0]['status']);
        $this->assertSame('success', $importer->import($context, [$draft], (int)$USER->id, false)[0]['status']);
        $this->assertSame(2, $DB->count_records('contentbank_content', ['contextid' => $context->id]));
        $this->assertSame(1, $DB->count_records('contentbank_content', ['contextid' => $othercontext->id]));
    }

    /** Case-sensitive names behave identically with MySQL and PostgreSQL collations. */
    public function test_duplicate_name_case(): void {
        global $DB, $USER;
        $context = \context_course::instance($this->getDataGenerator()->create_course()->id);
        $files = [$this->draft('Example.h5p'), $this->draft('example.h5p')];
        $importer = new importer();
        $results = $importer->import($context, $files, (int)$USER->id);
        $this->assertSame(['success', 'success'], array_column($results, 'status'));
        $this->assertSame(2, $DB->count_records('contentbank_content', ['contextid' => $context->id]));
        $this->assertSame(['skipped', 'skipped'], array_column($importer->import($context, $files, (int)$USER->id), 'status'));
    }

    /** A corrupt package and a wrong extension cannot prevent the later valid file. */
    public function test_mixed_batch_and_retry(): void {
        global $DB, $USER, $SESSION;
        $context = \context_course::instance($this->getDataGenerator()->create_course()->id);
        $files = [$this->draft('broken.h5p', 'not a zip'), $this->draft('wrong.txt', 'not H5P'), $this->draft()];
        $importer = new importer();
        $results = $importer->import($context, $files, (int)$USER->id);
        $this->assertSame(['error', 'error', 'success'], array_column($results, 'status'));
        $this->assertNotEmpty($results[0]['message']);
        $this->assertSame(0, $results[0]['contentid']);
        $this->assertSame(1, $DB->count_records('contentbank_content', ['contextid' => $context->id]));
        $this->assertEmpty($SESSION->core_h5p_messages['error'] ?? []);
        $validretry = $this->draft('broken.h5p');
        $this->assertSame('success', $importer->import($context, [$validretry], (int)$USER->id)[0]['status']);
    }

    /** Course selection rejects the site course and hidden courses. */
    public function test_invalid_targets(): void {
        global $USER;
        $this->assertSame('invalidcourse', target_course::get_error(SITEID));
        $this->assertSame('invalidcourse', target_course::get_error(0));
        $course = $this->getDataGenerator()->create_course(['visible' => 0]);
        $context = \context_course::instance($course->id);
        $this->expectException(\moodle_exception::class);
        $this->expectExceptionMessage(get_string('invalidcourse', 'local_h5pbulkupload'));
        (new importer())->import($context, [$this->draft()], (int)$USER->id);
    }

    /** Disabled H5P content type is rejected even when its classes exist. */
    public function test_disabled_content_type(): void {
        global $USER;
        \core\plugininfo\contenttype::enable_plugin('h5p', 0);
        $context = \context_course::instance($this->getDataGenerator()->create_course()->id);
        $this->assertSame('errormissingcontenttype', target_course::get_error($context->instanceid));
        $this->expectException(\moodle_exception::class);
        (new importer())->import($context, [$this->draft()], (int)$USER->id);
    }

    /** The system capability alone cannot grant native course upload permissions. */
    public function test_missing_course_permissions(): void {
        global $USER;
        $course = $this->getDataGenerator()->create_course();
        $context = \context_course::instance($course->id);
        $user = $this->getDataGenerator()->create_user();
        $role = $this->getDataGenerator()->create_role();
        assign_capability('local/h5pbulkupload:upload', CAP_ALLOW, $role, \context_system::instance()->id);
        role_assign($role, $user->id, \context_system::instance()->id);
        $this->setUser($user);
        $this->assertSame('errormissingpermissions', target_course::get_error($course->id));
        $this->expectException(\moodle_exception::class);
        (new importer())->import($context, [$this->draft()], (int)$USER->id);
    }

    /** A teacher still requires explicit system-level access to this tool. */
    public function test_missing_tool_permission(): void {
        global $USER;
        $course = $this->getDataGenerator()->create_course();
        $user = $this->getDataGenerator()->create_user();
        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'editingteacher');
        $this->setUser($user);
        $this->expectException(\required_capability_exception::class);
        (new importer())->import(\context_course::instance($course->id), [$this->draft()], (int)$USER->id);
    }

    /** Delegated teachers cannot introduce H5P libraries without the native capability. */
    public function test_missing_library_permission(): void {
        global $DB, $USER;
        $course = $this->getDataGenerator()->create_course();
        $context = \context_course::instance($course->id);
        $user = $this->getDataGenerator()->create_user();
        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'editingteacher');
        $role = $this->getDataGenerator()->create_role();
        assign_capability('local/h5pbulkupload:upload', CAP_ALLOW, $role, \context_system::instance()->id);
        role_assign($role, $user->id, \context_system::instance()->id);
        $this->setUser($user);
        $this->assertNull(target_course::get_error($course->id));
        $results = (new importer())->import($context, [$this->draft()], (int)$USER->id);
        $this->assertSame('error', $results[0]['status']);
        $this->assertNotEmpty($results[0]['message']);
        $this->assertFalse($DB->record_exists('contentbank_content', ['contextid' => $context->id]));
        $this->assertFalse($DB->record_exists('h5p_libraries', []));
    }

    /** Empty batches fail instead of reporting a misleading success. */
    public function test_empty_batch(): void {
        global $USER;
        $context = \context_course::instance($this->getDataGenerator()->create_course()->id);
        $this->expectException(\moodle_exception::class);
        $this->expectExceptionMessage(get_string('errormissingfiles', 'local_h5pbulkupload'));
        (new importer())->import($context, [], (int)$USER->id);
    }

    /** A disabled H5P library is rejected without leaving a Content bank record. */
    public function test_disabled_library(): void {
        global $DB, $USER;
        $context = \context_course::instance($this->getDataGenerator()->create_course()->id);
        $this->getDataGenerator()->get_plugin_generator('core_h5p')->create_library_record(
            'H5P.GreetingCard', 'Greeting card', 1, 0, 1, '', null, null, null, false
        );
        $results = (new importer())->import($context, [$this->draft()], (int)$USER->id);
        $this->assertSame('error', $results[0]['status']);
        $this->assertNotEmpty($results[0]['message']);
        $this->assertFalse($DB->record_exists('contentbank_content', ['contextid' => $context->id]));
    }

    /** User draft ownership is checked by context, irrespective of the supplied filename. */
    public function test_foreign_draft_rejected(): void {
        global $USER;
        $context = \context_course::instance($this->getDataGenerator()->create_course()->id);
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);
        $foreign = $this->draft();
        $this->setAdminUser();
        $this->expectException(\moodle_exception::class);
        $this->expectExceptionMessage(get_string('invaliddraftfile', 'local_h5pbulkupload'));
        (new importer())->import($context, [$foreign], (int)$USER->id);
    }

    /** Attribution cannot be changed to another user by a direct caller. */
    public function test_wrong_author_rejected(): void {
        $context = \context_course::instance($this->getDataGenerator()->create_course()->id);
        $other = $this->getDataGenerator()->create_user();
        $this->expectException(\moodle_exception::class);
        (new importer())->import($context, [$this->draft()], (int)$other->id);
    }

    /** Oversized batches are rejected before creating any content. */
    public function test_batch_limit(): void {
        global $USER;
        $context = \context_course::instance($this->getDataGenerator()->create_course()->id);
        $files = array_fill(0, 101, $this->draft());
        $this->expectException(\moodle_exception::class);
        $this->expectExceptionMessage(get_string('toomanyfiles', 'local_h5pbulkupload', 100));
        (new importer())->import($context, $files, (int)$USER->id);
    }
}
