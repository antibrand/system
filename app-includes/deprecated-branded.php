<?php
/**
 * Deprecated functions from WordPress version 4.9.8 that have been
 * renamed because of the WordPress-branded prefix.
 *
 * These functions may be removed. Remove from your system if desired.
 * @see app-settings.php
 *
 * @package App_Package
 * @subpackage Deprecated
 */

// Redirect to the installer.
if ( ! function_exists( 'wp_not_installed' ) ) {
	function wp_not_installed() {
		return app_not_installed();
	}
}

// function wp_admin_css() {}
// function wp_admin_css_uri() {}

if ( ! function_exists( 'wp_lostpassword_url' ) ) {
	function wp_lostpassword_url() {
		return app_lostpassword_url();
	}
}

if ( ! function_exists( 'is_multisite' ) ) {
	function is_multisite() {
		return is_network();
	}
}
