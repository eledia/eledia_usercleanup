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
 * Define capabilitys for usercleanup configuration block.
 *
 * @package    block
 * @subpackage eledia_usercleanup
 * @author     Benjamin Wolf <support@eledia.de>
 * @copyright  2018 eLeDia GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_eledia_usercleanup\task;

defined('MOODLE_INTERNAL') || die();

class cron_task extends \core\task\scheduled_task {

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('cleanuptask', 'block_eledia_usercleanup');
    }

    /**
     * Run forum cron.
     */
    public function execute() {
        global $CFG, $DB;

        $config = get_config('block_eledia_usercleanup');

        if (!isset($config->eledia_informinactiveuserafter)) {
            set_config('eledia_informinactiveuserafter', '120', 'block_eledia_usercleanup');
        }
        if (!isset($config->eledia_deleteinactiveuserafter)) {
            set_config('eledia_deleteinactiveuserafter', '120', 'block_eledia_usercleanup');
        }

        $today = time();

        // Switch to delete timestamp if setting no info is set here.
        if ($config->no_inform_mail) {
            $informexpired = time() - (((int)$config->eledia_deleteinactiveuserafter) * 24 * 60 * 60);
        } else {
            $informexpired = time() - (((int)$config->eledia_informinactiveuserafter) * 24 * 60 * 60);
        }

        // Get inactice user.
        $admins = get_admins();

        $adminlist = array();
        foreach ($admins as $adm) {
            $adminlist[] = $adm->id;
        }
        // We don't want delete the guest user.
        $adminlist[] = $CFG->siteguest;

        list($nochecksql, $nocheckparams) = $DB->get_in_or_equal($adminlist, SQL_PARAMS_NAMED, 'nocheck', false);
        $params = $nocheckparams;
        $params['lastaccess'] = $informexpired;
        $params['firstaccess'] = $informexpired;
        $params['timemodified'] = $informexpired;

        $sql = "deleted = 0
                AND confirmed = 1 AND id $nochecksql
                AND (
                    (lastaccess < :lastaccess AND lastaccess > 0)
                    OR (
                        lastaccess = 0
                        AND firstaccess < :firstaccess
                        AND firstaccess > 0
                    )
                    OR (
                        auth = 'manual'
                        AND firstaccess = 0
                        AND lastaccess = 0
                        AND timemodified > 0
                        AND timemodified < :timemodified
                    )
                )";
        $informuserlist = $DB->get_records_select('user', $sql, $params, '');

        // Call for remove_enrolled_users if setting dont delete enrolled users is set.
        if (!empty($config->ignore_enrolled_users)) {
            $informuserlist = $this->remove_enrolled_users($informuserlist);
        }
        // Call for remove_users_with_role if roles are set settings.
        if (!empty($config->exclude_roles)) {
            $informuserlist = $this->remove_users_with_role($informuserlist, $config->exclude_roles);
        }

        mtrace("... inactive user found: ".count($informuserlist));

        // Call for delete_users when setting no info is set and return.
        if (!empty($config->no_inform_mail)) {
            $this->delete_users($informuserlist);
            // Set lastrun in config table.
            set_config('eledia_usercleanuplastrun', time());
            mtrace("... setlastrunto: ".time());
            return;
        }

        // Get user which already got mailed.
        $informeduser = $DB->get_records('block_eledia_usercleanup');
        if ($informeduser) {
            // Remove user which are active from table.
            foreach ($informeduser as $iuser) {
                if (!array_key_exists($iuser->userid, $informuserlist)) {
                    $DB->delete_records('block_eledia_usercleanup', array('userid' => $iuser->userid));
                    mtrace("... $iuser->userid active again, deletet from user cleanup table");
                }
            }
        } else {
            $informeduser = Array();
            mtrace("... informeduserlist empty");
        }

        // Setting index to user id.
        $informeduser2 = array();
        foreach ($informeduser as $key => $infuser) {
            $informeduser2[$infuser->userid] = $infuser;
        }
        $informeduser = $informeduser2;

        // Mail users.
        mtrace("... user to mail timestamp $informexpired");
        if ($informuserlist) {
            foreach ($informuserlist as $informuser) {
                if (!array_key_exists($informuser->id, $informeduser)) {// No mail when already send one.

                    $site = get_site();
                    $supportuser = \core_user::get_support_user();

                    $data = new \stdClass();
                    $data->userinactivedays = $config->eledia_informinactiveuserafter;
                    $data->eledia_deleteinactiveuserafter = $config->eledia_deleteinactiveuserafter;
                    $data->firstname = $informuser->firstname;
                    $data->lastname = $informuser->lastname;
                    $data->sitename = format_string($site->fullname);
                    $data->admin = generate_email_signoff();
                    $data->link = $CFG->wwwroot .'/index.php';

                    $sm = get_string_manager();
                    $subject = $sm->get_string('email_subject', 'block_eledia_usercleanup', $data, $informuser->lang);
                    $message = $sm->get_string('email_message', 'block_eledia_usercleanup', $data, $informuser->lang);

                    $messagehtml = get_string('email_message', 'block_eledia_usercleanup', $data);
                    $messagehtml = text_to_html($messagehtml, false, false, true);

                    mtrace("... try mail to user $informuser->username and mail: $informuser->email");
                    email_to_user($informuser, $supportuser, $subject, $message, $messagehtml);

                    // Save mailed user.
                    $saveuserinfo = new \stdClass();
                    $saveuserinfo->userid = $informuser->id;
                    $saveuserinfo->mailedto = $informuser->email;
                    $saveuserinfo->timestamp = time();
                    $DB->insert_record('block_eledia_usercleanup', $saveuserinfo);
                }
            }
        }

        // Delete users.
        $deleteexpired = ((int)$config->eledia_deleteinactiveuserafter) * 24 * 60 * 60;
        $params = array($deleteexpired, $today);
        $deleteusers = $DB->get_records_select('block_eledia_usercleanup', "(timestamp + ?) < ?", $params, '', 'userid');

        if ($deleteusers) {
            $deleteuserids = array();
            foreach ($deleteusers as $u) {
                $deleteuserids[] = $u->userid;
            }
            list($delusersql, $deluserparams) = $DB->get_in_or_equal($deleteuserids, SQL_PARAMS_NAMED, 'deluser', true);
            $deleteuserlist = $DB->get_records_select('user', "id $delusersql AND deleted = 0 AND confirmed = 1", $deluserparams);
        }

        if (isset($deleteuserlist)) {
            $this->delete_users($deleteuserlist);
        }

        // Set lastrun in config table.
        set_config('eledia_usercleanuplastrun', time());
        mtrace("... setlastrunto: ".time());
    }

    // Function to remove users with enrolments form userlist(remove_enrolled_users).
    public function remove_enrolled_users($userlist) {
        global $DB;
        foreach ($userlist as $key => $user) {
            $params = array(time(), $user->id);
            $sql = 'SELECT * '
                    . 'FROM {user_enrolments} '
                    . 'WHERE (timeend > ? OR timeend = 0) '
                    . 'AND status = 0 '
                    . 'AND userid = ?';
            $enrolments = $DB->get_records_sql($sql, $params);
            if (count($enrolments) > 0) {
                unset($userlist[$key]);
            }
        }
        return $userlist;
    }

    // Remove users with specific roles in system context(remove_users_with_role).
    public function remove_users_with_role($userlist, $role_list) {
        $role_array = explode(',', $role_list);
        foreach ($userlist as $key => $user) {
            foreach ($role_array as $role) {
                if (user_has_role_assignment($user->id, $role)) {
                    unset($userlist[$key]);
                    continue;
                }
            }
        }
        return $userlist;
    }

    // Function to delete_users.
    public function delete_users($userlist) {
        global $DB;
        foreach ($userlist as $deleteuser) {
            delete_user($deleteuser);
            $DB->delete_records('block_eledia_usercleanup', array('userid' => $deleteuser->id));
            mtrace("... deleting inactive user $deleteuser->username");
        }
    }
}
