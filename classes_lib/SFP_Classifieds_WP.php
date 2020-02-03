<?php
/**
 * Classifieds WP
 *
 * Plugin url : https://wordpress.org/plugins/classifieds-wp/
 * Doc : http://documentation.classifiedswp.com/
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
if ( ! class_exists( 'SFP_Classifieds_WP' ) ) {

    class SFP_Classifieds_WP {

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
            add_filter( 'submit_classified_form_fields', array( __class__, 'submit_classified_form_fields' ) );
            add_filter( 'classified_manager_classified_listing_data_fields', array( __class__, 'classified_manager_classified_listing_data_fields' ) );


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
        }

        static public function test(){echo "<!-- SFP_Classifieds_WP test() function loaded ! -->" . PHP_EOL;}

        /**
         * Classified fields
         *
         * @since   1.0
         * @access  public
         * @param   array   $fields  List of classified fields
         * @return  array
         */
        static public function submit_classified_form_fields( $fields ) {
            // Remove unwanted fields
            unset( $fields['classified']['classified_images'] );
            unset( $fields['classified']['classified_price'] );
            unset( $fields['classified']['classified_website'] );

            // Modify default fields
            $fields['classified']['classified_title']['priority'] = 1;
            $fields['classified']['classified_type']['priority'] = 2;
            $fields['classified']['classified_description']['priority'] = 6;
            $fields['classified']['classified_location']['priority'] = 7;
            $fields['classified']['classified_contact']['priority'] = 8;

            // Add custom fields
            $fields['classified']['classified_nursing'] = array(
                'label'    => __( 'Soins de nursing', 'functionality-plugin' ),
                'type'     => 'select',
                'default'  => 0,
                'options'  => array(
                    0 => __( 'No', 'functionality-plugin' ),
                    1 => __( 'Yes', 'functionality-plugin' ),
                ),
                'priority' => 3,
                'required' => 0,
            );
            $fields['classified']['classified_dialyse'] = array(
                'label'    => __( 'Soins de dialyse', 'functionality-plugin' ),
                'type'     => 'select',
                'default'  => 0,
                'options'  => array(
                    0 => __( 'No', 'functionality-plugin' ),
                    1 => __( 'Yes', 'functionality-plugin' ),
                ),
                'priority' => 4,
                'required' => 0,
            );
            $fields['classified']['classified_chimio'] = array(
                'label'    => __( 'Soins de chimio', 'functionality-plugin' ),
                'type'     => 'select',
                'default'  => 0,
                'options'  => array(
                    0 => __( 'No', 'functionality-plugin' ),
                    1 => __( 'Yes', 'functionality-plugin' ),
                ),
                'priority' => 5,
                'required' => 0,
            );

            return $fields;
        }

        /**
         * Any function...
         *
         * @since   1.0
         * @access  public
         * @param   array   $fields  List of classified fields
         * @return  array
         */
        static public function classified_manager_classified_listing_data_fields( $fields ) {
            // Remove unwanted fields
            unset( $fields['_classified_price'] );
            unset( $fields['_classified_website'] );
            unset( $fields['_classified_price'] );

            // Modify default fields
            $fields['_classified_location']['priority'] = 1;
            $fields['_classified_contact']['priority'] = 3;
            $fields['_classified_unavailable']['priority'] = 5;

            // Add custom fields
            $fields['_classified_nursing'] = array(
                'label'   => __( 'Soins de nursing', 'functionality-plugin' ),
                'type'    => 'select',
                'default' => 0,
                'options' => array(
                    0 => __( 'No', 'functionality-plugin' ),
                    1 => __( 'Yes', 'functionality-plugin' ),
                ),
                'priority'=> 2,
            );
            $fields['_classified_dialyse'] = array(
                'label'   => __( 'Soins de dialyse', 'functionality-plugin' ),
                'type'    => 'select',
                'default' => 0,
                'options' => array(
                    0 => __( 'No', 'functionality-plugin' ),
                    1 => __( 'Yes', 'functionality-plugin' ),
                ),
                'priority'=> 4,
            );
            $fields['_classified_chimio'] = array(
                'label'   => __( 'Soins de chimio', 'functionality-plugin' ),
                'type'    => 'select',
                'default' => 0,
                'options' => array(
                    0 => __( 'No', 'functionality-plugin' ),
                    1 => __( 'Yes', 'functionality-plugin' ),
                ),
                'priority'=> 6,
            );

            return $fields;
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
