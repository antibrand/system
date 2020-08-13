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
 * The compatability version checked by plugins & themes
 *
 * This is may be defined in the app-config.php file.
 * Fallback is the version from whence this was derived.
 *
 * @global string $wp_version
 */
if ( defined( 'COMPAT_VERSION' ) && COMPAT_VERSION ) {
	$wp_version = COMPAT_VERSION;
} else {
	$wp_version = '4.9.8';
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
 * Debug display
 *
 * @since 1.0.0
 * @var   boolean
 */
if ( ! defined( 'WP_DEBUG_DISPLAY' ) && defined( 'APP_DEBUG_DISPLAY' ) ) {
	define( 'WP_DEBUG_DISPLAY', APP_DEBUG_DISPLAY );
} else {
	define( 'WP_DEBUG_DISPLAY', true );
}

/**
 * Debug log
 *
 * @since 1.0.0
 * @var   boolean
 */
if ( ! defined( 'WP_DEBUG_LOG' ) && defined( 'APP_DEBUG_LOG' ) ) {
	define( 'WP_DEBUG_LOG', APP_DEBUG_LOG );
} else {
	define( 'WP_DEBUG_LOG', false );
}

/**
 * Caching
 *
 * @since 1.0.0
 * @var   boolean
 */
if ( ! defined( 'WP_CACHE' ) && defined( 'APP_CACHE' ) ) {
	define( 'WP_CACHE', APP_CACHE );
} else {
	define( 'WP_CACHE', false );
}

/**
 * Installing
 *
 * @since 1.0.0
 * @var   boolean
 */
if ( ! defined( 'WP_INSTALLING' ) && defined( 'APP_INSTALLING' ) ) {
	define( 'WP_INSTALLING', APP_INSTALLING );
} else {
	define( 'WP_INSTALLING', false );
}

/**
 * Site admin
 *
 * @since 1.0.0
 * @var   boolean
 */
if ( ! defined( 'WP_ADMIN' ) && defined( 'APP_ADMIN' ) ) {
	define( 'WP_ADMIN', APP_ADMIN );
} else {
	define( 'WP_ADMIN', false );
}

/**
 * Memory limit
 *
 * @since 1.0.0
 * @var   string Returns the memory limit in megabytes.
 */
if ( ! defined( 'WP_MEMORY_LIMIT' ) && defined( 'APP_MEMORY_LIMIT' ) ) {
	define( 'WP_MEMORY_LIMIT', APP_MEMORY_LIMIT );
} else {
	define( 'WP_MEMORY_LIMIT', '40M' );
}

/**
 * Maximum memory limit
 *
 * @since 1.0.0
 * @var   string Returns the memory limit in megabytes.
 */
if ( ! defined( 'WP_MAX_MEMORY_LIMIT' ) && defined( 'APP_MAX_MEMORY_LIMIT' ) ) {
	define( 'WP_MAX_MEMORY_LIMIT', APP_MAX_MEMORY_LIMIT );
} else {
	define( 'WP_MAX_MEMORY_LIMIT', '256' );
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
 * Content directory
 *
 * Check for definition with a value.
 *
 * @since 1.0.0
 * @var   string Returns the path to the content directory.
 */
if ( ! defined( 'WP_CONTENT_DIR' ) && ( defined( 'APP_CONTENT_DIR' ) && APP_CONTENT_DIR ) ) {
	define( 'WP_CONTENT_DIR', APP_CONTENT_DIR );
} else {
	define( 'WP_CONTENT_DIR', ABSPATH . 'app-views' );
}

/**
 * Content URL
 *
 * Check for definition with a value.
 *
 * @since 1.0.0
 * @var   string Returns the URL to the content directory.
 */
if ( ! defined( 'WP_CONTENT_URL' ) && ( defined( 'APP_CONTENT_URL' ) && APP_CONTENT_URL ) ) {
	define( 'WP_CONTENT_URL', APP_CONTENT_URL );
} else {
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/app-views' );
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
 * Post revisions
 *
 * @since 1.0.0
 * @var   boolean Returns whether to allow revisions.
 */
if ( ! defined( 'WP_POST_REVISIONS' ) && defined( 'APP_POST_REVISIONS' ) ) {
	define( 'WP_POST_REVISIONS', APP_POST_REVISIONS );
} else {
	define( 'WP_POST_REVISIONS', false );
}

/**
 * Chron lock time out
 *
 * Define in seconds.
 *
 * @since Previous 3.3.0
 * @var   integer
 */
if ( ! defined( 'WP_CRON_LOCK_TIMEOUT' ) && defined( 'APP_CRON_LOCK_TIMEOUT' ) ) {
	define( 'WP_CRON_LOCK_TIMEOUT', APP_CRON_LOCK_TIMEOUT );
} else {
	define( 'WP_CRON_LOCK_TIMEOUT', 60 );
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

/**
 * Better passwords
 *
 * @since 1.0.0
 * @var   boolean
 */
if ( ! defined( 'WP_FEATURE_BETTER_PASSWORDS' ) && defined( 'APP_FEATURE_BETTER_PASSWORDS' ) ) {
	define( 'WP_FEATURE_BETTER_PASSWORDS', APP_FEATURE_BETTER_PASSWORDS );
} else {
	define( 'WP_FEATURE_BETTER_PASSWORDS', true );
}
