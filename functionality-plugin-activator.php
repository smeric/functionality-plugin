<?php
/**
 * Fired during plugin activation
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

// This class defines all code necessary to run during the plugin's activation.
if ( ! class_exists( 'SFP_Activator' ) ) {

    class SFP_Activator {

        /**
         * Fired during plugin activation.
         *
         * @since  1.0
         * @access public static
         * @return void
         */
        public static function activate() {
            flush_rewrite_rules();

            /**
             * Disable critical capabilities.
             */

            /*
            // Get the role object.
            $administrator = get_role( 'administrator' );

            // A list of capabilities to remove from administrators.
            $caps = array(
                'install_themes',
                'switch_themes',
                'install_plugins',
            );

            foreach ( $caps as $cap ) {
                // Remove the capability.
                $administrator->remove_cap( $cap );
            }
            */

        }

    }

}
