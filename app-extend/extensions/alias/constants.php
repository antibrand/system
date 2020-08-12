<?php
/**
 * Alias constants
 *
 * @package Alias
 * @subpackage App_Package
 */

// Stop here if the system is not loaded.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Debug mode
 *
 * @since 1.0.0
 * @var   boolean
 */
if ( ! defined( 'WP_DEBUG' ) && defined( 'APP_DEBUG' ) ) {
	define( 'WP_DEBUG', APP_DEBUG );
} else {
	define( 'WP_DEBUG', false );
}

/**
 * Installing
 *
 * @since 1.0.0
 * @var   boolean
 */
if ( ! defined( 'WP_INSTALLING' ) && defined( 'APP_INSTALLING' ) ) {
	define( 'WP_INSTALLING', APP_INSTALLING );
}

/**
 * Includes directory
 *
 * Check for definition with a value.
 *
 * @since 1.0.0
 * @var   string Returns the path to the includes directory.
 */
if ( ! defined( 'WPINC' ) && ( defined( 'APP_INC' ) && APP_INC ) ) {
	define( 'WPINC', APP_INC );
} else {
	define( 'WPINC', ABSPATH . 'app-includes' );
}

/**
 * Plugins directory
 *
 * Check for definition with a value.
 *
 * @since 1.0.0
 * @var   string Returns the path to the plugins directory.
 */
if ( ! defined( 'WP_PLUGIN_DIR' ) && ( defined( 'APP_PLUGIN_DIR' ) && APP_PLUGIN_DIR ) ) {
	define( 'WP_PLUGIN_DIR', APP_PLUGIN_DIR );
} else {
	define( 'WP_PLUGIN_DIR', ABSPATH . 'app-extend/plugins' );
}

/**
 * Plugins URL
 *
 * Check for definition with a value.
 *
 * @since 1.0.0
 * @var   string Returns the URL to the plugins directory.
 */
if ( ! defined( 'WP_PLUGIN_URL' ) && ( defined( 'APP_PLUGIN_URL' ) && APP_PLUGIN_URL ) ) {
	define( 'WP_PLUGIN_URL', APP_PLUGIN_URL );
} else {
	define( 'WP_PLUGIN_URL', ABSPATH . 'app-extend/plugins' );
}

/**
 * Languages directory
 *
 * Check for definition with a value.
 *
 * @since 1.0.0
 * @var   string Returns the path to the languages directory.
 */
if ( ! defined( 'WP_LANG_DIR' ) && ( defined( 'APP_LANG_DIR' ) && APP_LANG_DIR ) ) {
	define( 'WP_LANG_DIR', APP_LANG_DIR );
} else {
	define( 'WP_LANG_DIR', ABSPATH . 'app-languages' );
}

/**
 * Allow network
 *
 * @since 1.0.0
 * @var   boolean
 */
if ( ! defined( 'WP_ALLOW_MULTISITE' ) && defined( 'APP_ALLOW_NETWORK' ) ) {
	define( 'WP_ALLOW_MULTISITE', APP_ALLOW_NETWORK );
} else {
	define( 'WP_ALLOW_MULTISITE', false );
}

/**
 * Network enabled
 *
 * @since 1.0.0
 * @var   boolean
 */
if ( ! defined( 'MULTISITE' ) && defined( 'APP_NETWORK' ) ) {
	define( 'MULTISITE', APP_NETWORK );
} else {
	define( 'MULTISITE', false );
}

/**
 * Network admin
 *
 * @since 1.0.0
 * @var   boolean
 */
if ( ! defined( 'WP_NETWORK_ADMIN' ) && defined( 'APP_NETWORK_ADMIN' ) ) {
	define( 'WP_NETWORK_ADMIN', APP_NETWORK_ADMIN );
} else {
	define( 'WP_NETWORK_ADMIN', false );
}
