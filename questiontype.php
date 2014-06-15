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
        parent::move_files($questionid, $oldcontextid, $newcontextid);
        $this->move_files_in_hints($questionid, $oldcontextid, $newcontextid);
    }

    protected function delete_files($questionid, $contextid) {
        parent::delete_files($questionid, $contextid);
        $this->delete_files_in_hints($questionid, $contextid);
    }

    public function save_question_options($question) {
		
        global $DB;
        
        $context = $question->context;
	// Fetch old answer ids so that we can reuse them.
        $oldanswers = $DB->get_records('question_answers',
                    array('question' => $question->id), 'id ASC');
		
        $answer = array_shift($oldanswers);
        if (!$answer) {
            $answer = new stdClass();
            $answer->question = $question->id;
            $answer->answer = $question->sqlanswer;
            $answer->feedback = '';
            $answer->id = $DB->insert_record('question_answers', $answer);
        }
		
	$answer->answer = $question->sqlanswer;
		
	$DB->update_record('question_answers', $answer);
        
        /*$parentresult = parent::save_question_options($question);
        if ($parentresult !== null) {
            // Parent function returns null if all is OK.
            return $parentresult;
        }*/
        
        if ($options = $DB->get_record('qtype_sqlupiti_options', array('questionid' => $question->id))) {
            // No need to do anything, since the answer IDs won't have changed
            // But we'll do it anyway, just for robustness.
            $options->server  = $question->server;
            $options->username = $question->username;
            $DB->update_record('qtype_sqlupiti_options', $options);
        } else {
            $options = new stdClass();
            $options->questionid    = $question->id;
            $options->sqlanswer     = $question->sqlanswer;
            //if for saving editor field
            /*if (empty($question->sqlanswer['text'])) {
                $options->sqlanswer = '';
            } else {
                $options->sqlanswer = trim($question->sqlanswer['text']);
            }*/
            $options->server        = $question->server;
            $options->username      = $question->username;
            $options->password      = $question->password;
            $options->dbname        = $question->dbname;
            $DB->insert_record('qtype_sqlupiti_options', $options);
        }
		
        $this->save_hints($question);
	//tu napravit da se snimi ono kaj se upiše u text box...
    }
    
    public function get_question_options($question) {
        global $DB, $OUTPUT;
        // Get additional information from database
        // and attach it to the question object.
        if (!$question->options = $DB->get_record('qtype_sqlupiti_options',
                array('questionid' => $question->id))) {
            echo $OUTPUT->notification('Error: Missing question options!');
            return false;
        }

        return true;
    }

    protected function initialise_question_instance(question_definition $question, $questiondata) {
        // TODO.
        parent::initialise_question_instance($question, $questiondata);
        $this->initialise_question_answers($question, $questiondata);
        $question->sqlanswer = $questiondata->options->sqlanswer;
    }

    public function get_random_guess_score($questiondata) {
        // TODO.
        return 0;
    }

    public function get_possible_responses($questiondata) {
        // TODO.
        return array();
    }
}
