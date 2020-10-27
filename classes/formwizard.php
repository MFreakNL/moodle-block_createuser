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

use stdClass;

defined('MOODLE_INTERNAL') || die;

/**
 * Class create_users
 *
 * @copyright 16-09-20 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Wishal Fakira
 */
class formwizard {

    /**
     * @param stdClass $data
     */
    public static function add_user(stdClass $data) : void {
        global $SESSION;

        if (empty($SESSION->block_createuser)) {
            $SESSION->block_createuser = [];
        }

        $SESSION->block_createuser[] = $data;

    }

    /**
     * @param int $index
     */
    public static function delete_user(int $index) : void {
        global $SESSION;
        unset($SESSION->block_createuser[$index]);
    }
}