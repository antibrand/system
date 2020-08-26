<?php
/**
 * Install plugin network administration panel.
 *
 * @package App_Package
 * @subpackage Network
 * @since 3.1.0
 */

if ( isset( $_GET['tab'] ) && ( 'plugin-information' == $_GET['tab'] ) )
	define( 'IFRAME_REQUEST', true );

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

require( APP_ADMIN_PATH . '/plugin-install.php' );
