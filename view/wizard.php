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
 * Wizard creating users
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   block_createuser
 * @copyright 16-09-20 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Wishal Fakira
 **/

use block_createuser\form\form_new_users;
use block_createuser\form\wizard_approve_buttons;
use block_createuser\formwizard;
use block_createuser\users;

require_once(__DIR__ . '/../../../config.php');
defined('MOODLE_INTERNAL') || die();

$blockid = required_param('blockid', PARAM_INT);
$action = optional_param('action', '', PARAM_TEXT);
$index = optional_param('index', 0, PARAM_INT);

$blockcontext = context_block::instance($blockid);

require_login();
require_capability('block/createuser:manager', $blockcontext);

$PAGE->set_url('/blocks/createuser/view/wizard.php', [
    'blockid' => $blockid,
    'action' => $action,
    'index' => $index,
]);

$PAGE->set_context($blockcontext);
$PAGE->set_heading($SITE->fullname);

/** @var block_createuser_renderer $renderer */
$renderer = $PAGE->get_renderer('block_createuser');
switch ($action) {

    case 'addtask':
        users::create_task_form_wizard($SESSION->block_createuser);
        users::unset_session();
        redirect(new moodle_url('/', [

        ]), get_string('text:usersadded', 'block_createuser'));

        break;

    case 'deleteuser':
        formwizard::delete_user($index);
        redirect(new moodle_url($PAGE->url->get_path(), [
            'blockid' => $blockid,
            'action' => '',
        ]));
        break;

    default:

        $formadduser = new form_new_users($PAGE->url);
        $formapprovebtn = new wizard_approve_buttons(new moodle_url($PAGE->url->get_path(), [
            'blockid' => $blockid,
            'action' => 'addtask',
            'index' => $index,
        ]));

        if ($formadduser->is_cancelled()) {
            redirect(new moodle_url('/'));
        }
        if (($data = $formadduser->get_data()) != false) {

            formwizard::add_user($data);
            redirect(new moodle_url('/blocks/createuser/view/wizard.php', [
                'action' => '',
                'blockid' => $blockid,
            ]));
        }

        echo $OUTPUT->header();
        echo $formadduser->render();
        echo $renderer->table_wizard_users();

        if (!empty($SESSION->block_createuser)) {
            echo $formapprovebtn->render();
        }
        echo $OUTPUT->footer();
}
