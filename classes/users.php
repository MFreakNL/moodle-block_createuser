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
 * @copyright 21-09-20 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Wishal Fakira
 **/

namespace block_createuser;

defined('MOODLE_INTERNAL') || die;

/**
 * Class create_users
 *
 * @copyright 16-09-20 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Wishal Fakira
 */
class users {

    /**
     * @param $users
     */
    public static function create_task_form_wizard(array $users) : void {
        global $DB, $USER;
        if (empty($users)) {
            return;
        }
        $DB->insert_record('block_createuser', (object)[
            'usersdata' => serialize($users),
            'is_processed' => 0,
            'timecreated' => time(),
            'createdby' => $USER->id,

        ]);

    }

    /**
     * @param $user
     */
    protected static function create_single_user($user) : void {

        global $DB;

        $user->username = $user->email;
        $user->lang = 'nl';
        $user->id = user_create_user($user, false, false);

        $user = $DB->get_record('user', ['id' => $user->id]);
            echo '<pre>';print_r($user);echo '</pre>';
        setnew_password_and_mail($user);
        unset_user_preference('create_password', $user);
        set_user_preference('auth_forcepasswordchange', 1, $user);
    }

    /**
     * @param $users
     */
    public static function create_users(array $users) : void {

        array_map('static::create_single_user', $users);
    }
}
 