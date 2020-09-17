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

require_once(__DIR__ . '/../../../config.php');

defined('MOODLE_INTERNAL') || die();

$blockid = required_param('blockid', PARAM_INT);
$action = optional_param('action', '', PARAM_TEXT);

$blockcontext = context_block::instance($blockid);

require_login();

require_capability('block/createuser:manager', $blockcontext);

$PAGE->set_url('/blocks/createuser/view/wizard.php', ['blockid' => $blockid, 'action' => $action]);

$PAGE->set_context($blockcontext);
$PAGE->set_heading($SITE->fullname);
/** @var block_createuser_renderer $renderer */
$renderer = $PAGE->get_renderer('block_createuser');
switch ($action) {

    case 'confirm':
//        echo '<pre>';print_r($SESSION);echo '</pre>';die(__LINE__.' '.__FILE__);

        echo $OUTPUT->header();

        echo $renderer->table_wizard_users();
        echo $form->render();
        echo $OUTPUT->footer();
        break;

    default:
        $form = new \block_createuser\form\form_new_users($PAGE->url);
        if ($form->is_cancelled()) {
            redirect(new moodle_url('/'));
        }

        if (($data = $form->get_data()) != false) {
            $SESSION->block_createuser = serialize($data);
            redirect(new moodle_url('/blocks/createuser/view/wizard.php', [
                'action' => 'confirm',
                'blockid' => $blockid,
            ]));
        }

        echo $OUTPUT->header();
        echo $form->render();
        echo $OUTPUT->footer();
}
