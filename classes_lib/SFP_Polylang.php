<?php
/**
 * Polylang allows you to create a bilingual or multilingual WordPress site. 
 * 
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
if ( ! class_exists( 'SFP_Polylang' ) ) {

    class SFP_Polylang {

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
            // Duplicate post content from original across translation
            //add_filter( 'default_content', array( __class__, 'content_copy' ) );
            // Duplicate post title from original across translation
            //add_filter( 'default_title', array( __class__, 'title_copy' ) );
            // Duplicate post excerpt from original across translation
            //add_filter( 'default_excerpt', array( __class__, 'excerpt_copy' ) );


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
        }

        static public function test(){echo "<!-- SFP_Polylang test() function loaded ! -->" . PHP_EOL;}

        /**
         * Duplicate post content from original across translation
         *
         * @see https://junaidbhura.com/make-polylang-wordpress-plugin-copy-the-content-from-the-original-post/
         *
         * @since   1.0
         * @access  public
         * @param   string  $content  Original post content
         * @return  string
         */
        static public function content_copy( $content ) {
            if ( isset( $_GET['from_post'] ) ) {
                $my_post = get_post( $_GET['from_post'] );
                if ( $my_post )
                    return $my_post->post_content;
            }
            return $content;
        }

        /**
         * Duplicate post title from original across translation
         *
         * @see https://junaidbhura.com/make-polylang-wordpress-plugin-copy-the-content-from-the-original-post/
         *
         * @since   1.0
         * @access  public
         * @param   string  $title  Original post title
         * @return  string
         */
        static public function title_copy( $title ) {
            if ( isset( $_GET['from_post'] ) ) {
                $my_post = get_post( $_GET['from_post'] );
                if ( $my_post )
                    return $my_post->post_title;
            }
            return $title;
        }

        /**
         * Duplicate post excerpt from original across translation
         *
         * @see https://junaidbhura.com/make-polylang-wordpress-plugin-copy-the-content-from-the-original-post/#comment-313
         *
         * @since   1.0
         * @access  public
         * @param   string  $excerpt  Original post excerpt
         * @return  string
         */
        static public function excerpt_copy( $excerpt ) {
            if ( isset( $_GET['from_post'] ) ) {
                $my_post = get_post( $_GET['from_post'] );
                if ( $my_post )
                    return $my_post->post_excerpt;
            }
            return $excerpt;
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
