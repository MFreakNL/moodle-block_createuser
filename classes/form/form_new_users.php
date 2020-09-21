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
 *
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   block_createuser
 * @copyright 16-09-20 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Wishal Fakira
 **/

namespace block_createuser\form;

defined('MOODLE_INTERNAL') || die;

global $CFG;

require_once($CFG->libdir . '/formslib.php');

/**
 * Class form_add_courses
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   block_createuser
 * @copyright 16-09-20 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Wishal Fakira
 */
class form_new_users extends \moodleform {

    protected function definition() {

        $mform = &$this->_form;

        $mform->addElement('text', 'firstname', get_string('form:firstname', 'block_createuser'));
        $mform->setType('firstname', PARAM_TEXT);
        $mform->addRule('firstname', get_string('error:firstname', 'block_createuser'), 'required', null, 'client');

        $mform->addElement('text', 'lastname', get_string('form:lastname', 'block_createuser'));
        $mform->setType('lastname', PARAM_TEXT);
        $mform->addRule('lastname', get_string('error:lastname', 'block_createuser'), 'required', null, 'client');

        $mform->addElement('text', 'email', get_string('form:email', 'block_createuser'));
        $mform->setType('email', PARAM_EMAIL);
        $mform->addRule('email', get_string('error:email', 'block_createuser'), 'required', null, 'client');

        $this->add_action_buttons(true, get_string('btn:add', 'block_createuser'));
    }

    function validation($data, $files) {
        global $DB;
        $errors = parent::validation($data, $files);
        if ($DB->record_exists('user', ['email' => $data['email']])) {
            $errors['email'] = get_string('error:email_used', 'block_createuser');
        }
        if ($DB->record_exists('user', ['username' => $data['email']])) {
            $errors['email'] = get_string('error:username_used', 'block_createuser');
        }

        return $errors;
    }
}