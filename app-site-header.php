<?php
/**
 * Site header
 *
 * Loads the application environment and template.
 *
 * @package App_Package
 * @since 1.0.0
 */

// Get the system environment constants.
require_once( dirname( __FILE__ ) . '/app-environment.php' );

if ( ! isset( $wp_did_header ) ) {

	$wp_did_header = true;

	// Load the application library.
	require_once( dirname( __FILE__ ) . '/app-load.php' );

	// Set up the query.
	app();

	// Load the theme template.
	require_once( APP_INC_PATH . '/template-loader.php' );

}