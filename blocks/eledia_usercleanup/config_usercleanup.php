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
 * Configuration for the cleanup.
 *
 * @package    block
 * @subpackage eledia_usercleanup
 * @author     Benjamin Wolf <support@eledia.de>
 * @copyright  2013 eLeDia GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
require_once('config_usercleanup_form.php');

// Check for valid admin user - no guest autologin.
require_login(0, false);
$PAGE->set_url('/blocks/eledia_usercleanup/config_usercleanup.php');
$context = CONTEXT_SYSTEM::instance();
$PAGE->set_context($context);
$PAGE->navbar->add(get_string('pluginname', 'block_eledia_usercleanup'));
$PAGE->set_pagelayout('standard');

require_capability('moodle/site:config', $context);

$mform = new config_usercleanup_form();

if ($mform->is_cancelled()) {
    redirect($CFG->httpswwwroot);
}

if ($formdata = $mform->get_data()) {
    if (isset ($formdata->eledia_cleanup_active)) {
        set_config('eledia_cleanup_active', 1);
    } else {
        set_config('eledia_cleanup_active', 0);
    }
    set_config('eledia_deleteinactiveuserinterval', $formdata->eledia_deleteinactiveuserinterval);
    set_config('eledia_informinactiveuserafter', $formdata->eledia_informinactiveuserafter);
    set_config('eledia_deleteinactiveuserafter', $formdata->eledia_deleteinactiveuserafter);
}

$header = get_string('el_header', 'block_eledia_usercleanup');
$PAGE->set_heading($header);

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
