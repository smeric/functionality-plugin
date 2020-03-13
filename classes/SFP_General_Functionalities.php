<?php
/**
 * Usefull functionalities
 * 
 * @package     SFP
 * @subpackage  SFP/includes
 * @copyright   Copyright (c) 2016, Sébastien Méric
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 * @author      Sébastien Méric <sebastien.meric@gmail.com>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Main class.
if ( ! class_exists( 'SFP_General_Functionalities' ) ) {

    class SFP_General_Functionalities {

        /**
         * Initialize the class
         *
         * @since   1.0
         * @access  public
         *
         * @return  void
         */
        static public function init() {
            /**
             * Actions
             * add_action( 'hook_name', array( __class__, 'function_name' ), 10, 2 );
             */
            add_action( 'wp_head', array( __class__, 'test' ) );

            // Credits dashboard widget
            //add_action( 'wp_dashboard_setup', array( __class__, 'add_dashboard_widgets' ) );

            // Add a custom css box to some post types
            // @see https://wordpress.org/support/plugin/rt-custom-css-page-and-post
            //add_action( 'admin_menu',  array( __class__, 'custom_css_hooks' ) );
            //add_action( 'save_post',   array( __class__, 'save_custom_css' ) );
            //add_action( 'wp_head',     array( __class__, 'insert_custom_css' ) );

            // Registering redirections
            //add_action( 'login_form', array( __class__, 'redirect_after_login' ) );
            //add_action( 'wp_login_failed', array( __class__, 'front_end_login_fail' ) );
            //add_action( 'admin_init', array( __class__, 'no_admin_access' ), 100 );
            //add_action( 'wp_authenticate', array( __class__, 'catch_empty_user' ), 1, 2 );
            //add_action( 'login_init', array( __class__, 'loggedin_redirect' ) );

            // add target="_blank" et rel="" aux liens externes
            add_action( 'sfp_dynamic_external_js_snippet', array( __class__, 'external_links_extras' ) );


            /**
             * Filters
             * add_filter( 'hook_name', array( __class__, 'function_name' ) );
             */

            // Author Link on Site Functionality Plugin
            // A tester ---------------------------------------------------------------
            //add_filter( 'plugin_row_meta', array( __class__, 'author_link' ), 10, 2 );

            // Loading dynamic external js file
            add_action( 'wp_enqueue_scripts', array( __class__, 'dynamic_external_js_enqueue' ) );
            add_action( 'template_redirect', array( __class__, 'dynamic_external_js' ) );
            add_filter( 'query_vars', array( __class__, 'dynamic_external_js_query_vars' ) );
            add_filter( 'redirect_canonical', array( __class__, 'dynamic_external_js_canonical', 10, 2 ) );

            // Remove accents and special chars from media file names
            add_filter( 'sanitize_file_name', 'remove_accents', 10, 1 );
            add_filter( 'sanitize_file_name_chars', array( __class__, 'sanitize_file_name_chars' ), 10, 1 );

            // Remove special chars from permalinks
            add_action( 'wp_insert_post_data', array( __class__, 'process_permalink' ) );

            // Allow more file types upload in media library
            add_filter( 'upload_mimes', array( __class__, 'upload_mimes' ) );

            // Replace translatable original text
            //add_filter( 'gettext', array( __class__, 'replace_translatable_text' ), 10, 3);
            //add_filter( 'gettext_with_context', array( __class__, 'replace_contextualy_translatable_text' ), 10, 4 );

            // Insert media wrapped in figure with relative url in editor
            add_filter( 'image_send_to_editor', array( __class__, 'insert_figure' ), 10, 9 );
            // Changing the internal links to use relative shortlinks instead of permalinks in WordPress editor.
            add_filter( 'wp_link_query',  array( __class__, 'relative_shortlinks_as_permalinks' ) );

            // Add body classes
            add_filter( 'body_class', array( __class__, 'more_body_classes' ) );
            add_filter( 'admin_body_class', array( __class__, 'more_body_classes' ) );

            // Add the defer attribute to external JavaScript files load
            //add_filter( 'clean_url', array( __class__, 'defer_parsing_of_js' ), 11, 1 );
            add_filter( 'script_loader_tag', array( __class__, 'defer_parsing_of_js' ), 10 );

            // Send menu items link to target _top by default
            add_filter( 'wp_setup_nav_menu_item', array( __class__, 'filter_menu_target_top' ) );


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */

            // Enable shortcodes in text widgets
            add_filter( 'widget_text', 'do_shortcode' );
            add_filter( 'widget_text_content', 'shortcode_unautop' );

            add_shortcode( 'wplogout', array( __class__, 'logout_link_shortcode' ) );
            add_shortcode( 'wplostpassword', array( __class__, 'lost_password_link_shortcode' ) );
            add_shortcode( 'wploginform', array( __class__, 'login_form_shortcode' ) );
            add_shortcode( 'wplogged_in', array( __class__, 'logged_in_shortcode' ) );
            add_shortcode( 'wplogged_out', array( __class__, 'logged_out_shortcode' ) );
            add_shortcode( 'wpregistrationform', array( __class__, 'registration_form_shortcode' ) );

        }

        static public function test(){echo "<!-- SFP_General_Functionalities test() function loaded ! -->" . PHP_EOL;}

        /**
         * Author Link on Site Functionality Plugin
         *
         */
        static public function author_link( $links, $file ) {
            if ( strpos( $file, 'core-functionality.php' ) !== false ) {
                $links[1] = __( 'By <a href="http://www.sebastien-meric.com/">S&eacute;bastien M&eacute;ric</a>', 'functionality-plugin' );
            }
            return $links;
        }

        /**
         * Custom styles for selected post types
         *
         * @since   1.0
         * @access  private
         *
         * @hook    filter  custom_css_hooks_post_types  Post types where meta box is in use
         *
         * @return  array
         */
        static private function custom_css_hooks_post_types() {
            return apply_filters( 'custom_css_hooks_post_types', array( 'post', 'page' ) );
        }

        /**
         * Custom meta box for custom styles
         *
         * @since   1.0
         * @access  public
         *
         * @return  void
         */
        static public function custom_css_hooks() {
            $post_types = self::custom_css_hooks_post_types();
            foreach ( $post_types as $post_type )
                add_meta_box( 'post_type_custom_css', __( 'Custom styles', 'functionality-plugin' ), array( __class__, 'custom_css_input'), $post_type, 'normal', 'high' );
        }

        /**
         * Custom meta box content
         *
         * @since   1.0
         * @access  public
         *
         * @global  object  Post object
         *
         * @hook    filter  custom_css_input_tokens  Tokens used to personalyse classes or ids
         *
         * @return  void
         */
        static public function custom_css_input() {
            global $post;
            echo '<p>' . __( 'Here you can specify some CSS to be applied only to this post single post page.', 'functionality-plugin' ) . '</p>';
            echo '<input type="hidden" name="post_type_custom_css_noncename" id="post_type_custom_css_noncename" value="' . wp_create_nonce( 'post-type-custom-css' ) . '" />';
            echo '<textarea class="widefat" name="post_type_custom_css" id="post_type_custom_css" rows="6">' . get_post_meta( $post->ID, 'post_type_custom_css', true ) . '</textarea>';
            $tokens = apply_filters( 'custom_css_input_tokens', array(
                '%%post_id%%' => __( 'Current post ID', 'functionality-plugin' ),
                '%%post_slug%%' => __( 'Current post slug', 'functionality-plugin' ),
            ));
            foreach ( $tokens as $token => $value )
                $displayed_tokens[] = $token . ' : ' . $value;
            printf( __( '<p class="description">No &lt;style&gt; tags, only css declarations. Allowed tokens : %s</p>', 'functionality-plugin' ), implode( ', ', $displayed_tokens ) . '.' );
        }

        /**
         * Custom meta box save
         *
         * @since   1.0
         * @access  public
         *
         * @param   int    $post_id  The currently saved post id
         * @return  void
         */
        static public function save_custom_css( $post_id ) {
            $screen = get_current_screen();
            $post_type = $screen->post_type;
            if ( ! in_array( $post_type, self::custom_css_hooks_post_types() ) || ! wp_verify_nonce( $_POST['post_type_custom_css_noncename'], 'post-type-custom-css' ) || defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
                return $post_id;
            $post_type_custom_css = $_POST['post_type_custom_css'];
            update_post_meta( $post_id, 'post_type_custom_css', $post_type_custom_css );
        }

        /**
         * Custom meta box content displayed in single post page template
         *
         * @since   1.0
         * @access  public
         *
         * @global  object  Post object
         *
         * @hook    filter  insert_custom_css_tokens  Tokens used to personalyse classes or ids
         *
         * @return  void
         */
        static public function insert_custom_css() {
            if ( is_singular() ) {
                if ( have_posts() ) : while ( have_posts() ) : the_post();
                    global $post;
                    $tokens = apply_filters( 'insert_custom_css_tokens', array(
                        '%%post_id%%' => get_the_ID(),
                        '%%post_slug%%' => $post->post_name,
                    ));
                    $custom_css = get_post_meta( get_the_ID(), 'post_type_custom_css', true );
                    if ( $custom_css ) {
                        foreach ( $tokens as $token => $value ) {
                            $custom_css = preg_replace( '/' . $token . '/', $value, $custom_css );
                        }
                        echo '<!-- Post custom CSS --><style type="text/css" media="screen">' . preg_replace( "/[\n\r\t ]+/", ' ', $custom_css ) . '</style><!-- End post custom CSS -->' . PHP_EOL;
                    }
                endwhile; endif;
                rewind_posts();
            }
        }

        /**
         * Redirect users to front page after login
         *
         * @since   1.0
         * @access  public
         *
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void
         */
        static public function redirect_after_login() {
            global $redirect_to;
            if ( ! isset( $_GET['redirect_to'] ) ) {
                $redirect_to = get_option( 'siteurl' );
            }
        }

        /**
         * Redirect to referer on login fail
         *
         * @see     https://wordpress.org/support/topic/can-you-stop-wp_login_form-redirecting-to-wp-login-on-fail
         *
         * @since   1.0
         * @access  public
         *
         * @return  void
         */
        static public function front_end_login_fail() {
            // Where did the post submission come from ?
            $referrer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );
            // If it's not the default log-in screen
            if ( ! strstr( $referrer, 'wp-login' ) && ! strstr( $referrer, 'wp-admin' ) ) {
                // Let's append some information (login=failed) to the URL for the theme to use
                //exit( wp_redirect( add_query_arg( 'login', 'failed', $referrer ) ) );
                exit( wp_redirect( $referrer ) );
            }
        }

        /**
         * Prevent access to wp-admin for certain user roles
         *
         * @see     http://wordpress.stackexchange.com/questions/66093/how-to-prevent-access-to-wp-admin-for-certain-user-roles
         *
         * @since   1.0
         * @access  public
         *
         * @return  void
         */
        static public function no_admin_access() {
            // Where did the post submission come from ?
            $referrer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );
            // If there's a valid referrer, and it's not the default log-in screen
            if ( current_user_can( 'subscriber' ) && ! current_user_can( 'administrator' ) ) {
                // Let's append some information (adminaccess=disallow) to the URL for the theme to use
                //exit( wp_redirect( add_query_arg( 'adminaccess', 'disallow', $referrer ) ) );
                exit( wp_redirect( $referrer ) );
            }
        }

        /**
         * Redirect to referer on empty username or password
         *
         * @see     http://wordpress.stackexchange.com/questions/97560/redirect-after-empty-login-username-and-password
         *
         * @since   1.0
         * @access  public
         *
         * @return  void
         */
        static public function catch_empty_user( $username, $pwd ) {
            // Where did the post submission come from ?
            $referrer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );
            // If the username or password is empty, and it's not the default log-in screen
            if ( ( empty( $username ) || empty( $pwd ) ) && ! strstr( $referrer, 'wp-login' ) && ! strstr( $referrer, 'wp-admin' ) ) {
                // Let's append some information (login=failed) to the URL for the theme to use
                //exit( wp_redirect( add_query_arg( 'login', 'failed', $referrer ) ) );
                exit( wp_redirect( $referrer ) );
            }
        }

        /**
         * Redirect some logged-in users from wp-login.php to "redirect_to" page
         *
         * @see     https://wordpress.stackexchange.com/questions/187831/wp-login-php-redirect-logged-in-users-to-custom-url/187874
         *
         * @since   1.0
         * @access  public
         *
         * @return  void
         */
        static public function loggedin_redirect(){
            global $action, $redirect_to;

            if ( 'logout' === $action || ! is_user_logged_in() ) {
                return;
            }

            $redirect_to = current_user_can( 'edit_posts' )
                ? admin_url()
                : isset( $_GET['redirect_to'] )
                    ? $_GET['redirect_to']
                    : home_url();

            wp_safe_redirect( apply_filters(
                'loggedin_redirect',
                $redirect_to,
                wp_get_current_user()
            ), 302 );
            exit;
        }

        /**
         * No french punctuation and accents for filename
         *
         * Remove all french punctuation and accents from the filename of upload for client limitation (Safari Mac/IOS)
         *
         * @see     https://gist.github.com/herewithme/7704370
         * @since   1.0
         * @access  public
         *
         * @param   array  $special_chars  Special characters to be replaced
         * @return  array
         */
        static public function sanitize_file_name_chars( $special_chars = array() ) {
            $special_chars = array_merge( array( '’', '‘', '“', '”', '«', '»', '‹', '›', '—', 'æ', 'œ', '€' ), $special_chars );
            return $special_chars;
        }

        /**
         * Processes the permalink so we can remove any characters that may cause a problem when communicating
         * with the API.
         *
         * @see     https://tommcfarlin.com/remove-special-characters-from-permalinks/
         * @since   1.0
         * @access  public
         *
         * @param  array $data The array of information about the post.
         * @return array $data The data without the malformed information in the post name for the URL.
         */
        static public function process_permalink( $data ) {
            if ( ! in_array( $data['post_status'], array( 'draft', 'pending', 'auto-draft' ) ) ) {
                $data['post_name'] =
                    preg_replace(
                        '/(%ef%b8%8f|™|®|©|&trade;|&reg;|&copy;|&#8482;|&#174;|&#169;)/',
                        '',
                        $data['post_name']
                    );
            }
            return $data;
        }

        /**
         * Allow SVG upload in media library
         *
         * @since   1.0
         * @access  public
         *
         * @param   array  $mimes  Allowed mime types in media library
         * @return  array
         */
        static public function upload_mimes( $mimes ) {
            $mimes['svg'] = 'image/svg+xml';
            return $mimes;
        }

        /**
         * Replace translatable original plugins or theme text with a new translatable string
         *
         * @since   1.0
         * @access  public
         *
         * @param   string  $translated_text  New text
         * @param   string  $text             Text to replace
         * @param   string  $domain           Plugin domain slug
         * @return  void
         */
        static public function replace_translatable_text( $translated_text, $text, $domain ) {
            if ( 'woocommerce' == $domain && 'Related Products' == $text )
                $translated_text = __( 'You may also like', 'functionality-plugin' );

            return $translated_text;
        }

        /**
         * Replace contextualy translatable original plugins or theme text with a new translatable string
         *
         * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/gettext_with_context
         *
         * @since   1.0
         * @access  public
         *
         * @param   string  $translated_text  New text
         * @param   string  $text             Text to replace
         * @param   string  $context          Specific context string
         * @param   string  $domain           Plugin domain slug
         * @return  void
         */
        static public function replace_contextualy_translatable_text( $translated_text, $text, $context, $domain ) {
            if ( 'woocommerce' == $domain && 'Related Products' == $text )
                $translated_text = __( 'You may also like', 'functionality-plugin' );

            return $translated_text;
        }

        /**
         * Insert the figure tag to attached images in posts. Use relative url.
         *
         * @see https://css-tricks.com/moving-to-https-on-wordpress/
         *
         * @since  1.0.0
         * @access public
         * @param string       $html    The image HTML markup to send.
         * @param int          $id      The attachment id.
         * @param string       $caption The image caption.
         * @param string       $title   The image title.
         * @param string       $align   The image alignment.
         * @param string       $url     The image source URL.
         * @param string|array $size    Size of image. Image size or array of width and height values
         *                              (in that order). Default 'medium'.
         * @param string       $alt     The image alternative, or alt, text.
         * @return string return custom output for inserted images in posts
         */
        static public function insert_figure( $html, $id, $caption, $title, $align, $url, $size, $alt ) {
            // Remove protocol
            $img_src = wp_get_attachment_image_src( $id, $size );
            $url = $img_src[0];
            $relativeurl = wp_make_link_relative( $url );
            $html5 = '<figure id="attachment-' . $id . '" class="align-' . $align . ' media-' . $id . '">';
            $html5 .= '<img src="' . $relativeurl . '" alt="' . ( $alt ? $alt : ( $title ? esc_attr( $title ) : ( $caption ? esc_attr( $caption ) : '' ) ) ) . '" />';
            if ( $caption ) {
                $html5 .= '<figcaption class="attachment-description">' . $caption . '</figcaption>';
            }
            $html5 .= '</figure>';
            return $html5;
        }

        /**
         * Internal links as relative shortlinks
         *
         * Achieve actual permanent links when inserting links in WordPress editor by changing the
         * internal links to use shortlinks instead of "prety permalinks".
         *
         * @see https://fr.wordpress.org/plugins/wp-shortlinker/
         *
         * @since   1.0
         * @access  public
         *
         * @param   array   $results  An associative array of query results.
         * @return  array
         */
        static public function relative_shortlinks_as_permalinks( $results ) {
            foreach ( $results as &$result ) {
                // Relative shortlink
                $result['permalink'] =  wp_make_link_relative ( wp_get_shortlink( $result['ID'] ) );
            }

            return $results;
        }

        /**
         * Add classes to the page body tag.
         *
         * @copyright  Copyright (c) 2016, Sébastien Méric
         * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
         * @author     Sébastien Méric <sebastien.meric@gmail.com>
         *
         * @since   1.0
         * @param   array/string  $classes  Original body classes.
         * @return  array/string            Body classes.
         */
        static public function more_body_classes( $classes ) {
            global $wpdb, $post, $wp_query, $current_user;
            $c = array();

            // Start
            $c[] = 'start-sfp-classes';

            // Theme
            $theme = get_option( 'stylesheet' );
            $c[] = 'theme-' . $theme;

            // Language
            $lang = explode( '-', get_bloginfo( 'language' ) );
            $c[] = 'lang-' . $lang[0];

            // Current user
            foreach ( $current_user->roles as $user_role ) {
                $c[] = 'user-role-'. $user_role;
            }

            // Applies the singular class
            if ( is_singular() ){
                $c[] = 'singular';

                // Adds author class for the page author
                if ( function_exists( 'get_author_displayable_name' ) )
                    $c[] = 'author-' . sanitize_title( strtolower( get_author_displayable_name() ? get_author_displayable_name() : 'unknown' ) );
            }

            // Classes for pages
            if ( is_page() ) {
                $c[] = 'page-' . $post->post_name;
                // Top parent pages
                if ( $post->post_parent ){
                    //$ancestors = get_post_ancestors( $post->ID );
                    $ancestors = $post->ancestors;
                    $root      = count( $ancestors ) - 1;
                    $parent    = $ancestors[$root];
                    $slug      = get_post( $parent );
                    $slug      = $slug->post_name;
                }
                else {
                    $parent = $post->ID;
                    $slug   = $post->post_name;
                }
                $c[] = 'top-level-parent-page-id-' . $parent;
                $c[] = 'top-level-parent-page-'    . $slug;

                // Blog specific pages : blog page template
                //if ( is_page_template( 'page-templates/blog.php' ) ) {
                    //$c[] = 'blog-specific';
                //}

                // Page template
                $template = get_page_template();
                if ( $template != null ) {
                    $path = pathinfo( $template );
                    $c[] = 'page-template-' . $path['filename'];
                }
            }

            // Classes for categories
            elseif ( is_category() ) {
                // Add the category id
                $cat_id = intval( get_query_var( 'cat' ) );
                $c[] = 'category-id-' . $cat_id;

                // Add categories hierarchy
                $parent_cat_ids = get_cat_parents_id( $cat_id );
                array_shift( $parent_cat_ids );
                $grand = '';
                foreach ( $parent_cat_ids as $parent_cat_id ) {
                    $c[] = $grand . 'child-of-category-id-' . $parent_cat_id;
                    $grand .= 'grand-';
                    if ( $parent_cat_id == end( $parent_cat_ids ) ) {
                        $c[] = 'top-category-id-' . $parent_cat_id;
                    }
                }

                // Blog specific pages : category page
                //$c[] = 'blog-specific';
            }

            // Single posts...
            elseif ( is_singular() ){
                // Category id in post class
                if ( $cats = get_the_category( $post->ID ) )
                    foreach ( $cats as $category ) {
                        $c[] = 'in-category-id-' . $category->cat_ID;
                        $c[] = 'in-category-'    . $category->slug;
                    }

                // Adds tag classes for each tags on single posts
                if ( $tags = get_the_tags() )
                    foreach ( $tags as $tag ) {
                        $c[] = 'in-tag-id-' . $tag->term_id;
                        $c[] = 'in-tag-'    . $tag->slug;
                    }

                // Blog specific pages : single post page
                //if ( is_single() ) {
                    //$c[] = 'blog-specific';
                //}

                // Adds MIME-specific classes for attachments
                if ( is_attachment() ) {
                    $mime_type = get_post_mime_type();
                    $mime_prefix = array( 'application/', 'image/', 'text/', 'audio/', 'video/', 'music/' );
                    $c[] = 'attachment-id-' . $post->ID . ' attachment-mime-type-' . str_replace( $mime_prefix, '', $mime_type );
                }
            }

            // Taxonomies...
            elseif ( is_tax() ){
                $c[] = 'taxonomy';
                $c[] = 'taxonomy-' . preg_replace( '/_/', '-', sanitize_title( $wp_query->query_vars['taxonomy'] ) );
            }

            // Time archives...
            elseif ( is_date() ){
                // blog specific pages : time archives pages
                //$c[] = 'blog-specific';
            }

            // Tags archive...
            elseif ( is_tag() ){
                // Blog specific pages : tags archive pages
                //$c[] = 'blog-specific';
            }

            // Author archive...
            elseif ( is_author() ){
                // Blog specific pages : author archive pages
                //$c[] = 'blog-specific';
            }

            // Homepage...
            if ( ! is_home() && ! is_front_page() ){
                $c[] = 'not-home';
            }
            elseif ( is_home() && ! is_front_page() ) {
                //$c[] = 'blog-specific';
                $c[] = 'blog-home';
            }
            elseif ( is_front_page() ) {
                $c[] = 'home';
            }

            if ( ! is_user_logged_in() ) {
                $c[] = 'not-logged-in';
            }
            else {
                $c[] = 'logged-in';
            }

            // Debug mode : WP_DEBUG === true.
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                $c[] = 'debug-mode';
            }

            // End
            $c[] = 'end-sfp-classes';

            // body classes are built differently on the front and back end.
            // https://core.trac.wordpress.org/browser/tags/3.8.1/src/wp-admin/admin-header.php#L164
            if ( is_admin() ) {
                $classes .= implode( ' ', $c );
            }
            else {
                $classes = array_unique( array_merge( $classes, $c ) );
            }

            return $classes;
        }

        /**
         * Return logout link
         *
         * @since  1.0
         * @access public
         *
         * Example usage:
         * [wplogout]Custom text[/wplogout]
         * If you don't specify a custom text, will just display "Log Out" :
         * [wplogout][/wplogout] or just [wplogout]
         */
        static public function logout_link_shortcode ( $atts, $content = null ) {
            extract( shortcode_atts( array(
                'before' => '',
                'after' => '',
                'redirect' => get_permalink(),
            ), $atts, 'wplogout' ) );

            if ( ! $content ) {
                $content = __( 'Log Out', 'functionality-plugin' );
            }
            $logoutlink = wp_logout_url( $redirect );
            return $before . '<a href="' . $logoutlink . '" title="' . esc_attr__( 'Click this link to logout', 'functionality-plugin' ) . '">' . $content . '</a>' . $after;
        }

        /**
         * Return lost password link
         *
         * @since  1.0
         * @access public
         *
         * Example usage:
         * [wplostpassword]Custom text[/wplostpassword]
         * If you don't specify a custom text, will just display "Lost Password ?" :
         * [wplostpassword][/wplostpassword] or just [wplostpassword]
         */
        static public function lost_password_link_shortcode ( $atts, $content = null ) {
            extract( shortcode_atts( array(
                'before' => '',
                'after' => '',
            ), $atts, 'wplostpassword' ) );

            if ( ! $content ) {
                $content = __( 'Lost Password&nbsp;?', 'functionality-plugin' );
            }
            $lostpassword = wp_lostpassword_url( get_permalink() );
            return $before . '<a href="' . $lostpassword . '" title="' . esc_attr__( 'Click this link if you need to get a new password', 'functionality-plugin' ) . '">' . $content . '</a>' . $after;
        }

        /**
         * Return a login form
         *
         * @since  1.0
         * @access public
         *
         * Example usage:
         * [wploginform form_id="login_form_1"]
         */
        static public function login_form_shortcode ( $atts, $content = null ) {
            // Set up some defaults.
            $defaults = array(
                'log_out_if_logged_in' => 1,
                'remember'             => 1,
                'redirect'             => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
                'form_id'              => 'loginform',
                'id_username'          => 'user_login',
                'id_password'          => 'user_pass',
                'id_remember'          => 'rememberme',
                'id_submit'            => 'wp-submit',
                'label_username'       => __( 'Username', 'functionality-plugin' ),
                'label_password'       => __( 'Password', 'functionality-plugin' ),
                'label_remember'       => __( 'Remember me', 'functionality-plugin' ),
                'label_log_in'         => __( 'Log in', 'functionality-plugin' ),
                'log_out_message'      => __( 'Log out', 'functionality-plugin' ),
                'log_in_message'       => __( 'You are logged in.', 'functionality-plugin' ),
                'value_username'       => '',
                'value_remember'       => 0,
                'hello'                => 1,
                'create_account_link'  => '',
            );

            // Merge the user input arguments with the defaults.
            $atts = shortcode_atts( $defaults, $atts, 'wploginform' );

            // Set boolean values.
            $atts['log_out_if_logged_in'] = intval( $atts['log_out_if_logged_in'] );
            $atts['remember']             = intval( $atts['remember'] );
            $atts['value_remember']       = intval( $atts['value_remember'] );

            // Set 'echo' to 'false' because we want it to always return instead of print for shortcodes.
            $atts['echo']                 = false;

            // If user alteady logged-in, do not show the form.
            if ( is_user_logged_in() ) {
                $current_user = wp_get_current_user();
                $display_name = $current_user->display_name;
                if ( $atts['hello'] ) {
                    $hello_message = '<p class="logged-in-container">' . sprintf( __( 'You are logged in as <a href="%1$s">%2$s</a>.', 'functionality-plugin' ), get_edit_user_link(), $display_name ) . '</p>';
                }
                else {
                    $hello_message = '<p class="logged-in-container">' . $atts['log_in_message'] . '</p>';
                }
                if ( $atts['log_out_if_logged_in'] ) {
                    $hello_message = $hello_message . '<p class="logout-container"><a href="' . wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) ) . '" title="' . esc_attr__( 'Click this link to logout of this account', 'functionality-plugin' ) . '" class="logout">' . $atts['log_out_message'] . '</a></p>';
                }
                return $hello_message;
            }

            $create_account_link = '';
            if ( '' !== $atts['create_account_link'] ) {
                $create_account_link = '<p class="register-container"><a href="' . esc_url( $atts['create_account_link'] ) . '">' . __( 'Register', 'functionality-plugin' ) . '</a></p>';
            }

            return wp_login_form( $atts ) . $create_account_link;
        }

        /**
         * Displays content if the user viewing it is currently logged in. This also blocks content
         * from showing in feeds.
         *
         * @since  0.1.0
         * @access public
         *
         * Example usage:
         * [wplogged_in]you see me only if you are logged-in ![/wplogged_in]
         *
         * @param  array   $attr
         * @param  string  $content
         * @return string
         */
        static public function logged_in_shortcode( $attr, $content = null ) {

            return is_feed() || ! is_user_logged_in() || is_null( $content ) ? '' : do_shortcode( $content );
        }

        /**
         * Displays content if the user viewing it is not currently logged in.
         *
         * @since  0.1.0
         * @access public
         *
         * Example usage:
         * [wplogged_out]you see me only if you are logged-out ![/wplogged_out]
         *
         * @param  array   $attr
         * @param  string  $content
         * @return string
         */
        static public function logged_out_shortcode( $attr, $content = null ) {

            return is_user_logged_in() ? '' : do_shortcode( $content );
        }

        /**
         * function to registration Shortcode
         *
         * @since  1.0
         * @access public
         */
        static public function registration_form_shortcode( $atts, $content = null ) {
            extract( shortcode_atts( array(
                'redirect_on_success' => '',
                'redirect_instead' => get_option( 'home' ),
            ), $atts, 'wpregistrationform' ) );

            $firstname = '';
            $lastname  = '';
            $username  = '';
            $email     = '';
            $succress  = '';
            $error_msg = '';
            $errors = array();
            $successes = array();

            // if looged in or robot
            if ( is_user_logged_in() || isset( $_POST['sm'] ) && ( isset( $_POST['email'] ) && '' != sanitize_text_field( $_POST['email'] ) || ! isset( $_POST['hm'] ) ) ) {
                $output = '<p class="warning">' . __( 'If you are not already logged-in, there was a problem with your registration.', 'functionality-plugin' ) . '</p>';
                // redirect to home page
                if ( $redirect_instead ) {
                    $output .= '<p class="warning">' . sprintf( __( 'You will be redirected in a few seconds. If you are not, please <a href="%s">follow this link</a>.', 'functionality-plugin' ), $redirect_instead ) . '</p><script>self.location.href = \'' . $redirect_instead . '\';</script>';
                }
            }
            elseif ( isset( $_POST['sm'] ) && '' != sanitize_text_field( $_POST['sm'] ) ) {
                $firstname = sanitize_text_field( $_REQUEST['fn'] );
                $lastname  = sanitize_text_field( $_REQUEST['ln'] );
                $username  = sanitize_text_field( $_REQUEST['un'] );
                $email     = sanitize_text_field( $_REQUEST['em'] );
                $password  = esc_sql( sanitize_text_field( $_REQUEST['pw1'] ) );
                $password2 = esc_sql( sanitize_text_field( $_REQUEST['pw2'] ) );

                // nonce did not verify
                if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'registration_form_shortcode' ) ) {
                    $errors[] = __( 'Maybe you should try again...', 'functionality-plugin' );
                }
                else {
                    // empty field(s)
                    if ( ! $firstname || ! $lastname || ! $username || ! $email || ! $password || ! $password2 ) {
                        $errors[] = __( 'All fields are mandatory.', 'functionality-plugin' );
                    }
                    // invalid email
                    if ( ! is_email( $email ) ) {
                        $errors[] = __( 'The email you entered is not valid.', 'functionality-plugin' );
                    }
                    // passwords don't match
                    if ( $password !== $password2 ) {
                        $errors[] = __( 'Passwords don\'t match.', 'functionality-plugin' ); 
                    }
                    if ( empty( $errors ) ) {
                        // register user
                        $user_id = wp_create_user( $username, $password, $email );

                        // invalid user
                        if ( is_wp_error( $user_id ) ) {
                            $errors[] = __( 'Username and/or e-mail already registered. Please try another one.', 'functionality-plugin' ); 
                        } 
                        // success !
                        else {
                            update_user_meta( $user_id, 'first_name', $firstname );
                            update_user_meta( $user_id, 'last_name', $lastname );

                            $successes[] = __( 'Congratulations, your are register successfully for this site.', 'functionality-plugin' );
                            // redirect on success
                            if ( $redirect_on_success ) {
                                $successes[] = sprintf( __( 'You will be redirected in a few seconds. If you are not, please <a href="%s">follow this link</a>.', 'functionality-plugin' ), $redirect_on_success ) . '<script>self.location.href = \'' . $redirect_on_success . '\';</script>';
                            }
                        }
                    }
                }

                ob_start();
                ?>
                <div class="registration-form">
                    <div class="registration-heading">
                        <?php _e( 'Registration Form', 'functionality-plugin' ) ?>
                    </div>
                    <div class="registration-messages">
                        <?php
                        // error message(s)
                        if ( count( $errors ) > 1 ) {
                            echo '<ol class="error"><li>' . implode( '</li>' . PHP_EOL . '<li>', $errors ) . '</li></ol>';
                        }
                        elseif ( count( $errors ) === 1 ) {
                            echo '<p class="error">' . implode( '<br />', $errors ) . '</p>';
                        }
                        // success message(s)
                        elseif ( count( $successes ) > 1 ) {
                            echo '<ol class="success"><li>' . implode( '</li>' . PHP_EOL . '<li>', $successes ) . '</li></ol>';
                        }
                        elseif ( count( $successes ) === 1 ) {
                            echo '<p class="success">' . implode( '<br />', $successes ) . '</p>';
                        }
                       ?>
                    </div>
                    <form  name="form" id="registration"  method="post">
                        <div class="ftxt">
                            <label><?php _e( 'First Name :', 'functionality-plugin' ) ?></label> 
                            <input id="fn" name="fn" type="text" class="input" required value=<?php echo $firstname ?> > 
                        </div>
                        <div class="ftxt">
                            <label><?php _e( 'Last name :', 'functionality-plugin' ) ?></label>  
                            <input id="ln" name="ln" type="text" class="input" required value=<?php echo $lastname ?> >
                        </div>
                        <div class="ftxt">
                            <label><?php _e( 'Username :', 'functionality-plugin' ) ?></label> 
                            <input id="un" name="un" type="text" class="input" required value=<?php echo $username ?> >
                        </div>
                        <div class="ftxt">
                            <label><?php _e( 'E-mail :', 'functionality-plugin' ) ?> </label>
                            <input id="em" name="em" type="email" class="input" required value=<?php echo $email ?> >
                        </div>
                        <div class="ftxt" style="display: none;">
                            <label><?php _e( 'Confirm e-mail : ', 'functionality-plugin' ) ?></label>
                            <input id="email" name="email" type="email" value="" class="input" /> <span class="description"><?php _e( '(Do not fill this field if you are human)', 'functionality-plugin' ) ?></span>
                        </div>
                        <div class="ftxt">
                            <label><?php _e( 'Password :', 'functionality-plugin' ) ?></label>
                            <input id="pw1" name="pw1" type="password" required class="input" />
                        </div>
                        <div class="ftxt">
                            <label><?php _e( 'Confirm Password : ', 'functionality-plugin' ) ?></label>
                            <input id="pw2" name="pw2" type="password" class="input" />
                        </div>
                        <div class="fchk">
                            <label><?php _e( 'I am a human : ', 'functionality-plugin' ) ?></label>
                            <input id="hm" name="hm" type="checkbox" class="input" /> <span class="description"><?php _e( '(Check this box if you are not a robot)', 'functionality-plugin' ) ?></span>
                        </div>
                        <div class="fbtn"><input type="submit" name="sm" class="button" value="<?php _e( 'Register', 'functionality-plugin' ) ?>"/><?php wp_nonce_field( 'registration_form_shortcode', 'nonce' ) ?></div>
                    </form>
                </div>
                <?php
                $output = ob_get_contents();
                ob_end_clean();
            }

            return $output;
        }

        /**
         * Add a custom dashboard widget for credits !
         *
         * @since  1.0
         * @access public
         */
        static public function add_dashboard_widgets() {
            wp_add_dashboard_widget( 'dashboard_widget', __( 'Credits', 'functionality-plugin' ), array( __class__, 'textfor_dashboard_widget' ) );
        }

        static public function textfor_dashboard_widget( $post, $callback_args ) {
            $team_members = array();
            $team_members[] = array( 'job1', 'name1', 'website1', 'email1', 'phone1' );
            $team_members[] = array( 'job2', 'name2', 'website2', 'email2', 'phone2' );

            _e( '<p>We made this website. If you need anything, please contact us :</p>', 'functionality-plugin' );
            echo '<dl>';
            foreach ( $team_members as $team_member ) {
                echo '<dt>' . $team_member[0] . '</dt><dd><a href="' . $team_member[2] . '" target="_blank">' . $team_member[1] . '</a> &lt;<a href="mailto:' . $team_member[3] . '" target="_blank">' . $team_member[3] . '</a>&gt;<br />';
                printf( __( 'Phone : %s', 'functionality-plugin' ), $team_member[4] );
                echo '</dd>';
            }
            echo '</dl>';
        }

        /**
         * Essentially, this snippet tells WordPress to add the defer attribute to all
         * your JavaScript files except jQuery.
         *
         * @see     https://kinsta.com/blog/defer-parsing-of-javascript/
         *
         * @since   1.0
         * @access  public
         *
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void
         */
        static public function defer_parsing_of_js ( $url ) {
            // pas de defer dans l'admin
            if ( is_admin() ) return $url;
            // seulement pour les fichiers js
            if ( FALSE === strpos( $url, '.js' ) ) return $url;
            // on zap jquery.js car trop d'autres js en dépendent
            if ( strpos( $url, 'jquery.js' ) ) return $url;

            // type de guillemets (sait-on jamais...)
            //$apos = ( 0 === strpos( $url, '\"' ) ) ? '"' : "'";
            //return "$url$apos defer=".$apos."defer";

            return str_replace( ' src', ' defer src', $url );
        }

        /**
         * Send menu items link to target _top by default
         *
         * @see https://core.trac.wordpress.org/ticket/14652
         *
         * @since   1.0
         * @access  public
         *
         * @param   object  $menu_item  Menu item
         * @return  object  $menu_item
         */
        static public function filter_menu_target_top( $menu_item ) {
            if ( ! is_admin() && empty( $menu_item->target ) )
                $menu_item->target = '_top';
            return $menu_item;
        }


        /**
         * Create a dynamic external js file
         *
         * This external js file must be used to include every js snippets generated
         * by the SFP plugin
         *
         * @since   1.0
         * @access  public
         *
         * @return  void
         */
        static public function dynamic_external_js() {
            global $wp_query;
            if ( ! empty( $wp_query->query_vars['dynamic_external_js'] ) ) {
                header( 'Content-Type: text/javascript;charset=UTF-8' );
                ?>
(function($){
    'use strict';
    $(function(){
        console.log('SFP: Dynamic external js file loaded !');

        // Mise en cache de variables utiles :
        var $w=$(window),$d=$(document),$html=$('html'),$body=$('body');

<?php do_action( 'sfp_dynamic_external_js_snippet' ) ?>

    });

})(jQuery);<?php
                echo PHP_EOL;
                exit;
            }
            return;
        }

        /**
         * Register, enqueue and localize the dynamicly created external js file
         *
         * @since   1.0
         * @access  public
         *
         * @return  void
         */
        static public function dynamic_external_js_enqueue() {
            $js_url = get_option( 'permalink_structure' ) ? 'wp-content/plugins/functionality-plugin/assets/public/js/dynamic-scripts.js' : '?dynamic_external_js=1';

            wp_register_script(
                'sfp_dynamic_external_js',
                get_bloginfo( 'url' ) . '/' . $js_url,
                array( 'jquery' ),
                '',
                false
            );
            wp_enqueue_script( 'sfp_dynamic_external_js' );

            wp_localize_script( 'sfp_dynamic_external_js', 'sfp_dynamic_external_js', 
                array( 
                    'cpn' => get_current_post_type_name(),
                )
            );
        }

        /**
         * Filters the query variables whitelist before processing.
         *
         * Allow custom rewrite rules to allow access to external js file.
         *
         * @since   1.0
         * @access  public
         *
         * @param   array  $vars  Registered query vars
         * @return  array  $vars
         */
        static public function dynamic_external_js_query_vars( $vars ) {
            $vars[] = 'dynamic_external_js';
            return $vars;
        }

        /**
         * Filters the canonical redirect URL for external js file.
         *
         * @since   1.0
         * @access  public
         *
         * @param   string  $redirect_url   The redirect URL
         * @param   string  $requested_url  The requested URL
         * @return  mixed
         */
        static public function dynamic_external_js_canonical( $redirect_url, $requested_url ) {
            if ( substr( $requested_url, -76 ) == 'wp-content/plugins/functionality-plugin/assets/public/js/dynamic-scripts.js' )
                return false;
            return $redirect_url;
        }


        /**
         * External links
         *
         * Add target="_blank" and rel="external nofollow noopener noreferrer"
         * to external links via javascript
         *
         * @since   1.0
         * @access  public
         *
         * @param   int     $int     Integer
         * @param   string  $string  String
         * @param   array   $array   Array
         * @return  void
         */
        static public function external_links_extras() {
            ?>

        /**
         * Keep link target if already specified.
         * Add target="_blank" and rel="external nofollow noopener noreferrer"
         * to external links.
         * Add  target="_self" to other links.
         */
        $('a[href^="http"]').attr({
            'target':function(){
                if(this.target){
                    return this.target;
                }
                else{
                    return (this.hostname && this.hostname !== location.hostname)?'_blank':'_self';
                }
            },
            'rel':function(){
                if(this.hostname && this.hostname !== location.hostname){
                    var rel=this.rel;
                    if(!rel){
                        return 'external nofollow noopener noreferrer';
                    }
                    if(rel.indexOf('external')<0){
                        rel += ' external';
                    }
                    if(rel.indexOf('nofollow')<0){
                        rel += ' nofollow';
                    }
                    if(rel.indexOf('noopener')<0){
                        rel += ' noopener';
                    }
                    if(rel.indexOf('noreferrer')<0){
                        rel += ' noreferrer';
                    }
                    return rel;
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
         *
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


/**
 * Create rewrite rule for dynamic external js file
 *
 * @since  1.0
 * @return void
 */
add_action( 'init', function( ) {
    global $wp_rewrite;
    add_rewrite_rule( 'wp-content/plugins/functionality-plugin/assets/public/js/dynamic-scripts\.js$', $wp_rewrite->index . '?dynamic_external_js=1', 'top' );
} );

