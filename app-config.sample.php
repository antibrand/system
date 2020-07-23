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
 * @package App_Package
 */

/**
 * MySQL settings
 *
 * You can get this info from your web host.
 *
 * @since 1.0.0
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
 *
 * @since 1.0.0
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
 *
 * @since 1.0.0
 */
$table_prefix = 'app_';

/**
 * User login directory
 *
 * The default login directory is `/login`. You can
 * rename the directory and define the directory
 * name here.
 *
 * @since 1.0.0
 */
define( 'APP_LOGIN', 'login' );

/**
 * Disable automatic updates.
 *
 * This will prevent WordPress overwriting files.
 *
 * @since 1.0.0
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
 * It is strongly recommended that plugin and theme developers use
 * APP_DEBUG in their development environments.
 *
 * @since 1.0.0
 */
define( 'APP_DEV_MODE', false );
define( 'APP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', true );
define( 'WP_LOCAL_DEV', false );

// Use local URL if WP_LOCAL_DEV is true.
if ( defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV ) {
	// update_option( 'siteurl', 'https://local.example.dev' );
	// update_option( 'home', 'https://local.example.dev' );
}

// Set false to load scripts & styles separately.
// define( 'CONCATENATE_SCRIPTS', true );

/**
 * Network sites
 *
 * This is provided for reference. To begin network
 * activation change `APP_ALLOW_NETWORK` to `true`.
 *
 * Once acivated the user interface will provide
 * definitions with which to replace those
 * following `APP_ALLOW_NETWORK`.
 *
 * @since 1.0.0
 */
// define( 'APP_ALLOW_NETWORK', false );
// define( 'APP_NETWORK', false );
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

// Use unminified scripts.
// define( 'SCRIPT_DEBUG', true );

// Require analyzing the global $wpdb object.
// define( 'SAVEQUERIES', true );

// Site compression.
// define( 'COMPRESS_SCRIPTS', false );
// define( 'COMPRESS_CSS', false );
// define( 'ENFORCE_GZIP', false );

/**
 * Absolute path to the app directory
 *
 * Do not edit this lightly. The `ABSPATH` constant is
 * used extensively throughout the website management
 * system.
 *
 * The trailing slash is included when the constant is
 * redefined elsewhere.
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/**
 * Compatability version
 *
 * Sets a version which may be required by plugins & themes.
 */
if ( ! defined( 'COMPAT_VERSION' ) ) {
	define( 'COMPAT_VERSION', '5.0' );
}

/**
 * HTML templates directory
 *
 * Defines the directory of files which print the HTML
 * of various page templates and template partials.
 *
 * Also contains the themes directory.
 *
 * @since 1.0.0
 */
if ( ! defined( 'APP_VIEWS' ) ) {
	define( 'APP_VIEWS', 'app-views' );
}

/**
 * Path to HTML templates
 *
 * Defines the path to the directory of files which
 * print the HTML of various page templates and
 * template partials.
 *
 * The trailing slash is included when the constant is
 * redefined elsewhere.
 *
 * Also defined in…
 * @see app-load.php
 * @see app-settings.php
 * @see app-views/includes/config.php
 *
 * @since 1.0.0
 */
if ( ! defined( 'APP_VIEWS_PATH' ) ) {
	define( 'APP_VIEWS_PATH', ABSPATH . APP_VIEWS . '/' );
}

/**
 * User login path
 *
 * The default login directory is `/login`. You can
 * rename the directory and define the directory
 * name here.
 *
 * The trailing slash is included when the constant is
 * redefined elsewhere.
 *
 * @since 1.0.0
 */
if ( ! defined( 'APP_LOGIN' ) ) {
	define( 'APP_LOGIN', ABSPATH . 'login' . '/' );
}

/**
 * Default theme
 *
 * Slug of the default theme for this installation.
 * Used as the default theme when installing new sites.
 * It will be used as the fallback if the current theme doesn't exist.
 * Defined in the default constants file if not defined here.
 *
 * @see WP_Theme::get_core_default_theme()
 * @see app-includes/constants-default.php
 *
 * @since 1.0.0
 */
if ( ! defined( 'APP_DEFAULT_THEME' ) ) {
	define( 'APP_DEFAULT_THEME', 'theme' );
}

/**
 * System translation
 *
 * The following two translation definitions
 * are provided for reference while local
 * translations are worked out. The were
 * deprecated in WP 4.0.0.
 *
 * @since 1.0.0
 */

/**
 * Default language
 *
 * Keeping this in U.S. English until the languages settings
 * can be corrected.
 */
define( 'APP_LANG', 'en_US' );

// Default language directory.
define( 'APP_LANG_DIR', ABSPATH . 'app-languages' );

/**
 * End customization
 *
 * Do not add or edit anything below this comment block.
 *
 * @since 1.0.0
 */

// Sets up vars and included files.
require( ABSPATH . 'app-settings.php' );