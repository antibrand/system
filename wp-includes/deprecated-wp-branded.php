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

// function wp_admin_css() {}
// function wp_admin_css_uri() {}

/**
 * Returns the URL that allows the user to retrieve the lost password
 *
 * @param string $redirect Path to redirect to on login.
 * @return string Lost password URL.
 */
function wp_lostpassword_url() {
	return app_lostpassword_url();
}