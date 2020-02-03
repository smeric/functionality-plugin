<?php
/**
 * WPBakery Visual Composer
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
// Don't break if Visual composer is not loaded
if ( ! class_exists( 'SFP_Visual_Composer' ) && defined( 'WPB_VC_VERSION' ) ) {

    class SFP_Visual_Composer {

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

            add_action( 'init', array( __class__, 'iconsyca_init' ) );


            /**
             * Filters
             * add_filter( 'hook_name', array( __class__, 'function_name' ) );
             */


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
        }

        static public function test(){echo "<!-- SFP_Visual_Composer test() function loaded ! -->" . PHP_EOL;}

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
        static public function iconsyca_init(){

            add_filter( 'vc_iconpicker-type-iconsyca', array( __class__, 'vc_iconpicker_type_iconsyca' ) );


            $config = array(  
                'base'       => 'iconsyca',
                'type'       => 'iconpicker' ,
                'heading'    => __('Icon', 'functionality-plugin'),
                'param_name' => 'icon_iconsyca',
                'settings'   => array(
                    'emptyIcon'    => false, // default true, display an "EMPTY" icon?
                    'type'         => 'iconsyca',
                    'iconsPerPage' => 200, // default 100, how many icons per/page to display
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value'   => 'iconsyca',
                ),
                'description' => __( 'Select icon from library.', 'functionality-plugin' ),
              );

            vc_map( $config );

            vc_map_update( 'icon_type', array(
                __( 'Icons YCA', 'functionality-plugin' ) => 'iconsyca',
            ));
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
        static public function vc_iconpicker_type_iconsyca( $icons ) {
            $typicons_icons = array(
                'YCA Icons' => array(
                    array( 'typcn typcn-adjust-brightness' => 'Adjust Brightness' ),
                    array( 'typcn typcn-adjust-contrast' => 'Adjust Contrast' ),
                    array( 'typcn typcn-anchor-outline' => 'Anchor Outline' ),
                    array( 'typcn typcn-anchor' => 'Anchor' ),
                    array( 'typcn typcn-archive' => 'Archive' ),
                    array( 'typcn typcn-arrow-back-outline' => 'Arrow Back Outline' ),
                    array( 'typcn typcn-arrow-back' => 'Arrow Back' ),
                ),
            );

            return array_merge( $icons, $typicons_icons );
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
