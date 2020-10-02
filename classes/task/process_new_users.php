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
 * Task to proces new users created by wizard
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   block_createuser
 * @copyright 16-09-20 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Wishal Fakira
 **/

namespace block_createuser\task;

global $CFG;
require_once($CFG->dirroot . '/user/lib.php');

use block_createuser\users;
use core\task\scheduled_task;

defined('MOODLE_INTERNAL') || die();

/**
 * Class process_new_users
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   block_createuser
 * @copyright 16-09-20 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Wishal Fakira
 */
class process_new_users extends scheduled_task {

    /**
     * @return \lang_string|string
     * @throws \coding_exception
     */
    public function get_name() {
        return get_string('task:process_new_users', 'block_createuser');
    }

    /**
     * Do the job.
     * Throw exceptions on errors (the job will be retried).
     */
    public function execute() {
        global $DB;
        $wizarddata = $DB->get_records('block_createuser', [
            'is_processed' => 0,
        ]);

        foreach ($wizarddata as $data) {
            users::create_users(unserialize($data->usersdata), $data->createdby);
        }

        $DB->delete_records('block_createuser', [
            'is_processed' => 0,
        ]);
    }
}