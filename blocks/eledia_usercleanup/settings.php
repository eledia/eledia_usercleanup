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

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $taskurl = new moodle_url('/admin/tool/task/scheduledtasks.php');

    $configs[] = new admin_setting_heading('eledia_usercleanup_hdr',
                                        get_string('pluginname', 'block_eledia_usercleanup'),
                                        get_string('settingsinfo', 'block_eledia_usercleanup', $taskurl->out()));
    $configs[] = new admin_setting_configtext('eledia_informinactiveuserafter',
                                        get_string('eledia_informinactiveuserafter', 'block_eledia_usercleanup'),
                                        get_string('eledia_informinactiveuserafter_hint', 'block_eledia_usercleanup'),
                                        120,
                                        PARAM_INT, 10);
    $configs[] = new admin_setting_configtext('eledia_deleteinactiveuserafter',
                                        get_string('eledia_deleteinactiveuserafter', 'block_eledia_usercleanup'),
                                        get_string('eledia_deleteinactiveuserafter_hint', 'block_eledia_usercleanup'),
                                        120,
                                        PARAM_INT, 10);

    $configs[] = new admin_setting_configcheckbox('ignore_enrolled_users',
            get_string('ignore_enrolled_users', 'block_eledia_usercleanup'), '', false);
    $configs[] = new admin_setting_configcheckbox('no_inform_mail',
            get_string('no_inform_mail', 'block_eledia_usercleanup'), '', false);

    // Which roles should be excluded?
    $allroles = array();
    if ($roles = role_fix_names(get_all_roles(), context_system::instance())) {
        foreach ($roles as $role) {
            $rolename = strip_tags(format_string($role->localname, true));
            $allroles[$role->id] = $rolename;
        }
    }
    $configs[] = new admin_setting_configmultiselect('exclude_roles',
                                                get_string('exclude_roles', 'block_eledia_usercleanup'),
                                                get_string('exclude_roles_hint', 'block_eledia_usercleanup'),
                                                array(),
                                                $allroles);

    foreach ($configs as $config) {
        $config->plugin = 'block_eledia_usercleanup';
        $settings->add($config);
    }
}
