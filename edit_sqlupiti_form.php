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
        
        $attributes = 'rows="6" cols="175"';
		
        $mform->addElement('header','answers', get_string('answer', 'qtype_sqlupiti'));
	$mform->setExpanded('answers');
        $mform->addElement('textarea', 'sqlanswer', get_string('sqlquery','qtype_sqlupiti'), $attributes);
	$mform->setType('sqlanswer', PARAM_NOTAGS);
	
	$mform->addElement('header','databases', get_string('connect', 'qtype_sqlupiti'));
        $mform->setExpanded('databases');
	$mform->addElement('text', 'server', get_string('conserver', 'qtype_sqlupiti'));
        $mform->setType('server', PARAM_NOTAGS);
	$mform->addElement('text', 'username', get_string('conuser', 'qtype_sqlupiti'));
        $mform->setType('username', PARAM_NOTAGS);
	$mform->addElement('text', 'password', get_string('conpass', 'qtype_sqlupiti'));
        $mform->setType('password', PARAM_NOTAGS);
	$mform->addElement('text', 'dbname', get_string('condbname', 'qtype_sqlupiti'));
        $mform->setType('dbname', PARAM_NOTAGS);
        $mform->closeHeaderBefore('databases');
	
	$mform->addElement('header', 'picture', get_string('ermodel', 'qtype_sqlupiti'));
        $mform->addElement('filemanager', 'ermodel', get_string('ermodel', 'qtype_sqlupiti'), null,
                    array('subdirs' => 0, 'maxbytes' => 0, 'maxfiles' => 1, 'accepted_types' => array('image')));
        
    }

    protected function data_preprocessing($question) {
        $question = parent::data_preprocessing($question);
        $question = $this->data_preprocessing_hints($question);
        
        // Initialise file manager for ermodel.
        $draftitemid = file_get_submitted_draft_itemid('ermodel');

        file_prepare_draft_area($draftitemid, $this->context->id, 'qtype_sqlupiti',
                                'ermodel', !empty($question->id) ? (int) $question->id : null,
                                 array('subdirs' => 0, 'maxbytes' => 0, 'maxfiles' => 1, 'accepted_types' => array('image')));
        $question->ermodel = $draftitemid;

        return $question;
    }

    public function qtype() {
        return 'sqlupiti';
    }
}
