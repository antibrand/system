<?php
/**
 * Defines constants and global variables that can be overridden,
 * typically in system configuration file.
 *
 * @package App_Package
 * @subpackage Includes
 */

/**
 * Absolute path of the system directory
 *
 * Do not edit this lightly. The `ABSPATH` constant is
 * used extensively throughout the website management
 * system.
 *
 * The trailing slash is included when the constant is
 * redefined elsewhere.
 *
 * @since 1.0.0
 * @var   string Returns the path of the system directory.
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
 * Configuration file path
 *
 * Define the location of the configuration file.
 *
 * @see `APP_CONFIG_FILE` above.
 *
 * @since 1.0.0
 * @var   string Returns the path to the configuration file.
 */
define( 'APP_CONFIG_PATH', ABSPATH . APP_CONFIG_FILE );

/**
 * System assets directory name
 *
 * @since 1.0.0
 * @var   string Returns the name of the directory.
 */
define( 'APP_ASSETS_DIR', 'app-assets' );

/**
 * System assets directory path
 *
 * No trailing slash!
 *
 * @since 1.0.0
 * @var   string Returns the path to the directory.
 */
define( 'APP_ASSETS_PATH', ABSPATH . APP_ASSETS_DIR );

/**
 * System includes directory name
 *
 * @since 1.0.0
 * @var   string Returns the name of the directory.
 */
define( 'APP_INC_DIR', 'app-includes' );

/**
 * System includes directory path
 *
 * No trailing slash!
 *
 * @since 1.0.0
 * @var   string Returns the path to the directory.
 */
define( 'APP_INC_PATH', ABSPATH . APP_INC_DIR );

/**
 * Extensions and plugins parent directory name
 *
 * @since 1.0.0
 * @var   string Returns the name of the directory.
 */
define( 'APP_EXTEND_DIR', 'app-extend' );

/**
 * Extensions and plugins parent directory path
 *
 * No trailing slash!
 *
 * @since 1.0.0
 * @var   string Returns the path to the directory.
 */
define( 'APP_EXTEND_PATH', ABSPATH . APP_EXTEND_DIR );

/**
 * Plugins directory name
 *
 * @since 1.0.0
 * @var   string Returns the name of the directory.
 */
define( 'APP_PLUGINS_DIR', 'plugins' );

/**
 * Plugins directory path
 *
 * No trailing slash!
 *
 * @since 1.0.0
 * @var   string Returns the path to the directory.
 */
define( 'APP_PLUGINS_PATH', APP_EXTEND_PATH . '/' . APP_PLUGINS_DIR );

/**
 * Extensions directory name
 *
 * @since 1.0.0
 * @var   string Returns the name of the directory.
 */
define( 'APP_EXTENSIONS_DIR', 'extensions' );

/**
 * Extensions directory path
 *
 * No trailing slash!
 *
 * @since 1.0.0
 * @var   string Returns the path to the directory.
 */
define( 'APP_EXTENSIONS_PATH', APP_EXTEND_PATH . '/' . APP_EXTENSIONS_DIR );

// Define the administration pages directory.
define( 'APP_ADMIN_DIR', 'wp-admin' );

// Define the administration directory path.
define( 'APP_ADMIN_PATH', ABSPATH . APP_ADMIN_DIR . '/' );

/**
 * System views directory name
 *
 * Defines the directory of files which print the HTML
 * of various page templates and template partials in
 * the system back end, and the directory ocontaining
 * themes.
 *
 * @since 1.0.0
 * @var   string Returns the name of the directory.
 */
define( 'APP_VIEWS_DIR', 'app-views' );

/**
 * System views directory path
 *
 * No trailing slash!
 *
 * @since 1.0.0
 * @var   string Returns the path to the directory.
 */
define( 'APP_VIEWS_PATH', ABSPATH . APP_VIEWS_DIR );

/**
 * Language directory name
 *
 * @since 1.0.0
 * @var   string Returns the name of the directory.
 */
define( 'APP_LANG_DIR', 'app-languages' );

/**
 * Language directory path
 *
 * @since 1.0.0
 * @var   string Returns the path to the directory.
 */
define( 'APP_LANG_PATH', ABSPATH . 'APP_LANG_DIR' );

/**
 * System API
 *
 * URL constant to change the location of the API files.
 */

// This is a dummy URI for a fictitious system API.
define( 'APP_API_URI', 'https://api.antibrand.dev' );

/**
 * Disable most of the system
 *
 * Useful for fast responses for custom integrations.
 */
define( 'SHORTINIT', false );
