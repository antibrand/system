<?php
/**
 * Alias
 *
 * Creates aliases for PHP classes, methods, and functions
 * that have been moved or may be required for compatibility
 * with plugins & themes written for other systems.
 *
 * @package Alias
 * @version 1.0.0
 *
 * Plugin Name: alias
 * Description: Deprecated PHP constants, classes, methods, and functions.
 */

// Stop here if the system is not loaded.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Alias classes
 *
 * @since 1.0.0
 */
require( 'classes.php' );

/**
 * Alias constants
 *
 * @since 1.0.0
 */
require( 'constants.php' );

if ( ! function_exists( 'app_alias_styles' ) ) :

	function app_alias_styles() {
		wp_enqueue_style( 'app-alias', plugins_url( '/assets/css/styles.min.css', __FILE__ ), [], null, 'all' );
	}

	add_action( 'admin_enqueue_scripts', 'app_alias_styles' );
	add_action( 'wp_enqueue_scripts', 'app_alias_styles' );

endif;
