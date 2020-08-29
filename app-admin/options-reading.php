<?php
/**
 * Reading settings
 *
 * Deprecated file. Redirects to new settings page.
 *
 * @package App_Package
 * @subpackage Administration
 */

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

// Load the administration environment.
require_once( APP_INC_PATH . '/backend/app-admin.php' );

if ( ! is_network() ) {
	wp_redirect( admin_url( 'options-content.php#tab-display' ), 301 );
	exit;
}