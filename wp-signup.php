<?php
/**
 * Redirects to app-signup.php.
 *
 * Added in case needed by a external multisite method or plugin.
 * Remove if not needed.
 *
 * @package App_Package
 */

// Set up the application environment.
require( dirname( __FILE__ ) . '/app-load.php' );

// Redirect to app-signup.php
wp_safe_redirect( ABSPATH . 'app-signup.php' );