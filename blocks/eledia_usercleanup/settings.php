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
 * @copyright  2013 eLeDia GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $taskurl = new moodle_url('/admin/tool/task/scheduledtasks.php');

    $settings->add(new admin_setting_heading('eledia_usercleanup_hdr',
                                        get_string('pluginname', 'block_eledia_usercleanup'),
                                        get_string('settingsinfo', 'block_eledia_usercleanup', $taskurl->out())));


    $settings->add(new admin_setting_configtext('eledia_informinactiveuserafter',
                                        get_string('eledia_informinactiveuserafter', 'block_eledia_usercleanup'),
                                        '',
                                        120,
                                        PARAM_INT, 10));

    $settings->add(new admin_setting_configtext('eledia_deleteinactiveuserafter',
                                        get_string('eledia_deleteinactiveuserafter', 'block_eledia_usercleanup'),
                                        '',
                                        120,
                                        PARAM_INT, 10));

}
