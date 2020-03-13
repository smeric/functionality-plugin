<?php
/**
 * Google Site Kit
 *
 * @see https://wordpress.org/plugins/google-site-kit/
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
if ( ! class_exists( 'SFP_Googlesitekit' ) ) {

    class SFP_Googlesitekit {

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
            add_action( 'sfp_dynamic_external_js_snippet', array( __class__, 'page_scrolling' ) );
            add_action( 'sfp_dynamic_external_js_snippet', array( __class__, 'comment_submission' ) );
            add_action( 'sfp_dynamic_external_js_snippet', array( __class__, 'external_links' ) );


            /**
             * Filters
             * add_filter( 'hook_name', array( __class__, 'function_name' ) );
             */


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
        }

        static public function test(){echo "<!-- SFP_Googlesitekit test() function loaded ! -->" . PHP_EOL;}


        /**
         * Page scrolling.
         *
         * Tracking the percentage of single post type page scrolling.
         *
         * @source https://growthrocks.com/blog/scroll-tracking-google-analytics/
         *
         * @see https://github.com/Bounteous-Inc/scroll
         * @see https://scrolldepth.parsnip.io/
         *
         * @since   1.0
         * @access  public
         * @return  void
         */
        static public function page_scrolling() {
            ?>

        /**
         * Déclenchement d'événements Google analytics lors du scroll de la page
         *
         * source https://growthrocks.com/blog/scroll-tracking-google-analytics/
         */
        var maxp = 0;
        $d.on('scroll',function(){
            var h       = document.documentElement,
                b       = document.body,
                st      = 'scrollTop',
                sh      = 'scrollHeight',
                percent = parseInt ( (h[st] || b[st]) / ((h[sh] || b[sh]) - h.clientHeight) * 100 ),
                // Accès au nom du type de post courant au travers d'une variable 
                // globale passée par wp_localize_script
                cpn     = sfp_dynamic_external_js.cpn;

            // On ne traite que les page de contenu
            if('' === cpn)
                return;

            if ( percent >= 25 && maxp < 25 ) {
                console.log('SFP: Scroll de 25%');
                if(typeof gtag=='function'){
                    gtag( 'event', 'Scroll de + de 25%', {
                        'event_category' : 'Scroll '+cpn,
                        'event_label'    : window.location.href,
                        'non_interaction': true
                    });
                }
            }
            else if ( percent >= 50 && maxp < 50 ) {
                console.log('SFP: Scroll de 50%');
                if(typeof gtag=='function'){
                    gtag( 'event', 'Scroll de + de 50%', {
                        'event_category' : 'Scroll '+cpn,
                        'event_label'    : window.location.href,
                        'non_interaction': true
                    });
                }
            }
            else if ( percent >= 75 && maxp < 75 ) {
                console.log('SFP: Scroll de 75%');
                if(typeof gtag=='function'){
                    gtag( 'event', 'Scroll de + de 75%', {
                        'event_category' : 'Scroll '+cpn,
                        'event_label'    : window.location.href,
                        'non_interaction': true
                    });
                }
            }
            else if ( percent >= 90 && maxp < 90 ) {
                console.log('SFP: Scroll de 90%');
                if(typeof gtag=='function'){
                    gtag( 'event', 'Scroll de + de 90%', {
                        'event_category' : 'Scroll '+cpn,
                        'event_label'    : window.location.href
                    });
                }
            }

            if ( percent > maxp ) {
                maxp = percent;
            }
        });

            <?php
        }


        /**
         * Comment submission.
         *
         * Tracking when a user submits a WordPress comment.
         *
         * @source https://felix-arntz.me/blog/customizing-google-analytics-configuration-site-kit-plugin/
         *
         * @since   1.0
         * @access  public
         * @return  void
         */
        static public function comment_submission() {
            ?>

        /**
         * Déclenchement d'événements Google analytics lors de la soumission
         * d'un commentaire.
         *
         * source https://felix-arntz.me/blog/customizing-google-analytics-configuration-site-kit-plugin/
         */
        $('#commentform input[type="submit"]').on('click',function(){
            console.log('SFP: Comment submited');
            if(typeof gtag=='function'){
                gtag('event','Commentaire',{
                    'event_category':'Implication',
                    'event_label':window.location.href
                });
            }
        });

            <?php
        }

        /**
         * External link click.
         *
         * @since   1.0
         * @access  public
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void
         */
        static public function external_links() {
            ?>

        /**
         * Déclenchement d'événements Google analytics lors de clics sur
         * des liens externes.
         *
         * source https://growthrocks.com/blog/scroll-tracking-google-analytics/
         */
        $('a').filter(function(){
           return this.hostname && this.hostname !== location.hostname;
        }).on('click',function(){
            console.log('SFP: External link followed ('+this.href+')');
            if(typeof gtag=='function'){
                gtag('event','Clic',{
                    'event_category':'Lien sortant',
                    'event_label':this.href,
                    'transport_type':'beacon'
                });
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
