<?php
/**
 * Base configuration for the website management system
 *
 * The form script that creates a configuration file uses
 * a sample file during the installation. That sample may
 * be this file that you are reading, if the file name
 * is apended by `.sample` (e.g. app-config.sample.php).
 *
 * You don't have to use the configuration creation form,
 * you can copy the sample file, remove the `.sample`,
 * and fill in the definition values.
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
 * App identity
 *
 * Define white label names and URLs.
 *
 * @since 1.0.0
 */

// Check for an identity configuration file.
if ( file_exists( ABSPATH . 'id-config.php' ) ) {
	require( ABSPATH . 'id-config.php' );

// Check one level above root for an identity config file.
} elseif ( file_exists( dirname( ABSPATH ) . 'id-config.php' ) ) {
	require( dirname( ABSPATH ) . 'id-config.php' );

// Check for a sample identity config file.
} elseif ( file_exists( ABSPATH . 'id-config.sample.php' ) ) {
	require( ABSPATH . 'id-config.sample.php' );

// Check one level above root for a sample identity config file.
} elseif ( file_exists( dirname( ABSPATH ) . 'id-config.sample.php' ) ) {
	require( dirname( ABSPATH ) . 'id-config.sample.php' );

// Fallback definitions.
} else {

	// Define a name of the website management system.
	if ( ! defined( 'APP_NAME' ) ) {
		define( 'APP_NAME', 'system' );
	}

	// Define a tagline of the website management system.
	if ( ! defined( 'APP_TAGLINE' ) ) {
		define( 'APP_TAGLINE', '' );
	}

	// Define a URL for the website management system.
	if ( ! defined( 'APP_WEBSITE' ) ) {
		define( 'APP_WEBSITE', '' );
	}

	// Define a logo path for the website management system.
	if ( ! defined( 'APP_IMAGE' ) ) {
		define( 'APP_IMAGE', '' );
	}
}

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
 * This will prevent any automatic updates that may be implemented.
 *
 * @since 1.0.0
 *
 * @todo Review this if updates are removed entirely.
 */
define( 'automatic_updater_disabled', true );
define( 'wp_auto_update_core', false );

// PHP memory limit for this site.
// define( 'APP_MEMORY_LIMIT', '256M' );

// Increase admin-side memory limit.
// define( 'APP_MAX_MEMORY_LIMIT', '256M' );

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
define( 'APP_DEBUG_LOG', false );
define( 'APP_DEBUG_DISPLAY', true );
define( 'WP_LOCAL_DEV', false );

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
 * Compatability version
 *
 * Sets a version which may be required by plugins & themes.
 *
 * @since 1.0.0
 * @var string The general or precise version number.
 */
define( 'COMPAT_VERSION', '5.0' );

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
define( 'APP_LOGIN', ABSPATH . 'login' . '/' );

/**
 * Default theme
 *
 * Slug of the default theme for this installation.
 * Used as the default theme when installing new sites.
 * It will be used as the fallback if the current theme doesn't exist.
 * Defined in the default constants file if not defined here.
 *
 * @see WP_Theme::get_core_default_theme()
 *
 * @since 1.0.0
 * @var   string The directory name of the theme.
 */
define( 'APP_DEFAULT_THEME', 'theme' );

/**
 * Default language
 *
 * Keeping this in U.S. English until the languages settings
 * can be corrected.
 */
define( 'APP_LANG', 'en_US' );

/**
 * Default language directory
 *
 * @since 1.0.0
 * @var   string
 */
define( 'APP_LANG_DIR', ABSPATH . 'app-languages' );

/**
 * Allow better passwords feature
 *
 * @since 1.0.0
 * @var   boolean Default is true.
 */
// define( 'APP_FEATURE_BETTER_PASSWORDS', true );

/**
 * Allow post revisions
 *
 * @since 1.0.0
 * @var   boolean Default is true.
 */
// define( 'APP_POST_REVISIONS', true );

/**
 * Medai trash
 *
 * Allow the trash status for the 'attachment' post type.
 *
 * @since 1.0.0
 * @var   boolean Default is false.
 */
define( 'MEDIA_TRASH', false );

/**
 * System chaching
 *
 * @since 1.0.0
 * @var   boolean Default is false
 */
define( 'APP_CACHE', false );

/**
 * End customization
 *
 * Do not add or edit anything below this comment block.
 */

// Get the system settings.
require( ABSPATH . 'app-settings.php' );
