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

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $settings->add(
        new admin_setting_configselect(
            'block_createuser/role',
            get_string('settings:selectable_role', 'block_createuser'),
            get_string('settings:selectable_role_desc', 'block_createuser'),
            '', // Default.
            \block_createuser\helper::get_all_roles()
        )
    );
    $settings->add(
        new admin_setting_configtext(
            'block_createuser/courseids',
            get_string('settings:courseids', 'block_createuser'),
            get_string('settings:courseids_desc', 'block_createuser'),
            '', // Default.
            PARAM_TEXT
        )
    );

    $oneday = 86400;
    $settings->add(new admin_setting_configduration('block_createuser/enrolment_duration',
        get_string('setting:enrolment_duration', 'block_createuser'),
        get_string('setting:enrolment_duration_desc', 'block_createuser'), $oneday * 60));

    $profilefields = \block_createuser\helper::get_profile_fields();

    $settings->add(new admin_setting_configselect('block_createuser/profile_user_link',
        get_string('setting:user_link', 'block_createuser'),
        get_string('setting:user_link_desc', 'block_createuser'), '',
        $profilefields));
}
