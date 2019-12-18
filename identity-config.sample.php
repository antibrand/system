<?php
/**
 * Identity configuration
 *
 * To define your own identity for the website management system
 * copy or rename this file as `identity-config.php` and include
 * in the root directory.
 *
 * Support is included for `identity-config.php` to be included
 * one directory above the website management system root.
 *
 * @see `wp-includes/default-constants.php` for fallback definitions.
 *
 * @package WMS
 */

// Define a name of the website management system.
if ( ! defined( 'APP_NAME' ) ) {
	define( 'APP_NAME', 'Website Management System' );
}

// Define a tagline of the website management system.
if ( ! defined( 'APP_TAGLINE' ) ) {
	define( 'APP_TAGLINE', 'Generic, white-label website and content management' );
}

// Define a URL for the website management system.
if ( ! defined( 'APP_WEBSITE' ) ) {
	define( 'APP_WEBSITE', 'https://antibrand.dev/' );
}

// Define a logo path for the website management system.
if ( ! defined( 'APP_LOGO' ) ) {
	define( 'APP_LOGO', dirname( dirname( $_SERVER['PHP_SELF'] ) ) . '/app-assets/images/app-logo.jpg' );
}