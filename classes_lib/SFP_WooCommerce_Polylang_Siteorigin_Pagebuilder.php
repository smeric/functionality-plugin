<?php
/**
 * WooCommerce + Polylang + Siteorigin Pagebuilder
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
if ( ! class_exists( 'SFP_WooCommerce_Polylang_Siteorigin_Pagebuilder' )
    && class_exists( 'WooCommerce' )
    && class_exists( 'Polylang' )
) {

    class SFP_WooCommerce_Polylang_Siteorigin_Pagebuilder {

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

			add_action( 'pll_rewrite_rules', array( __class__, 'fix_rewrite_rules' ) );


            /**
             * Filters
             * add_filter( 'hook_name', array( __class__, 'function_name' ) );
             */


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
        }

        static public function test(){echo "<!-- SFP_WooCommerce_Polylang_Siteorigin_Pagebuilder test() function loaded ! -->" . PHP_EOL;}

		/**
		 * When PageBuilder is enabled, Polylang is not able to display the localized WooCommerce
		 * single product page and displays a 404 error.
		 *
		 * @see https://siteorigin.com/thread/strange-problem-with-pagebuilder-polylang-woocommerce/
		 *
		 * @since  1.0
		 * @author David Briard
		 * @access public
		 * @param  int    $int    Integer
		 * @param  string $string String
		 * @param  array  $array  Array
		 * @return void
		 */
		static public function fix_rewrite_rules( $types ) {
			$force_rewrite = array( 'product', );
			$types = array_merge( $force_rewrite, $types );
			return $types;
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
