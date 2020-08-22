<?php
/**
 * Aliased actions that may have been renamed because of
 * a branded prefix.
 *
 * @package Alias
 * @subpackage App_Package
 */

// Dashboard widgets.
add_action( 'app_dashboard_setup', function() {
	echo do_action( 'wp_dashboard_setup' );
} );