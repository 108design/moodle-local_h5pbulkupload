<?php
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

$string['contentbanklink'] = 'Inhaltsspeicher öffnen';
$string['course'] = 'Zielkurs';
$string['courseoption'] = '{$a->fullname} ({$a->shortname}, ID {$a->id})';
$string['duplicate'] = 'Im Zielkurs ist bereits ein H5P-Inhalt mit diesem Dateinamen vorhanden.';
$string['error'] = 'Fehler';
$string['errormissingcontenttype'] = 'Der H5P-Inhaltstyp ist in diesem Kurs nicht verfügbar.';
$string['errormissingfiles'] = 'Bitte mindestens eine H5P-Datei hinzufügen.';
$string['errormissingpermissions'] = 'Ihnen fehlen Berechtigungen zum Hochladen von H5P-Inhalten in diesen Kurs.';
$string['filename'] = 'Datei';
$string['h5pbulkupload:upload'] = 'Mehrere H5P-Dateien in Inhaltsspeicher hochladen';
$string['import'] = 'H5P-Dateien hochladen';
$string['importbusy'] = 'In diesem Kurs läuft bereits ein Massenimport. Bitte versuche es in Kürze erneut.';
$string['importerror'] = 'Die Datei konnte nicht importiert werden. Prüfe die Serverprotokolle oder kontaktiere die Administration.';
$string['importfailed'] = '{$a->failed} von {$a->total} Dateien konnten nicht importiert werden.';
$string['importpartial'] = '{$a->success} von {$a->total} Dateien wurden importiert; {$a->skipped} wurden übersprungen und {$a->failed} sind fehlgeschlagen.';
$string['importsuccess'] = 'Alle {$a} H5P-Dateien wurden erfolgreich importiert.';
$string['invalidcourse'] = 'Bitte einen gültigen, sichtbaren Zielkurs auswählen.';
$string['invaliddraftfile'] = 'Es können nur Dateien aus Deinem eigenen Upload-Entwurfsbereich importiert werden.';
$string['invalidfiletype'] = 'Es können ausschließlich .h5p-Dateien importiert werden.';
$string['invalidpackage'] = 'Diese Datei ist kein gültiges, verfügbares H5P-Paket.';
$string['nofiles'] = 'Es wurden keine Dateien übermittelt.';
$string['open'] = 'Öffnen';
$string['packages'] = 'H5P-Dateien';
$string['packages_help'] = 'Ziehen Sie mehrere .h5p-Dateien in dieses Feld oder wählen Sie sie über die Dateiauswahl aus.';
$string['pluginname'] = 'h5p Bulk Upload';
$string['privacy:metadata'] = 'Das Plugin h5p Bulk Upload speichert selbst keine personenbezogenen Daten. Importierte Inhalte werden im Moodle-Inhaltsspeicher abgelegt.';
$string['result'] = 'Ergebnis';
$string['skipduplicates'] = 'Dateien mit vorhandenem Namen überspringen';
$string['skipduplicates_help'] = 'Wenn aktiviert, wird eine Datei nicht importiert, falls im Zielkurs bereits ein H5P-Inhalt mit exakt demselben Dateinamen vorhanden ist.';
$string['skipped'] = 'Übersprungen';
$string['status'] = 'Status';
$string['success'] = 'Importiert';
$string['toomanyfiles'] = 'Bitte höchstens {$a} Dateien gleichzeitig hochladen.';
$string['uploadintro'] = 'Wählen Sie einen Zielkurs und fügen Sie alle H5P-Dateien hinzu, die in dessen Inhaltsspeicher importiert werden sollen. Es werden keine Kursaktivitäten angelegt.';

// Contextual administration help.
$string['adminhelp'] = 'Hilfe zur Verwaltung';
$string['adminhelpmore'] = 'Mehr dazu im Hilfepanel';
$string['adminhelptopics'] = 'Hilfethemen';
$string['adminhelp_overview'] = 'H5P-Pakete importieren';
$string['adminhelp_overview_article'] = '<p>Wähle zuerst den Zielkurs und lade danach die gewünschten H5P-Pakete hoch. Der Import legt Inhalte im Inhaltsspeicher des Kurses an; Kursaktivitäten entstehen dadurch nicht automatisch.</p><p>Du benötigst die passenden Rechte für den Zielkurs. Moodle prüft die Pakete und benötigten H5P-Bibliotheken; nicht unterstützte oder fehlerhafte Dateien können abgelehnt werden.</p>';
$string['adminhelp_results'] = 'Duplikate und Ergebnisprüfung';
$string['adminhelp_results_article'] = '<p>Prüfe vor dem Start die gewählte Behandlung von Duplikaten. Zusätzliche Importe ersetzen bestehende Inhalte nicht.</p><p>Kontrolliere anschließend das Ergebnis jeder Datei. Ein fehlgeschlagenes Paket bedeutet nicht, dass alle anderen Dateien ebenfalls fehlgeschlagen sind.</p>';

$string['coursesearch'] = 'Nach Kursname, Kurzname oder ID suchen';
$string['course_help'] = 'Gib mindestens zwei Zeichen des Kursnamens, Kurznamens oder der ID-Nummer ein oder suche nach der numerischen Kurs-ID. Angezeigt werden nur sichtbare Kurse, in denen Du H5P-Inhalte hochladen darfst. Es erscheinen bis zu 20 passende Kurse; grenze die Suche bei Bedarf weiter ein.';

$string['adminhelp_overview_help'] = 'Wähle einen berechtigten Zielkurs und füge Deine H5P-Pakete hinzu. Das Hilfepanel erklärt Import und Berechtigungen.';
$string['adminhelp_results_help'] = 'Der Duplikatschutz vergleicht exakte Dateinamen in der Ziel-Inhaltssammlung. Das Hilfepanel erklärt Ergebnisse und erneute Versuche.';


$string['updateguard_heading'] = 'Update angehalten';
$string['updateguard_component'] = 'Das Paket für {$a} wurde abgewiesen.';
$string['updateguard_hint'] = 'Verwende ein freigegebenes Paket für die installierte Ausgabe. Du kannst den Vorgang erneut prüfen.';
$string['updateguard_unchanged'] = 'Dieser Vorgang hat keine Plugin-Dateien ersetzt.';
$string['updateguard_help'] = 'Das installierte Plugin prüft vor dem Ersetzen die Herausgebersignatur, den Paketinhalt, den erlaubten Updatekanal und die Kompatibilität. Eine gültige Signatur erlaubt keinen Wechsel auf eine andere Ausgabe. Wird ein Paket abgewiesen, stoppt die gesamte Sammelaktion.';
$string['updateguard_signature'] = 'Die Herausgebersignatur oder der Paketinhalt konnte nicht bestätigt werden.';
$string['updateguard_policy'] = 'Das Paket passt nicht zur installierten Ausgabe oder zum erlaubten Paketprofil.';
$string['updateguard_channel'] = 'Updates über diesen Kanal sind für dieses Plugin nicht freigegeben.';
$string['updateguard_metadata'] = 'Die Versions- oder Abhängigkeitsangaben sind nicht konsistent.';
$string['updateguard_dependency'] = 'Eine benötigte Plugin-Version fehlt. Aktualisiere die Abhängigkeit gemeinsam mit diesem Plugin oder vorher.';
$string['updateguard_approval'] = 'Die Pakete oder der installierte Stand haben sich seit der Vorschau geändert. Prüfe den Vorgang erneut.';
$string['updateguard_busy'] = 'Eine andere Installation läuft. Versuche es nach deren Abschluss erneut.';
$string['updateguard_installed'] = 'Der installierte Updateschutz ist unvollständig. Stelle die Dateien der zuletzt installierten freigegebenen Version wieder her.';
$string['updateguard_operation'] = 'Diese Kombination von Installationsaktionen wird nicht unterstützt. Starte einen einzelnen Updatevorgang erneut.';
$string['updateguard_failed'] = 'Das Update konnte nicht geprüft werden. Es wurde keine Installation gestartet.';
$string['updateguard_back'] = 'Zur Pluginübersicht';
