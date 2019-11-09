<?php
/**
 * The base configuration for the website management system
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
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
 * Define the application name
 *
 * This constant is used throughout the website management system
 * and allows you to "white label" the application.
 */
define( 'APP_NAME', 'system' );

/**
 * Disable automatic updates.
 *
 * This will prevent WordPress overwriting files.
 *
 * @todo Review this if updates are removed entirely.
 */
define( 'automatic_updater_disabled', true );
define( 'wp_auto_update_core', false );

/**
 * For developers: debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define( 'WP_DEBUG', true );

// Multisite definitions.
define( 'WP_ALLOW_MULTISITE', false );

/**
 * End customization
 *
 * Do not add or edit anything below this comment block.
 */

// Absolute path to the app directory.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

// Sets up vars and included files.
require_once( ABSPATH . 'wp-settings.php' );