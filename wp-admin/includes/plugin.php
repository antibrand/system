<?php
/**
 * Temporary patch
 *
 * This will be moved to a plugin which creates the
 * directories & files for the patch.
 */

// Get the system environment constants from the root directory.
require_once( dirname( dirname( dirname( __FILE__ ) ) ) . '/app-environment.php' );

// Get plugin.php from its new location.
require( APP_INC_PATH . '/backend/plugin.php' );
