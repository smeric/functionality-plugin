<?php
/**
 * Contact Form 7
 * 
 * https://fr.wordpress.org/plugins/contact-form-7/
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
if ( ! class_exists( 'SFP_CF7' ) ) {

    class SFP_CF7 {

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


            /**
             * Filters
             * add_filter( 'hook_name', array( __class__, 'function_name' ) );
             */
            // Custom ajax loader
            //add_filter( 'wpcf7_ajax_loader', array( __class__, 'ajax_loader' ) );


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
            
            /**
             * Shortcode utilisé pour générer une chaine de charactères unique
             * pour l'extension : Contact Form 7 Dynamic Text Extension
             * https://wordpress.org/plugins/contact-form-7-dynamic-text-extension/
             * [dynamichidden rnd "CF7_RND"]
             */
            //add_shortcode( 'CF7_RND', array( __class__, 'random_string' ) );
        }

        static public function test(){echo "<!-- SFP_CF7 test() function loaded ! -->" . PHP_EOL;}

        /**
         * Retourne une chaine de charactère aléatoire "unique"
         *
         * @since   1.0
         * @access  public
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void
         */
        static public function random_string(){
            return md5( uniqid( rand(), true ) );
        }

        /**
         * Custom ajax loader
         *
         * @since   1.0
         * @access  public
         * 
         * @return  string
         */
        static public function ajax_loader() {
            $main_class = Functionality_Plugin::get_instance();
            return trailingslashit( $main_class->plugin_url ) . 'assets/public/images/ajax-loader.gif';
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
