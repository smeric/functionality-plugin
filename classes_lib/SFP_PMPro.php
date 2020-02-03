<?php
/**
 * Paid Memberships Pro
 *
 * https://fr.wordpress.org/plugins/paid-memberships-pro/
 * https://fr.wordpress.org/plugins/pmpro-register-helper/
 *
 * https://github.com/strangerstudios/pmpro-addon-packages
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
// Don't break if Register Helper is not loaded
if ( ! class_exists( 'SFP_PMPro' ) && defined( 'PMPRO_VERSION' ) && defined( 'PMPRORH_VERSION' ) ) {

    class SFP_PMPro {

        /**
         * Initialize the class
         *
         * @since  1.0
         * @access public
         * @return void
         */
        static public function init() {
            // Don't break if Register Helper is not loaded
            /*if ( ! defined( 'PMPRO_VERSION' ) || ! defined( 'PMPRORH_VERSION' ) ) {
                return false;
            }*/

            /**
             * Actions
             * add_action( 'hook_name', array( __class__, 'function_name' ), 10, 2 );
             */
            add_action( 'wp_head', array( __class__, 'test' ) );

            // Redirect non-members away from CPTs with specific term
            //add_action( 'template_redirect', array( __class__, 'term_template_redirect' ) );
            // Remove the (hidden as an HTML comment) comment that is added to mark a site
            // as using Paid Memberships Pro
            remove_action( 'wp_footer', 'pmpro_footer_link' );
            // Remove the redirect on homepage access
            remove_action( 'template_redirect', 'pmpromh_template_redirect_homepage' );
            // Register billing and checkout fields
            add_action( 'init', array( __class__, 'register_fields' ) );
            
            // Additional infos on user profile edit page
            //add_action( 'edit_user_profile_intro', array( __class__, 'edit_user_profile_intro' ) );


            /**
             * Filters
             * add_filter( 'hook_name', array( __class__, 'function_name' ) );
             */
            //add_filter( 'pmpro_longform_address', '__return_false' );
            // @see https://github.com/strangerstudios/paid-memberships-pro/blob/dev/includes/content.php#L380
            add_filter( 'the_content', 'pmpro_membership_content_filter', 999999 );
            // No register message on excerpt
            remove_filter('the_excerpt', 'pmpro_membership_excerpt_filter', 15);

            // Fix username issue
            add_filter( 'pmpro_registration_checks', array( __class__, 'fix_username_issue' ) );
            // Un-require some fields
            add_filter( 'pmpro_required_billing_fields', array( __class__, 'required_billing_fields' ) );

            // Hide widgets by sidebar ID on members only content when the current user does not have access.
            //add_filter( 'widget_display_callback', array( __class__, 'widget_display_callback' ), 10, 3);

            // Additional membership pages
            add_filter('pmpro_extra_page_settings', array( __class__, 'pmpro_extra_page_settings' ) );

            // Remove standard billing address fields...
            add_filter( 'pmpro_include_billing_address_fields', '__return_false' );
            // ... and pre-populate the new ones
            add_filter( 'pmprorh_get_html', array( __class__, 'get_user_metadata_pmpro_prepop' ), 10, 2 );

            // Use different menu for members and none members
            add_filter( 'wp_nav_menu_args', array( __class__, 'menu_display' ), 15 );

            // L'inscription concerne-t-elle un adhérent d'AnotherSite ?
            add_action( 'pmpro_membership_level_after_other_settings', array( __class__, 'pmpro_membership_level_after_other_settings' ) );
            add_action( 'pmpro_save_membership_level', array( __class__, 'pmpro_save_membership_level' ) );
            add_filter( 'pmpro_registration_checks', array( __class__, 'another_site_registration_checks' ) );

            // Gives admin access to everything
            // TODO : replace level ID with administrator created level
            add_filter( 'pmpro_has_membership_access_filter', array( __class__, 'pmpro_has_membership_access_filter' ), 10, 3 );

            // Affichage du prix de chaque abonnement
            add_filter( 'pmpro_format_price', array( __class__, 'pmpro_format_price' ), 10, 4 );
            // Modification de chaines à traduire
            add_filter( 'gettext', array( __class__, 'gettext' ), 10, 3);

            /**
             * PMPro Addon Package extension
             */
            // Set levels as "all access levels" so members of these levels will be able to view all Addon Packages.
            //add_filter( 'pmproap_all_access_levels', array( __class__, 'pmproap_all_access_levels' ), 10, 3 );


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
            // Display some member's informations
            add_shortcode( 'members_details', array( __class__, 'members_details_shortcode' ) );
            // Display login page as shortcode
            add_shortcode( 'pmpro_login', array( __class__, 'pmpro_login_page_shortcode' ) );
            // Display profile page as shortcode
            add_shortcode( 'pmpro_profile', array( __class__, 'pmpro_profile_page_shortcode' ) );
        }

        static public function test(){echo "<!-- SFP_PMPro test() function loaded ! -->" . PHP_EOL;}


        /**
         * Redirect non-members away from CPTs with specific term
         *
         * @see     https://gist.github.com/strangerstudios/be666d7681118b30b875
         *
         * @since   1.0
         * @access  public
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void
         */
        static public function term_template_redirect() {
            global $post;
            // TODO : change category and level ID here
            if ( has_term( 'my_category', 'category', $post ) && ! pmpro_hasMembershipLevel() ) {
                wp_redirect( pmpro_url( 'levels' ) );
                exit;
            }
        }


        /**
         * Adds additional user fields
         *
         * Adds additional user fields to the membership checkout, user profile, and register form pages
         *
         * @see     http://www.paidmembershipspro.com/add-ons/free-add-ons/pmpro-register-helper-add-checkout-and-profile-fields/quick-start-guide/
         *
         * @since   1.0
         * @access  public
         *
         * @return  void
         */
        static public function register_fields() {
            // Define the fields
            $fields = array();

            $fields[] = new PMProRH_Field (
                'gender',                                                 // input name, will also be used as meta key
                'select',                                                 // type of field
                array(
                    'label'    => __( 'Gender', 'functionality-plugin' ), // custom field label
                    'required' => true,                                   // make this field required
                    'profile'  => true,                                   // show in user profile => "only", Show on checkout page too => true, show for admins only => 'only_admin'
                    'options' =>  array(                                  // <option> elements for select field
                        ''  => '',                                        // blank option - cannot be selected if this field is required
                        'm' => __( 'Male', 'functionality-plugin' ),      // <option value="m">Male</option>
                        'f' => __( 'Female', 'functionality-plugin' )     // <option value="f">Female</option>
                    ),
                )
            );

            $address_fields = array(
                'bfirstname' => __( 'First Name', 'functionality-plugin' ),
                'blastname'  => __( 'Last Name', 'functionality-plugin' ),
                'baddress1'  => __( 'Address 1', 'functionality-plugin' ),
                'bcity'      => __( 'City', 'functionality-plugin' ),
                'bzipcode'   => __( 'Zipcode', 'functionality-plugin' ),
                //'bmobile'    => __( 'Mobile', 'functionality-plugin' ),
                //'bphone'     => __( 'Phone', 'functionality-plugin' ),
            );

            //define the fields
            foreach ( $address_fields as $name => $label ) {
                $fields[] = new PMProRH_Field(
                    $name,                   // input name, will also be used as meta key
                    'text',                  // type of field
                    array(
                        'label'    => $label,
                        'size'     => 30,    // input size
                        'required' => false, // make this field required
                        'profile'  => true,  // show in user profile => "only", Show on checkout page too => true, show for admins only => 'only_admin'
                    )
                );
            }

            $fields[] = new PMProRH_Field(
                'bmobile',              // input name, will also be used as meta key
                'text',                 // type of field
                array(
                    'label'   => __( 'Mobile', 'functionality-plugin' ),
                    'size'    => 30,    // input size
                    'required' => true, // make this field required
                    'profile' => true,  // show in user profile => "only", Show on checkout page too => true, show for admins only => 'only_admin'
                )
            );

            /*
            $fields[] = new PMProRH_Field(
                'bemail',               // input name, will also be used as meta key
                'text',                 // type of field
                array(
                    'label'    => __( 'E-mail Address', 'functionality-plugin' ),
                    'size'     => 30,   // input size
                    'required' => true, // make this field required
                    'profile'  => true, // show in user profile => "only", Show on checkout page too => true, show for admins only => 'only_admin'
                )
            );
            */

            /*
            $fields[] = new PMProRH_Field(
                'bconfirmemail',        // input name, will also be used as meta key
                'text',                 // type of field
                array(
                    'label'    => __( 'Confirm E-mail', 'functionality-plugin' ),
                    'size'     => 30,   // input size
                    'required' => true, // make this field required
                    'profile'  => true, // show in user profile => "only", Show on checkout page too => true, show for admins only => 'only_admin'
                )
            );
            */

            // Add a new checkout box with label
            pmprorh_add_checkout_box( 'billing_mailing_address', __( 'Billing Address', 'functionality-plugin' ) );

            // Add the fields into a new checkout_boxes are of the checkout page
            foreach ( $fields as $field ) {
                pmprorh_add_registration_field (
                    'billing_mailing_address', // location on checkout page
                    $field                     // PMProRH_Field object
                );
            }

            // That's it. See the PMPro Register Helper readme for more information and examples.
        }


        /**
         * Additional infos on user profile edit page
         *
         * @since   1.0
         * @access  public
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void
         */
        static public function edit_user_profile_intro( $user ) {
            
        }


        /**
         * Fix username issue
         *
         * @see     https://gist.github.com/mathieuhays/6298e2bdc8d127ec3c3a
         *
         * @since   1.0
         * @access  public
         * @param   bool    $pmpro_continue_registration  Shoud we continue ?
         * @return  void
         */
        static public function fix_username_issue( $pmpro_continue_registration ) {
            if ( ! $pmpro_continue_registration ) {
                return $pmpro_continue_registration;
            }

            global $username, $pmpro_error_fields;

            $safe_username = sanitize_user( $username, true );
            $shouldReturn = false;

            // Check for invalid characters
            if ( $username !== $safe_username ) {
                pmpro_setMessage( __( 'The username specified contains forbidden characters.', 'functionality-plugin' ), 'pmpro_error' );
                $pmpro_error_fields[] = 'username';
                $shouldReturn = true;
            }

            // Check if username exists
            if ( username_exists( $safe_username ) || username_exists( $username ) ) {
                pmpro_setMessage(__( 'The username specified already exists.', 'functionality-plugin' ), 'pmpro_error' );
                $pmpro_error_fields[] = 'username';
                $shouldReturn = true;
            }

            if ( $shouldReturn )
                return false;

            return $pmpro_continue_registration;
        }


        /**
         * Un-require fields
         *
         * Change some of the billing fields to be not required to support international addresses
         * that don't have a state, etc.
         * Default fields are: bfirstname, blastname, baddress1, bcity, bstate, bzipcode, bphone, bemail,
         * bcountry, CardType, AccountNumber, ExpirationMonth, ExpirationYear, CVV
         *
         * @since   1.0
         * @access  public
         * 
         * @param   array   $fields   Array
         * @return  void
         */
        static public function required_billing_fields( $fields ) {
            unset( $fields['bstate'] );
            unset( $fields['bcountry'] );
            //unset( $fields['bemail'] );
            //unset( $fields['bconfirmemail'] );
            unset( $fields['bphone'] );

            //$fields['bmobile'] = '';

            return $fields;
        }


        /**
         * Hide widgets by sidebar ID on members only content when the current user does not have access.
         *
         * TODO : Update below with the array of sidebar IDs you want to filter.
         *
         * @see     https://gist.github.com/strangerstudios/b81443b278a6b27102b666c36084d624
         *
         * @since   1.0
         * @access  public
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void
         */
        static public function widget_display_callback( $instance, $widget, $args ) {
            $hide_sidebars_array = array( 'sidebar-1','sidebar-2' );
            global $post;
            if ( is_user_logged_in() && function_exists( 'pmpro_has_membership_access' ) && !pmpro_has_membership_access( $post->ID ) ) {
                if ( in_array( $args['id'], $hide_sidebars_array ) )
                    return false;
                else
                    return $instance;
            }
        }


        /**
         * Additional membership pages
         *
         * @since   1.0
         * @access  public
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void
         */
        static public function pmpro_extra_page_settings( $pages = array() ) {
            //$pages['login'] = array( 'title' => __( 'Membership Login', 'functionality-plugin' ), 'hint' => __( 'Include the shortcode [pmpro_login].', 'functionality-plugin' ) );
            $pages['profile'] = array( 'title' => __( 'Membership Profile', 'functionality-plugin' ), 'hint' => __( 'Include the shortcode [pmpro_profile].', 'functionality-plugin' ) );
            return $pages;
        }


        /**
         * Pre populate user infos fields with their values if already provided
         *
         * @since   1.0
         * @access  public
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void
         */
        static public function get_user_metadata_pmpro_prepop( $r, $instance ) {
            $current_user = wp_get_current_user();

            if ( ! ( $current_user instanceof WP_User ) )
                return $r;

            $fields = array(
                'bemail'        => $current_user->user_email,
                'bconfirmemail' => $current_user->user_email,
                'bfirstname'    => $current_user->first_name,
                'blastname'     => $current_user->user_lastname,
            );

            foreach ( $fields as $key => $default ) {
                if ( $key === $instance->name ) {
                    $value = get_user_meta( $current_user->ID, $key, true );
                    if ( ! $value && $default )
                        $value = $default;
                    $r = preg_replace ( '/ name="' . esc_attr( $instance->name ) . '" value=""/', ' name="' . esc_attr( $instance->name ) . '" value="' . esc_attr( $value ) . '"', $r );
                    break;
                }
            }

            return $r;
        }


        /**
         * Use different menu for members and none members
         *
         * @since   1.0
         * @access  public
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void
         */
        static public function menu_display( $args = NULL ) {
            //print_r($args);
            if ( $args['menu_id'] == 'header-menu' ) {
                if ( ! is_user_logged_in() || ! pmpro_hasMembershipLevel() ) {
                    // If user isn't logged in or is logged in but doesn't have a membership - show this primary menu.
                    $args['menu'] = 'Menu principal';
                }
                else {
                    // Registered user menu.
                    $args['menu'] = 'Menu principal adherents';
                }
            }
            return $args;
        }


        /**
         * L'inscription concerne-t-elle un adhérent de AnotherSite ?
         *
         * @since   1.0
         * @access  public
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void
         */
        static public function another_site_registration_checks( $continue ) {
            // Is bemail even set?
            if ( isset($_REQUEST['bemail'] ) ) {
                global $pmpro_level;
                // gel all levels
                $levels = self::get_all_levels_data();
                $another_site_levels = array();
                foreach( $levels as $level ){
                    if ( intval( get_option( 'pmpro_another_site_specific_' . $level->id, '' ) ) ){
                        $another_site_levels[] = $level->id;
                    }
                }
                // Okay check if it's an AnotherSite member email
                if ( is_object( $pmpro_level )
                        && null !== $pmpro_level
                        && in_array( $pmpro_level->id, $another_site_levels )
                        && ! self::is_another_site_member( $_REQUEST['bemail'] )
                    ) {
                    pmpro_setMessage( sprintf( __( "It doesn't seems that you are a valid member of AnotherSite. Please <a href=\"%s\" >choose another subscription type</a> as this one is for  AnotherSite members only.", 'functionality-plugin' ), pmpro_url( 'levels' ) ), 'pmpro_error' );
                    return false;
                }
            }
            
            return $continue;
        }

		/**
		 * Get all PMPro levels
		 *
		 * @since   1.0
		 * @access  public
		 * @param   int     $int     Integer
		 * @param   string  $string  String
		 * @param   array   $array   Array
		 * @return  void
		 */
		private function get_all_levels_data() {
			global $wpdb;
            $sqlQuery = "SELECT * FROM $wpdb->pmpro_membership_levels ORDER BY id ASC";
            $levels = $wpdb->get_results( $sqlQuery, OBJECT );
            return $levels;
		}

		/**
		 * Check AnotherSite API for membership
		 *
		 * @since   1.0
		 * @access  public
		 * @param   int     $int     Integer
		 * @param   string  $string  String
		 * @param   array   $array   Array
		 * @return  void
		 */
        static public function is_another_site_member( $email ) {
            $member = trim( wp_remote_retrieve_body( wp_remote_get( "http://api.anothersite.com/?email=" . sanitize_email( $email ) ) ) );

            if ( $member === '1' || $member === 1 )
                $return = true;
            else
                $return = false;

            return $return;
        }


        /**
         * Adds a checkbox field to the edit membership levels page so
         * one can force a level to be "another site" specific
         *
         * @since   1.0
         * @access  public
         * @return  void
         */
        static public function pmpro_membership_level_after_other_settings () {
            $level_id = intval( $_REQUEST['edit'] );
            $another_site_specific = 0;
            if ( $level_id > 0 ) {
                $another_site_specific = self::is_another_site_specific( $level_id );
            }
            ?>
            <h3 class="topborder"><?php _e( 'Level reserved for members of AnotherSite', 'functionality-plugin' ) ?></h3>
            <p><?php _e( 'If you need to be member of AnotherSite to purchase this level, then check this', 'functionality-plugin' ) ?></p>
            <table>
                <tbody class="form-table">
                <tr>
                    <th scope="row" valign="top"><label><?php _e( 'Must be member of AnotherSite:', 'functionality-plugin' ) ?></label></th>
                    <td>
                        <input type="checkbox" name="another_site_specific" value="1" <?php echo $another_site_specific ? ' checked="checked"' : '' ?> /> <label for="another_site_specific"><?php _e( 'Yes', 'functionality-plugin' ) ?></label>
                    </td>
                </tr>
                </tbody>
            </table>
        <?php
        }


        /**
         * Is a level "another site" specific ?
         *
         * @since   1.0
         * @access  private
         * @param   int  $level_id  ID of checked level
         * @return  int  0 or 1
         */
        static private function is_another_site_specific ( $level_id ) {
            return intval( get_option( 'pmpro_another_site_specific_' . $level_id, '' ) );
        }


        /**
         * Save the option that make a level AnotherSite specific or not
         *
         * @since   1.0
         * @access  public
         * @param   int  $level_id  ID of checked level
         * @return  void
         */
        static public function pmpro_save_membership_level ( $level_id ) {
            update_option( 'pmpro_another_site_specific_' . $level_id, ( $_REQUEST['another_site_specific'] ? 1 : 0 ) );
        }


        /**
         * Give administrator (and members with level N) access to everything
         *
         * TODO : Replace level ID to "Access to all" level ID :)
         *
         * @see     https://wordpress.org/support/topic/give-administrator-all-access
         *
         * @since   1.0
         * @access  public
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void
         */
        static public function pmpro_has_membership_access_filter( $access, $post, $user ) {
            if ( is_user_logged_in() && current_user_can( 'administrator' )
                || // Level 3 ALWAYS has access !
                ! empty( $user->membership_level ) && $user->membership_level->ID == 3
            ) {
                $access = true;
            }

            return $access;
        }


        /**
         * Affichage du prix de chaque abonnement
         *
         * @since   1.0
         * @access  public
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void
         */
        static public function pmpro_format_price( $formatted, $price, $pmpro_currency, $pmpro_currency_symbol ) {
            return intval( $price ) . ' ' . $pmpro_currency_symbol . ' TTC par an';
        }


        /**
         * Change some of the text strings in PMPro via the WP gettext filter.
         *
         * TODO : Specify the translatable strings that must be changed.
         *
         * @see     https://gist.github.com/strangerstudios/dd8252a9c7a1c95eba3774af30226bab
         *
         * @since   1.0
         * @access  public
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void
         */
        static public function gettext( $translated_text, $text, $domain ) {
            if ( 'pmpro' == $domain ) {
                if ( 'Payment Information' == $text )
                    $translated_text = __( 'Payment Information', 'functionality-plugin' );
                elseif ( 'Submit and Check Out' == $text )
                    $translated_text = __( 'Submit and Check Out', 'functionality-plugin' );
                elseif ( 'Already have an account?' == $text )
                    $translated_text = __( 'Already have an account?', 'functionality-plugin' );
                elseif ( 'Log in here' == $text )
                    $translated_text = __( 'Log in here', 'functionality-plugin' );
                elseif ( 'I agree to the %s' == $text )
                    $translated_text = __( 'I agree to the %s', 'functionality-plugin' );
            }

            return $translated_text;
        }


        /**
         * Display some member's informations
         *
         * Use the shortcode [members_details] to show information on your post/page
         *
         * @since   1.0
         * @access  public
         *
         * @param   array   $atts  Shortcode attributes
         * @return  string         What to display...
         */
        static public function members_details_shortcode( $atts ){
            if ( is_user_logged_in() && function_exists( 'pmpro_hasMembershipLevel' ) && pmpro_hasMembershipLevel() ) {
                global $current_user;

                $user_name = $current_user->display_name;
                $level_name = $current_user->membership_level->name;
                $expiration_date = date( __( 'd-m-Y', 'functionality-plugin' ), $current_user->membership_level->enddate );
                
                if ( ! isset( $current_user->membership_level->enddate ) || empty( $current_user->membership_level->enddate ) ) {
                    //Change this line for the expiry date if level never expires
                    $expiration_date = __( 'Never', 'functionality-plugin' );
                }
                
                return sprintf( __( 'Hi %1$s, <br/><br/>Membership: %2$s<br/>Your membership expires: %3$s', 'functionality-plugin' ), $user_name, $level_name, $expiration_date );

            }else{
                return __( 'You do not have a level', 'functionality-plugin' );
            }
        }


        /**
         * Display login page as shortcode [pmpro_login]
         *
         * @since   1.0
         * @access  public
         */
        static public function pmpro_login_page_shortcode( $atts, $content = null, $code='' ) {
            global $pmpro_page_name;

            $temp_content = pmpro_loadTemplate( 'login', 'local', 'pages' );

            return apply_filters( 'pmpro_pages_shortcode_login', $temp_content );
        }


        /**
         * Display profile page as shortcode [pmpro_profile]
         *
         * @since   1.0
         * @access  public
         */
        static public function pmpro_profile_page_shortcode( $atts, $content = null, $code='' ) {
            global $pmpro_page_name;

            $temp_content = pmpro_loadTemplate( 'profile', 'local', 'pages' );

            return apply_filters( 'pmpro_pages_shortcode_profile', $temp_content );
        }


        /**
         * MPro Addon Package extension
         *
         * Set levels as "all access levels" so members of these levels will be able to view all Addon Packages.
         * Requires Paid Memberships Pro and the pmpro-addon-packages plugin.
         *
         * @see https://www.paidmembershipspro.com/add-ons/pmpro-purchase-access-to-a-single-page/
         * @see https://www.paidmembershipspro.com/using-pmpro-addons-plugin/
         * @see https://github.com/strangerstudios/pmpro-addon-packages
         * @see https://gist.github.com/strangerstudios/3845777
         *
         * @since   1.0
         * @access  public
         */
        static public function pmproap_all_access_levels( $levels, $user_id, $post_id ) {
            //I'm just adding the level, but I could do some calculation based on the user and post id to programatically give access to content
            $levels = array(2);
            return $levels;
        }


        /**
         * Check if the current user is logged in and an old member
         *
         * @since   1.0
         * @access  public
         * @return  mixed  boolean/null
         */
        static public function was_member() {
            global $wpdb, $current_user;

            $return = false;

            // Must be logged in to know if she was a member...
            if ( empty( $current_user->ID ) ) {
                $return = null;
            }
            else {
                //$old_member = $wpdb->get_var("SELECT id FROM $wpdb->pmpro_memberships_users WHERE user_id = '" . $current_user->ID . "' AND status NOT IN('cancelled')");
                $old_member = $wpdb->get_var("SELECT id FROM $wpdb->pmpro_memberships_users WHERE user_id = '" . $current_user->ID . "' AND status = 'expired'");
                if ( ! empty( $old_member ) )
                    $return = true;
            }

            return $return;
        }

        /**
         * Shortcode to display content to old members only
         *
         * @since  1.0
         * @access public
         *
         * Example usage:
         * [was_member]This part for old members only[/was_member]
         */
        static public function was_member_shortcode ( $atts, $content = null ) {
            extract( shortcode_atts( array(
                'silent' => 0,
            ), $atts, 'was_member' ) );

            $return = '';
            $was_member = self::was_member();

            if ( ! $was_member ) {
                if ( ! $silent ) {
                    $return = __( 'Sorry but this content is for old members only.', 'functionality-plugin' );
                }
                else {
                    $return = '';
                }
            }
            else {
                $return = do_shortcode( $content );
            }
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
