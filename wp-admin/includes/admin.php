<?php
/**
 * Core Administration API
 *
 * @package App_Package
 * @subpackage Administration
 * @since Previous 2.3.0
 */

if ( ! defined( 'WP_ADMIN' ) ) {

	/*
	 * This file is being included from a file other than wp-admin/admin.php, so
	 * some setup was skipped. Make sure the admin message catalog is loaded since
	 * load_default_textdomain() will not have done so in this context.
	 */
	load_textdomain( 'default', APP_LANG_DIR . '/admin-' . get_locale() . '.mo' );
}

// Administration Hooks.
require_once( ABSPATH . APPINC . '/backend/admin-filters.php' );

// Bookmark Administration API.
require_once( ABSPATH . 'wp-admin/includes/bookmark.php' );

// Comment Administration API.
require_once( ABSPATH . 'wp-admin/includes/comment.php' );

// Administration File API.
require_once( ABSPATH . 'wp-admin/includes/file.php' );

// Image Administration API.
require_once( ABSPATH . 'wp-admin/includes/image.php' );

// Media Administration API.
require_once( ABSPATH . 'wp-admin/includes/media.php' );

// Import Administration API.
require_once( ABSPATH . 'wp-admin/includes/import.php' );

// Misc Administration API.
require_once( ABSPATH . 'wp-admin/includes/misc.php' );

// Options Administration API.
require_once( ABSPATH . 'wp-admin/includes/options.php' );

// Plugin Administration API.
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// Post Administration API.
require_once( ABSPATH . 'wp-admin/includes/post.php' );

// Administration Screen API.
require_once( ABSPATH . 'wp-admin/includes/screen.php' );

// Taxonomy Administration API.
require_once( ABSPATH . 'wp-admin/includes/taxonomy.php' );

// Template Administration API.
require_once( ABSPATH . 'wp-admin/includes/template.php' );

// List Table Administration API.
require_once( ABSPATH . 'wp-admin/includes/list-table.php' );

// Theme Administration API.
require_once( ABSPATH . 'wp-admin/includes/theme.php' );

// User Administration API.
require_once( ABSPATH . 'wp-admin/includes/user.php' );

// Update Administration API.
require_once( ABSPATH . 'wp-admin/includes/update.php' );

// Deprecated Administration API.
require_once( ABSPATH . 'wp-admin/includes/deprecated.php' );

// Network support API.
if ( is_network() ) {
	require_once( ABSPATH . APPINC . '/backend/network-admin-filters.php' );
	require_once( ABSPATH . 'wp-admin/includes/network-functions.php' );
	require_once( ABSPATH . 'wp-admin/includes/network-deprecated.php' );
}