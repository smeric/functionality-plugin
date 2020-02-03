<?php
/**
 * WooCommerce + Yoast SEO
 *
 * https://fr.wordpress.org/plugins/woocommerce/
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
if ( ! class_exists( 'SFP_WooCommerce_Yoast_SEO' ) && class_exists( 'WooCommerce' ) && defined( 'WPSEO_VERSION' ) ) {

    class SFP_WooCommerce_Yoast_SEO {

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
            add_filter( 'wpseo_breadcrumb_links', array( __class__, 'override_yoast_breadcrumb_trail' ) );


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */

        }

        static public function test(){echo "<!-- SFP_WooCommerce_Yoast_SEO test() function loaded ! -->" . PHP_EOL;}

        /**
         * Add WooCommerce endpoints to Yoast SEO breadcrumbs.
         *
         * @since   0.1
         * @access  public
         * @author  Sébastien Méric <sebastien.meric@gmail.com>
         *
         * @param   array   $links   Links used in breadcrumbs
         * @return  array
         **/
        static public function override_yoast_breadcrumb_trail( $links ) {
            if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url() ) {
                // Remove the last element : "My account" unlinked title...
                array_pop( $links );
                // ... And replace it with the linked one
                $links[] = array(
                    'url'  => get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ),
                    'text' => get_the_title( get_option( 'woocommerce_myaccount_page_id' ) ),
                );

                // Get endpoints list
                $endpoints = WC()->query->get_query_vars();

                // For each endpoint, add its title at breadcrumbs end
                foreach ( $endpoints as $endpoint => $translated_endpoint ) {
                    if ( is_wc_endpoint_url( $endpoint ) ) {
                        // Special case for addresses editon pages
                        if ( $endpoint === 'edit-address' ) {
                            global $wp;

                            $load_address = isset( $wp->query_vars['edit-address'] ) ? wc_edit_address_i18n( sanitize_title( $wp->query_vars['edit-address'] ), true ) : 'billing';

                            if ( 'billing' === $load_address ) {
                                // For billing address editon page
                                $links[] = array(
                                    'url'  => wc_get_endpoint_url( $endpoint ),
                                    'text' => wc_page_endpoint_title( $endpoint ),
                                );
                                $links[] = array(
                                    'url'  => '',
                                    'text' => __( 'Billing address', 'woocommerce' ),
                                );
                                break;
                            }
                            elseif ( 'shipping' === $load_address ) {
                                // For shipping address editon page
                                $links[] = array(
                                    'url'  => wc_get_endpoint_url( $endpoint ),
                                    'text' => wc_page_endpoint_title( $endpoint ),
                                );
                                $links[] = array(
                                    'url'  => '',
                                    'text' => __( 'Shipping address', 'woocommerce' ),
                                );
                                break;
                            }
                            else {
                                // For addresses editon page "menu"
                                $links[] = array(
                                    'url'  => '',
                                    'text' => wc_page_endpoint_title( $endpoint ),
                                );
                                break;
                            }
                        }
                        // Default case : add current endpoint title with no link at breadcrumbs end
                        else {
                            $links[] = array(
                                'url'  => '',
                                'text' => wc_page_endpoint_title( $endpoint ),
                            );
                            break;
                        }
                    }
                }
            }

            return $links;
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
