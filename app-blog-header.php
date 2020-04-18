<?php
/**
 * Loads the application environment and template.
 *
 * @package App_Package
 */

if ( ! isset( $wp_did_header ) ) {

	$wp_did_header = true;

	// Load the application library.
	require_once( dirname( __FILE__ ) . '/app-load.php' );

	// Set up the query.
	app();

	// Load the theme template.
	require_once( ABSPATH . WPINC . '/template-loader.php' );

}