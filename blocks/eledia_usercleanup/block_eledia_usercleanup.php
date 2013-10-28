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
 * Block Definition. The Block is used to configure the plugin and defines the cron.
 *
 * @package    block
 * @subpackage eledia_usercleanup
 * @author     Benjamin Wolf <support@eledia.de>
 * @copyright  2013 eLeDia GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_eledia_usercleanup extends block_base {

    function init() {
        $this->title   = get_string('title', 'block_eledia_usercleanup');
        $this->version = 2012021400;// Format yyyymmddvv.
        $this->cron = 1;
    }

    function applicable_formats() {
        return array('site'=>true);
    }

    function get_content() {
        global $USER, $CFG, $COURSE;
        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content =  new object();
        $this->content->text = '';
        $this->content->footer = '';

        if (has_capability('moodle/site:config', CONTEXT_SYSTEM::instance())) {

            $this->content->text .= '<ul>';
            $this->content->text .= '<li>';
            $this->content->text .= '<a href="'.$CFG->wwwroot.'/blocks/eledia_usercleanup/config_usercleanup.php" >';
            $this->content->text .= get_string('el_header', 'block_eledia_usercleanup');
            $this->content->text .= '</a>';
            $this->content->text .= '</li>';
            $this->content->text .= '</ul>';
        }
        return $this->content;
    }

    function cron() {
        global $CFG, $DB;
        error_reporting(E_ALL);

        if(!isset($CFG->eledia_cleanup_active)){
            set_config('eledia_cleanup_active', '0');
        }
        if ($CFG->eledia_cleanup_active) {
            if (!isset($CFG->eledia_deleteinactiveuserinterval)) {
                set_config('eledia_deleteinactiveuserinterval', '7');
            }
            if (!isset($CFG->eledia_informinactiveuserafter)) {
                set_config('eledia_informinactiveuserafter', '120');
            }
            if (!isset($CFG->eledia_deleteinactiveuserafter)) {
                set_config('eledia_deleteinactiveuserafter', '120');
            }

            if (isset($CFG->eledia_usercleanuplastrun)) {// Check if the script was called befor & set timediff between now and last run.
                $lastrundiff = time()-$CFG->eledia_usercleanuplastrun;
                echo"\nlast usercleanup plugin was ".date('H:i:s d.m.Y', $CFG->eledia_usercleanuplastrun);
            } else {
                $lastrundiff = ((int)($CFG->eledia_deleteinactiveuserinterval)*24*60*60)+1;
                echo"\nfirst run of usercleanup plugin";

            }
            $today = time();

            if ($lastrundiff > ((int)($CFG->eledia_deleteinactiveuserinterval)*24*60*60)) {// Check if interval is over.
                $informexpired = time() - (((int)$CFG->eledia_informinactiveuserafter)*24*60*60);

                // Get inactice user.
                $not_to_check_user = $CFG->siteguest;
                $admins = get_admins();

                foreach ($admins as $adm) {
                    $not_to_check_user .= ', '.$adm->id;
                }
                $sql = "deleted = 0
                        AND confirmed = 1 AND id NOT IN($not_to_check_user)
                        AND ((lastaccess < $informexpired AND lastaccess > 0)
                            OR (lastaccess = 0 AND firstaccess < $informexpired AND firstaccess > 0)
                            OR (auth = 'manual' AND firstaccess = 0 AND lastaccess = 0  AND timemodified > 0 AND timemodified < $informexpired))";
                $informuserlist = $DB->get_records_select('user', $sql, null,'','id, email, lang, firstname, lastname, currentlogin, username, lastaccess');
                echo "\ninactive user found: ".count($informuserlist);

                // Get user which already get mails.
                $informeduser = $DB->get_records('block_eledia_usercleanup');
                if ($informeduser) {
                    // Remove user which are active from table.
                    foreach ($informeduser as $iuser) {
                        if(!array_key_exists($iuser->user, $informuserlist)){
                            $DB->delete_records('block_eledia_usercleanup', array('user' => $iuser->user));
                            echo "\n $iuser->user active again, deletet from user cleanup table";
                        }
                    }
                } else {
                    $informeduser = Array();
                    echo"\ninformuserlist leer";
                }

                // Setting index to user id.
                $informeduser2 = array();
                foreach ($informeduser as $key => $infuser) {
                    $informeduser2[$infuser->user] = $infuser;
                }
                $informeduser = $informeduser2;

                // Mail users.
                echo "\nuser to mail timestamp $informexpired";
                if ($informuserlist) {
                    foreach ($informuserlist as $informuser) {
                        if (!array_key_exists($informuser->id, $informeduser)) {// No mail when already send one.

//                            $user =new object();
//                            $user->lang        = $informuser->lang;
//                            $user->email        = $informuser->email;
//                            $user->mailformat = 1;  // Always send HTML version as well.

                            $site = get_site();
                            $supportuser = core_user::get_support_user();

                            $data = new object();
                            $data->userinactivedays = $CFG->eledia_informinactiveuserafter;
                            $data->eledia_deleteinactiveuserafter = $CFG->eledia_deleteinactiveuserafter;
                            $data->firstname = $informuser->firstname;
                            $data->lastname = $informuser->lastname;
                            $data->sitename = format_string($site->fullname);
                            $data->admin = generate_email_signoff();
                            $data->link = $CFG->wwwroot .'/index.php';

                            $subject = get_string('email_subject', 'block_eledia_usercleanup', $data);

                            $message     = get_string('email_message', 'block_eledia_usercleanup', $data);
                            $messagehtml = text_to_html(get_string('email_message', 'block_eledia_usercleanup', $data), false, false, true);

                            echo "\ntry mail to user $informuser->username and mail: $informuser->email";
                            email_to_user($informuser, $supportuser, $subject, $message, $messagehtml);

                            // Save mailed user.
                            $saveuserinfo = new object();
                            $saveuserinfo->user = $informuser->id;
                            $saveuserinfo->mailedto = $informuser->email;
                            $saveuserinfo->timestamp = time();
                            $DB->insert_record('block_eledia_usercleanup', $saveuserinfo);
                        }
                    }
                }

               // Delete users.
               $deleteexpired = ((int)$CFG->eledia_deleteinactiveuserafter)*24*60*60;
               $deleteuserids = $DB->get_records_select('block_eledia_usercleanup', "(timestamp + $deleteexpired) < $today AND user > 2", null,'','user');

               if ($deleteuserids) {
                    $deleteuserstring = false;
                    foreach ($deleteuserids as $u) {
                        if(!$deleteuserstring){
                            $deleteuserstring = "($u->user";
                        }else{
                            $deleteuserstring .= ", $u->user";
                        }
                    }
                    $deleteuserstring .= ')';

                    $deleteuserlist = $DB->get_records_select('user', "id IN $deleteuserstring AND deleted = 0 AND confirmed = 1");
               }

                if (isset($deleteuserlist)) {
                    foreach ($deleteuserlist as $deleteuser) {
                        delete_user($deleteuser);
                        $DB->delete_records('block_eledia_usercleanup', array('user' => $deleteuser->id));
                        echo "\ndeleting inactive user $deleteuser->username";
                    }
                }
                // Set lastrun in config table.
                set_config('eledia_usercleanuplastrun', time());
                echo "\nsetlastrunto: ".time()."\n";
            }
        }
    }
}
