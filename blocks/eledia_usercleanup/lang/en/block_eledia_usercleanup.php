<?php

$string['pluginname'] = 'eledia User Cleanup';
$string['title'] = 'eledia User Cleanup';
$string['el_navname'] = 'User Cleanup';
$string['el_header'] = 'Configuration User Cleanup';
$string['el_config_header'] = 'Configuration to delete inactive user accounts';

$string['eledia_deleteinactiveuserinterval'] = 'Check for inactive Users every * days';
$string['eledia_informinactiveuserafter'] = 'Days of inactivity after which an user will be informed of his pending deletion';
$string['eledia_deleteinactiveuserafter'] = 'Number of Days after a notified user will be deleted ';

$string['save_changes'] = 'save changes';
$string['saved'] = 'Changes saved';
$string['back'] = 'back';
$string['eledia_cleanup_active'] = 'activate usercleanup';

$string['email_subject'] = 'inactiv on {$a->sitename}';
$string['email_message'] = 'Dear {$a->firstname} {$a->lastname},

you haven\'t logged into $a->sitename for more than {$a->userinactivedays} days now. This is a timeperiod after which a user usually gets deleted.
To prevent this you only need to log into {$a->link} within the next {$a->deleteinactiveuserafter} days.

Best regards
{$a->admin}

::: This message was created automatically :::
    ';
?>