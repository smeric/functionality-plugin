<?php
/**
 * Gravity forms
 * 
 * see https://www.gravityforms.com/
 *
 * @package    SFP
 * @subpackage SFP/includes
 * @copyright  Copyright (c) 2016, Sébastien Méric
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since      1.0
 * @author     Sébastien Méric <sebastien.meric@gmail.com>
 * 
 * TODO : Move this file to the classes directory to activate it.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Main class.
if ( ! class_exists( 'SFP_Gravity_Forms' ) ) {

    class SFP_Gravity_Forms {

        /**
         * Initialize the class
         *
         * @since  1.0
         * @access public
         * @return void
         */
        static public function init() {
            /**
             * Actions
             * add_action( 'hook_name', array( __class__, 'function_name' ), 10, 2 );
             */
            add_action( 'wp_head', array( __class__, 'test' ) );

            // Auto login after registration for user registration addon
            // See https://www.gravityforms.com/add-ons/user-registration/
            //add_action( 'gform_user_registered', array( $this, 'registration_autologin' ),  10, 4 );


            /**
             * Filters
             * add_filter( 'hook_name', array( __class__, 'function_name' ) );
             */


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
        }

        static public function test(){echo "<!-- SFP_Gravity_Forms test() function loaded ! -->" . PHP_EOL;}

        /**
         * Auto login after registration.
         *
         * The default behavior in WordPress after registration is to require the user
         * to login with the account information that they just created. This login
         * requirement can be an annoying and extra step for the user and isn’t necessary.
         *
         * @see https://wpcodeus.com/gravity-forms-automatically-login-user/
         * @requires https://www.gravityforms.com/add-ons/user-registration/
         *
         * @since   1.0
         *
         * @access  public
         * @param   int     $user_id     Current user ID
         * @param   ???  $user_config  ???
         * @param   ???  $entry  ???
         * @param   string  $password  Password
         * @return  void
         */
        static public function registration_autologin( $user_id, $user_config, $entry, $password ) {
            $user = get_userdata( $user_id );
            $user_login = $user->user_login;
            $user_password = $password;
            $user->set_role( get_option( 'default_role', 'subscriber' ) );

            wp_signon( array(
                'user_login'    => $user_login,
                'user_password' =>  $user_password,
                'remember'      => false
            ));
        }

        /**
         * Any function...
         *
         * @since   1.0
         * @access  public
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void
         */
        static public function function_name( $int, $string, $array ) {
            // Do whatever...
        }

    }

}
