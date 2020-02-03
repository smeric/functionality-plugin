<?php
/**
 * Insites Cookie Consent + Polylang
 *
 * https://github.com/insites/cookieconsent-wpplugin
 * https://cookieconsent.insites.com/
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
if ( ! class_exists( 'SFP_Insites_Cookie_Consent_Polylang' ) ) {

    class SFP_Insites_Cookie_Consent_Polylang {

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

            add_action( 'wp_loaded', array( __class__, 'polylang_integration' ) );


            /**
             * Filters
             * add_filter( 'hook_name', array( __class__, 'function_name' ) );
             */
            add_filter( 'option_icc_popup_options', array( __class__, 'translate_options' ), 10, 2 );


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
        }

        static public function test(){echo "<!-- SFP_Insites_Cookie_Consent_Polylang test() function loaded ! -->" . PHP_EOL;}

        /**
         * Register string on the admin side
         *
         * icc_popup_options is the js object used to populate the popup
         *
         * @since   1.0
         * @access  public
         * @return  void
         */
        static public function polylang_integration() {
            if ( function_exists( 'pll_register_string' ) ) {
                pll_register_string( 
                    __( 'Cookies popup options', 'functionality-plugin' ),
                    ( get_option('icc_popup_options') ? get_option('icc_popup_options') : '' ),
                    __( 'Insites cookie consent', 'functionality-plugin' ),
                    true
                );
            }
        }

        /**
         * Then display it translated on frontend
         *
         * @since   1.0
         * @access  public
         * @param   string  $value   Option value
         * @param   string  $option  Option name
         * @return  string
         */
        static public function translate_options( $value, $option ) {
            return ( function_exists( 'pll__' ) ? pll__( $value ) : $value );
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
