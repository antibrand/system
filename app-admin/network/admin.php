<?php
/**
 * Network Administration Bootstrap
 *
 * @package App_Package
 * @subpackage Network
 * @since 3.1.0
 */

define( 'APP_NETWORK_ADMIN', true );

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

// Load the administration environment.
require_once( APP_INC_PATH . '/backend/app-admin.php' );

// Do not remove this check. It is required by individual network admin pages.
if ( ! is_network() ) {
	wp_die( __( 'Network support is not enabled.' ) );
}

$redirect_network_admin_request = 0 !== strcasecmp( $current_blog->domain, $current_site->domain ) || 0 !== strcasecmp( $current_blog->path, $current_site->path );

/**
 * Filters whether to redirect the request to the Network Admin.
 *
 * @since 3.2.0
 *
 * @param bool $redirect_network_admin_request Whether the request should be redirected.
 */
$redirect_network_admin_request = apply_filters( 'redirect_network_admin_request', $redirect_network_admin_request );
if ( $redirect_network_admin_request ) {
	wp_redirect( network_admin_url() );
	exit;
}
unset( $redirect_network_admin_request );
