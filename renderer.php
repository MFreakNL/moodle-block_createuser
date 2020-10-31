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
 * UI renders
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   block_createuser
 * @copyright 16-09-20 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Wishal Fakira
 **/

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/tablelib.php');

/**
 * Class block_createuser_renderer
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   block_createuser
 * @copyright 16-09-20 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Wishal Fakira
 */
class block_createuser_renderer extends plugin_renderer_base {

    /**
     * Button wizard
     *
     * @param array $params
     *
     * @return string
     * @throws moodle_exception
     */
    public function button_wizard(array $params) : string {
        return html_writer::link(new moodle_url('/blocks/createuser/view/wizard.php', $params),
            get_string('btn:admin', 'block_createuser'), ['class' => 'btn btn-primary']);
    }

    /**
     * Table wizard users
     *
     * @return string
     * @throws moodle_exception
     */
    public function table_wizard_users() : string {
        $templatedata = new stdClass();
        $templatedata->users = \block_createuser\helper::get_all_users();

        return $this->render_from_template('block_createuser/overview_users', $templatedata);
    }
}