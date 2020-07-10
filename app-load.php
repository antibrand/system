<?php
/**
 * Bootstrap file for setting the ABSPATH constant
 * and loading the app-config.php file. The app-config.php
 * file will then load the app-settings.php file, which
 * will then set up the environment.
 *
 * If the app-config.php file is not found then an error
 * will be displayed asking the visitor to set up the
 * app-config.php file.
 *
 * Will also search for app-config.php in the parent
 * directory to allow the directory to remain untouched.
 *
 * @package App_Package
 */

// Define ABSPATH as this file's directory.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

// HTML templates directory name.
if ( ! defined( 'APP_VIEWS' ) ) {
	define( 'APP_VIEWS', 'app-views' );
}

// Define APP_VIEWS_PATH for page markup directory.
if ( ! defined( 'APP_VIEWS_PATH' ) ) {
	define( 'APP_VIEWS_PATH',  ABSPATH . APP_VIEWS . '/' );
}

// Define APPASSETS for assets directory.
if ( ! defined( 'APPASSETS' ) ) {
	define( 'APPASSETS', ABSPATH . 'app-assets/' );
}

error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

/**
 * If app-config.php exists in the root, or if it exists in the root and app-settings.php
 * doesn't, load app-config.php. The secondary check for app-settings.php has the added benefit
 * of avoiding cases where the current directory is a nested installation.
 *
 * If neither set of conditions is true, initiate loading the setup process.
 */

if ( file_exists( ABSPATH . 'app-config.php' ) ) {

	// The config file resides in ABSPATH.
	require_once( ABSPATH . 'app-config.php' );

} elseif ( @file_exists( dirname( ABSPATH ) . '/app-config.php' ) && ! @file_exists( dirname( ABSPATH ) . '/app-settings.php' ) ) {

	// The config file resides one level above ABSPATH but is not part of another installation.
	require_once( dirname( ABSPATH ) . '/app-config.php' );

// If a config file doesn't exist.
} else {

	define( 'WPINC', 'wp-includes' );
	require_once( ABSPATH . WPINC . '/load.php' );

	// Standardize $_SERVER variables across setups.
	wp_fix_server_vars();

	require_once( ABSPATH . WPINC . '/functions.php' );

	/**
	 * Try to find the configuration template.
	 *
	 * @see wp-includes/functions.php
	 */
	$path = wp_guess_url() . '/app-views/includes/config.php';

	/**
	 * We're going to redirect to config.php. While this shouldn't result
	 * in an infinite loop, that's a silly thing to assume, don't you think? If
	 * we're traveling in circles, our last-ditch effort is "Need more help?"
	 */
	if ( false === strpos( $_SERVER['REQUEST_URI'], 'config' ) ) {
		header( 'Location: ' . $path );
		exit;
	}

	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
	require_once( ABSPATH . WPINC . '/version.php' );

	wp_check_php_mysql_versions();
	wp_load_translations_early();

	// Die with an error message.
	$die = sprintf(
		'<p>%1s <code>app-config.php</code> %2s</p>',
		__( 'There doesn\'t seem to be a' ),
		__( 'file. This is needed to run the website management system.' )
	);

	$die .= sprintf(
		'<p>%1s <code>app-config.php</code> %2s</p>',
		__( 'You can create a' ),
		__( 'file through a web interface, but this doesn\'t work for all server setups. The safest way is to manually create the file.' )
	);

	$die .= '<p><a href="' . $path . '" class="button button-large">' . __( 'Create a configuration file' ) . '</a>';

	wp_die( $die, __( 'Error' ) );
}