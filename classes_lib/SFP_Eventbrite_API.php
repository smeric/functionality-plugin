<?php
/**
 * Eventbrite support
 *
 * https://wordpress.org/plugins/eventbrite-api/
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
if ( ! class_exists( 'SFP_Eventbrite_API' ) ) {

    class SFP_Eventbrite_API {

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

            add_action( 'after_setup_theme', array( __class__, 'add_theme_support' ) );
            add_action( 'init', array( __class__, 'load_textdomain' ) );


            /**
             * Filters
             * add_filter( 'hook_name', array( __class__, 'function_name' ) );
             */
            add_filter( 'eventbrite_ticket_form_widget_height', array( __class__, 'eventbrite_ticket_form_widget_height' ) );
            add_filter( 'eventbrite_meta_separator', array( __class__, 'eventbrite_meta_separator' ) );

            // Getting Eventbrite API WordPress plugin to work on systems with 32-bit builds of PHP
            // @see http://wvega.com/850/getting-eventbrite-api-wordpress-plugin-to-work-on-systems-with-32-bit-builds-of-php/
            add_filter( 'post_link', array( __class__, 'filter_event_permalink' ), 11 );
            add_action( 'the_post', array( __class__, 'eventbrite_workaround' ) );
            add_action( 'http_api_curl', array( __class__, 'http_api_curl' ), 10, 3 );
            add_filter( 'eventbrite_transient_name', array( __class__, 'eventbrite_transient_name' ), 10, 3 );
            add_filter( 'eventbrite_ticket_form_widget', array( __class__, 'eventbrite_ticket_form_widget' ) );


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
        }

        static public function test(){echo "<!-- SFP_Eventbrite_API test() function loaded ! -->" . PHP_EOL;}


        /**
         * Add support for Eventbrite.
         *
         * @see     https://wordpress.org/plugins/eventbrite-api/faq/
         * @see     https://github.com/Automattic/eventbrite-api/tree/master/tmpl
         *
         * @since   1.0
         * @access  public
         *
         * @return  void
         */
        static public function add_theme_support() {
            add_theme_support( 'eventbrite' );
        }

        /**
         * Load language file from WordPress plugins languages directory.
         *
         * @since  1.0
         * @access public
         * @param  int    $int    Integer
         * @param  string $string String
         * @param  array  $array  Array
         * @return void
         */
        static public function load_textdomain() {
            load_textdomain( 'eventbrite', trailingslashit( WP_LANG_DIR ) . 'plugins/eventbrite-fr_FR.mo' );
        }

        /**
         * Modify default widget height
         *
         * @since  1.0
         * @access public
         * @param  int  $height  Widget height
         * @return Integer
         */
        static public function eventbrite_ticket_form_widget_height( $height ) {
            return $height + 20;
        }

        /**
         * Change default separator.
         *
         * @since  1.0
         * @access public
         * @param  string $sep Separator
         * @return String
         */
        static public function eventbrite_meta_separator( $sep ){
            return eventbrite_is_archive() ? '<span class="sep"> &middot; </span>' : $sep;
        }

        /**
         * Filter the permalink for events to point to our rewrites.
         *
         * @see https://github.com/Automattic/eventbrite-api/blob/master/inc/class-eventbrite-query.php#L376
         *
         * @since  1.0
         * @access public
         * @param  string $url The original unfiltered permalink.
         * @return string Permalink URL
         */
        static public function filter_event_permalink( $url ) {
            // eg. http://mysite.com/events/july-test-drive-11829569561
            if ( function_exists( 'eventbrite_is_event' ) && eventbrite_is_event() ) {
                $url = sprintf( '%1$s/%2$s/%3$s-%4$s/',
                    esc_url( home_url() ),                             // protocol://domain
                    sanitize_title( get_queried_object()->post_name ), // page-with-eventbrite-template
                    sanitize_title( get_post()->post_title ),          // event-title
                    get_post()->tickets[0]->event_id                   // event ID
                );
            }

            //return 'hello';
            return $url;
        }

        /**
         * Having the event’s ID stored as the event_id property of the post object
         *
         * @since  1.0
         * @access public
         * @param  object  $post  The post object
         * @return void
         */
        static public function eventbrite_workaround( $post ) {
            if ( ! is_a( $post, 'Eventbrite_Event' ) ) {
                return;
            }

            if ( ! is_integer( $post->ID ) ) {
                $post->event_id = $post->ID;
            }
        }

        /**
         * The code below uses the http_api_curl action to capture all requests sent to
         * http://www.eventbriteapi.com and replace the incorrect ID with the value we stored
         * in event_id.
         * Please note that this code will work only if the HTTP requests are being made using
         * cURL. If a different transport is used, the http_api_curl action won’t be fired.
         *
         * @since  1.0
         * @access public
         * @param  int    $int    Integer
         * @param  string $string String
         * @param  array  $array  Array
         * @return void
         */
        static public function http_api_curl( $handle, $r, $url ) {
            if ( false === strpos( $url, 'www.eventbriteapi.com' ) || false === strpos( $url, '2147483647' ) ) {
                return;
            }

            $eventbrite_id = get_query_var( 'eventbrite_id' );

            if ( empty( $eventbrite_id ) ) {
                return;
            }

            $new_url = str_replace( '2147483647', $eventbrite_id, $url );
            curl_setopt( $handle, CURLOPT_URL, $new_url);
        }

        /**
         * Modify the transient's name based on endpoint and parameters.
         *
         * @see    https://github.com/Automattic/eventbrite-api/blob/master/inc/class-eventbrite-manager.php#L235
         *
         * @since  1.0
         * @access public
         * @param  int    $int    Integer
         * @param  string $string String
         * @param  array  $array  Array
         * @return void
         */
        static public function eventbrite_transient_name( $transient_name, $endpoint, $params ) {
            if ( $endpoint == 'event_details' ) {
                $params['p'] = get_query_var( 'eventbrite_id' );
                $transient_name = 'eventbrite_' . md5( $endpoint . implode( $params ) );
            }

            return $transient_name;
        }

        /**
         * The ticket form widget shows an error saying the event is not publicly available
         *
         * @see     https://github.com/Automattic/eventbrite-api/blob/master/inc/functions.php#L399
         *
         * @since   1.0
         * @access  public
         * @param   int    $int    Integer
         * @param   string $string String
         * @param   array  $array  Array
         * @return  void
         */
        static public function eventbrite_ticket_form_widget( $form_widget ) {
            $event_id = get_post()->tickets[0]->event_id;

            if ( empty( $event_id ) ) {
                return $form_widget;
            }

            return preg_replace( '/eid=\d+/', 'eid=' . urlencode( $event_id ), $form_widget );
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

    /**
     * Template Tags
     */

    if ( ! function_exists( 'eventbrite_is_archive' ) ) :
        /**
         * Determine if a page is in event list view.
         *
         * @param  mixed $query Null, or an Eventbrite_Query object.
         * @return bool True if an event single view, false otherwise.
         */
        function eventbrite_is_archive( $query = null ) {
            // If an Eventbrite_Query object is passed in, check the is_single property.
            if ( ! is_a( $query, 'Eventbrite_Query' ) ) {
                global $wp_query;
                $template_name = get_post_meta( $wp_query->post->ID, '_wp_page_template', true );
                $eventbrite_id = get_query_var( 'eventbrite_id' );
                if ( 'eventbrite-index.php' === $template_name && ! $eventbrite_id ) {
                    return true;
                }
            }
            elseif ( $query->is_archive ) {
                return true;
            }
            return false;
        }
    endif;

    if ( ! function_exists( 'eventbrite_is_index' ) ) :
        /**
         * Determine if a page is the default event list view.
         *
         * @param  mixed $query Null, or an Eventbrite_Query object.
         * @return bool True if an event single view, false otherwise.
         */
        function eventbrite_is_index( $query = null ) {
            // If an Eventbrite_Query object is passed in, check the is_single property.
            if ( ! is_a( $query, 'Eventbrite_Query' ) ) {
                $organizer_id = get_query_var( 'organizer_id' );
                $venue_id = get_query_var( 'venue_id' );
                if ( eventbrite_is_archive() && ! isset( $organizer_id ) && ! isset( $venue_id ) ) {
                    return true;
                }
            }
            elseif ( $query->is_archive && ! isset( $query->query['venue_id'] ) && ! isset( $query->query['organizer_id'] ) ) {
                return true;
            }
            return false;
        }
    endif;

    if ( ! function_exists( 'eventbrite_is_venue_archive' ) ) :
        /**
         * Determine if a page is the venues event list view.
         *
         * @param  mixed $query Null, or an Eventbrite_Query object.
         * @return bool True if an event single view, false otherwise.
         */
        function eventbrite_is_venue_archive( $query = null ) {
            // If an Eventbrite_Query object is passed in, check the is_single property.
            if ( ! is_a( $query, 'Eventbrite_Query' ) ) {
                $venue_id = get_query_var( 'venue_id' );
                if ( eventbrite_is_archive() && isset( $venue_id ) ) {
                    return get_query_var( 'venue_id' );
                }
            }
            elseif ( $query->is_archive && isset( $query->query['venue_id'] ) ) {
                return $query->query['venue_id'];
            }
            return false;
        }
    endif;

    if ( ! function_exists( 'eventbrite_is_organizer_archive' ) ) :
        /**
         * Determine if a page is the organizers event list view.
         *
         * @param  mixed $query Null, or an Eventbrite_Query object.
         * @return bool True if an event single view, false otherwise.
         */
        function eventbrite_is_organizer_archive( $query = null ) {
            // If an Eventbrite_Query object is passed in, check the is_single property.
            if ( ! is_a( $query, 'Eventbrite_Query' ) ) {
                $organizer_id = get_query_var( 'organizer_id' );
                if ( eventbrite_is_archive() && isset( $organizer_id ) ) {
                    return get_query_var( 'organizer_id' );
                }
            }
            elseif ( $query->is_archive && isset( $query->query['organizer_id'] ) ) {
                return $query->query['organizer_id'];
            }
            return false;
        }
    endif;


}
