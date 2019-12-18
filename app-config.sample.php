<?php
/**
 * Base configuration for the website management system
 *
 * The app-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "app-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @package WMS
 */

/**
 * MySQL settings
 *
 * You can get this info from your web host.
 */

// Database name.
define( 'DB_NAME', 'database_name_here' );

// Database username.
define( 'DB_USER', 'username_here' );

// Database password.
define( 'DB_PASSWORD', 'password_here' );

// Database hostname.
define( 'DB_HOST', 'localhost' );

// Database charset to use in creating database tables.
define( 'DB_CHARSET', 'utf8' );

// Database collate type. Don't change this if in doubt.
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 */
define( 'AUTH_KEY',         'Put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'Put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'Put your unique phrase here' );
define( 'NONCE_KEY',        'Put your unique phrase here' );
define( 'AUTH_SALT',        'Put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'Put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'Put your unique phrase here' );
define( 'NONCE_SALT',       'Put your unique phrase here' );

/**
 * Database table prefix
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'app_';

/**
 * User login directory
 *
 * The default login directory is `/login`. You can
 * rename the directory and define the directory
 * name here.
 */
define( 'APP_LOGIN', 'login' );

/**
 * Disable automatic updates.
 *
 * This will prevent WordPress overwriting files.
 *
 * @todo Review this if updates are removed entirely.
 */
define( 'automatic_updater_disabled', true );
define( 'wp_auto_update_core', false );

// PHP memory limit for this site.
// define( 'WP_MEMORY_LIMIT', '256M' );

// Increase admin-side memory limit.
// define( 'WP_MAX_MEMORY_LIMIT', '256M' );

/**
 * Site development
 *
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
// define( 'WP_DEBUG', true );
// define( 'WP_DEBUG_LOG', true );
// define( 'WP_DEBUG_DISPLAY', true );

// Switch for local dev
// define( 'WP_LOCAL_DEV', true );

/**
 * Multisite network
 *
 * This is provided for reference. To begin network
 * activation change `WP_ALLOW_MULTISITE` to `true`.
 *
 * Once acivated the user interface will provide
 * definitions with which to replace those
 * following `WP_ALLOW_MULTISITE`.
 */
// define( 'WP_ALLOW_MULTISITE', true );
// define( 'MULTISITE', false );
// define( 'SUBDOMAIN_INSTALL', '' );
// define( 'DOMAIN_CURRENT_SITE', '' );
// define( 'PATH_CURRENT_SITE', '' );
// define( 'SITE_ID_CURRENT_SITE', 1 );
// define( 'BLOG_ID_CURRENT_SITE', 1 );

// Force SSL for registration & login only.
// define( 'FORCE_SSL_LOGIN', false );

// Force SSL for the entire admin.
// define( 'FORCE_SSL_ADMIN', false );

// Disable the file editors.
// define( 'DISALLOW_FILE_EDIT', true );

// Don't allow users to update core, plugins, or themes.
// define( 'DISALLOW_FILE_MODS', true );

// Allow editing images to replace the originals.
// define( 'IMAGE_EDIT_OVERWRITE', true );

// Set false to load scripts & styles separately.
// define( 'CONCATENATE_SCRIPTS', true );

// Use unminified scripts.
// define( 'SCRIPT_DEBUG', true );

// Require analyzing the global $wpdb object.
// define( 'SAVEQUERIES', true );

// Site compression.
// define( 'COMPRESS_SCRIPTS', false );
// define( 'COMPRESS_CSS', false );
// define( 'ENFORCE_GZIP', false );

/**
 * End customization
 *
 * Do not add or edit anything below this comment block.
 */

// Absolute path to the app directory.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/**
 * User login path
 *
 * The default login directory is `/login`. You can
 * rename the directory and define the directory
 * name here.
 */
define( 'APP_LOGIN', '/' . 'login' . '/' );

/**
 * System translation
 *
 * The following two translation definitions
 * are provided for reference while local
 * translations are worked out. The were
 * deprecated in WP 4.0.0.
 */

// Default language.
// define( 'WPLANG', 'en_US' );

// Default language directory.
// define( 'WP_LANG_DIR', ABSPATH . 'wp-content/languages' );

// Sets up vars and included files.
require_once( ABSPATH . 'wp-settings.php' );