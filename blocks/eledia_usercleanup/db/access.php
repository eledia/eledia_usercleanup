<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(

    'block/eledia_usercleanup:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_PERSONAL,

        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(),

        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),
);
