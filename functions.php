<?php
/**
 * Template functions
 *
 * @package    SFP
 * @copyright  Copyright (c) 2016, Sébastien Méric
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since      1.0
 * @author     Sébastien Méric <sebastien.meric@gmail.com>
 */

/**
 * Get taxonomies of a post type
 */
if ( ! function_exists( 'get_post_type_taxonomies' ) ) {
    function get_post_type_taxonomies( $post_type = 'post' ) {
        $taxonomies = get_object_taxonomies( $post_type, 'objects' );
        return ( array ) $taxonomies; // returning array of taxonomies
    }
}

/**
 * Get post object by slug
 */
if ( ! function_exists( 'get_post_by_slug' ) ) {
    function get_post_by_slug( $slug , $post_type = 'post' ) {
        $posts = get_posts( array(
            'name' => $slug,
            'posts_per_page' => 1,
            'post_type' => $post_type,
            'post_status' => 'publish'
        ));
        if ( empty( $posts ) ) {
            return null;
        }
        return $posts[0];
    }
}

/**
 * Obtain the path to the admin directory.
 *
 * @see https://gist.github.com/tazziedave/72e03cecd0cd756785e0f28f652f7d8c
 *
 * @return string
 */
if ( ! function_exists( 'get_admin_path' ) ) {
    function get_admin_path() {
        // Replace the site base URL with the absolute path to its installation directory. 
        $blogUrl = preg_replace( "(^https?://)", "", trailingslashit( get_bloginfo( 'url' ) ) );
        $adminUrl = preg_replace( "(^https?://)", "", trailingslashit( get_admin_url() ) );
        $admin_path = str_replace( '/', DIRECTORY_SEPARATOR, str_replace( $blogUrl, ABSPATH,  $adminUrl ) );

        // Make it filterable, so other plugins can hook into it.
        $admin_path = apply_filters( 'get_admin_path', $admin_path );

        return $admin_path;
    }
}

/**
 * Retrieve a category parents ids.
 *
 * @copyright Copyright (c) 2016, Sébastien Méric
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author    Sébastien Méric <sebastien.meric@gmail.com>
 *
 * @since  1.0
 * @param  int   $cat_id  A category id.
 * @param  array $cats_id Some ids in parent chain if some already found.
 * @return array          Parent categories.
 */
if ( ! function_exists( 'get_cat_parents_id' ) ) {
    function get_cat_parents_id( $cat_id = -1, $cats_id = array() ) {
        $cat_id = intval( $cat_id );
		// get_current_cat_id function should be included
        $current_cat_id = ( $cat_id <= -1 )
			? ( function_exists( 'get_current_cat_id' )
				? get_current_cat_id()
				: 0 )
			: $cat_id;
        if ( ! $current_cat_id )
            return $cats_id;
        $get_cat = get_category( $current_cat_id );
        $get_cat = $get_cat->category_parent;
        if ( $get_cat == 0 ) {
            $cats_id[] = $current_cat_id;
            return $cats_id;
        }
        else {
            $cats_id[] = $current_cat_id;
            return get_cat_parents_id( $cats_id, intval( $get_cat ) );
        }
    }
}


/**
 * Modify the list of allowed tags in posts and comments.
 *
 * @see https://wordpress.stackexchange.com/questions/17089/what-are-allowedposttags-and-allowedtags
 *
 * @since  1.0
 * @return void
 */
if ( ! function_exists( 'expand_allowed_tags' ) ) {
    add_action( 'init', 'expand_allowed_tags' );
    function expand_allowed_tags() {
        global $allowedposttags;
        $allowedposttags['style'] = array(
            'type' => array(),
        );

        //global $allowedtags;
        //$allowedtags["span"] = array( "style" => array() );
    }
}


/**
 * Get the current post type singular name.
 *
 * @see https://wordpress.stackexchange.com/questions/169504/how-to-get-current-get-post-types-name
 *
 * @since  1.0
 * @return string Post type name
 */
if ( ! function_exists( 'get_current_post_type_name' ) ) {
    function get_current_post_type_name() {
        if ( ! is_singular() ) {
            return '';
        }
        $post = get_queried_object();
        $post_type = get_post_type_object( get_post_type( $post ) );
        if ( $post_type ) {
            return esc_html( $post_type->labels->singular_name );
        }
        else {
            return '';
        }
    }
}
