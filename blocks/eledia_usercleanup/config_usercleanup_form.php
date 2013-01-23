<?php  // $Id: config_usercleanup_form.php,v 1.6 2012-02-14 10:30:04 bwolf Exp $

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->libdir.'/formslib.php');

class config_usercleanup_form extends moodleform {

    function definition() {
    global $CFG;

        $mform =& $this->_form;                

        $mform->addElement('header', '', get_string('el_config_header', 'block_eledia_usercleanup'), 'config_usercleanup');

        if(!isset($CFG->eledia_cleanup_active)){
            set_config('eledia_cleanup_active', '0');
        }
        $mform->addElement('checkbox', 'eledia_cleanup_active',  get_string('eledia_cleanup_active', 'block_eledia_usercleanup'));
        $mform->setDefault('eledia_cleanup_active', $CFG->eledia_cleanup_active);

        if(!isset($CFG->eledia_deleteinactiveuserinterval)){
            set_config('eledia_deleteinactiveuserinterval', '7');
        }
        $mform->addElement('text', 'eledia_deleteinactiveuserinterval',  get_string('eledia_deleteinactiveuserinterval','block_eledia_usercleanup'),  'maxlength="10" size="8"');
        $mform->setDefault('eledia_deleteinactiveuserinterval', $CFG->eledia_deleteinactiveuserinterval);

        if(!isset($CFG->eledia_informinactiveuserafter)){
            set_config('eledia_informinactiveuserafter', '120');
        }
        $mform->addElement('text', 'eledia_informinactiveuserafter',  get_string('eledia_informinactiveuserafter','block_eledia_usercleanup'),  'maxlength="10" size="8"');
        $mform->setDefault('eledia_informinactiveuserafter', $CFG->eledia_informinactiveuserafter);

        if(!isset($CFG->eledia_deleteinactiveuserafter)){
            set_config('eledia_deleteinactiveuserafter', '120');
        }
        $mform->addElement('text', 'eledia_deleteinactiveuserafter',  get_string('eledia_deleteinactiveuserafter','block_eledia_usercleanup'),  'maxlength="10" size="8"');
        $mform->setDefault('eledia_deleteinactiveuserafter', $CFG->eledia_deleteinactiveuserafter);
        
//Rollen holen
        $mform->addElement('submit', 'submitbutton', get_string('save_changes', 'block_eledia_usercleanup'));
        $mform->addElement('cancel', 'cancelbutton', get_string('back', 'block_eledia_usercleanup'));
    }

    function definition_after_data(){
        global $CFG;
        $mform =& $this->_form;

        if($mform->isSubmitted()){
            if(isset ($mform->_submitValues['eledia_cleanup_active'])){
                set_config('eledia_cleanup_active', 1);
            }  else {
                set_config('eledia_cleanup_active', 0);
            }
            set_config('eledia_deleteinactiveuserinterval', $mform->_submitValues['eledia_deleteinactiveuserinterval']);
            set_config('eledia_informinactiveuserafter', $mform->_submitValues['eledia_informinactiveuserafter']);
            set_config('eledia_deleteinactiveuserafter', $mform->_submitValues['eledia_deleteinactiveuserafter']);

            $mform->addElement('static', 'saved', '', get_string('saved', 'block_eledia_usercleanup'));
        }        
    }
}
?>