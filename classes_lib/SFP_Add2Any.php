<?php
/**
 * Add to Any
 * 
 * https://fr.wordpress.org/plugins/add-to-any/
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
if ( ! class_exists( 'SFP_Add2Any' ) ) {

    class SFP_Add2Any {

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
            // Should share buttons appear on those pages ?
            //add_filter( 'addtoany_sharing_disabled', array( __class__, 'sharing_disabled' ) );


            /**
             * Shortcodes
             * add_shortcode( 'shortcode_name', array( __class__, 'function_name' ) );
             */
            // Display post meta with add2any sharing button
            //add_shortcode( 'entry_meta_add2any', array( __class__, 'entry_meta_shortcode' ) );
        }

        static public function test(){echo "<!-- SFP_Add2Any test() function loaded ! -->" . PHP_EOL;}

        /**
         * Do not print sharing buttons on pages in these cases.
         *
         * @since   1.0
         * @access  public
         * @param   boolean  $sharing_disabled  Should share buttons appear on that page ?
         * @return  boolean
         */
        static public function sharing_disabled( $sharing_disabled = false ) {
            if (
                is_page( get_option( 'page_for_posts' ) )
                || is_page( get_option( 'page_on_front' ) )
                /*|| is_page_template( 'page-templates/home-page.php' )
                || is_page_template( 'page-templates/news.php' ) ||
                is_page_template( 'page-templates/with-subpages-content.php' )*/
            ) {
                $sharing_disabled = true;
            }

            return $sharing_disabled;
        }

		/**
		 * Display post meta
		 *
		 * @see     http://stackoverflow.com/questions/17747629/how-to-list-categories-by-shortcode-in-wordpress
		 *
		 * @since   1.0
		 * @access  public
		 */
		static public function entry_meta_shortcode( $atts, $content = null ) {
			extract( shortcode_atts( array(
				'before' => '<div class="entry-meta section-meta">',
				'after'  => '</div>',
				'date'   => 1,
				'cats'   => 1,
			), $atts, 'entry_meta_add2any' ) );

			ob_start();
			
			$published = '';
			if ( $date ) {
				$published .= sprintf( __( 'Published on %1$s at %2$s', 'functionality-plugin' ), get_the_date( __( 'Y/m/d', 'functionality-plugin' ) ), get_the_time( __( 'G:i', 'functionality-plugin' ) ) );
			}
			if ( $cats ) {
				$categories = get_the_category_list( ', ', '', get_the_ID() );
				if ( $categories ) {
					$published .= $date ? '<br />' . sprintf( __( 'in %s', 'functionality-plugin' ), $categories ) : sprintf( __( 'Published in %s', 'functionality-plugin' ), $categories );
				}
			}
			if ( $published ) {
?>
				<p class="entry-published"><?php echo $published ?></p>
<?php
			}

			if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) {
?>
				<div class="entry-share at-above-post addthis-toolbox addthis-toolbox-qualikine"><?php ADDTOANY_SHARE_SAVE_KIT( array( 'linkname' => get_the_title(), 'linkurl' => wp_get_shortlink() ) ) ?></div>
<?php
			}

			$temp_content = ob_get_contents();
			ob_end_clean();

			return $before . $temp_content . $after;
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
