<?php
/**
 * General purpose admin pages
 *
 * @package App_Package
 * @subpackage Administration
 * @since 1.0.0
 */

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

// Get the administration bootstrap.
if ( file_exists( APP_INC_PATH . '/backend/app-admin.php' ) ) {
	require_once( APP_INC_PATH . '/backend/app-admin.php' );
}