<?php
/**
 * WooCommerce
 *
 * https://fr.wordpress.org/plugins/woocommerce/
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
if ( ! class_exists( 'SFP_WooCommerce' ) && class_exists( 'WooCommerce' ) ) {

    class SFP_WooCommerce {

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

            // Redirect user after successful login.
            //add_action( 'init', array( __class__, 'custom_redirect_to_account_page' ) );
            // Redirect user to custom login page instead of wp-login.php.
            //add_action( 'login_form_login', array( __class__, 'redirect_to_custom_login' ) );

            // Remove product editor box support
            //add_action( 'admin_init', array( __class__, 'remove_boxes' ) );
            // Remove metaboxes
            //add_action( 'admin_menu', array( __class__, 'remove_metaboxes' ) );
            // Modify default flat rate
            //add_action( 'woocommerce_flat_rate_shipping_add_rate', array( __class__, 'add_another_custom_flat_rate' ), 10, 2 );


            /**
             * Filters
             * add_filter( 'hook_name', array( __class__, 'function_name' ) );
             */

            // Remove default woocommerce style sheets
            //add_filter( 'woocommerce_enqueue_styles', '__return_false' );

            //add_filter( 'authenticate', array( __class__, 'allow_email_login_for_customers' ), 20, 3 );
            // Adds the WooCommerce image display to all attachments galleries.
            //add_filter( 'wp_get_attachment_link', array( __class__, 'add_prettyphotos_to_attachment_link' ) );
            // Define the number of products displayed per shop page.
            //add_filter( 'loop_shop_per_page', array( __class__, 'products_per_page' ), 20 );
            // Remove related products
            //add_filter( 'woocommerce_related_products_args', array( __class__, 'remove_related_products' ), 10 );

            // Remove reviews' tab
            //add_filter( 'woocommerce_product_tabs', array( __class__, 'force_product_remove_reviews_tab' ), 98 );
            // Remove product reviews support
            //add_filter( 'woocommerce_register_post_type_product', array( __class__, 'register_post_type_product' ) );
            // Alternate way to remove product reviews :
            //remove_action( 'woocommerce_product_tabs', 'woocommerce_product_reviews_tab', 30 );
            //remove_action( 'woocommerce_product_tab_panels', 'woocommerce_product_reviews_panel', 30 );

            // Remove all WooCommerce tabs :
            //add_filter( 'woocommerce_product_tabs', array( __class__, 'force_product_remove_tabs' ), 98 );
            // Or get rid of "stupid tabs" only :
            //remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
            //remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20, 2);
            //add_action( 'woocommerce_single_product_summary', 'woocommerce_product_description_panel', 20 );

            // Modify paypal image on checkout
            //add_filter( 'woocommerce_paypal_icon', array( __class__, 'replace_paypal_icon' ) );

            // Modify My Account menu items
            //add_filter ( 'woocommerce_account_menu_items', array( __class__, 'account_menu_items' ) );
            //add_filter ( 'formatted_woocommerce_price', array( __class__, 'formatted_woocommerce_price' ), 15, 5 );
            // Extend search
            //add_action( 'pre_get_posts', array( __class__, 'search_pre_get_posts' ) );


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */


            /**
             * Faire apparaitre le contenu d'une page en tant que description générale appliquée à
             * l'ensemble des produits vendu dans la boutique
             */
            // Ajout d'une section d'admin dans WooCommerce > Réglages > Produits
            //add_filter( 'woocommerce_get_sections_products', array( __class__, 'new_products_section' ) );
            // Ajout de champs dans cette section
            //add_filter( 'woocommerce_get_settings_products', array( __class__, 'new_products_section_settings' ), 10, 2 );
            // Display the general products description in all product pages
            //add_action( 'woocommerce_before_single_product_summary', array( __class__, 'display_general_products_description' ) );

            /**
             * Translations
             */
            // Some user defined labels
            //add_filter( 'woocommerce_cart_shipping_method_full_label', array( __class__, 'cart_shipping_method_full_label' ), 10, 2 );
            // WooCommerce core text strings modifications
            //add_filter( 'gettext', array( __class__, 'text_changes' ), 10, 3);
            // Translate billing & shipping address edit pages slugs
            // see woocommerce/templates/myaccount/my-address.php
            add_filter( 'woocommerce_my_account_get_addresses', array( __class__, 'translate_addresses_edit_pages_slugs' ) );

        }

        static public function test(){echo "<!-- SFP_WooCommerce test() function loaded ! -->" . PHP_EOL;}


        /**
         * Redirect user after successful login.
         *
         * @since  1.0
         * @access public
         * @param  string $redirect_to URL to redirect to.
         * @return string
         */
        static public function custom_redirect_to_account_page( $redirect_to ) {
            if ( is_admin() && ! current_user_can( 'edit_posts' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

                /* print_r(wp_get_current_user());
                if(user_can( wp_get_current_user(), 'edit_posts' )) echo "current_user_can( 'edit_posts' )";
                else echo "not current_user_can( 'edit_posts' )";
                die(); */

                $redirect_to = home_url();
                $myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
                if ( $myaccount_page_id ) {
                    $redirect_to = get_permalink( $myaccount_page_id );
                }
                wp_redirect( $redirect_to );
                exit;
            }
        }


        /**
         * Redirect the user to the custom login page instead of wp-login.php.
         */
        static public function redirect_to_custom_login() {
            if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
                $woocommerce_myaccount_page = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
                $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;

                if ( is_user_logged_in() ) {
                    $user = wp_get_current_user();
                    if ( user_can( $user, 'manage_options' ) ) {
                        if ( $redirect_to ) {
                            wp_safe_redirect( $redirect_to );
                        }
                        else {
                            wp_redirect( admin_url() );
                        }
                    }
                    else {
                        wp_redirect( $woocommerce_myaccount_page );
                    }
                    exit;
                }
                // If this is not the login iframe inside the admin area...
                elseif ( ! isset( $_REQUEST['interim-login'] ) ) {
                    // ... then redirected to WooCommerce login page
                    if ( ! empty( $redirect_to ) ) {
                        $woocommerce_myaccount_page = esc_url( add_query_arg( 'redirect_to', urlencode( $redirect_to ), $woocommerce_myaccount_page ) );
                    }

                    wp_safe_redirect( $woocommerce_myaccount_page );
                    exit;
                }
            }
        }

        /**
         * Remove product editor box support
         * 
         * @since 0.1
         * @access public
         * @return void
         */
        static public function remove_boxes() {
            remove_post_type_support( 'product', 'editor' );
            remove_post_type_support( 'product', 'comments' );
        }

        /**
         * Remove metaboxes
         * 
         * @since 0.1
         * @access public
         * @return void
         */
        static public function remove_metaboxes() {
            remove_meta_box( 'commentsdiv', 'product', 'normal' );
        }

        /**
         * Modify default flat rate
         * 
         * @since  0.1
         * @param  object $method
         * @param  array  $rate
         * @return array
         */
        static public function add_another_custom_flat_rate( $method, $rate ) {
            $new_rate          = $rate;
            //$new_rate['id']   .= ':' . 'custom_rate_name'; // Append a custom ID.
            //$new_rate['label'] = $rate['label']; // Rename to 'Rushed Shipping'.
            // Minimum flat rate of 20€
            $new_rate['cost']  = $rate['cost'] >= 20 ? $rate['cost'] : 20; // Add $2 to the cost.

            // Add it to WC.
            $method->add_rate( $new_rate );
        }

        /**
         * Filter added to the authenticate filter hook, to fetch a username based on entered email
         * The user must have the email_login_allowed custom capability.
         *
         * @since  1.0
         * @access public
         * @param  obj $user [description]
         * @param  string $username [description]
         * @param  string $password [description]
         * @return boolean
         */
        static public function allow_email_login_for_customers( $user, $username, $password ) {
            if ( is_email( $username ) ) {
                $user = get_user_by_email( $username );
                if ( $user && user_can( $user, 'email_login_allowed' ) ) {
                    $username = $user->user_login;
                }
            }
            return wp_authenticate_username_password( null, $username, $password );
        }

        /**
         * Adds the WooCommerce image display to all attachments galleries.
         *
         * @since  1.0
         * @access public
         * @param  string $html [description]
         * @return string
         */
        static public function add_prettyphotos_to_attachment_link( $html ){
            global $post;
            // First check that woo exists to prevent fatal errors
            if ( function_exists( 'is_woocommerce' ) ) {
                $lightbox_en = get_option( 'woocommerce_enable_lightbox' ) == 'yes' ? true : false;
                if ( $lightbox_en && ( ! empty( $post->post_content ) && strstr( $post->post_content, '[gallery' ) ) ) {
                    $html = str_replace( '<a', '<a data-rel="prettyPhoto[product-gallery]"', $html );
                }
            }
            return $html;
        }

        /**
         * Define the number of products displayed per shop page.
         *
         * @since  1.0
         * @access public
         * @param  integer $nb Number of products displayed per shop page.
         * @return integer
         */
        static public function products_per_page( $nb ) {
            return 24;
        }

        /**
         * Clear the query arguments for related products so none show.
         *
         * @since  1.0
         * @access public
         * @param  array $args Query arguments for related products.
         * @return array
         */
        static public function remove_related_products( $args ) {
            return array();
        }

        /**
         * Remove reviews' tab
         * 
         * @since 0.1
         * @author Raz Ohad
         * @see https://gist.github.com/bainternet/5874466
         * @access public
         * @param  array $tabs
         * @return array
         */
        static public function force_product_remove_reviews_tab( $tabs ) {
            unset( $tabs['reviews'] );
            return $tabs;
        }

        /**
         * Remove product reviews support
         * 
         * @since 0.1
         * @author Raz Ohad
         * @see https://gist.github.com/bainternet/5874466
         * @access public
         * @param  array $product_attributes
         * @return array
         */
        static public function register_post_type_product( $product_attributes = array() ) {
            // Remove product reviews support
            if ( ( $key = array_search( 'comments', $product_attributes['supports'] ) ) !== false ) {
                unset( $product_attributes['supports'][$key] );
            }
            // Remove editor support
            if ( ( $key = array_search( 'editor', $product_attributes['supports'] ) ) !== false ) {
                unset( $product_attributes['supports'][$key] );
            }
            $product_attributes['supports'] = array_values( $product_attributes['supports'] );
            //print_r($product_attributes);
            //die();
            return $product_attributes;
        }

        /**
         * Remove all WooCommerce tabs
         * 
         * @since 0.1
         * @author Raz Ohad
         * @see https://gist.github.com/bainternet/5874466
         * @access public
         * @param  array $tabs
         * @return array
         */
        static public function force_product_remove_tabs( $tabs ) {
            return array();
        }

        /**
         * Modify paypal image on checkout
         * 
         * @since  0.1
         * @param  array $tabs
         * @return array
         */
        static public function replace_paypal_icon() {
            $main_class = Functionality_Plugin::get_instance();
            return trailingslashit( $main_class->plugin_url ) . 'assets/public/images/paypal.png';
        }

        /**
         * Modify My Account menu items
         * 
         * @since 0.1
         * @access public
         * @return void
         */
        static public function account_menu_items( $myorder = array() ) {
            $myorder = array(
                'dashboard'       => __( 'Dashboard', 'woocommerce' ),
                'orders'          => __( 'Orders', 'woocommerce' ),
                //'downloads'       => __( 'Downloads', 'woocommerce' ),
                'edit-address'    => __( 'Addresses', 'woocommerce' ),
                //'payment-methods' => __( 'Payment Methods', 'woocommerce' ),
                'edit-account'    => __( 'Account Details', 'woocommerce' ),
                'customer-logout' => __( 'Logout', 'woocommerce' ),
            );
            return $myorder;
        }

        /**
         * Replace the $thousand_separator with a &nbsp;
         *
         * @since  1.0
         * @access public
         * @param  integer $nb Number of products displayed per shop page.
         * @return integer
         */
        static public function formatted_woocommerce_price( $formated_price, $price, $decimals, $decimal_separator, $thousand_separator ) {
            return number_format( $price, $decimals, $decimal_separator, '&nbsp;' );
        }

        /**
         * Add custom join and where statements to product search query
         *
         * @param  mixed $query query object
         * @return void
         */
        static public function search_pre_get_posts( $query ){
            if ( $query->is_search() ) {
                add_filter( 'posts_join', array( __class__, 'search_post_join' ), 10, 2 );
                add_filter( 'posts_where', array( __class__, 'search_post_where' ), 10, 2 );
                add_filter( 'posts_groupby', array( __class__, 'search_post_groupby' ), 10, 2 );
            }
        }

        /**
         * Add Custom Join Code for wp_postmeta table
         *
         * @param  string $join
         * @param  object $query
         * @return string
         */
        static public function search_post_join( $join = '', $query ){
            global $wpdb;

            // Escape if nothing is searched
            if ( empty( $query->query_vars['s'] ) )
                return $join;

            // Meta data
            $join .= "
                INNER JOIN
                    {$wpdb->postmeta} AS pm ON ({$wpdb->posts}.ID = pm.post_id)
            ";

            // Taxonomies
            $join .= "
                INNER JOIN
                    {$wpdb->term_relationships} AS tr ON ({$wpdb->posts}.ID = tr.object_id)
                INNER JOIN
                    {$wpdb->term_taxonomy} AS tt ON (tt.term_taxonomy_id = tr.term_taxonomy_id)
                INNER JOIN
                    {$wpdb->terms} AS terms ON (terms.term_id = tt.term_id)
            ";

            return $join;
        }

        /**
         * Add custom where statement to product search query
         *
         * @param  string $where
         * @param  object $query
         * @return string
         */
        static public function search_post_where( $where = '', $query ){
            global $wpdb;

            // Escape if nothing is searched
            if ( empty( $query->query_vars['s'] ) )
                return $where;

            $where = preg_replace("/post_title LIKE ('%[^%]+%')/", "post_title LIKE $1)" .
                // Default fields
                " OR ({$wpdb->post_excerpt} LIKE $1)" .
                " OR (pm.meta_key = '_sku' AND CAST(pm.meta_value AS CHAR) LIKE $1)" .
                " OR (pm.meta_key = '_author' AND CAST(pm.meta_value AS CHAR) LIKE $1)" .
                " OR (pm.meta_key = '_publisher' AND CAST(pm.meta_value AS CHAR) LIKE $1)" .
                " OR (pm.meta_key = '_format' AND CAST(pm.meta_value AS CHAR) LIKE $1)" .
                // Custom fields
                //" OR (pm.meta_key = 'a_propos_du_designer' AND CAST(pm.meta_value AS CHAR) LIKE $1)" .
                //" OR (pm.meta_key = 'designer-name' AND CAST(pm.meta_value AS CHAR) LIKE $1)" .
                // Product tags and categories
                " OR (tt.taxonomy IN('product_cat','product_tag') AND CAST(terms.name AS CHAR) LIKE $1 "
                , $where);

            return $where;
        }

        /**
         * Add custom groupby statement to product search query
         *
         * @param  string $groupby
         * @param  object $query
         * @return string
         */
        static public function search_post_groupby( $groupby, $query ) {
            global $wpdb;

            // Escape if nothing is searched
            if ( empty( $query->query_vars['s'] ) )
                return $groupby;

            // We need to group on post ID
            $groupby_id = "{$wpdb->posts}.ID";

            // groupby was empty, use ours
            if ( ! strlen( trim( $groupby ) ) )
                return $groupby_id;

            // Wasn't empty, append ours
            return $groupby . ', ' . $groupby_id;
        }

        /**
         * Translate user defined strings in :
         * WooCommerce > Settings > Shipping > Shipping zones > Locations not covered by your other zones > Shipping method title
         *
         * @since  1.0
         * @access public
         * @param  integer $nb Number of products displayed per shop page.
         * @return integer
         */
        static public function cart_shipping_method_full_label( $label, $method ) {
            $return = $label;
            if ( 'Estimate shipping costs on request' == $label ) {
                $return = __( 'Estimate shipping costs on request', 'functionality-plugin' );
            }

            return $return;
        }

        /**
         * Change some of the text strings via the WP gettext filter.
         *
         * @since   1.0
         * @access  public
         *
         * @param   string  $translated_text  New text
         * @param   string  $text             Text to replace
         * @param   string  $domain           Plugin domain slug
         * @return  void
         */
        static public function text_changes( $translated_text, $text, $domain ) {
            if ( 'woocommerce' == $domain ) {
                if ( 'Related Products' == $text ) {
                    $translated_text = __( 'You may also like', 'functionality-plugin' );
                }
            }

            return $translated_text;
        }

        /**
         * Translate billing & shipping address edit pages slugs.
         *
         * @since   1.0
         * @access  public
         *
         * @param   array  $slugs  Billing & shipping address edit pages slugs
         * @return  array
         */
        static public function translate_addresses_edit_pages_slugs( $slugs = array() ){
            return array(
                _x( 'billing', 'edit-address-slug', 'woocommerce' ) => __( 'Billing Address', 'woocommerce' ),
                _x( 'shipping', 'edit-address-slug', 'woocommerce' ) => __( 'Shipping Address', 'woocommerce' )
            );
        }

        /**
         * Create a new section beneath the products tab in 
         * Woocommerce > Settings > Products
         * 
         * @since  0.1
         * 
         * @param  array  $sections
         * @return array
         */
        static public function new_products_section( $sections ) {
            $sections['sfp_extended_options'] = __( 'Extended options', 'functionality-plugin' );
            return $sections;
        }

        /**
         * Add settings to the new created section
         * 
         * @since  0.1
         * 
         * @param  array  $rate
         * @return array
         */
        static public function new_products_section_settings( $settings = array(), $current_section ) {
            // Check if the current section is the new one we added
            if ( $current_section == 'sfp_extended_options' ) {
                // Add Title to the Settings
                $settings[] = array(
                    'type' => 'title',
                    'name' => __( 'Extended options', 'functionality-plugin' ),
                    'desc' => __( 'The following options extends tne WooCommerce plugin options and are specific to this website.', 'functionality-plugin' ),
                    'id'   => 'sfp_extended_options_title'
                );

                // Add single_select_page field option
                $settings[] = array(
                    'type'     => 'single_select_page',
                    'name'     => __( 'Page used for general products description', 'functionality-plugin' ),
                    'desc'     => __( 'If needed, select the page that contains a general products description that will be displayed at the top of every products pages.', 'functionality-plugin' ),
                    'id'       => 'sfp_products_description',
                    'class'    => 'wc-enhanced-select-nostd',
                    'css'      => 'min-width:300px;',
                );

                // End of section
                $settings[] = array(
                    'type' => 'sectionend',
                    'id'   => 'sfp_extended_options',
                );
            }

            return $settings;
        }

        /**
         * Display a general product description in every product page
         * 
         * @since  0.1
         * 
         * @return void
         */
        static public function display_general_products_description() {
            // Page containing the description
            $page_id = get_option( 'sfp_products_desc' );

            // Display description
            echo '<div class="general-description">' . self::get_post_content( $page_id ) . '</div>';
        }

        /**
         * Utility used to get a post content by ID with formating
         * 
         * @see https://polylang.wordpress.com/documentation/documentation-for-developers/functions-reference/
         *
         * @since  0.1
         * 
         * @return void
         */
        static public function get_post_content( $id, $raw = false ) {
            // Multilanguage with Polylang
            if ( function_exists( 'pll_current_language' ) && pll_current_language() != pll_get_post_language( $id ) ) {
                $id = pll_get_post( $id );
            };
            $post_object = get_post( $id );
            return $raw ? $post_object->post_content : wpautop( do_shortcode( $post_object->post_content ) );
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
