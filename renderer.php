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
    public function formulation_and_controls(question_attempt $qa,
            question_display_options $options) {

        $question = $qa->get_question();
        $currentanswer = $qa->get_last_qt_var('answer');

        $questiontext = $question->format_questiontext($qa);
        $inputname = $qa->get_qt_field_name('answer');
        
        $textareaattributes = array(
            'rows' => '3',
            'cols' => '50',
            'name' => $inputname,
            'id' => $inputname,
        );
        
        if (!empty($currentanswer)){
            $mysqli = new mysqli($question->server, $question->username, $question->password, $question->dbname);
        
            $sqlquery = $currentanswer;
        
            $query_result = $mysqli->query($sqlquery);
        
            if ($query_result){
                $rows = $query_result->fetch_all();
                $table = new html_table();
                $head = array();
                $data = array();
                $colnum = mysqli_num_fields($query_result);
                for ($i=0; $i < $colnum; $i++){
                    array_push($head, $query_result->fetch_field()->name);
                }
                foreach ($rows as $value){
                    array_push($data, $value);
                }
                $table->head = $head;
                $table->data = $data;
                $query_result->close();
                $output = html_writer::table($table);
            }else{
                $output = $mysqli->error;
            }
            
        }else{
            $output = '';
        }
        
        $ermodel = self::get_url_for_image($qa, 'ermodel');
        
        $img = html_writer::tag('img', '', array('src'=>$ermodel, 'class'=>'ermodelimg', 'alt'=>'nekitekst'));
        
        if ($options->readonly) {
            $textareaattributes['readonly'] = 'readonly';
        }
        
        $placeholder = false;
        if (preg_match('/_____+/', $questiontext, $matches)) {
            $placeholder = $matches[0];
        }

        if ($placeholder) {
            $questiontext = substr_replace($questiontext, $input,
                    strpos($questiontext, $placeholder), strlen($placeholder));
        }
        
        $result = html_writer::start_tag('table');
        $result .= html_writer::start_tag('tr') . html_writer::tag('td', $questiontext);
        $result .= html_writer::start_tag('td', array('rowspan' => '3')) . $output
                    . html_writer::end_tag('td') . html_writer::end_tag('tr');
        $result .= html_writer::start_tag('tr') . html_writer::start_tag('td') . html_writer::start_tag('table') . html_writer::start_tag('tr')
                    . html_writer::start_tag('td', array('rowspan' => '2')) . html_writer::tag('textarea', $currentanswer, $textareaattributes) . html_writer::end_tag('td') 
                    . html_writer::start_tag('td') . 'slika za kvaèicu/x' . html_writer::end_tag('td');
        $result .= html_writer::end_tag('tr') . html_writer::start_tag('tr') . html_writer::start_tag('td') . 'button go' 
                    . html_writer::end_tag('td') . html_writer::end_tag('td') . html_writer::end_tag('table')
                    . html_writer::end_tag('td') . html_writer::end_tag('tr');
        $result .= html_writer::start_tag('tr') . html_writer::start_tag('td') . $img
                . html_writer::end_tag('td') . html_writer::end_tag('tr') . html_writer::end_tag('table');
        

        /* if ($qa->get_state() == question_state::$invalid) {
            $result .= html_writer::nonempty_tag('div',
                    $question->get_validation_error(array('answer' => $currentanswer)),
                    array('class' => 'validationerror'));
        }*/
        return $result;
    }
    
    protected static function get_url_for_image(question_attempt $qa, $filearea, $itemid = 0) {
        $question = $qa->get_question();
        $qubaid = $qa->get_usage_id();
        $slot = $qa->get_slot();
        $fs = get_file_storage();
        if ($filearea == 'ermodel') {
            $itemid = $question->id;
        }
        $componentname = $question->qtype->plugin_name();
        $draftfiles = $fs->get_area_files($question->contextid, $componentname,
                                                                        $filearea, $itemid, 'id');
        if ($draftfiles) {
            foreach ($draftfiles as $file) {
                if ($file->is_directory()) {
                    continue;
                }
                $url = moodle_url::make_pluginfile_url($question->contextid, $componentname,
                                            $filearea, "$slot/$qubaid/{$itemid}", '/',
                                            $file->get_filename());
                return $url->out();
            }
        }
        return null;
    }

    public function specific_feedback(question_attempt $qa) {
        // TODO.
        return '';
    }

    public function correct_response(question_attempt $qa) {
        // TODO.
        return '';
    }
}
