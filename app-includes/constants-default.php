<?php
/**
 * Defines constants and global variables that can be overridden,
 * typically in system configuration file.
 *
 * @package App_Package
 * @subpackage Includes
 */

/**
 * Defines initial constants
 *
 * @see app_debug_mode()
 *
 * @since Previous 3.0.0
 * @global int    $blog_id The current site ID.
 */
function app_initial_constants() {

	global $blog_id;

	/**
	 * App config
	 *
	 * Define the location of the configuration file.
	 *
	 * @since 1.0.0
 	 * @var   string Returns the path to the config file.
	 */
	if ( ! defined( 'APP_CONFIG' ) && defined( 'ABSPATH' ) ) {
		define( 'APP_CONFIG', ABSPATH . 'app-config.php' );
	}

	/**
	 * App identity
	 *
	 * Define white label names and URLs.
	 *
	 * @since 1.0.0
	 */

	// Check for an identity config file.
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

	/**#@+
	 * Constants for expressing human-readable data sizes in their respective number of bytes.
	 *
	 * @since Previous 4.4.0
	 */
	define( 'KB_IN_BYTES', 1024 );
	define( 'MB_IN_BYTES', 1024 * KB_IN_BYTES );
	define( 'GB_IN_BYTES', 1024 * MB_IN_BYTES );
	define( 'TB_IN_BYTES', 1024 * GB_IN_BYTES );
	/**#@-*/

	$current_limit     = @ini_get( 'memory_limit' );
	$current_limit_int = app_convert_hr_to_bytes( $current_limit );

	// Define memory limits.
	if ( ! defined( 'APP_MEMORY_LIMIT' ) ) {

		if ( false === app_is_ini_value_changeable( 'memory_limit' ) ) {
			define( 'APP_MEMORY_LIMIT', $current_limit );
		} elseif ( is_network() ) {
			define( 'APP_MEMORY_LIMIT', '64M' );
		} else {
			define( 'APP_MEMORY_LIMIT', '40M' );
		}
	}

	if ( ! defined( 'APP_MAX_MEMORY_LIMIT' ) ) {

		if ( false === app_is_ini_value_changeable( 'memory_limit' ) ) {
			define( 'APP_MAX_MEMORY_LIMIT', $current_limit );
		} elseif ( -1 === $current_limit_int || $current_limit_int > 268435456 /* = 256M */ ) {
			define( 'APP_MAX_MEMORY_LIMIT', $current_limit );
		} else {
			define( 'APP_MAX_MEMORY_LIMIT', '256M' );
		}
	}

	// Set memory limits.
	$app_limit_int = app_convert_hr_to_bytes( APP_MEMORY_LIMIT );

	if ( -1 !== $current_limit_int && ( -1 === $app_limit_int || $app_limit_int > $current_limit_int ) ) {
		@ini_set( 'memory_limit', APP_MEMORY_LIMIT );
	}

	if ( ! isset( $blog_id ) ) {
		$blog_id = 1;
	}

	/**
	 * System API
	 *
	 * URL constant to change the location of the API files.
	 */
	if ( ! defined( 'APP_API_URI' ) ) {

		// This is a dummy URI.
		define( 'APP_API_URI', 'https://api.antibrand.dev' );
	}

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
	if ( ! defined( 'APP_VIEWS' ) ) {
		define( 'APP_VIEWS', 'app-views' );
	}

	// No trailing slash, full paths only - APP_CONTENT_URL is defined further down
	if ( ! defined( 'APP_CONTENT_DIR' ) ) {
		define( 'APP_CONTENT_DIR', ABSPATH . 'app-views' );
	}

	// Add define( 'APP_DEBUG', true ); to app-config.php to enable display of notices during development.
	if ( ! defined( 'APP_DEBUG' ) ) {
		define( 'APP_DEBUG', false );
	}

	// Define the administration pages directory.
	if ( ! defined( 'APP_ADMIN_DIR' ) ) {
		define( 'APP_ADMIN_DIR', 'wp-admin' );
	}

	/**
	 * Add define( 'APP_DEBUG_DISPLAY', null); to app-config.php to
	 * use the globally configured setting for display_errors and
	 * not force errors to be displayed. Use false to force
	 * display_errors off.
	 */
	if ( ! defined( 'APP_DEBUG_DISPLAY' ) ) {
		define( 'APP_DEBUG_DISPLAY', true );
	}

	// Add define( 'APP_DEBUG_LOG', true ); to enable error logging to app-views/debug.log.
	if ( ! defined( 'APP_DEBUG_LOG' ) ) {
		define( 'APP_DEBUG_LOG', false );
	}

	// Define caching.
	if ( ! defined( 'APP_CACHE' ) ) {
		define( 'APP_CACHE', false );
	}

	/**
	 * Add define( 'SCRIPT_DEBUG', true ); to app-config.php to enable
	 * loading of non-minified, non-concatenated scripts and stylesheets.
	 */
	if ( ! defined( 'SCRIPT_DEBUG' ) ) {

		if ( ! empty( $GLOBALS['app_version'] ) ) {
			$develop_src = false !== strpos( $GLOBALS['app_version'], '-src' );
		} else {
			$develop_src = false;
		}

		define( 'SCRIPT_DEBUG', $develop_src );
	}

	/**
	 * Private
	 */
	if ( ! defined( 'MEDIA_TRASH' ) ) {
		define( 'MEDIA_TRASH', false );
	}

	if ( ! defined( 'SHORTINIT' ) ) {
		define( 'SHORTINIT', false );
	}

	// Constants for features added that should short-circuit their plugin implementations.
	define( 'APP_FEATURE_BETTER_PASSWORDS', true );

	/**#@+
	 * Constants for expressing human-readable intervals
	 * in their respective number of seconds.
	 *
	 * Please note that these values are approximate and are provided for convenience.
	 * For example, MONTH_IN_SECONDS wrongly assumes every month has 30 days and
	 * YEAR_IN_SECONDS does not take leap years into account.
	 *
	 * If you need more accuracy consider using the DateTime class.
	 * @link https://secure.php.net/manual/en/class.datetime.php
	 *
	 * @since Previous 3.5.0
	 * @since Previous 4.4.0 Introduced `MONTH_IN_SECONDS`.
	 */
	define( 'MINUTE_IN_SECONDS', 60 );
	define( 'HOUR_IN_SECONDS', 60 * MINUTE_IN_SECONDS );
	define( 'DAY_IN_SECONDS', 24 * HOUR_IN_SECONDS );
	define( 'WEEK_IN_SECONDS', 7 * DAY_IN_SECONDS );
	define( 'MONTH_IN_SECONDS', 30 * DAY_IN_SECONDS );
	define( 'YEAR_IN_SECONDS', 365 * DAY_IN_SECONDS );
	/**#@-*/
}

/**
 * Defines plugin directory constants
 *
 * Defines must-use plugin directory constants, which may be overridden in the sunrise.php drop-in
 *
 * @since Previous 3.0.0
 */
function app_plugin_directory_constants() {

	if ( ! defined( 'APP_CONTENT_URL' ) ) {

		// Full url - APP_CONTENT_DIR is defined further up.
		define( 'APP_CONTENT_URL', get_option( 'siteurl' ) . '/app-views' );
	}

	/**
	 * Allows for the plugins directory to be moved from the default location.
	 *
	 * @since Previous 2.6.0
	 */
	if ( ! defined( 'APP_PLUGIN_DIR' ) ) {

		// Full path, no trailing slash.
		define( 'APP_PLUGIN_DIR', ABSPATH . 'app-extend/plugins' );
	}

	/**
	 * Allows for the plugins directory to be moved from the default location.
	 *
	 * @since Previous 2.6.0
	 */
	if ( ! defined( 'APP_PLUGIN_URL' ) ) {

		// Full url, no trailing slash.
		define( 'APP_PLUGIN_URL', get_option( 'siteurl' ) . '/app-extend/plugins' );
	}

	/**
	 * Allows for the extensions directory to be moved from the default location.
	 *
	 * @since 1.0.0
	 */
	if ( ! defined( 'APP_EXTEND_DIR' ) ) {

		// Full path, no trailing slash.
		define( 'APP_EXTEND_DIR', ABSPATH . 'app-extend/extensions' );
	}

	/**
	 * Allows for the extensions directory to be moved from the default location.
	 *
	 * @since Previous 1.0.0
	 */
	if ( ! defined( 'APP_EXTEND_URL' ) ) {

		// Full url, no trailing slash.
		define( 'APP_EXTEND_URL', get_option( 'siteurl' ) . '/app-extend/extensions' );
	}
}

/**
 * Defines cookie related constants
 *
 * Defines constants after network is loaded.
 * @since Previous 3.0.0
 */
function app_cookie_constants() {

	/**
	 * Used to guarantee unique hash cookies
	 *
	 * @since Previous 1.5.0
	 */
	if ( ! defined( 'COOKIEHASH' ) ) {

		$siteurl = get_site_option( 'siteurl' );

		if ( $siteurl ) {
			define( 'COOKIEHASH', md5( $siteurl ) );
		} else {
			define( 'COOKIEHASH', '' );
		}
	}

	/**
	 * @since Previous 2.0.0
	 */
	if ( ! defined( 'USER_COOKIE' ) ) {
		define( 'USER_COOKIE', 'wordpressuser_' . COOKIEHASH );
	}

	/**
	 * @since Previous 2.0.0
	 */
	if ( ! defined( 'PASS_COOKIE' ) ) {
		define( 'PASS_COOKIE', 'wordpresspass_' . COOKIEHASH );
	}

	/**
	 * @since Previous 2.5.0
	 */
	if ( ! defined( 'AUTH_COOKIE' ) ) {
		define( 'AUTH_COOKIE', 'wordpress_' . COOKIEHASH );
	}

	/**
	 * @since Previous 2.6.0
	 */
	if ( ! defined( 'SECURE_AUTH_COOKIE' ) ) {
		define( 'SECURE_AUTH_COOKIE', 'wordpress_sec_' . COOKIEHASH );
	}

	/**
	 * @since Previous 2.6.0
	 */
	if ( ! defined( 'LOGGED_IN_COOKIE' ) ) {
		define( 'LOGGED_IN_COOKIE', 'wordpress_logged_in_' . COOKIEHASH );
	}

	/**
	 * @since Previous 2.3.0
	 */
	if ( ! defined( 'TEST_COOKIE' ) ) {
		define( 'TEST_COOKIE', 'wordpress_test_cookie' );
	}

	/**
	 * @since Previous 1.2.0
	 */
	if ( ! defined( 'COOKIEPATH' ) ) {
		define( 'COOKIEPATH', preg_replace( '|https?://[^/]+|i', '', get_option( 'home' ) . '/' ) );
	}

	/**
	 * @since Previous 1.5.0
	 */
	if ( ! defined( 'SITECOOKIEPATH' ) ) {
		define( 'SITECOOKIEPATH', preg_replace( '|https?://[^/]+|i', '', get_option( 'siteurl' ) . '/' ) );
	}

	/**
	 * @since Previous 2.6.0
	 */
	if ( ! defined( 'ADMIN_COOKIE_PATH' ) ) {
		define( 'ADMIN_COOKIE_PATH', SITECOOKIEPATH . 'wp-admin' );
	}

	/**
	 * @since Previous 2.6.0
	 */
	if ( ! defined( 'PLUGINS_COOKIE_PATH' ) ) {
		define( 'PLUGINS_COOKIE_PATH', preg_replace( '|https?://[^/]+|i', '', APP_PLUGIN_URL ) );
	}

	/**
	 * @since Previous 2.0.0
	 */
	if ( ! defined( 'COOKIE_DOMAIN' ) ) {
		define( 'COOKIE_DOMAIN', false );
	}
}

/**
 * Defines cookie related constants
 *
 * @since Previous 3.0.0
 */
function app_ssl_constants() {

	/**
	 * @since Previous 2.6.0
	 */
	if ( ! defined( 'FORCE_SSL_ADMIN' ) ) {

		if ( 'https' === parse_url( get_option( 'siteurl' ), PHP_URL_SCHEME ) ) {
			define( 'FORCE_SSL_ADMIN', true );
		} else {
			define( 'FORCE_SSL_ADMIN', false );
		}
	}

	force_ssl_admin( FORCE_SSL_ADMIN );
}

/**
 * Defines functionality related constants
 *
 * @since Previous 3.0.0
 */
function app_functionality_constants() {

	/**
	 * Autosave
	 *
	 * @since Previous 2.5.0
	 * @var   integer
	 */
	if ( ! defined( 'AUTOSAVE_INTERVAL' ) ) {
		define( 'AUTOSAVE_INTERVAL', 60 );
	}

	/**
	 * Empty trash
	 *
	 * @since Previous 2.9.0
	 * @var   integer
	 */
	if ( ! defined( 'EMPTY_TRASH_DAYS' ) ) {
		define( 'EMPTY_TRASH_DAYS', 30 );
	}

	/**
	 * Post revisions
	 *
	 * @since 1.0.0
	 * @var   boolean
	 */
	if ( ! defined( 'APP_POST_REVISIONS' ) ) {
		define( 'APP_POST_REVISIONS', true );
	}

	/**
	 * Chron lock time out
	 *
	 * Define in seconds.
	 *
	 * @since Previous 3.3.0
	 * @var   integer
	 */
	if ( ! defined( 'APP_CRON_LOCK_TIMEOUT' ) ) {
		define( 'APP_CRON_LOCK_TIMEOUT', 60 );
	}
}

/**
 * Defines templating related constants
 *
 * @since Previous 3.0.0
 */
function app_templating_constants() {

	/**
	 * Filesystem path to the current active template directory
	 * @since Previous 1.5.0
	 */
	define( 'TEMPLATEPATH', get_template_directory() );

	/**
	 * Filesystem path to the current active template stylesheet directory
	 * @since Previous 2.1.0
	 */
	define( 'STYLESHEETPATH', get_stylesheet_directory() );

	/**
	 * Slug of the default theme for this installation.
	 * Used as the default theme when installing new sites.
	 * It will be used as the fallback if the current theme doesn't exist.
	 *
	 * @since Previous 3.0.0
	 * @see WP_Theme::get_core_default_theme()
	 */
	if ( ! defined( 'APP_DEFAULT_THEME' ) ) {
		define( 'APP_DEFAULT_THEME', 'theme' );
	}

}
