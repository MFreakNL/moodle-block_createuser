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
 * Block instance
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   block_createuser
 * @copyright 16-09-20 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Wishal Fakira
 **/

/**
 * Class block_createuser
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   block_createuser
 * @copyright 31-10-20 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Wishal Fakira
 */
class block_createuser extends block_base {

    /**
     * Init.
     *
     * @return void
     * @throws coding_exception
     */
    public function init() : void {
        $this->title = get_string('pluginname', 'block_createuser');
    }

    /**
     * Subclasses should override this and return true if the
     * subclass block has a settings.php file.
     *
     * @return boolean
     */
    public function has_config() {
        return true;
    }

    /**
     * Applicable formats.
     *
     * @return array
     */
    public function applicable_formats() {
        return ['all' => true];
    }

    /**
     * Specialization.
     *
     * Happens right after the initialisation is complete.
     *
     * @return void
     * @throws coding_exception
     */
    public function specialization() {
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_createuser');
        } else {
            $this->title = $this->config->title;
        }
    }

    /**
     * The content object.
     *
     * @return stdObject
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function get_content() {
        global $COURSE;

        if ($this->content !== null) {
            return $this->content;
        }

        if ((!isloggedin() || isguestuser())) {
            $this->content = new stdClass();
            $this->content->text = '';

            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        // Params.
        $params = [];
        $params['blockid'] = $this->instance->id;
        $params['courseid'] = $COURSE->id;

        if (has_capability('block/createuser:manager', $this->context)) {
            $renderer = $this->page->get_renderer('block_createuser');
            $this->content->text .= $renderer->button_wizard($params);
        }

        return $this->content;
    }

}