<?php
/**
 * Writing settings
 *
 * Deprecated file. Redirects to new settings page.
 *
 * @package App_Package
 * @subpackage Administration
 */

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! is_network() ) {
	wp_redirect( admin_url( 'options-content.php#tab-publish' ), 301 );
	exit;
}