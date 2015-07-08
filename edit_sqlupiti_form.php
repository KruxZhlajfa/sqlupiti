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
	
	public function get_per_answer_fields($mform, $label, $gradeoptions,
            &$repeatedoptions, &$answersoption) {
				
		$attributes = 'rows="6" cols="100"';
		$repeated = array();
        $repeated[] = $mform->createElement('editor', 'answer',
                $label, $attributes);
        $repeated[] = $mform->createElement('select', 'fraction',
                get_string('grade'), $gradeoptions);
        $repeatedoptions['answer']['type'] = PARAM_NOTAGS;
        $repeatedoptions['fraction']['default'] = 0;
        $answersoption = 'answers';
        return $repeated;
    }

    protected function definition_inner($mform) {
        $attributes = 'rows="6" cols="100"';
		$fraction_options = array(
			'0.0' => get_string('nonegrade', 'qtype_sqlupiti'),
			'0.9' => '90%',
			'0.8' => '80%',
			'0.7' => '70%',
			'0.6' => '60%',
			'0.5' => '50%',
			'0.4' => '40%',
			'0.3' => '30%',
			'0.2' => '20%',
			'0.1' => '10%',
		);
		
        $mform->addElement('header','answers', get_string('answer', 'qtype_sqlupiti'));
	$mform->setExpanded('answers');
        $mform->addElement('textarea', 'sqlanswer', get_string('sqlquery','qtype_sqlupiti'), $attributes);
	$mform->setType('sqlanswer', PARAM_NOTAGS);
        $mform->addRule('sqlanswer', get_string('missinganswer','qtype_sqlupiti'), 'required', null, 'server');
		
		$this->add_per_answer_fields($mform, get_string('queryno', 'qtype_sqlupiti', '{no}'),
                $fraction_options, 1, 2);
		
	
	$mform->addElement('header','databases', get_string('connect', 'qtype_sqlupiti'));
        $mform->setExpanded('databases');
	$mform->addElement('text', 'server', get_string('conserver', 'qtype_sqlupiti'));
        $mform->setType('server', PARAM_NOTAGS);
        $mform->addRule('server', get_string('missingserver','qtype_sqlupiti'), 'required', null, 'server');
	$mform->addElement('text', 'username', get_string('conuser', 'qtype_sqlupiti'));
        $mform->setType('username', PARAM_NOTAGS);
        $mform->addRule('username', get_string('missinguser','qtype_sqlupiti'), 'required', null, 'server');
	$mform->addElement('text', 'password', get_string('conpass', 'qtype_sqlupiti'));
        $mform->setType('password', PARAM_NOTAGS);
        $mform->addRule('password', get_string('missingpass','qtype_sqlupiti'), 'required', null, 'server');
	$mform->addElement('text', 'dbname', get_string('condbname', 'qtype_sqlupiti'));
        $mform->setType('dbname', PARAM_NOTAGS);
        $mform->addRule('dbname', get_string('missingdbname','qtype_sqlupiti'), 'required', null, 'server');
        $mform->closeHeaderBefore('databases');
	
	$mform->addElement('header', 'picture', get_string('ermodel', 'qtype_sqlupiti'));
        $mform->setExpanded('picture');
        $mform->addElement('filemanager', 'ermodel', get_string('ermodel', 'qtype_sqlupiti'), null,
                    array('subdirs' => 0, 'maxbytes' => 0, 'maxfiles' => 1, 'accepted_types' => array('image')));
        
    }

    protected function data_preprocessing($question) {
        $question = parent::data_preprocessing($question);
        $question = $this->data_preprocessing_answers($question, true);
        //$question = $this->data_preprocessing_hints($question, true, true);
        
        // Initialise file manager for ermodel.
        $draftitemid = file_get_submitted_draft_itemid('ermodel');

        file_prepare_draft_area($draftitemid, $this->context->id, 'qtype_sqlupiti',
                                'ermodel', !empty($question->id) ? (int) $question->id : null,
                                 array('subdirs' => 0, 'maxbytes' => 0, 'maxfiles' => 1, 'accepted_types' => array('image')));
        $question->ermodel = $draftitemid;

        return $question;
    }
	
	public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $answers = $data['answer'];

        foreach ($answers as $key => $answer) {
            // Check no of choices.
            $trimmedanswer = trim($answer['text']);
            $fraction = (float) $data['fraction'][$key];
            if ($trimmedanswer === '' && empty($fraction)) {
                continue;
            }
            if ($trimmedanswer === '') {
                $errors['fraction['.$key.']'] = get_string('errgradesetanswerblank', 'qtype_sqlupiti');
            }
			if($fraction == 0){
				$errors['fraction['.$key.']'] = get_string('erranswersetgradeblank', 'qtype_sqlupiti');
			}
        }
		
        return $errors;
    }

    public function qtype() {
        return 'sqlupiti';
    }
}
