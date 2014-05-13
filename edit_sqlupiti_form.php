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
 * Defines the editing form for the sqlupiti question type.
 *
 * @package    qtype
 * @subpackage sqlupiti
 * @copyright  2014 Krunoslav Smrekar (ksmrekar@riteh.hr)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * sqlupiti question editing form definition.
 *
 * @copyright  2014 Krunoslav Smrekar (ksmrekar@riteh.hr)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_sqlupiti_edit_form extends question_edit_form {

    protected function definition_inner($mform) {
		/*$mform->addElement('text', 'sqlqueryanswer', 'Točan SQL upit:');
		$mform->addElement('text', 'servername', 'Server na kojoj se nalazi baza za ispitivanje:');
		$mform->addElement('text', 'sqlqueryanswer', 'Username za taj server:');
		$mform->addElement('text', 'sqlqueryanswer', 'Password za taj server:');
		$mform->addElement('text', 'sqlqueryanswer', 'Ime baze s servera (točan naziv u bazi):');*/
		
		$mform->addElement('header','answers', get_string('answer', 'qtype_sqlupiti'));
		$mform->setExpanded('foo'); //nece delat, ne znam zakej
		$mform->addElement('editor', 'sqlanswer', get_string('sqlquery','qtype_sqlupiti'));
		$mform->setType('sqlanswer', PARAM_RAW);
		
		$mform->addElement('header','databases', get_string('connect', 'qtype_sqlupiti'));
		//$mform->setExpanded('foo'); nece delat, ne znam zakej
		$mform->addElement('text', 'server', get_string('conserver', 'qtype_sqlupiti'));
		$mform->addElement('text', 'username', get_string('conuser', 'qtype_sqlupiti'));
		$mform->addElement('password', 'password', get_string('conpass', 'qtype_sqlupiti'));
		$mform->addElement('text', 'database', get_string('condbname', 'qtype_sqlupiti'));
		
		$mform->addElement('header', 'picture', get_string('ermodel', 'qtype_sqlupiti'));
		$mform->addElement('filepicker', 'ERmodel', get_string('ermodel', 'qtype_sqlupiti'));
                
                
		
		
		//$this->add_interactive_settings();
    }

    protected function data_preprocessing($question) {
        $question = parent::data_preprocessing($question);
        $question = $this->data_preprocessing_hints($question);

        return $question;
    }

    public function qtype() {
        return 'sqlupiti';
    }
}
