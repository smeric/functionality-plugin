<?php
/**
 * Nemus Slider
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
if ( ! class_exists( 'SFP_Nemus_Slider' ) ) {

    class SFP_Nemus_Slider {

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

            add_action( 'add_meta_boxes', array( __class__, 'add_custom_box' ) );
            add_action( 'save_post', array( __class__, 'save_postdata' ) );


            /**
             * Filters
             * add_filter( 'hook_name', array( __class__, 'function_name' ) );
             */
            add_filter( 'nemus-slider-auto-slide-query', array( __class__, 'auto_slide_query' ) );
            // Replaces the excerpt "more" text by a link
            add_filter( 'nemus-slider-autoslide-caption', array( __class__, 'excerpt_more' ), 10, 4 );


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
        }

        static public function test(){echo "<!-- SFP_Nemus_Slider test() function loaded ! -->" . PHP_EOL;}

        /**
         * Utilisation des "Featured posts" pour les articles du slideshow
         *
         * Define the custom box
         * Adds a box to the main column on the Post edit screens
         *
         * @since   1.0
         * @access  public
         * @return  void
         */
        static public function add_custom_box() {
            add_meta_box(
                'sfp_nemus_slider_sectionid',
                __( 'Featured post', 'functionality-plugin' ),
                array( __class__, 'inner_custom_box' ),
                'post',
                'side',
                'high'
            );
        }

        /**
         * Prints the box content
         *
         * @since   1.0
         * @access  public
         * @param   object  $post    Post object
         * @return  void
         */
        static public function inner_custom_box( $post ) {
            // Use nonce for verification
            wp_nonce_field( 'sfp_nemus_slider_field_nonce', 'sfp_nemus_slider_noncename' );

            // Get saved value, if none exists, "default" is selected
            $saved = intval( get_post_meta( $post->ID, 'sfp_nemus_slider_featured_post', true ) );
            if ( !$saved )
                $saved = 0;

            printf(
                '<p><input type="checkbox" name="sfp_nemus_slider_featured_post" value="1" id="sfp_nemus_slider_featured_post" %2$s />'.
                '<label for="sfp_nemus_slider_featured_post"> %1$s ' .
                '</label></p>' .
                '<p class="description">%3$s</p>',
                __( 'Feature this post', 'functionality-plugin' ),
                checked( $saved, 1, false ),
                __( 'Check to display this post in the homepage slideshow.', 'functionality-plugin' )
            );
        }

        /**
         * Do something with the data entered
         * When the post is saved, saves our custom data
         *
         * @since   1.0
         * @access  public
         * @param   int  $post_id  Post ID
         * @return  void
         */
        static public function save_postdata( $post_id ) {
            // Verify if this is an auto save routine. 
            // If it is our form has not been submitted, so we don't want to do anything
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
                return;

            if ( ! wp_verify_nonce( $_POST['sfp_nemus_slider_noncename'], 'sfp_nemus_slider_field_nonce' ) )
                return;

            update_post_meta( $post_id, 'sfp_nemus_slider_featured_post', isset( $_POST['sfp_nemus_slider_featured_post'] ) ? $_POST['sfp_nemus_slider_featured_post'] : 0 );
        }

        /**
         * Use this meta key / value to select slideshow posts
         *
         * @since   1.0
         * @access  public
         * @param   array   $args   Array
         * @return  array
         */
        static public function auto_slide_query( $args ) {
            $args['meta_key'] = 'sfp_nemus_slider_featured_post' ;
            $args['meta_value'] = 1;
            return $args;
        }

        /**
         * Replaces the excerpt "more" text by a link
         *
         * @since   1.0
         * @access  public
         * @param   string  $the_excerpt  String
         * @param   int     $id           Integer
         * @param   int     $slide_id     Integer
         * @param   int     $post_id      Integer
         * @return  string
         */
        static public function excerpt_more( $the_excerpt, $id, $slide_id, $post_id ) {
            return $the_excerpt . '<p><a class="more-tag" href="'. get_permalink( $post_id ) . '">' . __( 'Read more <span class="meta-nav">&raquo;</span>', 'functionality-plugin' ) . '</a></p>';
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
