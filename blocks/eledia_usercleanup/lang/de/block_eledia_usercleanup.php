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
 * German language file.
 *
 * @package    block
 * @subpackage eledia_usercleanup
 * @author     Benjamin Wolf <support@eledia.de>
 * @copyright  2013 eLeDia GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['back'] = 'zurück';

$string['dayserror'] = 'Anzahl der Tage darf nicht 0 oder negativ sein';

$string['eledia_usercleanup:addinstance'] = 'Nutzerbereinigungs Block hinzufügen';
$string['eledia_cleanup_active'] = 'Nutzerbereinigung aktivieren';
$string['eledia_deleteinactiveuserafter'] = 'Wieviel Tage nach der Information soll der/die Nutzer/in gelöscht werden?';
$string['eledia_deleteinactiveuserinterval'] = 'Intervall für die Prüfung der Inaktivität/Löschung in Tagen';
$string['eledia_informinactiveuserafter'] = 'Nach wieviel inaktiven Tagen soll der/die Nutzer/in informiert werden?';
$string['el_config_header'] = 'Konfiguration für das Löschen inaktiver Nutzer/innen';
$string['el_header'] = 'Konfiguration Nutzerbereinigung';
$string['el_navname'] = 'Nutzerbereinigung';
$string['email_subject'] = 'Löschung Ihres Accounts für das {$a->sitename} (Moodle)';
$string['email_message'] = 'Guten Tag {$a->firstname} {$a->lastname},

Sie haben sich länger als {$a->userinactivedays} Tage nicht auf dem {$a->sitename} (Moodle) eingeloggt.
Zur Pflege der Lernplattform werden inaktive Nutzer/innen regelmäßig gelöscht.

Möchten Sie Ihren Account weiterhin nutzen, dann loggen sie sich bitte, innerhalb von {$a->eledia_deleteinactiveuserafter} Tagen nach Empfang dieser eMail auf der Lernplattform ein: {$a->link}. So verhindern Sie, dass Ihr Moodle-Account gelöscht wird.

Freundliche Grüße
{$a->admin}

::: Diese Nachricht wurde automatisch erzeugt :::
    ';

$string['hint'] = 'Das Intervall zur Prüfung greift für beide Prozesse(Inaktivität/Löschung).
    Sinnvoll ist hier eine Einstellung die kleiner ist als die beiden anderen.';

$string['pluginname'] = 'Nutzerbereinigung';

$string['title'] = 'Nutzerbereinigung';

$string['save_changes'] = 'Änderungen speichern';
$string['saved'] = 'Änderungen gespeichert';
