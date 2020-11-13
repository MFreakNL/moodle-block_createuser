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
 * Users related functions
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   block_createuser
 * @copyright 21-09-20 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Wishal Fakira
 **/

namespace block_createuser;

defined('MOODLE_INTERNAL') || die;
require_once($CFG->dirroot . '/group/lib.php');

/**
 * Class users
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   block_createuser
 * @copyright 21-09-20 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Wishal Fakira
 */
class users {

    /**
     * Create task from wizard
     *
     * @param array $users
     *
     * @throws \dml_exception
     */
    public static function create_task_form_wizard(array $users) : void {
        global $DB, $USER;
        if (empty($users)) {
            return;
        }

        $DB->insert_record('block_createuser', (object)[
            'usersdata' => json_encode($users),
            'is_processed' => 0,
            'timecreated' => time(),
            'createdby' => $USER->id,
        ]);
    }

    /**
     * create_single_user
     *
     * @param array $user
     * @param int   $key
     * @param array $data
     */
    protected static function create_single_user(array $user, int $key, array $data) : void {
        global $DB, $CFG;
        try {
            $user = (object)$user;
            $user->username = strtolower($user->email);
            $user->mnethostid = $CFG->mnet_localhost_id;
            $user->lang = $CFG->lang;
            $user->calendartype = $CFG->calendartype;
            $user->secret      = random_string(15);
            $user->id = user_create_user($user, false, false);

            $user = $DB->get_record('user', ['id' => $user->id]);
            $fieldid = get_config('block_createuser', 'profile_user_link');

            if (!empty($fieldid)) {
                helper::update_user_profile_value($user->id, $fieldid, $data['createdby']);
            }

            // Sends email with password to user.
            setnew_password_and_mail($user);
            unset_user_preference('create_password', $user);
            set_user_preference('auth_forcepasswordchange', 1, $user);
            $courseids = helper::get_courseids_from_settings();

            // Enrol users to all courses.
            array_walk($courseids, 'static::enrol', ['user' => $user, 'createdby' => $data['createdby']]);

        } catch (\Exception $exception) {
            mtrace('Error creating user: ' . $exception->getMessage());
        }
    }

    /**
     * unset_session
     */
    public static function unset_session() : void {
        global $SESSION;
        unset($SESSION->block_createuser);
    }

    /**
     * Create user
     *
     * @param array $users
     * @param int   $createdby
     */
    public static function create_users(array $users, int $createdby) : void {
        array_walk($users, 'static::create_single_user', ['createdby' => $createdby]);
    }

    /**
     * Enrol
     *
     * @param int   $courseid
     * @param int   $key
     * @param array $userdata
     *
     * @return void
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function enrol(int $courseid, $key, $userdata) : void {
        global $DB;

        $user = $userdata['user'];
        if (empty($user)) {
            return;
        }

        // Check if we need to enrol users for a new course.
        $enrol = enrol_get_plugin('manual');

        if ($enrol === null) {
            return;
        }

        $instance = $DB->get_record('enrol', [
            'courseid' => $courseid,
            'enrol' => 'manual',
        ], '*');

        if (empty($instance)) {
            return;
        }

        $now = time();
        $period = get_config('block_createuser', 'enrolment_duration');
        $timeend = $now + $period;
        $enrol->enrol_user($instance,
            $user->id,
            get_config('block_createuser', 'role'),
            $now,
            $timeend,
            ENROL_USER_ACTIVE,
            true
        );

        $groupsfrommanager = self::get_manager_groups($courseid, $userdata['createdby']);
        if (empty($groupsfrommanager)) {
            return;
        }

        foreach ($groupsfrommanager as $groups) {
            foreach ($groups as $groupid) {
                groups_add_member($groupid, $user->id);
            }
        }
    }

    /**
     * Get manager groups
     *
     * @param int $courseid
     * @param int $createdby
     *
     * @return mixed
     */
    private static function get_manager_groups(int $courseid, int $createdby) {
        static $holder = [];
        $key = $courseid . '-' . $createdby;

        if (isset($holder[$key])) {
            return $holder[$key];
        }
        $holder[$key] = groups_get_user_groups($courseid, $createdby);

        return $holder[$key];
    }
}
