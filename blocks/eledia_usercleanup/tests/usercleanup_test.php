<?php

// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Usercleanup Testcase.
 *
 * @package    block_eledia_usercleanup
 * @category   test
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

defined('MOODLE_INTERNAL') || die();

class block_eledia_usercleanup_testcase extends advanced_testcase {
    public function test_generator() {
        global $DB, $CFG;
        require_once("$CFG->libdir/cronlib.php");

        $this->resetAfterTest(true);
        $generator = $this->getDataGenerator();

        // Set settings.
        $DB->set_field('block', 'visible', 1, array('name' => 'eledia_usercleanup'));

        set_config('eledia_informinactiveuserafter', 1, 'block_eledia_usercleanup');
        set_config('eledia_deleteinactiveuserafter', 1, 'block_eledia_usercleanup');
        set_config('ignore_enrolled_users', 1, 'block_eledia_usercleanup');
        set_config('exclude_roles', '1', 'block_eledia_usercleanup');

        // Build up Testdata.
        $user1 = $generator->create_user(array('lastaccess' => 1));// Should be deleted.
        $user2 = $generator->create_user(array('lastaccess' => 1));// Should be informed.
        $course = $generator->create_course();
        $user3 = $generator->create_and_enrol($course, 'student', array('lastaccess' => 1));// Should let be because of enrolment.
        $user4 = $generator->create_user(array('lastaccess' => (1)));// Should let be because role.
        $generator->role_assign(1, $user4->id);
        $user5 = $generator->create_user(array('lastaccess' => (time()-(60*60*20))));// Should let be because time.

        $task = new \block_eledia_usercleanup\task\cron_task();
        $task->execute();

        // We dont wont to wait a day to deletion. So change the timestamp of informed here.
        $DB->set_field('block_eledia_usercleanup', 'timestamp', 0, array('userid' => $user1->id));
        $task->execute();

        //Check outcome.
        $user1_record = $DB->get_record('user', array('id' => $user1->id));
        $user2_record = $DB->get_record('user', array('id' => $user2->id));
        $user3_record = $DB->get_record('user', array('id' => $user3->id));
        $user4_record = $DB->get_record('user', array('id' => $user4->id));
        $user5_record = $DB->get_record('user', array('id' => $user5->id));

        $this->assertEquals(1, $user1_record->deleted);
        $this->assertEquals(0, $user2_record->deleted);
        // Informed check.
        $informed_record2 = $DB->get_record('block_eledia_usercleanup', array('userid' => $user2->id));
        $this->assertEquals($user2->email, $informed_record2->mailedto);
        $this->assertEquals(0, $user3_record->deleted);
        $this->assertEquals(0, $user4_record->deleted);
        $this->assertEquals(0, $user5_record->deleted);

        // Testcase deletion without warning.
        set_config('no_inform_mail', '1', 'block_eledia_usercleanup');
        $user6 = $generator->create_user(array('lastaccess' => (time()-(60*60*24*4))));// Should be deleted.
        $task->execute();
        $user6_record = $DB->get_record('user', array('id' => $user6->id));
        $this->assertEquals(1, $user6_record->deleted);
    }
}
