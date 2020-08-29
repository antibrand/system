<?php
/**
 * User Dashboard About administration panel.
 *
 * @package App_Package
 * @subpackage Administration
 * @since Previous 3.4.0
 */

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

// Load the administration environment.
require_once( APP_INC_PATH . '/backend/app-admin.php' );

require( APP_ADMIN_PATH . '/about.php' );
