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
 * Strings for component 'qtype_sqlupiti', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package    qtype
 * @subpackage sqlupiti
 * @copyright  2014 Krunoslav Smrekar (ksmrekar@riteh.hr)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['answer'] = 'SQL upit';
$string['connect'] = 'Podaci za povezivanje';
$string['conserver'] = 'Server za spajanje na bazu';
$string['conuser'] = 'Korisničko ime za server';
$string['conpass'] = 'Lozinka za server';
$string['condbname'] = 'Ime baze';
$string['ermodel'] = 'ER model';
$string['pluginname'] = 'SQL upit';
$string['pluginname_help'] = 'Dodaje se naziv pitanja, tekst pitanja te njegovo rješenje u obliku SQL upita (obavezno upisati). '
        . 'Potrebno je navesti i podatke za povezivanje (server, korisničko ime, lozinku i ime baze, ti ovi podaci su obavezni za uspisivanje) '
        . 'i upload slike za ER dijagram koji se prikaže kod studenta na pitanju.';
$string['pluginname_link'] = 'question/type/sqlupiti';
$string['pluginnameadding'] = 'Dodavanje pitanja za SQL upit';
$string['pluginnameediting'] = 'Uređivanje pitanja za SQL upit';
$string['pluginnamesummary'] = 'SQL upit je tip pitanja u kojem studenti mogu upisivati SQL upite i provjerava se njihova točnost. '
        . 'Točnost se provjerava na osnovu već upisanog točnog upita od strane profesora. Ona ne ovisi o redoslijedu ispisa upita.';
$string['sqlquery'] = 'Točan SQL upit';
$string['runquery'] = 'Pokreni upit';
$string['numofrows'] = 'Broj redaka: ';
$string['pleaseenterquery'] = 'Molim unesite upit za pokretanje!';
$string['correctanswer'] = 'Točan SQL upit je: ';
$string['correctlower'] = 'Točno rješnje ima manje redaka. Rješenje ima {$a} redaka.';
$string['correcthigher'] = 'Točno rješenje ima više redaka. Rješenje ima {$a} redaka.';
$string['missinganswer'] = 'Točan SQL upit je obavezan!';
$string['missingserver'] = 'Ime servera je obavezno!';
$string['missinguser'] = 'Korisničko ime je obavezno!';
$string['missingpass'] = 'Lozinka je obavezna!';
$string['missingdbname'] = 'Ime baze podataka je obavezna';
$string['conerror'] = 'GREŠKA!';
$string['conerrormessage'] = 'U pitanju se nalaze krivi podaci za povezivanje na bazu podataka ili nije moguće povezivanje. '
        . 'Pozovite profesora u vezi greške! Greška je povezana za ovo pitanje!';
$string['queryno'] = 'Dodatni upit {$a}';
$string['nonegrade'] = 'Ništa';
$string['errgradesetanswerblank'] = 'Dodatni upit je upisan, ali ocjena nije postavljena.';
$string['erranswersetgradeblank'] = 'Ocjena je oznacena, ali dodatni upit nije upisan.';
$string['stuquery'] = 'Rezultat upita od studenta:';
$string['corrquery'] = 'Rezultat točnog upita:';