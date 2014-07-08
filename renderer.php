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
 * sqlupiti question renderer class.
 *
 * @package    qtype
 * @subpackage sqlupiti
 * @copyright  2014 Krunoslav Smrekar (ksmrekar@riteh.hr)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Generates the output for sqlupiti questions.
 *
 * @copyright  2014 Krunoslav Smrekar (ksmrekar@riteh.hr)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_sqlupiti_renderer extends qtype_renderer {

    public function formulation_and_controls(question_attempt $qa, question_display_options $options) {

        $question = $qa->get_question();
        $currentanswer = $qa->get_last_qt_var('answer');

        $questiontext = $question->format_questiontext($qa);
        $inputname = $qa->get_qt_field_name('answer');

        $textareaattributes = array(
            'rows' => '5',
            'cols' => '80',
            'name' => $inputname,
            'id' => $inputname
        );

        $button = array(
            'type' => 'submit',
            'value' => get_string('runquery', 'qtype_sqlupiti')
        );

        $outputattributes = array('style' => 'overflow:auto; max-height:400px; vertical-align:top;');

        //disable button and textarea if reviewing quiz
        if ($options->readonly) {
            $textareaattributes['readonly'] = 'readonly';
            $button['disabled'] = 'disabled';
        }

        //Work out visuals for correctness of question
        $feedbackimg = '';
        if ($options->correctness) {
            list($fraction, ) = $question->grade_response(array('answer' => $currentanswer));
            $feedbackimg = $this->feedback_image($fraction);
        }

        @$mysqli = new mysqli($question->server, $question->username, $question->password, $question->dbname);

        if ($mysqli->connect_error) {
            $output = '<b style="color: red;">' . get_string('conerror', 'qtype_sqlupiti') . '<br><br>'
                    . get_string('conerrormessage', 'qtype_sqlupiti') . '</b>';
        } else if (!empty($currentanswer)) {
            $sqlquery = $currentanswer;

            $query_result = $mysqli->query($sqlquery);

            if ($query_result) {
                $row_cnt = $query_result->num_rows;
                $rows = $query_result->fetch_all();
                $table = new html_table();
                $head = array();
                $data = array();
                $colnum = mysqli_num_fields($query_result);
                for ($i = 0; $i < $colnum; $i++) {
                    array_push($head, $query_result->fetch_field()->name);
                }
                foreach ($rows as $value) {
                    array_push($data, $value);
                }
                $table->head = $head;
                $table->data = $data;
                $query_result->close();
                $output = get_string('numofrows', 'qtype_sqlupiti') . '<b>' . $row_cnt . '</b>' . '<br>';
                $output .= html_writer::table($table);
            } else {
                $output = '<b style="color: red;">' . 'ERROR:' . '</b><br>' . $mysqli->error;
            }
        } else {
            $output = '';
        }

        //print the result or error for the student query
        //get image for ER model
        $qubaid = $qa->get_usage_id();
        $slot = $qa->get_slot();
        $fs = get_file_storage();

        $draftfiles = $fs->get_area_files($question->contextid, 'qtype_sqlupiti', 'ermodel', $question->id, 'id');

        if ($draftfiles) {
            foreach ($draftfiles as $file) {
                if ($file->is_directory()) {
                    continue;
                }
                $url = moodle_url::make_pluginfile_url($question->contextid, 'qtype_sqlupiti', 'ermodel', "$qubaid/$slot/{$question->id}", '/', $file->get_filename());
            }
            $img = html_writer::tag('img', '', array('src' => $url->out(), 'class' => 'ermodelimg', 'style' => 'max-height: 400px; max-width: 600px;'));
        } else {
            $img = '';
        }

        $placeholder = false;
        if (preg_match('/_____+/', $questiontext, $matches)) {
            $placeholder = $matches[0];
        }

        if ($placeholder) {
            $questiontext = substr_replace($questiontext, $input, strpos($questiontext, $placeholder), strlen($placeholder));
        }

        $result = html_writer::start_tag('table');
        $result .= html_writer::start_tag('tr') . html_writer::tag('td', $questiontext);
        $result .= html_writer::start_tag('td', array('rowspan' => '3', 'style' => 'vertical-align:top;'))
                . html_writer::start_tag('div', $outputattributes) . $output
                . html_writer::end_tag('div') . html_writer::end_tag('td') . html_writer::end_tag('tr');
        $result .= html_writer::start_tag('tr') . html_writer::start_tag('td') . html_writer::start_tag('table') . html_writer::start_tag('tr')
                . html_writer::start_tag('td', array('rowspan' => '2')) . html_writer::tag('textarea', $currentanswer, $textareaattributes)
                . html_writer::end_tag('td') . html_writer::start_tag('td') . $feedbackimg . html_writer::end_tag('td');
        $result .= html_writer::end_tag('tr') . html_writer::start_tag('tr') . html_writer::start_tag('td') . html_writer::tag('input', '', $button)
                . html_writer::end_tag('td') . html_writer::end_tag('td') . html_writer::end_tag('table')
                . html_writer::end_tag('td') . html_writer::end_tag('tr');
        $result .= html_writer::start_tag('tr') . html_writer::start_tag('td') . $img
                . html_writer::end_tag('td') . html_writer::end_tag('tr') . html_writer::end_tag('table') . '<br>';


        if ($qa->get_state() == question_state::$invalid) {
            $result .= html_writer::nonempty_tag('div', $question->get_validation_error(array('answer' => $currentanswer)), array('class' => 'validationerror'));
        }
        return $result;
    }

    public function specific_feedback(question_attempt $qa) {
        $question = $qa->get_question();
        $response = $qa->get_last_qt_var('answer');

        if ($response) {
            $mysqli = new mysqli($question->server, $question->username, $question->password, $question->dbname);

            $correct_query = $question->sqlanswer;
            $correct_result = $mysqli->query($correct_query);
            $correct_row_cnt = $correct_result->num_rows;

            $student_query = $response;
            $student_row_cnt = NULL;
            if ($student_query != NULL) {
                $student_result = $mysqli->query($student_query);
                if (!$mysqli->error) {
                    $student_row_cnt = $student_result->num_rows;
                }
            }

            if ($correct_row_cnt < $student_row_cnt) {
                return get_string('correctlower', 'qtype_sqlupiti', $correct_row_cnt);
            } else if ($correct_row_cnt == $student_row_cnt) {
                return '';
            } else {
                return get_string('correcthigher', 'qtype_sqlupiti', $correct_row_cnt);
            }
        } else {
            return '';
        }
    }

    public function correct_response(question_attempt $qa) {
        $question = $qa->get_question();
        return get_string('correctanswer', 'qtype_sqlupiti') . '<b>' . $question->sqlanswer . '</b>';
    }

}
