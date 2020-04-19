<?php
/**
 * Redirects to app-activate.php.
 *
 * Added in case needed by a external multisite method or plugin.
 * Remove if not needed.
 *
 * @package App_Package
 */

define( 'WP_INSTALLING', true );

// Set up the application environment.
require( dirname( __FILE__ ) . '/app-load.php' );

// Redirect to app-activate.php
wp_safe_redirect( ABSPATH . 'app-activate.php' );