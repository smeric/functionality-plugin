<?php
/**
 * Stripe Payments for WordPress – WP Simple Pay
 *
 * https://fr.wordpress.org/plugins/stripe/
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
if ( ! class_exists( 'SFP_Stripe' ) ) {

    class SFP_Stripe {

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

            // Send an email immediately after Stripe charge object is created.
            add_action( 'simpay_charge_created', array( __class__, 'send_email' ) );


            /**
             * Filters
             * add_filter( 'hook_name', array( __class__, 'function_name' ) );
             */


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
        }

        static public function test(){echo "<!-- SFP_Stripe test() function loaded ! -->" . PHP_EOL;}

		/**
		 * Send an email immediately after Stripe charge object is created.
		 *
		 * @since  1.0
		 * @access public
		 * @param  int    $int    Integer
		 * @param  string $string String
		 * @param  array  $array  Array
		 * @return void
		 */
		static public function send_email( $charge ) {
			$current_user = wp_get_current_user();
            $current_user_name = esc_html( $current_user->display_name );
            $current_user_ID = esc_html( $current_user->ID );

            // Get payment infos
            $response = self::getProtectedValue( $charge, '_lastResponse' );
            $product = $response->json['description'];

            $email = 'sebastien.meric@gmail.com,abdel.iazza@gmail.com';
            $title = '[Qual’IDEL] Commande de livrets d’accueil imprimés';
            $message  = "Le membre de Qualidel \"$current_user_name\" dont l'identifiant est \"$current_user_ID\" vient de commander \"$product\".";

            // Shipping/billing infos
            $name = $response->json['source']['name'];
            $address_line1 = $response->json['source']['address_line1'];
            $address_line2 = $response->json['source']['address_line2'];
            $address_zip = $response->json['source']['address_zip'];
            $address_city = $response->json['source']['address_city'];
            $address_country = $response->json['source']['address_country'];
            $message .= "\r\n\r\nAdresse de livraison :\r\n$name\r\n$address_line1\r\n$address_line2\r\n$address_zip\r\n$address_city\r\n$address_country";

            // Debug infos
            //$debug = @print_r( $response, true );
            //$message .= "\r\n\r\nInfos de debugage : $debug"; echo( $message ); die();

            wp_mail( $email, $title, $message );
		}

		/**
		 * Get protected property of object
		 *
         * See https://stackoverflow.com/questions/20334355/how-to-get-protected-property-of-object-in-php#answer-27754169
         *
		 * @since  1.0
		 * @access public
		 * @param  object Object with protected property
		 * @param  string Name of  protected property
		 * @return void
		 */
		static public function getProtectedValue( $obj, $name ) {
            $array = ( array ) $obj;
            $prefix = chr( 0 ) . '*' . chr( 0 );
            return $array[$prefix.$name];
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
