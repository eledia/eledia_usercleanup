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
 * Configuration form for the cleanup.
 *
 * @package    block
 * @subpackage eledia_usercleanup
 * @author     Benjamin Wolf <support@eledia.de>
 * @copyright  2013 eLeDia GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    // It must be included from a Moodle page.
}

require_once($CFG->libdir.'/formslib.php');

class config_usercleanup_form extends moodleform {

    public function definition() {
        global $CFG;

        $mform =& $this->_form;

        $mform->addElement('header', '', get_string('el_config_header', 'block_eledia_usercleanup'), 'config_usercleanup');

        if (!isset($CFG->eledia_cleanup_active)) {
            set_config('eledia_cleanup_active', '0');
        }
        $mform->addElement('checkbox',
                            'eledia_cleanup_active',
                            get_string('eledia_cleanup_active', 'block_eledia_usercleanup'));
        $mform->setDefault('eledia_cleanup_active', $CFG->eledia_cleanup_active);

        if (!isset($CFG->eledia_deleteinactiveuserinterval)) {
            set_config('eledia_deleteinactiveuserinterval', '7');
        }
        $mform->addElement('text',
                            'eledia_deleteinactiveuserinterval',
                            get_string('eledia_deleteinactiveuserinterval', 'block_eledia_usercleanup'),
                            'maxlength="10" size="8"');
        $mform->setDefault('eledia_deleteinactiveuserinterval', $CFG->eledia_deleteinactiveuserinterval);
        $mform->setType('eledia_deleteinactiveuserinterval', PARAM_INT);

        if (!isset($CFG->eledia_informinactiveuserafter)) {
            set_config('eledia_informinactiveuserafter', '120');
        }
        $mform->addElement('text',
                            'eledia_informinactiveuserafter',
                            get_string('eledia_informinactiveuserafter', 'block_eledia_usercleanup'),
                            'maxlength="10" size="8"');
        $mform->setDefault('eledia_informinactiveuserafter', $CFG->eledia_informinactiveuserafter);
        $mform->setType('eledia_informinactiveuserafter', PARAM_INT);

        if (!isset($CFG->eledia_deleteinactiveuserafter)) {
            set_config('eledia_deleteinactiveuserafter', '120');
        }
        $mform->addElement('text',
                            'eledia_deleteinactiveuserafter',
                            get_string('eledia_deleteinactiveuserafter', 'block_eledia_usercleanup'),
                            'maxlength="10" size="8"');
        $mform->setDefault('eledia_deleteinactiveuserafter', $CFG->eledia_deleteinactiveuserafter);
        $mform->setType('eledia_deleteinactiveuserafter', PARAM_INT);

        $mform->addElement('submit', 'submitbutton', get_string('save_changes', 'block_eledia_usercleanup'));
        $mform->addElement('cancel', 'cancelbutton', get_string('back', 'block_eledia_usercleanup'));
    }

    public function definition_after_data() {
        $mform =& $this->_form;

        if ($mform->isSubmitted()) {
            $mform->addElement('static', 'saved', '', get_string('saved', 'block_eledia_usercleanup'));
        }
    }

    public function validation ($data, $files) {

        $errors = parent::validation($data, $files);

        if ($data['eledia_informinactiveuserafter'] < 1) {
            $errors['eledia_informinactiveuserafter'] = get_string('dayserror', 'block_eledia_usercleanup');
        }

        if ($data['eledia_deleteinactiveuserafter'] < 1) {
            $errors['eledia_deleteinactiveuserafter'] = get_string('dayserror', 'block_eledia_usercleanup');
        }

        return $errors;
    }
}

