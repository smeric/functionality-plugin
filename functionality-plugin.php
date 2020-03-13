<?php
/**
 * @package     SFP
 * @link        https://github.com/smeric/Code-snippets/tree/master/WordPress/functionality-plugin
 * @copyright   Copyright (c) 2016, Sébastien Méric
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 * @author      Sébastien Méric <sebastien.meric@gmail.com>
 *
 * @wordpress-plugin
 * Plugin Name: Functionality plugin for this particular website
 * Plugin URI:  https://github.com/smeric/Code-snippets/tree/master/WordPress/functionality-plugin
 * Description: Adds funcionalities to this website regardless the theme actualy in use. That means you may change theme keeping the functionnalities working anyway :)
 * Version:     1.0
 * Author:      Sébastien Méric
 * Author URI:  http://www.sebastien-meric.com/
 * License:     GNU General Public License v2.0 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: functionality-plugin
 * Domain Path: /languages/
 * 
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Main class using the Singleton Pattern.
// See https://code.tutsplus.com/articles/design-patterns-in-wordpress-the-singleton-pattern--wp-31621
if ( ! class_exists( 'Functionality_Plugin' ) ) {

    class Functionality_Plugin {

        /**
         * Holds class instance
         *
         * @since  1.0
         * @access private
         * @var    Instance of Functionality_Plugin class
         */
        static private $instance;
        
        /**
         * Private static variables
         *
         * @since  1.0
         * @access private
         */
        static private $basename   = "";
        static private $plugin_dir = "";
        static private $plugin_url = "";
        static private $version    = "";

        /**
         * Main Instance
         *
         * Ensures that only one instance exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @since     1.0
         * @access public
         * @static
         * @staticvar array $instance
         * @return    Instance of this class
         */
        static public function get_instance() {
            if ( ! isset ( self::$instance ) && ! ( self::$instance instanceof Functionality_Plugin ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Protected constructor to prevent new instances.
         *
         * Initializes the plugin by setting localization, filters, and administration functions.
         *
         * @since  1.0
         * @access protected
         * @return void
         */
        protected function __construct() {
            self::setup_globals();
            self::includes();
            spl_autoload_register( array( __CLASS__, 'autoload' ) );
            self::init();
            self::setup_actions();
        }

        /**
         * Globals
         *
         * @since  1.0
         * @access private
         * @static
         * @return void
         */
        static private function setup_globals() {
            // Paths
            self::$basename   = plugin_basename( __FILE__ );
            self::$plugin_dir = plugin_dir_path( __FILE__ );
            self::$plugin_url = plugin_dir_url ( __FILE__ );
            // Plugin version
            self::$version    = self::get_version();
        }

        /**
         * Returns current plugin version.
         * 
         * @since  1.0
         * @author Gary Jones
         * @source https://code.garyjones.co.uk/get-wordpress-plugin-version
         * @access private
         * @static
         * @return string Plugin version
         */
        static private function get_version() {
            if ( ! function_exists( 'get_plugins' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }

            $plugin_data    = get_plugin_data( __FILE__ );
            $plugin_version = $plugin_data['Version'];

            return $plugin_version;
        }

        /**
         * Include some file(s)...
         *
         * @since  1.0
         * @access private
         * @static
         * @return void
         */
        static private function includes() {
            // Template Tags
            require_once self::$plugin_dir . 'functions.php';
        }

        /**
         * Autoload usefull classes
         * 
         * @since  1.0
         * @access private
         * @static
         * @return Boolean
         */
        static private function autoload( $class ) {
            $path = self::$plugin_dir . 'classes/' . $class . '.php';

            if ( is_file( $path ) ) {
                require_once $path;
                return true;
            }
            else {
                return false;
            }
        }

        /**
         * Initialize each classes found in the classes directory
         *
         * @since  1.0
         * @access private
         * @static
         * @return void
         */
        static private function init() {
            // Get classes names from classes directory files name
            $classes = self::scan_dir_for_classes();

            // Call the init function for all of them
            foreach ( $classes as $class ) {
                if ( class_exists( $class ) ) {
                    $class::init();
                }
            }

        }

        /**
         * Scan classes directory and get back classes names to be loaded
         *
         * @since  1.0
         * @access private
         * @static
         * @return array Classes names
         */
        static private function scan_dir_for_classes() {
            $path = self::$plugin_dir . 'classes/';

            $classes = array_map(
                // Remove files extentions to keep only classes names
                function( $e ) {
                    return pathinfo( $e, PATHINFO_FILENAME );
                },
                // Reindex array
                array_values(
                    // Remove dot dirs from list
                    array_diff(
                        scandir( $path ), array( '..', '.' )
                    )
                )
            );

            return $classes;
        }


        /**
         * Setup the default hooks
         *
         * @since  1.0
         * @access private
         * @static
         * @return void
         */
        static private function setup_actions() {
            // Load plugin's language file
            add_action( 'plugins_loaded', array( __class__, 'load_textdomain' ) );

            // Register stylesheets & javascript external files
            //add_action( 'wp_enqueue_scripts', array( __class__, 'enqueue_public_styles' ) );
            //add_action( 'wp_enqueue_scripts', array( __class__, 'enqueue_public_scripts' ) );
            //add_action( 'admin_enqueue_scripts', array( __class__, 'enqueue_admin_styles' ) );
            //add_action( 'admin_enqueue_scripts', array( __class__, 'enqueue_admin_scripts' ) );

            do_action( 'functionality_plugin_setup_actions' );
        }

        /**
         * Loads the plugin language file
         *
         * @since  1.0
         * @access private
         * @return void
         */
        private function load_textdomain() {
            // Set filter for plugin's languages directory
            $lang_dir = dirname( self::$basename ) . '/languages/';
            $lang_dir = apply_filters( 'site_functionality_languages_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale',  get_locale(), 'functionality-plugin' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'functionality-plugin', $locale );

            // Setup paths to current locale file
            $mofile_local  = $lang_dir . $mofile;
            $mofile_global = WP_LANG_DIR . '/functionality-plugin/' . $mofile;

            if ( file_exists( $mofile_global ) ) {
                load_textdomain( 'functionality-plugin', $mofile_global );
            }
            elseif ( file_exists( $mofile_local ) ) {
                load_textdomain( 'functionality-plugin', $mofile_local );
            }
            else {
                // Load the default language files
                load_plugin_textdomain( 'functionality-plugin', false, $lang_dir );
            }
        }

        /**
         * Register the stylesheets for the public-facing side of the site.
         *
         * @since  1.0
         * @access public
         * @return void
         */
        public function enqueue_public_styles() {
            wp_enqueue_style( self::$basename . '-public', self::$plugin_url . 'assets/public/css/style.css', array(), self::$version, 'all' );
        }

        /**
         * Register the JavaScript for the public-facing side of the site.
         *
         * @since  1.0
         * @access public
         * @return void
         */
        public function enqueue_public_scripts() {
            wp_enqueue_script( self::$basename . '-public', self::$plugin_url . 'assets/public/js/scripts.js', array( 'jquery' ), self::$version, false );
        }

        /**
         * Register the stylesheets for the admin area.
         *
         * @since  1.0
         * @access public
         * @return void
         */
        public function enqueue_admin_styles() {
            wp_enqueue_style( self::$basename . '-admin', self::$plugin_url . 'assets/admin/css/style.css', array(), self::$version, 'all' );
        }

        /**
         * Register the JavaScript for the admin area.
         *
         * @since  1.0
         * @access public
         * @return void
         */
        public function enqueue_admin_scripts() {
            wp_enqueue_script( self::$basename . '-admin', self::$plugin_url . 'assets/admin/js/scripts.js', array( 'jquery' ), self::$version, false );
        }

        /**
         * Throw error on object clone
         *
         * @since  1.0
         * @access public
         * @return void
         */
        public function __clone() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'functionality-plugin' ), '1.6' );
        }

        /**
         * Disable unserializing of the class
         *
         * @since  1.0
         * @access public
         * @return void
         */
        public function __wakeup() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'functionality-plugin' ), '1.6' );
        }

    }

    /**
     * The code that runs during plugin activation.
     *
     * @since  1.0
     * @return void
     */
    if ( ! function_exists( 'functionality_plugin_activate' ) ) {
        register_activation_hook( __FILE__, 'functionality_plugin_activate' );
        function functionality_plugin_activate() {
            require_once plugin_dir_path( __FILE__ ) . 'functionality-plugin-activator.php';
            SFP_Activator::activate();
        }
    }

    /**
     * The code that runs during plugin deactivation.
     *
     * @since  1.0
     * @return void
     */
    if ( ! function_exists( 'functionality_plugin_deactivate' ) ) {
        register_deactivation_hook( __FILE__, 'functionality_plugin_deactivate' );
        function functionality_plugin_deactivate() {
            require_once plugin_dir_path( __FILE__ ) . 'functionality-plugin-deactivator.php';
            SFP_Deactivator::deactivate();
        }
    }

    /**
     * Get everything running !
     *
     * @since  1.0
     * @return void
     */
    add_action( 'init', function(){
        return Functionality_Plugin::get_instance();
    }, 1 );

}

