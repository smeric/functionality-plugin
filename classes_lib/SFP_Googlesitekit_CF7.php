<?php
/**
 * Google Site Kit and Contact Form 7
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
if ( ! class_exists( 'SFP_Googlesitekit_CF7' ) ) {

    class SFP_Googlesitekit_CF7 {

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
            add_action( 'sfp_dynamic_external_js_snippet', array( __class__, 'form_submission' ) );


            /**
             * Filters
             * add_filter( 'hook_name', array( __class__, 'function_name' ) );
             */


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
        }

        static public function test(){echo "<!-- SFP_Googlesitekit_CF7 test() function loaded ! -->" . PHP_EOL;}

        /**
         * Form submition.
         *
         * Tracking when a user submits successfully a form.
         *
         * @see https://felix-arntz.me/blog/customizing-google-analytics-configuration-site-kit-plugin/
         * @see https://contactform7.com/tracking-form-submissions-with-google-analytics/
         *
         * @since   1.0
         * @access  public
         * @return  void
         */
        static public function form_submission() {
            ?>

        /**
         * Déclenchement d'un événement Google analytics lors de l'envoi
         * réussit d'un formulaire de contact CF7
         *
         * source https://wordpress.org/support/topic/wpcf7mailsent-not-working/
         */
        $d.ajaxComplete(function(){
            if($('.wpcf7-mail-sent-ok').length){
                console.log('SFP: Mail successfully sent !');
                if(typeof gtag=='function'){
                    gtag('event','Envoi',{
                        'event_category':'Formulaire de contact',
                        'event_label':window.location.href
                    });
                }
            }
        });

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
