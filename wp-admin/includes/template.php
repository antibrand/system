<?php
/**
 * Template patch
 *
 * @package App_Package
 * @subpackage Directory_Patch
 * @since 1.0.0
 */

// Get definitions & variables from the patch directory index.
require_once( dirname( dirname( __FILE__ ) ) . '/index.php' );

// Get template.php from its new location.
if ( file_exists( APP_INC_PATH . '/backend/template.php' ) ) {
	require_once( APP_INC_PATH . '/backend/template.php' );
}
