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

// Debug mode.
if ( ! defined( 'WP_DEBUG' ) && defined( 'APP_DEBUG' ) ) {
	define( 'WP_DEBUG', APP_DEBUG );
} else {
	define( 'WP_DEBUG', false );
}

// Installing.
if ( ! defined( 'WP_INSTALLING' ) && defined( 'APP_INSTALLING' ) ) {
	define( 'WP_INSTALLING', APP_INSTALLING );
}

// Languages directory.
if ( ! defined( 'WP_PLUGIN_DIR' ) && ( defined( 'APP_PLUGIN_DIR' ) && APP_PLUGIN_DIR ) ) {
	define( 'WP_PLUGIN_DIR', APP_PLUGIN_DIR );
} else {
	define( 'WP_PLUGIN_DIR', ABSPATH . 'app-extend/plugins' );
}

// Languages directory.
if ( ! defined( 'WP_LANG_DIR' ) && ( defined( 'APP_LANG_DIR' ) && APP_LANG_DIR ) ) {
	define( 'WP_LANG_DIR', APP_LANG_DIR );
} else {
	define( 'WP_LANG_DIR', ABSPATH . 'app-languages' );
}
