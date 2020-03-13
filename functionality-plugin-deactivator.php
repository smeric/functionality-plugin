<?php
/**
 * Fired during plugin deactivation
 * 
 * @package    SFP
 * @subpackage SFP/includes
 * @copyright  Copyright (c) 2016, Sébastien Méric
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since      1.0
 * @author     Sébastien Méric <sebastien.meric@gmail.com>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// This class defines all code necessary to run during the plugin's deactivation.
if ( ! class_exists( 'SFP_Deactivator' ) ) {

    class SFP_Deactivator {

        /**
         * Fired during plugin deactivation.
         *
         * @since  1.0
         * @access public static
         * @return void
         */
        public static function deactivate() {
            flush_rewrite_rules();
        }

    }

}
