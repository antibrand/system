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
	require( ABSPATH . APPINC . '/Text/Diff.php' );
	/** Text_Diff_Renderer class */
	require( ABSPATH . APPINC . '/Text/Diff/Renderer.php' );
	/** Text_Diff_Renderer_inline class */
	require( ABSPATH . APPINC . '/Text/Diff/Renderer/inline.php' );
}

require( ABSPATH . APPINC . '/classes/includes/class-app-text-diff-renderer-table.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-text-diff-renderer-inline.php' );