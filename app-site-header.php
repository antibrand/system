<?php
/**
 * Site header
 *
 * Loads the application environment and template.
 *
 * @package App_Package
 * @since 1.0.0
 */

// Get the default system constants.
require_once( dirname( __FILE__ ) . '/app-constants.php' );

if ( ! isset( $wp_did_header ) ) {

	$wp_did_header = true;

	// Load the application library.
	require_once( dirname( __FILE__ ) . '/app-load.php' );

	// Set up the query.
	app();

	// Load the theme template.
	require_once( ABSPATH . APP_INC . '/template-loader.php' );

}