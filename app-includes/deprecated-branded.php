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

/**
 * Get constants
 */
if ( ! function_exists( 'wp_initial_constants' ) ) {
	function wp_initial_constants() {
		return app_initial_constants();
	}
}
if ( ! function_exists( 'wp_plugin_directory_constants' ) ) {
	function wp_plugin_directory_constants() {
		return app_plugin_directory_constants();
	}
}
if ( ! function_exists( 'wp_cookie_constants' ) ) {
	function wp_cookie_constants() {
		return app_cookie_constants();
	}
}
if ( ! function_exists( 'wp_ssl_constants' ) ) {
	function wp_ssl_constants() {
		return app_ssl_constants();
	}
}
if ( ! function_exists( 'wp_functionality_constants' ) ) {
	function wp_functionality_constants() {
		return app_functionality_constants();
	}
}
if ( ! function_exists( 'wp_templating_constants' ) ) {
	function wp_templating_constants() {
		return app_templating_constants();
	}
}

if ( ! function_exists( 'wp_convert_hr_to_bytes' ) ) {
	function wp_convert_hr_to_bytes() {
		return app_convert_hr_to_bytes();
	}
}
