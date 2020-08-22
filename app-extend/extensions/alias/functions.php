<?php
/**
 * Aliased functions that may have been renamed because of
 * a branded prefix or may have been moved to a new class,
 * or both.
 *
 * @package Alias
 * @subpackage App_Package
 */

// Alias namespaces.
use \AppNamespace\Includes as Includes;
use \AppNamespace\Backend as Backend;

/**
 * Dashboard widgets
 *
 * @since  1.0.0
 * @return Backend\Dashboard\add_dashboard_widget().
 */
if ( method_exists( '\AppNamespace\Backend\Dashboard', 'add_dashboard_widget' ) && ! function_exists( 'wp_add_dashboard_widget' ) ) {

	function wp_add_dashboard_widget( $widget_id, $widget_name, $callback, $control_callback = null, $callback_args = null ) {
		$dashboard = new Backend\Dashboard;
		return $dashboard->add_dashboard_widget( $widget_id, $widget_name, $callback, $control_callback = null, $callback_args = null );
	}
} else {
	function wp_add_dashboard_widget() {
		return null;
	}
}

// Redirect to the installer.
if ( ! function_exists( 'wp_not_installed' ) ) {
	function wp_not_installed() {
		return app_not_installed();
	}
}

// PHP ini value is changeable.
if ( ! function_exists( 'wp_is_ini_value_changeable' ) ) {
	function wp_is_ini_value_changeable() {
		return app_is_ini_value_changeable();
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
