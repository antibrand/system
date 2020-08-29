<?php
/**
 * Install helper patch
 *
 * @package App_Package
 * @subpackage Directory_Patch
 * @since 1.0.0
 */

// Get definitions & variables from the patch directory index.
require_once( dirname( __FILE__ ) . '/index.php' );

// Get install-helper.php from its new location.
if ( file_exists( APP_INC_PATH . '/backend/install-helper.php' ) ) {
	require_once( APP_INC_PATH . '/backend/install-helper.php' );
}