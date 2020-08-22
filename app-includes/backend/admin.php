<?php
/**
 * Core Administration API
 *
 * @package App_Package
 * @subpackage Administration
 * @since Previous 2.3.0
 */

if ( ! defined( 'APP_ADMIN' ) ) {

	/*
	 * This file is being included from a file other than APP_ADMIN_DIR/admin.php, so
	 * some setup was skipped. Make sure the admin message catalog is loaded since
	 * load_default_textdomain() will not have done so in this context.
	 */
	load_textdomain( 'default', APP_LANG_DIR . '/admin-' . get_locale() . '.mo' );
}

// Administration Hooks.
require_once( APP_INC_PATH . '/backend/admin-filters.php' );

// Bookmark Administration API.
require_once( APP_INC_PATH . '/backend/bookmark.php' );

// Comment Administration API.
require_once( APP_INC_PATH . '/backend/comment.php' );

// Administration File API.
require_once( APP_INC_PATH . '/backend/file.php' );

// Image Administration API.
require_once( APP_INC_PATH . '/backend/image.php' );

// Media Administration API.
require_once( APP_INC_PATH . '/backend/media.php' );

// Import Administration API.
require_once( APP_INC_PATH . '/backend/import.php' );

// Misc Administration API.
require_once( APP_INC_PATH . '/backend/misc.php' );

// Plugin Administration API.
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// Post Administration API.
require_once( ABSPATH . 'wp-admin/includes/post.php' );

// Administration Screen API.
require_once( APP_INC_PATH . '/backend/screen.php' );

// Taxonomy Administration API.
require_once( ABSPATH . 'wp-admin/includes/taxonomy.php' );

// Template Administration API.
require_once( ABSPATH . 'wp-admin/includes/template.php' );

// List Table Administration API.
require_once( ABSPATH . 'wp-admin/includes/list-table.php' );

// Theme Administration API.
require_once( ABSPATH . 'wp-admin/includes/theme.php' );

// User Administration API.
require_once( APP_INC_PATH . '/backend/user.php' );

// Update Administration API.
require_once( ABSPATH . 'wp-admin/includes/update.php' );

// Deprecated Administration API.
require_once( APP_INC_PATH . '/backend/deprecated.php' );

// Network support API.
if ( is_network() ) {
	require_once( APP_INC_PATH . '/backend/network-admin-filters.php' );
	require_once( ABSPATH . 'wp-admin/includes/network-functions.php' );
	require_once( ABSPATH . 'wp-admin/includes/network-deprecated.php' );
}