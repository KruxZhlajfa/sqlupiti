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
		
		$mform->addElement('header','databases', 'Podaci za povezivanje');
		//$mform->setExpanded('foo'); nece delat, ne znam zakej
		$mform->addElement('text', 'server', 'Server za spajanje na bazu');
		$mform->addElement('text', 'username', 'Username za server');
		$mform->addElement('password', 'password', 'Password za server');
		$mform->addElement('text', 'database', 'Ime baze');
		
		$mform->addElement('header', 'picture', 'ER model');
		$mform->addElement('filepicker', 'ERmodel', 'ER Model');
		
		
		//$this->add_interactive_settings();
    }
	
	/*public function validation($data){
	
		if ($data = $mform->get_data()) {
			$destination_directory="C:\Program Files (x86)\VertrigoServ\www\moodle\question\type\sqlupiti\image";
			$mform->save_files($destination_directory);
		}

	}*/

    protected function data_preprocessing($question) {
        $question = parent::data_preprocessing($question);
        $question = $this->data_preprocessing_hints($question);

        return $question;
    }

    public function qtype() {
        return 'sqlupiti';
    }
}
