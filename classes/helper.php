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

namespace block_createuser;

use ArrayIterator;
use stdClass;

defined('MOODLE_INTERNAL') || die;

/**
 * Class helper
 *
 * @copyright 16-09-20 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Wishal Fakira
 */
class helper {

    /**
     * @return ArrayIterator
     * @throws \moodle_exception
     */
    public static function get_all_users() : ArrayIterator {
        global $SESSION, $PAGE;
        $users = [];
        $formdata = $SESSION->block_createuser ?? [];

        foreach ($formdata as $i => $user) {
            $user->actionurl = (new \moodle_url('/blocks/createuser/view/wizard.php', [
                'index' => $i,
                'action' => 'deleteuser',
                'blockid' => $PAGE->url->get_param('blockid'),
            ]))->out(false);
            $users[$i] = $user;
        }

        return new ArrayIterator($users);
    }

    /**
     * @return array
     * @throws \dml_exception
     */

    public static function get_courseids_from_settings() : array {
        $results = [];
        $courseids = get_config('block_createuser', 'courseids');
        $courseids = explode(',', $courseids);

        foreach ($courseids as $courseid) {
            if (is_numeric($courseid) === false) {
                continue;
            }
            $results[$courseid] = $courseid;
        }

        return $results;
    }

    /**
     * @return array
     * @throws \dml_exception
     */
    public static function get_all_roles() : array {
        global $DB;

        return $DB->get_records_menu('role', null, 'sortorder ASC', 'id,shortname');
    }

    /**
     * Get all available profile fields
     *
     * @return arra
     * @throws \dml_exception
     */
    public static function get_profile_fields() : array {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/user/profile/lib.php');
        require_once($CFG->dirroot . '/user/profile/definelib.php');
        $rs = $DB->get_recordset_sql("SELECT f.* FROM {user_info_field} f ORDER BY name ASC");
        $fields = ['' => ''];
        foreach ($rs as $field) {
            $fields[$field->id] = $field->name;
        }
        $rs->close();
        if (empty($fields)) {
            return [];
        }

        return $fields;
    }

    /**
     * @param int    $userid
     * @param int    $fieldid
     * @param string $value
     *
     * @throws \dml_exception
     */
    public static function update_user_profile_value(int $userid, $fieldid, string $value) : void {
        global $DB;

        $row = $DB->get_record('user_info_data', [
            'userid' => $userid,
            'fieldid' => $fieldid,
        ]);

        $dataObject = new stdClass();
        $dataObject->userid = $userid;
        $dataObject->fieldid = $fieldid;
        $dataObject->data = s($value);

        if (!$row) {
            $DB->insert_record('user_info_data', $dataObject);

            return;
        }

        if ($value !== $row->data) {
            $dataObject->id = $row->id;
            $DB->update_record('user_info_data', $dataObject);
        }
    }

}