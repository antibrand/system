<?php
/**
 * Directory patch
 *
 * This directory exists only as a patch for plugins
 * that require files that had been in a directory
 * with the same name.
 *
 * @package App_Package
 * @subpackage Directory_Patch
 * @since 1.0.0
 *
 * @todo Move this to a plugin which creates the
 * directories & files for the patch.
 */

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

/**
 * This directory name
 *
 * @since 1.0.0
 * @var   string Returns the name of the directory.
 */
define( 'ADMIN_PATCH_DIR', 'wp-admin' );

/**
 * This directory path
 *
 * @since 1.0.0
 * @var   string Returns the path to the directory.
 */
define( 'ADMIN_PATCH_PATH', ABSPATH . 'ADMIN_PATCH_DIR' );

/**
 * This includes directory name
 *
 * @since 1.0.0
 * @var   string Returns the name of the directory.
 */
define( 'ADMIN_PATCH_INC_DIR', 'includes' );

/**
 * This includes directory path
 *
 * @since 1.0.0
 * @var   string Returns the path to the directory.
 */
define( 'ADMIN_PATCH_INC_PATH', ADMIN_PATCH_PATH . '/' . ADMIN_PATCH_INC_DIR );
