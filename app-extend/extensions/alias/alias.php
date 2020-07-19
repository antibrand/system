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
