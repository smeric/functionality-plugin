<?php
/**
 * Yoast SEO
 *
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
if ( ! class_exists( 'SFP_Yoast_SEO' ) && defined( 'WPSEO_VERSION' ) ) {

    class SFP_Yoast_SEO {

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

            // A tester ---------------------------------------------------------------
            // Remove WPSEO Notifications
            /*
            if ( class_exists( 'Yoast_Notification_Center' ) ) {
                remove_action( 'admin_notices', array( Yoast_Notification_Center::get(), 'display_notifications' ) );
                remove_action( 'all_admin_notices', array( Yoast_Notification_Center::get(), 'display_notifications' ) );
            }
            */


            /**
             * Filters
             * add_filter( 'hook_name', array( __class__, 'function_name' ) );
             */
            // Add Taxonomy name as a prefix to term name in breadcrumb.
            add_filter( 'wpseo_breadcrumb_links', array( __class__, 'taxonomies_breadcrumb_links' ) );

            // A tester ---------------------------------------------------------------
            // Don't let WPSEO metabox be high priority
            //add_filter( 'wpseo_metabox_prio', function(){ return 'low'; } );
            // Limit the number of sitemap entries
            //add_filter( 'wpseo_sitemap_entries_per_page', array( __class__, 'max_entries_per_sitemap' ) );
            // Exclude multiple content types from sitemap
            //add_filter( 'wpseo_sitemap_exclude_post_type', array( __class__, 'sitemap_exclude_post_type' ), 10, 2 );
            // Exclude multiple taxonomies from sitemap
            //add_filter( 'wpseo_sitemap_exclude_taxonomy', array( __class__, 'sitemap_exclude_taxonomy' ), 10, 2 );


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */

        }

        static public function test(){echo "<!-- SFP_Yoast_SEO test() function loaded ! -->" . PHP_EOL;}

        /**
         * Add Taxonomy name as a prefix to term name.
         *
         * @since   0.1
         * @access  public
         * @author  Sébastien Méric <sebastien.meric@gmail.com>
         *
         * @param   array   $breadcrumb   Breadcrumb elements
         * @return  array
         **/
        static public function taxonomies_breadcrumb_links( $breadcrumb ) {
            //print_r($breadcrumb);
            $index = count( $breadcrumb ) - 1;
            if ( isset ( $breadcrumb[$index]['term'] ) ) {
                $prefix = '%s';
                if ( 'category' == $breadcrumb[$index]['term']->taxonomy ) {
                    $prefix = __( 'Category : %s', 'functionality-plugin' );
                }
                elseif ( 'tag' == $breadcrumb[$index]['term']->taxonomy ) {
                    $prefix = __( 'Tag : %s', 'functionality-plugin' );
                }

                do_action( 'sfp_taxonomies_breadcrumb_links', $prefix, $breadcrumb, $index );

                $breadcrumb[$index]['term']->name = sprintf( $prefix, $breadcrumb[$index]['term']->name );
            }
            //print_r($breadcrumb);
            //die();

            return $breadcrumb;
        }

        /**
         * Limit the number of sitemap entries
         *
         * Credit: Yoast Developers
         * Last Tested: Jan 31 2019 using Yoast SEO 9.5 on WordPress 5.0.3
         * Yoast SEO defaults to 1000
         * Google allows up to 50000 URLs or 50MB (uncompressed)
         *
         * @source https://kb.yoast.com/kb/enable-xml-sitemaps-in-the-wordpress-seo-plugin/
         *
         * @since   0.1
         * @access  public
         * @author  Yoast Developers
         *
         * @return  int  Maximum number of sitemap entries to display
         **/
        static public function max_entries_per_sitemap() {
            return 100;
        }

        /**
         * Exclude multiple content types from sitemap
         *
         * @source https://kb.yoast.com/kb/how-to-customize-the-sitemap-index/
         *
         * @since   1.0
         * @access  public
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void/boolean
         **/
        static public function sitemap_exclude_post_type( $value, $post_type ) {
            $post_type_to_exclude = array( 'post_type_slug1', 'post_type_slug2', 'post_type_slug3' );

            if ( in_array( $post_type, $post_type_to_exclude ) ) {
                return true;
            }
        }

        /**
         * Exclude multiple taxonomies from sitemap
         *
         * @source https://kb.yoast.com/kb/how-to-customize-the-sitemap-index/
         *
         * @since   1.0
         * @access  public
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void/boolean
         **/
        static public function sitemap_exclude_taxonomy( $value, $taxonomy ) {
            $taxonomy_to_exclude = array( 'taxonomy_slug1', 'taxonomy_slug2', 'taxonomy_slug3' );

            if ( in_array( $taxonomy, $taxonomy_to_exclude ) ) {
                return true;
            }
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
