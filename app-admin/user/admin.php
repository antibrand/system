<?php
/**
 * User Administration Bootstrap
 *
 * @package App_Package
 * @subpackage Administration
 * @since Previous 3.1.0
 */

define( 'WP_USER_ADMIN', true );

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

// Load the administration environment.
require_once( APP_INC_PATH . '/backend/app-admin.php' );

if ( ! is_network() ) {
	wp_redirect( admin_url() );
	exit;
}

$redirect_user_admin_request = ( ( $current_blog->domain != $current_site->domain ) || ( $current_blog->path != $current_site->path ) );

/**
 * Filters whether to redirect the request to the user admin in network.
 *
 * @since Previous 3.2.0
 * @param bool $redirect_user_admin_request Whether the request should be redirected.
 */
$redirect_user_admin_request = apply_filters( 'redirect_user_admin_request', $redirect_user_admin_request );

if ( $redirect_user_admin_request ) {
	wp_redirect( user_admin_url() );
	exit;
}

unset( $redirect_user_admin_request );