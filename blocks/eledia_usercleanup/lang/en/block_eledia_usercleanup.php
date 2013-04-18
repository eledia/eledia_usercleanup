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
 * @copyright  2013 eLeDia GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['back'] = 'back';

$string['dayserror'] = 'Number of days should not be 0 or negative';

$string['eledia_usercleanup:addinstance'] = 'add user count block';
$string['eledia_cleanup_active'] = 'activate usercleanup';
$string['eledia_deleteinactiveuserafter'] = 'Number of Days after a notified user will be deleted ';
$string['eledia_deleteinactiveuserinterval'] = 'Check for inactive Users every * days';
$string['eledia_informinactiveuserafter'] = 'Days of inactivity after which an user will be informed of his pending deletion';
$string['el_config_header'] = 'Configuration to delete inactive user accounts';
$string['el_header'] = 'Configuration User Cleanup';
$string['el_navname'] = 'User Cleanup';
$string['email_subject'] = 'inactiv on {$a->sitename}';
$string['email_message'] = 'Dear {$a->firstname} {$a->lastname},

you haven\'t logged into $a->sitename for more than {$a->userinactivedays} days now. This is a timeperiod after which a user usually gets deleted.
To prevent this you only need to log into {$a->link} within the next {$a->deleteinactiveuserafter} days.

Best regards
{$a->admin}

::: This message was created automatically :::
    ';

$string['pluginname'] = 'User Cleanup';

$string['title'] = 'User Cleanup';

$string['save_changes'] = 'save changes';
$string['saved'] = 'Changes saved';
