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


}