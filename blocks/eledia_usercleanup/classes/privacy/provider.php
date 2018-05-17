<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Privacy Subsystem implementation for block_eledia_usercleanup.
 *
 * @package    block_eledia_usercleanup
 * @license    http://www.gnu.org/copyleft/gpl.eledia_usercleanup GNU GPL v3 or later
 */

namespace block_eledia_usercleanup\privacy;

defined('MOODLE_INTERNAL') || die();

use \core_privacy\local\request\approved_contextlist;
use \core_privacy\local\request\writer;
use \core_privacy\local\request\helper;
use \core_privacy\local\request\deletion_criteria;
use \core_privacy\local\metadata\collection;

/**
 * Privacy Subsystem implementation for block_eledia_usercleanup.
 * @license    http://www.gnu.org/copyleft/gpl.eledia_usercleanup GNU GPL v3 or later
 */
class provider implements
        // This tool stores user data.
        \core_privacy\local\metadata\provider,

        // This tool may provide access to and deletion of user data.
        \core_privacy\local\request\plugin\provider {

    /**
     * Returns information about how block_eledia_usercleanup stores its data.
     *
     * @param   collection     $collection The initialised collection to add items to.
     * @return  collection     A listing of user data stored through this system.
     */
    public static function get_metadata(collection $collection) : collection {

        $cleanuptable = [
            'userid' => 'privacy:metadata:userid',
            'mailedto' => 'privacy:metadata:mailedto',
            'timestamp' => 'privacy:metadata:timestamp',
        ];
        $collection->add_database_table('block_eledia_usercleanup', $cleanuptable, 'pluginname');

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param   int         $userid     The user to search.
     * @return  contextlist   $contextlist  The contextlist containing the list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid) : \core_privacy\local\request\contextlist {

        // This block saves data in its own table without context so just return the user context here.
        $contextlist = new \core_privacy\local\request\contextlist();

        $sql = "SELECT c.id
                  FROM {context} c
                 WHERE c.contextlevel = :contextuser
                   AND c.instanceid = :userid";

        $params = [
            'contextuser' => CONTEXT_USER,
            'userid' => $userid,
        ];

        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param   approved_contextlist    $contextlist    The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        foreach ($contextlist as $context) {
            if (!$context instanceof \context_user) {
                continue;
            }

            $cleanupinfo = $DB->get_record('block_eledia_usercleanup', array('userid' => $context->instanceid));
            if (empty($cleanupinfo)) {
                continue;
            }

            $data = new \stdClass();
            $data->userid = $cleanupinfo->userid;
            $data->mailedto = $cleanupinfo->mailedto;
            $data->timestamp = $cleanupinfo->timestamp;
            writer::with_context($context)->export_data([], $data);
        }
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * Data are delted by user deletion event anyway. No nee for further action here.
     *
     * @param \context $context The context to delete in.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * Data are delted by user deletion event anyway. No nee for further action here.
     *
     * @param approved_contextlist $contextlist A list of contexts approved for deletion.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
    }
}
