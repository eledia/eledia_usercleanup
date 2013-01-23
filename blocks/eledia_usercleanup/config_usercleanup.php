<?php

    require_once('../../config.php');
    require_once('config_usercleanup_form.php');

 /// Check for valid admin user - no guest autologin
    require_login(0, false);
    $PAGE->set_url('/blocks/eledia_usercleanup/config_usercleanup.php');
    $PAGE->set_context(get_context_instance(CONTEXT_SYSTEM));

    $context = get_context_instance(CONTEXT_SYSTEM);

    require_capability('moodle/site:config', $context);

//    global $COURSE;

    $mform = new config_usercleanup_form();

    if ($mform->is_cancelled()) {
        redirect($CFG->httpswwwroot);

    } else if ($genparams = $mform->get_data()) {
//print_object($genparams);
    }

    $header = get_string('el_header', 'block_eledia_usercleanup');
    $PAGE->set_heading($header);

    echo $OUTPUT->header();
    $mform->display();
    echo $OUTPUT->footer();

?>