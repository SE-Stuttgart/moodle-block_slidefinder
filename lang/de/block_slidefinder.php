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
 * Language settings german.
 *
 * @package    block_slidefinder
 * @copyright  2022 Universtity of Stuttgart <kasra.habib@iste.uni-stuttgart.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

// Config.
$string['pluginname'] = 'Foliensuche';

// Block.
$string['search_term'] = 'Gesucht: ';
$string['chapter'] = 'Kapitel';
$string['misconfigured_info'] = 'Die folgenden Dateien sind zwar als übereinstimmend gekennzeichnet, '
    . 'wurden aber nicht korrekt eingerichtet. Vielleicht stimmt die Kapitelanzahl zwischen Buch und pdf nicht überein?';
$string['pdf_replace'] = ' (Buch)';

// Search Field.
$string['search'] = 'Suche...';
$string['select_course'] = 'Wähle Kurs...';

// Capabilities.
$string['slidefinder:myaddinstance'] = 'Füge einen neuen Foliensuche Block zu meinem Moodle Dashboard hinzu';
$string['slidefinder:addinstance'] = 'Füge einen neuen Foliensuche Block zu dieser Seite hinzu';

// Error.
$string['error_message'] = 'Es ist ein Problem aufgetreten. '
    . 'Bitte kontaktieren Sie den Ersteller des Plugins und senden Sie ihm den Fehler. Dies ist der Fehler:';
$string['error_user_not_found'] = 'User does not exist.';
$string['error_course_not_found'] = 'Course is misconfigured';
$string['error_course_access_denied'] = 'Access to course denied.';
$string['error_book_pdf_mismatch'] = 'There exists an mismatch of book and pdf.';
