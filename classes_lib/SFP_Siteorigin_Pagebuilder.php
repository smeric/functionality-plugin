<?php
/**
 * Siteorigin Pagebuilder
 * 
 * https://fr.wordpress.org/plugins/siteorigin-panels/
 * https://fr.wordpress.org/plugins/so-widgets-bundle/
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
if ( ! class_exists( 'SFP_Siteorigin_Pagebuilder' ) ) {

    class SFP_Siteorigin_Pagebuilder {

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
            add_filter( 'siteorigin_panels_row_style_fields',     array( __class__, 'custom_row_style_fields' ) );
            add_filter( 'siteorigin_panels_row_style_attributes', array( __class__, 'custom_row_style_attributes' ), 10, 2);

            add_filter( 'siteorigin_panels_widget_style_fields',     array( __class__, 'custom_widget_style_fields' ) );
            add_filter( 'siteorigin_panels_widget_style_attributes', array( __class__, 'custom_widget_style_attributes' ), 10, 2);

            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
        }

        static public function test(){echo "<!-- SFP_Siteorigin_Pagebuilder test() function loaded ! -->" . PHP_EOL;}

        /**
         * Adding a custom option under the Row Styles
         *
         * @see https://siteorigin.com/docs/page-builder/hooks/custom-row-settings/
         *
         * @since   1.0
         * @access  static
         * @param   array  $fields  Array
         * @return  array
         */
        static public function custom_row_style_fields( $fields ) {
            $fields['row-margin-top'] = array(
                'name'        => __( 'Top margin', 'functionality-plugin' ),
                'type'        => 'measurement',
                'group'       => 'layout',
                'description' => __( 'Space before this row.', 'functionality-plugin' ),
                'priority'    => 4,
            );

            return $fields;
        }

        /**
         * 
         *
         * @see https://siteorigin.com/docs/page-builder/hooks/custom-row-settings/
         *
         * @since   1.0
         * @access  public
         * @param   array  $fields  Array
         * @return  array
         */
        static public function custom_row_style_attributes( $attributes, $args ) {
            if ( ! empty( $args['row-margin-top'] ) ) {
                $attributes['style'] .= 'margin-top:' . esc_attr($args['row-margin-top']) . ';';
            }

            return $attributes;
        }

        /**
         * 
         *
         * @see https://siteorigin.com/docs/page-builder/hooks/custom-row-settings/
         *
         * @since  1.0
         * @access  static
         * @param   array  $fields  Array
         * @return  array
         */
        static public function custom_widget_style_fields( $fields ) {
            // Layout
            /*$fields['widget-from-top'] = array(
                'name'        => __( 'From top', 'functionality-plugin' ),
                'type'        => 'measurement',
                'group'       => 'layout',
                'description' => __( 'Distance between widget and top row border.', 'functionality-plugin' ),
                'priority'    => 8,
            );*/
            /*$fields['widget-from-left'] = array(
                'name'        => __( 'From left', 'functionality-plugin' ),
                'type'        => 'measurement',
                'group'       => 'layout',
                'description' => __( 'Distance between widget and left row border.', 'functionality-plugin' ),
                'priority'    => 11,
            );*/
            $fields['widget-padding-top'] = array(
                'name'        => __( 'Padding top', 'functionality-plugin' ),
                'type'        => 'measurement',
                'group'       => 'layout',
                'description' => __( 'Padding on top of this widget.', 'functionality-plugin' ),
                'priority'    => 12,
            );
            $fields['widget-padding-right'] = array(
                'name'        => __( 'Padding right', 'functionality-plugin' ),
                'type'        => 'measurement',
                'group'       => 'layout',
                'description' => __( 'Padding on the right of this widget.', 'functionality-plugin' ),
                'priority'    => 13,
            );
            $fields['widget-padding-bottom'] = array(
                'name'        => __( 'Padding bottom', 'functionality-plugin' ),
                'type'        => 'measurement',
                'group'       => 'layout',
                'description' => __( 'Padding on the bottom of this widget.', 'functionality-plugin' ),
                'priority'    => 14,
            );
            $fields['widget-padding-left'] = array(
                'name'        => __( 'Padding left', 'functionality-plugin' ),
                'type'        => 'measurement',
                'group'       => 'layout',
                'description' => __( 'Padding on the left of this widget.', 'functionality-plugin' ),
                'priority'    => 15,
            );
            $fields['widget-margin-top'] = array(
                'name'        => __( 'Margin top', 'functionality-plugin' ),
                'type'        => 'measurement',
                'group'       => 'layout',
                'description' => __( 'Margin on top of this widget.', 'functionality-plugin' ),
                'priority'    => 17,
            );
            $fields['widget-margin-right'] = array(
                'name'        => __( 'Margin right', 'functionality-plugin' ),
                'type'        => 'measurement',
                'group'       => 'layout',
                'description' => __( 'Margin on the right of this widget.', 'functionality-plugin' ),
                'priority'    => 18,
            );
            $fields['widget-margin-bottom'] = array(
                'name'        => __( 'Margin bottom', 'functionality-plugin' ),
                'type'        => 'measurement',
                'group'       => 'layout',
                'description' => __( 'Margin on the bottom of this widget.', 'functionality-plugin' ),
                'priority'    => 19,
            );
            $fields['widget-margin-left'] = array(
                'name'        => __( 'Margin left', 'functionality-plugin' ),
                'type'        => 'measurement',
                'group'       => 'layout',
                'description' => __( 'Margin on the left of this widget.', 'functionality-plugin' ),
                'priority'    => 20,
            );

            // Design
            $fields['widget-rounded-corners'] = array(
                'name'        => __( 'Rounded corners', 'functionality-plugin' ),
                'type'        => 'measurement',
                'group'       => 'design',
                'priority'    => 22,
            );

            return $fields;
        }

        /**
         * 
         *
         * @see https://siteorigin.com/docs/page-builder/hooks/custom-row-settings/
         *
         * @since   1.0
         * @access  public
         * @param   array  $fields  Array
         * @return  array
         */
        static public function custom_widget_style_attributes( $attributes, $args ) {
            /*if ( ! empty( $args['widget-from-top'] ) ) {
                $attributes['style'] .= 'top:' . esc_attr( $args['widget-from-top'] ) . ';';
            }*/
            /*if ( ! empty( $args['widget-from-left'] ) ) {
                $attributes['style'] .= 'left:' . esc_attr( $args['widget-from-left'] ) . ';';
            }*/
            if ( ! empty( $args['widget-padding-top'] ) ) {
                $attributes['style'] .= 'padding-top:' . esc_attr( $args['widget-padding-top'] ) . ';';
            }
            if ( ! empty( $args['widget-padding-right'] ) ) {
                $attributes['style'] .= 'padding-right:' . esc_attr( $args['widget-padding-right'] ) . ';';
            }
            if ( ! empty( $args['widget-padding-bottom'] ) ) {
                $attributes['style'] .= 'padding-bottom:' . esc_attr( $args['widget-padding-bottom'] ) . ';';
            }
            if ( ! empty( $args['widget-padding-left'] ) ) {
                $attributes['style'] .= 'padding-left:' . esc_attr( $args['widget-padding-left'] ) . ';';
            }
            if ( ! empty( $args['widget-margin-top'] ) ) {
                $attributes['style'] .= 'margin-top:' . esc_attr( $args['widget-margin-top'] ) . ';';
            }
            if ( ! empty( $args['widget-margin-right'] ) ) {
                $attributes['style'] .= 'margin-right:' . esc_attr( $args['widget-margin-right'] ) . ';';
            }
            if ( ! empty( $args['widget-margin-bottom'] ) ) {
                $attributes['style'] .= 'margin-bottom:' . esc_attr( $args['widget-margin-bottom'] ) . ';';
            }
            if ( ! empty( $args['widget-margin-left'] ) ) {
                $attributes['style'] .= 'margin-left:' . esc_attr( $args['widget-margin-left'] ) . ';';
            }
            if ( ! empty( $args['widget-rounded-corners'] ) ) {
                $attributes['style'] .= '-webkit-border-radius: ' . esc_attr( $args['widget-rounded-corners'] ) . '; -moz-border-radius: ' . esc_attr( $args['widget-rounded-corners'] ) . '; border-radius: ' . esc_attr( $args['widget-rounded-corners'] ) . ';';
            }

            return $attributes;
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
