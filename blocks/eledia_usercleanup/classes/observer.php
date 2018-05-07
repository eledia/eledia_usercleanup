<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public licence as published by
// the Free Software Foundation, either version 3 of the licence, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public licence for more details.
//
// You should have received a copy of the GNU General Public licence
// along with Moodle.  If not, see <http://www.gnu.org/licences/>.

/**
 * @package    block
 * @subpackage eledia_usercleanup
 * @author     Benjamin Wolf <support@eledia.de>
 * @copyright  2018 eLeDia GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Event handler for removing warning entry for user.
 *
 */
class eledia_usercleanup {

    /**
     * Remove notify entry for deleted users.
     *
     * @param \core\event\user_deleted $event
     * @return boolean
     */
    public static function user_deleted(\core\event\user_deleted $event) {
        global $DB;

        $userid = $event->objectid;
        $notify = $DB->get_record('block_eledia_usercleanup', array('userid' => $userid));
        if(!empty($notify)) {
            $DB->delete_records('block_eledia_usercleanup', array('id' => $notify->id));
        }
        return;
    }

}
