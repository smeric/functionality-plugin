<?php
/**
 * Paid Memberships Pro + Yoast SEO
 *
 * https://fr.wordpress.org/plugins/paid-memberships-pro/
 * https://fr.wordpress.org/plugins/wordpress-seo/
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
// Don't break if PMPro is not loaded
if ( ! class_exists( 'SFP_PMPro_Yoast_SEO' ) && defined( 'PMPRO_VERSION' ) ) {

    class SFP_PMPro_Yoast_SEO {

        /**
         * Initialize the class
         *
         * @since  1.0
         * @access public
         * @return void
         */
        static public function init() {
            // Don't break if PMPro is not loaded
            /*if ( ! defined( 'PMPRO_VERSION' ) ) {
                return false;
            }*/

            /**
             * Actions
             * add_action( 'hook_name', array( __class__, 'function_name' ), 10, 2 );
             */
            add_action( 'wp_head', array( __class__, 'test' ) );


            /**
             * Filters
             * add_filter( 'hook_name', array( __class__, 'function_name' ) );
             */
            // Filter the Membership Checkout meta description using Yoast SEO.
            add_filter( 'wpseo_metadesc', array( __class__, 'filter_wpseo_metadesc' ) );
            // Filter the Membership Checkout wp_title using Yoast SEO.
            add_filter( 'wpseo_title', array( __class__, 'my_pmpro_filter_wpseo_title' ) );


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */

        }

        static public function test(){echo "<!-- SFP_PMPro_Yoast_SEO test() function loaded ! -->" . PHP_EOL;}


        /**
         * Filter the Membership Checkout meta description using Yoast SEO.
         *
         * TODO : Update below to your preferred dynamic page description.
         *
         * @see     https://gist.github.com/strangerstudios/c188f7383410ee57374c
         *
         * @since   1.0
         * @access  public
         * 
         * @param   string  $description  Page meta description
         * @return  string
         */
        static public function filter_wpseo_metadesc( $description ) {
            global $pmpro_pages, $pmpro_level;
            if ( is_page( $pmpro_pages['checkout'] ) && isset( $pmpro_level->description ) ) {
                $description = $pmpro_level->description;
            }
            return $description;
        }


        /**
         * Filter the Membership Checkout wp_title using Yoast SEO.
         *
         * TODO : Update below to your preferred dynamic page title
         *
         * @see     https://gist.github.com/strangerstudios/6c75c90d81ba04388d0a
         *
         * @since   1.0
         * @access  public
         * 
         * @param   string  $title  Page title
         * @return  string
         */
        static function my_pmpro_filter_wpseo_title( $title ) {
            global $pmpro_pages, $pmpro_level;
            if ( is_page( $pmpro_pages['checkout'] ) && isset( $pmpro_level->name ) ) {
                $title = sprintf( __( '%s: complete your membership checkout', 'functionality-plugin' ), $pmpro_level->name );
            }
            return $title;
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
