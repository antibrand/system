<?php
/**
 * Template functions
 *
 * @package    WebsiteApp
 * @subpackage UB_Theme
 * @since      1.0.0
 */

// Namespace specificity for theme functions & filters.
namespace UB_Theme\Includes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Body classes
 *
 * Adds custom classes to the array of body classes.
 *
 * @since  1.0.0
 * @access public
 * @param  array $classes Classes for the body element.
 * @return array Returns the array of body classes.
 */
function body_classes( $classes ) {

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;

}
add_filter( 'body_class', 'UB_Theme\Includes\body_classes' );

/**
 * Pingback header
 *
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 *
 * @since  1.0.0
 * @access public
 * @return string Returns the link element in '<head>`.
 */
function pingback_header() {

	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}

}
add_action( 'wp_head', 'UB_Theme\Includes\pingback_header' );