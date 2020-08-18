<?php
/**
 * Bootstrap file for setting the ABSPATH constant
 * and loading the configuration file. The configuration
 * file will then load the settings file, which will
 * then set up the environment.
 *
 * If the configuration file is not found then an error
 * will be displayed asking the visitor to set up the
 * configuration file.
 *
 * Will also search for configuration file in the parent
 * directory to allow the directory to remain untouched.
 *
 * @package App_Package
 */

// Get the default system constants.
require_once( dirname( __FILE__ ) . '/app-constants.php' );

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

// Define APP_ASSETS for assets directory.
if ( ! defined( 'APP_ASSETS' ) ) {
	define( 'APP_ASSETS', ABSPATH . 'app-assets/' );
}

error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

/**
 * If the configuration file exists in the root, or if it exists in the root and app-settings.php
 * doesn't, load the configuration file. The secondary check for app-settings.php has the added
 * benefit  of avoiding cases where the current directory is a nested installation.
 *
 * If neither set of conditions is true, initiate loading the setup process.
 */
if ( file_exists( APP_CONFIG_PATH ) ) {

	// The config file resides in ABSPATH.
	require_once( APP_CONFIG_PATH );

} elseif ( @file_exists( dirname( ABSPATH ) . '/app-config.php' ) && ! @file_exists( dirname( ABSPATH ) . '/app-settings.php' ) ) {

	// The config file resides one level above ABSPATH but is not part of another installation.
	require_once( dirname( ABSPATH ) . '/app-config.php' );

// If a config file doesn't exist.
} else {

	require_once( ABSPATH . APP_INC . '/load.php' );

	// Standardize $_SERVER variables across setups.
	wp_fix_server_vars();

	require_once( ABSPATH . APP_INC . '/functions.php' );

	/**
	 * Try to find the configuration template.
	 *
	 * @see app-includes/functions.php
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

	define( 'APP_CONTENT_DIR', ABSPATH . 'app-views' );
	require_once( ABSPATH . APP_INC . '/version.php' );

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
