<?php
/**
 * File: formwizard.php
 * Encoding: UTF8
 *
 * @package: block_createuser
 *
 * @Version: 1.0.0
 * @Since  21-09-20
 * @Author : Mfreak.nl | LdesignMedia.nl - Wishal Fakira
 **/

namespace block_createuser;

defined('MOODLE_INTERNAL') || die;

/**
 * Class create_users
 *
 * @copyright 16-09-20 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Wishal Fakira
 */
class formwizard {

    /**
     * @param object $data
     */
    public static function add_user(object $data) : void {
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