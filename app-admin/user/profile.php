<?php
/**
 * User Profile Administration Screen.
 *
 * @package App_Package
 * @subpackage Administration
 * @since Previous 3.1.0
 */

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

// Load the administration environment.
require_once( APP_INC_PATH . '/backend/app-admin.php' );

require( APP_ADMIN_PATH . '/profile.php' );
