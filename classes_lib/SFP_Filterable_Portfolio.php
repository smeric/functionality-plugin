<?php
/**
 * Filterable Portfolio
 * 
 * @source https://fr.wordpress.org/plugins/filterable-portfolio/
 * @source https://github.com/sayful1/filterable-portfolio
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
if ( ! class_exists( 'SFP_Filterable_Portfolio' ) ) {

    class SFP_Filterable_Portfolio {

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
            add_action( 'sfp_dynamic_external_js_snippet', array( __class__, 'slider_config' ) );
            add_action( 'wp_print_scripts', array( __class__, 'deregister_scripts' ) );


            /**
             * Filters
             * add_filter( 'hook_name', array( __class__, 'function_name' ) );
             */
             add_filter( 'filterable_portfolio_meta_box_fields', array( __class__, 'remove_meta_fields' ) );


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
        }

        static public function test(){echo "<!-- SFP_Filterable_Portfolio test() function loaded ! -->" . PHP_EOL;}

        /**
         * Add, remove or modify any field.
         *
         * @bug https://wordpress.org/support/topic/filter-hook-for-date-removal-not-working/
         * @pull https://github.com/sayful1/filterable-portfolio/pull/7
         *
         * @since   1.0
         * @access  public
         * @param   array  $fields  Meta fields for single porfolio elements
         * @return  array  $fields
         */
        static public function remove_meta_fields( $fields ) {
            //unset( $fields['_project_images'] );
            //unset( $fields['_client_name'] );
            //unset( $fields['_project_url'] );
            unset( $fields['_project_date'] );

            return $fields;
        }

        /**
         * Deregister slider config script...
         *
         * @source https://github.com/sayful1/filterable-portfolio/blob/master/includes/class-filterable-portfolio-scripts.php
         *
         * @since   1.0
         * @access  public
         * @return  void
         */
        static public function deregister_scripts() {
            wp_dequeue_script( 'filterable-portfolio' );
            wp_deregister_script( 'filterable-portfolio' );
        }

        /**
         * ... And register my own config !
         *
         * @since   1.0
         * @access  public
         * @return  void
         */
        static public function slider_config() {
            ?>

        /**
         * J'ai simplement recopié le script du plugin pour pouvoir modifier les
         * caractéristiques du slider car aucun moyen de le configurer n'est 
         * mise à dispo dans le backoffice...
         *
         * source https://github.com/sayful1/filterable-portfolio/blob/master/assets/src/frontend/slider.js
         */
        if (tns) {
            var sliders,
                i,
                slider,
                sliderOuter,
                controls,
                showDots,
                showArrows,
                autoplay,
                autoplayHoverPause,
                autoplayTimeout,
                speed,
                gutter,
                loop,
                lazyload,
                slideBy,
                mobile,
                tablet,
                desktop,
                wideScreen,
                fullHD,
                highScreen;

            sliders = document.querySelectorAll('.fp-tns-slider');
            for(i=0;i<sliders.length;i++){
                slider=sliders[i];

                sliderOuter = slider.parentNode;
                controls = sliderOuter.querySelector('.fp-tns-slider-controls');

                mobile = parseInt(slider.getAttribute('data-mobile'));
                tablet = parseInt(slider.getAttribute('data-tablet'));
                desktop = parseInt(slider.getAttribute('data-desktop'));
                wideScreen = parseInt(slider.getAttribute('data-wide-screen'));
                fullHD = parseInt(slider.getAttribute('data-full-hd'));
                highScreen = parseInt(slider.getAttribute('data-high-screen'));

                showDots = slider.getAttribute('data-dots') === 'true';
                showArrows = slider.getAttribute('data-arrows') === 'true';

                autoplay = slider.getAttribute('data-autoplay') === 'true';
                autoplayHoverPause = slider.getAttribute('data-autoplay-hover-pause') === 'true';
                // Default is 5000
                autoplayTimeout = 10000;
                // Default is 500
                speed = 1000;

                gutter = parseInt(slider.getAttribute('data-gutter'));
                loop = slider.getAttribute('data-loop') === 'true';
                lazyload = slider.getAttribute('data-lazyload') === 'true';

                slideBy = slider.getAttribute('data-slide-by');
                slideBy = (slideBy === 'page') ? 'page' : parseInt(slideBy);

                tns({
                    container: slider,
                    slideBy: slideBy,
                    loop: loop,
                    lazyload: lazyload,
                    autoplay: autoplay,
                    autoplayTimeout: autoplayTimeout,
                    autoplayHoverPause: autoplayHoverPause,
                    speed: speed,
                    gutter: gutter,
                    nav: showDots,
                    controls: showArrows,
                    controlsContainer: controls ? controls : false,
                    edgePadding: 0,
                    items: mobile,
                    responsive: {
                        600: {items: tablet},
                        1000: {items: desktop},
                        1200: {items: wideScreen},
                        1500: {items: fullHD},
                        1921: {items: highScreen}
                    }
                });
            }
        }


            <?php
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
