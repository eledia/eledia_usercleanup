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
 * This file keeps track of upgrades to the eledia_usercleanup block
 *
 * @package    block
 * @subpackage eledia_usercleanup
 * @author     Benjamin Wolf <support@eledia.de>
 * @copyright  2014 eLeDia GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 *
 * @param int $oldversion
 * @param object $block
 */
function xmldb_block_eledia_usercleanup_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2013041201) {

        // Define table eledia_usercleanup to be renamed to block_eledia_usercleanup
        $table = new xmldb_table('eledia_usercleanup');

        // Launch rename table for eledia_usercleanup
        $dbman->rename_table($table, 'block_eledia_usercleanup');

        // block_eledia_usercleanup savepoint reached
        upgrade_block_savepoint(true, 2013041201, 'eledia_usercleanup');
    }

    if ($oldversion < 2014030300) {

        // Rename field user on table block_eledia_usercleanup to userid.
        $table = new xmldb_table('block_eledia_usercleanup');
        $field = new xmldb_field('user', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'id');

        // Launch rename field user.
        $dbman->rename_field($table, $field, 'userid');

        // Block_eledia_usercleanup savepoint reached.
        upgrade_block_savepoint(true, 2014030300, 'eledia_usercleanup');
    }

    return true;
}