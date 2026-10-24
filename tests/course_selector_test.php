<?php
// This file is part of a 108design source-available software product.
//
// Copyright (C) 2026 Andreas Giesen <andreas@108design.com>
//
// Use and modification are permitted only under the Software License included
// with this distribution. Redistribution and circumvention of Pro feature or
// licensing restrictions are prohibited. See LICENSE.md for the full terms.

namespace local_h5pbulkupload;

use local_h5pbulkupload\external\search_courses;
use local_h5pbulkupload\local\course_selector;
use local_h5pbulkupload\local\target_course;

defined('MOODLE_INTERNAL') || die();

/**
 * Native course search and authority regressions.
 *
 * @package    local_h5pbulkupload
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    See LICENSE.md for the full terms.
 */
#[\PHPUnit\Framework\Attributes\CoversClass(course_selector::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(search_courses::class)]
final class course_selector_test extends \advanced_testcase {
    /** Prepare isolated native courses. */
    protected function setUp(): void {
        parent::setUp();
        $this->resetAfterTest();
        $this->setAdminUser();
    }

    /** No site-wide option list is produced for an initial/short query. */
    public function test_empty_and_short_query_do_not_enumerate_courses(): void {
        $this->getDataGenerator()->create_course(['fullname' => 'Alpha search']);
        $this->assertSame([], course_selector::selected(0));
        foreach (['', ' ', 'A', str_repeat('x', 101)] as $query) {
            $this->assertSame([], course_selector::search($query));
        }
    }

    /** Native text fields and the explicit numeric-ID path find the same authorized course. */
    public function test_text_fields_id_and_selected_preload(): void {
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'Selector full title & example',
            'shortname' => 'SelectorShortName',
            'idnumber' => 'SelectorIdNumber',
        ]);
        foreach (['full title', 'SelectorShortName', 'SelectorIdNumber', (string)$course->id] as $query) {
            $options = course_selector::search($query);
            $this->assertArrayHasKey($course->id, $options);
            $this->assertStringContainsString('title & example', $options[$course->id]);
            $this->assertStringNotContainsString('&amp;', $options[$course->id]);
        }
        $this->assertSame([(int)$course->id], array_keys(course_selector::selected($course->id)));
        $result = search_courses::clean_returnvalue(search_courses::execute_returns(), search_courses::execute('SelectorShortName'));
        $this->assertSame(['id', 'label'], array_keys($result[0]));
        $this->assertEquals($course->id, $result[0]['id']);
    }

    /** Hidden/site courses cannot be discovered via exact ID, including by an administrator. */
    public function test_hidden_and_site_course_are_excluded(): void {
        $hidden = $this->getDataGenerator()->create_course(['fullname' => 'Selector hidden', 'visible' => 0]);
        foreach ([$hidden->id, SITEID] as $id) {
            $this->assertSame([], course_selector::selected($id));
            $this->assertArrayNotHasKey($id, course_selector::search((string)$id));
        }
        $this->assertSame([], course_selector::search('Selector hidden'));
    }

    /** Do not stop at the first native page when target policy excludes candidates. */
    public function test_filtered_native_pages_and_response_limit(): void {
        for ($i = 0; $i < course_selector::LIMIT; $i++) {
            $this->getDataGenerator()->create_course(['fullname' => sprintf('SelectorPage A hidden %02d', $i), 'visible' => 0]);
        }
        $last = $this->getDataGenerator()->create_course(['fullname' => 'SelectorPage Z visible']);
        $this->assertSame([(int)$last->id], array_keys(course_selector::search('SelectorPage')));
        for ($i = 0; $i < course_selector::LIMIT; $i++) {
            $this->getDataGenerator()->create_course(['fullname' => sprintf('SelectorPage Z visible %02d', $i)]);
        }
        $this->assertCount(course_selector::LIMIT, course_selector::search('SelectorPage'));
    }

    /** System access alone must not disclose courses without native target rights. */
    public function test_native_course_permissions_are_required_for_text_and_id(): void {
        $course = $this->getDataGenerator()->create_course(['fullname' => 'Selector protected']);
        $user = $this->getDataGenerator()->create_user();
        $role = create_role('Selector tool access', 'selectortool', '');
        assign_capability('local/h5pbulkupload:upload', CAP_ALLOW, $role, \context_system::instance()->id);
        role_assign($role, $user->id, \context_system::instance()->id);
        $this->setUser($user);
        $this->assertSame([], course_selector::search('Selector protected'));
        $this->assertArrayNotHasKey($course->id, course_selector::search((string)$course->id));
        $this->assertSame([], course_selector::selected($course->id));
        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'editingteacher');
        // Fixture permission changes do not expire Moodle's ten-minute session search cache.
        \cache::make('core', 'coursecat')->purge();
        $this->assertNull(target_course::get_error($course->id));
        $this->assertArrayHasKey($course->id, course_selector::search('Selector protected'));
        $this->assertArrayHasKey($course->id, course_selector::selected($course->id));
        // The same already cached result must stop disclosing a course after authority is removed.
        assign_capability('moodle/contentbank:upload', CAP_PROHIBIT, $role, \context_course::instance($course->id)->id, true);
        accesslib_clear_all_caches_for_unit_testing();
        $this->assertNotNull(target_course::get_error($course->id));
        $this->assertArrayNotHasKey($course->id, course_selector::search('Selector protected'));
        $this->assertSame([], course_selector::selected($course->id));
    }

    /** The AJAX endpoint independently enforces the tool capability. */
    public function test_ajax_requires_tool_access(): void {
        $this->setUser($this->getDataGenerator()->create_user());
        $this->expectException(\required_capability_exception::class);
        search_courses::execute('Selector');
    }

    /** Disabled native H5P upload is also excluded from exact-ID suggestions. */
    public function test_disabled_content_type_is_not_selectable(): void {
        $course = $this->getDataGenerator()->create_course(['fullname' => 'Selector disabled type']);
        \core\plugininfo\contenttype::enable_plugin('h5p', 0);
        $this->assertSame([], course_selector::selected($course->id));
        $this->assertSame([], course_selector::search('Selector disabled type'));
    }
}
