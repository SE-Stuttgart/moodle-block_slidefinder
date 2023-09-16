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
 * Block core and UI
 *
 * @package    block_slidefinder
 * @copyright  2022 Universtity of Stuttgart <kasra.habib@iste.uni-stuttgart.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/locallib.php');

class block_slidefinder extends block_base {
    /**
     * Set the initial properties for the block
     */
    function init() {
        $this->title = get_string('pluginname', get_class($this));
    }

    /**
     * Describe Block Content.
     */
    public function get_content() {
        global $OUTPUT, $PAGE, $DB, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        // Params.
        $cid = optional_param('id', 0, PARAM_INT);          // Do we have a set course id? Or are we on our dashboard (default).
        $lrf_cid = optional_param('lrf_cid', 0, PARAM_INT); // Selected course ID (by our course selection).
        $search = optional_param('search', '', PARAM_TEXT); // Searched pattern (search hook).

        // Main Content (text) and Footer of the block
        $text = '';
        $footer = '';

        try {
            // Get all current params.
            $hiddenparams = $_GET;

            // Filter out 'lrf_cid' as a param we use and change.
            $hiddenparams = array_filter($hiddenparams, function ($key) {
                return $key !== 'lrf_cid';
            }, ARRAY_FILTER_USE_KEY);

            // Restructure (for mustache) the name=>value list into a list of array objects having the name and value attribute.
            $hiddenparams = array_map(function ($name, $value) {
                return array("name" => $name, "value" => $value);
            }, array_keys($hiddenparams), $hiddenparams);

            if ($cid == 0) { // My Moodle Page.
                if ($lrf_cid != 0) {
                    // Course.
                    if (!$course = $DB->get_record('course', array('id' => $lrf_cid))) {
                        throw new moodle_exception(get_string('error_course_not_found', 'block_slidefinder'));
                    }
                    // Does the user have access to the course?
                    if (!can_access_course($course)) {
                        throw new moodle_exception(get_string('error_course_access_denied', 'block_slidefinder'));
                    }
                } else {
                    $course = null;
                }
                $text .= $OUTPUT->render_from_template('block_slidefinder/lrf_drop_down', [
                    'action' => $PAGE->url,
                    'course_selector_param_name' => 'lrf_cid',
                    'course_selector_options' => block_slidefinder_select_course_options($lrf_cid),
                    'hidden_params' => $hiddenparams
                ]);
            } else { // Course Page.
                // Course.
                if (!$course = $DB->get_record('course', array('id' => $cid))) {
                    throw new moodle_exception(get_string('error_course_not_found', 'block_slidefinder'));
                }
                // Does the user have access to the course?
                if (!can_access_course($course)) {
                    throw new moodle_exception(get_string('error_course_access_denied', 'block_slidefinder'));
                }
            }

            $data = [[], []];
            if (!is_null($course)) {
                $data = block_slidefinder_get_content_as_chapters_for_all_book_pdf_matches_from_course($course->id, $USER->id);
                if (!empty($data[1])) {
                    $footer .= get_string('misconfigured_info', get_class($this));
                    foreach ($data[1] as $key => $value) {
                        $footer .= '<br>';
                        $footer .= $value;
                    }
                }
            }

            $text .= $OUTPUT->render_from_template('block_slidefinder/lrf_search', [
                'action' => $PAGE->url,
                'lrf_cid' => $lrf_cid,
                'course_selector_param_name' => 'lrf_cid',
                'search_term_param_name' => 'search',
                'search_term_placeholder' => get_string('search', get_class($this)),
                'search_term_label' => get_string('search_term', get_class($this)),
                'search_term' => $search,
                'chapter_label' => get_string('chapter', get_class($this)),
                'content' => base64_encode(json_encode($data[0])),
                'hidden_params' => $hiddenparams
            ]);
        } catch (\Throwable $th) {
            debugging($th);
            $text .= get_string('error_message', get_class($this));
            $footer .= $th;
        }

        $this->content = new stdClass();
        $this->content->text = $text;
        $this->content->footer = $footer;
        return $this->content;
    }
}
