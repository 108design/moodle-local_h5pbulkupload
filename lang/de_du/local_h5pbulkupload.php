<?php
// This file is part of Moodle - https://moodle.org/
// This file is part of a 108design source-available software product.
//
// Copyright (C) 2026 Andreas Giesen <andreas@108design.com>
//
// Use and modification are permitted only under the Software License included
// with this distribution. Redistribution and circumvention of Pro feature or
// licensing restrictions are prohibited. See LICENSE.md for the full terms.

/**
 * German language strings.
 *
 * @package    local_h5pbulkupload
 * @copyright  2026 Andreas Giesen <andreas@108design.com>
 * @license    See LICENSE.md for the full terms.
 */

$string['errormissingpermissions'] = 'Dir fehlen Berechtigungen zum Hochladen von H5P-Inhalten in diesen Kurs.';
$string['importbusy'] = 'In diesem Kurs läuft bereits ein Massenimport. Bitte versuche es in Kürze erneut.';
$string['importerror'] = 'Die Datei konnte nicht importiert werden. Prüfe die Serverprotokolle oder kontaktiere die Administration.';
$string['invaliddraftfile'] = 'Es können nur Dateien aus deinem eigenen Upload-Entwurfsbereich importiert werden.';
$string['packages_help'] = 'Ziehe mehrere .h5p-Dateien in dieses Feld oder wählst sie über die Dateiauswahl aus.';
$string['uploadintro'] = 'Wähle einen Zielkurs und füge alle H5P-Dateien hinzu, die in dessen Inhaltsspeicher importiert werden sollen. Es werden keine Kursaktivitäten angelegt.';
$string['adminhelp_overview_article'] = '<p>Wähle zuerst den Zielkurs und lade danach die gewünschten H5P-Pakete hoch. Der Import legt Inhalte im Inhaltsspeicher des Kurses an; Kursaktivitäten entstehen dadurch nicht automatisch.</p><p>Du benötigst die passenden Rechte für den Zielkurs. Moodle prüft die Pakete und benötigten H5P-Bibliotheken; nicht unterstützte oder fehlerhafte Dateien können abgelehnt werden.</p>';
$string['adminhelp_results_article'] = '<p>Prüfe vor dem Start die gewählte Behandlung von Duplikaten. Zusätzliche Importe ersetzen bestehende Inhalte nicht.</p><p>Kontrolliere anschließend das Ergebnis jeder Datei. Ein fehlgeschlagenes Paket bedeutet nicht, dass alle anderen Dateien ebenfalls fehlgeschlagen sind.</p>';
$string['course_help'] = 'Gib mindestens zwei Zeichen des Kursnamens, Kurznamens oder der ID-Nummer ein oder suchst nach der numerischen Kurs-ID. Angezeigt werden nur sichtbare Kurse, in denen du H5P-Inhalte hochladen darfst. Es erscheinen bis zu 20 passende Kurse; grenze die Suche bei Bedarf weiter ein.';
$string['adminhelp_overview_help'] = 'Wähle einen berechtigten Zielkurs und füge deine H5P-Pakete hinzu. Das Hilfepanel erklärt Import und Berechtigungen.';
$string['adminhelp_results_help'] = 'Der Duplikatschutz vergleicht exakte Dateinamen in der Ziel-Inhaltssammlung. Das Hilfepanel erklärt Ergebnisse und erneute Versuche.';
$string['updateguard_hint'] = 'Verwende ein freigegebenes Paket für die installierte Ausgabe. Du kannst den Vorgang erneut prüfen.';
$string['updateguard_dependency'] = 'Eine benötigte Plugin-Version fehlt. Aktualisiere die Abhängigkeit gemeinsam mit diesem Plugin oder vorher.';
$string['updateguard_approval'] = 'Die Pakete oder der installierte Stand haben sich seit der Vorschau geändert. Prüfe den Vorgang erneut.';
$string['updateguard_busy'] = 'Eine andere Installation läuft. Versuche es nach deren Abschluss erneut.';
$string['updateguard_installed'] = 'Der installierte Updateschutz ist unvollständig. Stelle die Dateien der zuletzt installierten freigegebenen Version wieder her.';
$string['updateguard_operation'] = 'Diese Kombination von Installationsaktionen wird nicht unterstützt. Starte einen einzelnen Updatevorgang erneut.';
$string['updateinfo_choice'] = 'Kontrolliere vor dem Fortfahren die Paketquelle und Moodles Prüfergebnisse. Mit „{$a}“ unten kannst du abbrechen.';
