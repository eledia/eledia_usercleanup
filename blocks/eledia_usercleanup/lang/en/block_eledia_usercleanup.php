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
 * English language file.
 *
 * @package    block
 * @subpackage eledia_usercleanup
 * @author     Benjamin Wolf <support@eledia.de>
 * @copyright  2018 eLeDia GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['back'] = 'back';

$string['cleanuptask'] = 'User cleanup';

$string['dayserror'] = 'Number of days should not be 0 or negative';

$string['eledia_usercleanup:addinstance'] = 'add user count block';
$string['eledia_cleanup_active'] = 'activate usercleanup';
$string['eledia_deleteinactiveuserafter'] = 'Days to delete';
$string['eledia_deleteinactiveuserafter_hint'] = 'Days after a notified user will be deleted ';
$string['eledia_informinactiveuserafter'] = 'Day to inform';
$string['eledia_informinactiveuserafter_hint'] = 'Inactiv days after a user will be informed';
$string['el_config_header'] = 'Configuration to delete inactive user accounts';
$string['el_header'] = 'Configuration User Cleanup';
$string['el_navname'] = 'User Cleanup';
$string['email_subject'] = 'inactiv on {$a->sitename}';
$string['email_message'] = 'Dear {$a->firstname} {$a->lastname},

you haven\'t logged into {$a->sitename} for more than {$a->userinactivedays} days now. This is a timeperiod after which a user usually gets deleted.
To prevent this you only need to log into {$a->link} within the next {$a->eledia_deleteinactiveuserafter} days.

Best regards
{$a->admin}

::: This message was created automatically :::
    ';
$string['exclude_roles'] = 'Exclude roles';
$string['exclude_roles_hint'] = 'Users assigned to this roles will be ignored.';

$string['hint'] = 'The check setting takes effect on both other settings (inactivity/deletion).
    Therefor it is recommended to choose a smaller value for the check, than for the other settings.';

$string['ignore_enrolled_users'] = 'Ignore users with course enrolments';

$string['notavailable'] = 'Not available';
$string['no_inform_mail'] = 'Send no warning but delete imediately(uses ´days to deleted´ setting)';

$string['pluginname'] = 'User Cleanup';
$string['privacy:metadata:userid'] = 'userid';
$string['privacy:metadata:mailedto'] = 'mailed to';
$string['privacy:metadata:timestamp'] = 'email timestamp';
$string['privacy:metadata:block'] = 'The usercleanup block stores temporare data about the emailed warings in its own table.';

$string['settingsinfo'] = 'The values for the intervals should be longer than the interval of the scheduled task.<br />The value of this interval can be found here <a href="{$a}">Scheduled tasks</a>.';
$string['save_changes'] = 'save changes';
$string['saved'] = 'Changes saved';

$string['title'] = 'User Cleanup';
