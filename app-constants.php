<?php
/**
 * Defines constants and global variables that can be overridden,
 * typically in system configuration file.
 *
 * @package App_Package
 * @subpackage Includes
 */

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

// No trailing slash, full paths only - APP_CONTENT_URL is defined further down
define( 'APP_CONTENT_DIR', ABSPATH . 'app-views' );
