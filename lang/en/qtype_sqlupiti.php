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
$string['answer'] = 'SQL query';
$string['connect'] = 'Connection to database';
$string['conserver'] = 'Server name where the database is stored';
$string['conuser'] = 'Username for server';
$string['conpass'] = 'Password for server';
$string['condbname'] = 'Database name';
$string['ermodel'] = 'ER model';
$string['pluginname'] = 'SQL query';
$string['pluginname_help'] = 'The question name has to be entered, question text and it\'s correct SQL query. '
        . 'You have to also enter data for connection where the database for that query is stored (server name, username, password and the database name). '
        . 'At last you can upload a picture for the ER diagram that is displayed for the student, the ER diagram is not requeired.';
$string['pluginname_link'] = 'question/type/sqlupiti';
$string['pluginnameadding'] = 'Adding question for SQL query';
$string['pluginnameediting'] = 'Editing question for SQL query';
$string['pluginnamesummary'] = 'SQL query is a question type in which students can write SQL queries and it\'s correctness is determined. '
        . 'The correctness is determined with the SQL query that the teacher has entered for that question. '
        . 'The correctness doesn\'t depend on "ORDER BY", i.e. the order of the result doesn\'t matter.';
$string['sqlquery'] = 'The right SQL query for the question';
$string['runquery'] = 'Run query';
$string['numofrows'] = 'Number of rows: ';
$string['pleaseenterquery'] = 'Please enter a query to run!';
$string['correctanswer'] = 'The correct SQL query is: ';
$string['correctlower'] = 'The correct result has less rows. The result has {$a} rows.';
$string['correcthigher'] = 'The correct result has more rows. The result has {$a} rows.';
$string['missinganswer'] = 'Correct SQL query is required!';
$string['missingserver'] = 'Server name is required!';
$string['missinguser'] = 'Username is required!';
$string['missingpass'] = 'Password is required!';
$string['missingdbname'] = 'Database name is required!';