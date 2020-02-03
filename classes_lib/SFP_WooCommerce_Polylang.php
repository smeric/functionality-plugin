<?php
/**
 * WooCommerce + Polylang
 *
 * https://fr.wordpress.org/plugins/woocommerce/
 * https://fr.wordpress.org/plugins/polylang/
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
if ( ! class_exists( 'SFP_WooCommerce_Polylang' ) && class_exists( 'WooCommerce' ) && class_exists( 'Polylang' ) ) {

    class SFP_WooCommerce_Polylang {

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
            //add_filter('woocommerce_get_checkout_url',             array( __class__, 'translate_page' ) );
            add_filter( 'woocommerce_get_checkout_page_id',        array( __class__, 'translate_page' ) );
            add_filter( 'woocommerce_get_cart_page_id',            array( __class__, 'translate_page' ) );
            add_filter( 'woocommerce_get_myaccount_page_id',       array( __class__, 'translate_page' ) );
            add_filter( 'woocommerce_get_edit_address_page_id',    array( __class__, 'translate_page' ) );
            add_filter( 'woocommerce_get_view_order_page_id',      array( __class__, 'translate_page' ) );
            add_filter( 'woocommerce_get_change_password_page_id', array( __class__, 'translate_page' ) );
            add_filter( 'woocommerce_get_thanks_page_id',          array( __class__, 'translate_page' ) );
            add_filter( 'woocommerce_get_shop_page_id',            array( __class__, 'translate_page' ) );
            add_filter( 'woocommerce_get_terms_page_id',           array( __class__, 'translate_page' ) );
            add_filter( 'woocommerce_get_pay_page_id',             array( __class__, 'translate_page' ) );


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
        }

        static public function test(){echo "<!-- SFP_WooCommerce_Polylang test() function loaded ! -->" . PHP_EOL;}

        /**
         * You can translate your products and product categories in your wp-admin, but WooCommerce
         * can not figure out to shift properly between your translations on the frontend. This happens
         * because WooCommerce does not natively support Polylang’s translations...
         *
         * @see https://jesperlundnielsen.dk/how-to-make-woocommerce-work-with-polylang-wordpress/
         * @see https://github.com/gdarko/Woocommerce-Polylang-Integration
         * @see https://gist.github.com/damiencarbery/a9c4299613f09761b3d3fcfc3c6f0177
         * @see https://wordpress.org/plugins/woocommerce-polylang-integration/
         *
         * @since  1.0
         * @access public
         * @param  int    $int    Integer
         * @param  string $string String
         * @param  array  $array  Array
         * @return void
         */
        static public function translate_page( $page ) {
            return function_exists( 'pll_get_post' ) ? pll_get_post( $page ) : $page;
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
