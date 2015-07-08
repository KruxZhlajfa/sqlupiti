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
 * Question type class for the sqlupiti question type.
 *
 * @package    qtype
 * @subpackage sqlupiti
 * @copyright  2014 Krunoslav Smrekar (ksmrekar@riteh.hr)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/questionlib.php');
require_once($CFG->dirroot . '/question/engine/lib.php');
require_once($CFG->dirroot . '/question/type/sqlupiti/question.php');


/**
 * The sqlupiti question type.
 *
 * @copyright  2014 Krunoslav Smrekar (ksmrekar@riteh.hr)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_sqlupiti extends question_type {
    
    public function extra_question_fields() {
        return array('qtype_sqlupiti_options', 'sqlanswer',
            'server', 'username', 'password', 'dbname');
    }

    public function move_files($questionid, $oldcontextid, $newcontextid) {
        $fs = get_file_storage();
        parent::move_files($questionid, $oldcontextid, $newcontextid);
        $fs->move_area_files_to_new_context($oldcontextid,
                                    $newcontextid, 'qtype_sqlupiti', 'ermodel', $questionid);
        $this->move_files_in_hints($questionid, $oldcontextid, $newcontextid);
    }

    protected function delete_files($questionid, $contextid) {
        $fs = get_file_storage();
        parent::delete_files($questionid, $contextid);
        $draftfiles = $fs->get_area_files($contextid, 'qtype_sqlupiti', 'ermodel', $questionid, 'id');
        
        foreach ($draftfiles as $file){
            $file->delete();
        }
        $this->delete_files_in_hints($questionid, $contextid);
    }

    public function save_question_options($question) {
        global $DB;
		
		$context = $question->context;
		$oldanswers = $DB->get_records('question_answers',
                array('question' => $question->id), 'id ASC');
		
		//echo implode($question->answer);
		
		foreach ($question->answer as $key => $answerdata) {
            if (trim($answerdata['text']) == '') {
                continue;
            }
			//echo gettype();
            // Update an existing answer if possible.
            $answer = array_shift($oldanswers);
            if (!$answer) {
                $answer = new stdClass();
                $answer->question = $question->id;
                $answer->answer = '';
                $answer->feedback = '';
                $answer->id = $DB->insert_record('question_answers', $answer);
            }

            // Doing an import.
			/*$answer->answer = $this->import_or_save_files($answerdata,
                    $context, 'question', 'answer', $answer->id);*/
			$answer->answer = $answerdata['text'];
            $answer->fraction = $question->fraction[$key];
			$answer->answerformat = 1;

            $DB->update_record('question_answers', $answer);
        }
        
        if ($options = $DB->get_record('qtype_sqlupiti_options', array('questionid' => $question->id))) {
            // No need to do anything, since the answer IDs won't have changed
            // But we'll do it anyway, just for robustness.
            $options->sqlanswer = $question->sqlanswer;
            $options->server    = $question->server;
            $options->username  = $question->username;
            $options->password  = $question->password;
            $options->dbname    = $question->dbname;
            $DB->update_record('qtype_sqlupiti_options', $options);
        } else {
            $options = new stdClass();
            $options->questionid    = $question->id;
            $options->sqlanswer     = $question->sqlanswer;
            $options->server        = $question->server;
            $options->username      = $question->username;
            $options->password      = $question->password;
            $options->dbname        = $question->dbname;
            $DB->insert_record('qtype_sqlupiti_options', $options);
        }
        
        file_save_draft_area_files($question->ermodel, $question->context->id,
                                    'qtype_sqlupiti', 'ermodel', $question->id,
                                    array('subdirs' => 0, 'maxbytes' => 0, 'maxfiles' => 1, 'accepted_types' => array('image')));
		
        $this->save_hints($question);
    }
    
    public function get_question_options($question) {
        global $DB, $OUTPUT;
        // Get additional information from database
        // and attach it to the question object.
		global $DB, $OUTPUT;
        $question->options = $DB->get_record('qtype_sqlupiti_options',
                array('questionid' => $question->id), '*', MUST_EXIST);
        parent::get_question_options($question);
    }

    protected function initialise_question_instance(question_definition $question, $questiondata) {
        parent::initialise_question_instance($question, $questiondata);
        $this->initialise_question_answers($question, $questiondata, false);
    }

    public function get_random_guess_score($questiondata) {
        foreach ($questiondata->options->answers as $aid => $answer) {
            if ('*' == trim($answer->answer)) {
                return $answer->fraction;
            }
        }
        return 0;
    }

    public function get_possible_responses($questiondata) {
        $responses = array();

        $starfound = false;
        foreach ($questiondata->options->answers as $aid => $answer) {
            $responses[$aid] = new question_possible_response($answer->answer,
                    $answer->fraction);
            if ($answer->answer === '*') {
                $starfound = true;
            }
        }

        if (!$starfound) {
            $responses[0] = new question_possible_response(
                    get_string('didnotmatchanyanswer', 'question'), 0);
        }

        $responses[null] = question_possible_response::no_response();

        return array($questiondata->id => $responses);
    }
}
