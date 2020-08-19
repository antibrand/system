<?php
/**
 * Defines constants and global variables that can be overridden,
 * typically in system configuration file.
 *
 * @package App_Package
 * @subpackage Includes
 */

/**
 * Absolute path to the system directory
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
define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/**
 * Configuration file name
 *
 * Separate constant from the path for renaming the
 * file without redefining the path to the file.
 *
 * @since 1.0.0
 * @var   string Returns the name of the file.
 */
define( 'APP_CONFIG_FILE', 'app-config.php' );

/**
 * Sample configuration file name
 *
 * Uses the same path as the configuration file.
 *
 * @see `APP_CONFIG_PATH` below.
 *
 * @since 1.0.0
 * @var   string Returns the name of the file.
 */
define( 'APP_CONFIG_SAMPLE', 'app-config.sample.php' );

// Store the locations of functions, classes, and core content.
define( 'APP_INC', 'app-includes' );

/**
 * Configuration file path
 *
 * Define the location of the configuration file.
 *
 * @see `APP_CONFIG_FILE` above.
 *
 * @since 1.0.0
 * @var   string Returns the path to the configuration file.
	*/
if ( ! defined( 'APP_CONFIG_PATH' ) && defined( 'ABSPATH' ) ) {
	define( 'APP_CONFIG_PATH', ABSPATH . APP_CONFIG_FILE );
}



/**
 * System API
 *
 * URL constant to change the location of the API files.
 */

// This is a dummy URI for a fictitious system API.
define( 'APP_API_URI', 'https://api.antibrand.dev' );

// Define the administration pages directory.
define( 'APP_ADMIN_DIR', 'wp-admin' );

// Define the administration directory path.
define( 'APP_ADMIN_PATH', ABSPATH . APP_ADMIN_DIR . '/' );

/**
 * HTML templates & themes directory
 *
 * Defines the directory of files which print the HTML
 * of various page templates and template partials in
 * the system back end, and the directory ocontaining
 * themes.
 *
 * @since 1.0.0
 */
define( 'APP_VIEWS', 'app-views' );

// Path to HTML templates.
define( 'APP_VIEWS_PATH', ABSPATH . APP_VIEWS . '/' );

// No trailing slash, full paths only - APP_CONTENT_URL is defined further down
define( 'APP_CONTENT_DIR', ABSPATH . APP_VIEWS );

// Define caching.
if ( ! defined( 'APP_CACHE' ) ) {
	define( 'APP_CACHE', false );
}

// Allow the trash status for the 'attachment' post type.
if ( ! defined( 'MEDIA_TRASH' ) ) {
	define( 'MEDIA_TRASH', false );
}

/**
 * Disable most of the system
 *
 * Useful for fast responses for custom integrations.
 */
define( 'SHORTINIT', false );

// Allow better passwords feature.
define( 'APP_FEATURE_BETTER_PASSWORDS', true );
