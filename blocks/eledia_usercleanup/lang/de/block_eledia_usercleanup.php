<?php

$string['pluginname'] = 'eledia Nutzerbereinigung';
$string['title'] = 'eledia Nutzerbereinigung';
$string['el_navname'] = 'Nutzerbereinigung';
$string['el_header'] = 'Konfiguration Nutzerbereinigung';
$string['el_config_header'] = 'Konfiguration für das Löschen inaktiver Nutzer/innen';

$string['eledia_deleteinactiveuserinterval'] = 'Intervall für die Prüfung der Nutzer/innen in Tagen';
$string['eledia_informinactiveuserafter'] = 'Nach wieviel inaktiven Tagen soll der/die Nutzer/in informiert werden?';
$string['eledia_deleteinactiveuserafter'] = 'Wieviel Tage nach der Information soll der/die Nutzer/in gelöscht werden?';

$string['save_changes'] = 'Änderungen speichern';
$string['saved'] = 'Änderungen gespeichert';
$string['back'] = 'zurück';
$string['eledia_cleanup_active'] = 'Nutzerbereinigung aktivieren';

$string['email_subject'] = 'Löschung Ihres Accounts für das {$a->sitename} (Moodle)';
$string['email_message'] = 'Guten Tag {$a->firstname} {$a->lastname},

Sie haben sich länger als {$a->userinactivedays} Tage nicht auf dem {$a->sitename} (Moodle) eingeloggt.
Zur Pflege der Lernplattform werden inaktive Nutzer/innen regelmäßig gelöscht.

Möchten Sie Ihren Account weiterhin nutzen, dann loggen sie sich bitte, innerhalb von {$a->eledia_deleteinactiveuserafter} Tagen nach Empfang dieser eMail auf der Lernplattform ein: {$a->link}. So verhindern Sie, dass Ihr Moodle-Account gelöscht wird.

Freundliche Grüße
{$a->admin}

::: Diese Nachricht wurde automatishc erzeugt :::
    ';
?>