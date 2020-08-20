<?php
/**
 * Diff bastard child of old MediaWiki Diff Formatter.
 *
 * Basically all that remains is the table structure and some method names.
 *
 * @package App_Package
 * @subpackage Diff
 */

if ( ! class_exists( 'Text_Diff', false ) ) {
	/** Text_Diff class */
	require( APP_INC_PATH . '/Text/Diff.php' );
	/** Text_Diff_Renderer class */
	require( APP_INC_PATH . '/Text/Diff/Renderer.php' );
	/** Text_Diff_Renderer_inline class */
	require( APP_INC_PATH . '/Text/Diff/Renderer/inline.php' );
}

require( APP_INC_PATH . '/classes/includes/class-app-text-diff-renderer-table.php' );
require( APP_INC_PATH . '/classes/includes/class-app-text-diff-renderer-inline.php' );