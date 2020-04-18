<?php
/**
 * Front to the application. This file doesn't do anything, but loads
 * app-blog-header.php which does and tells the application to load the theme.
 *
 * @package App_Package
 */

/**
 * Tells the application to load the theme and output it.
 *
 * @var bool
 */
define( 'WP_USE_THEMES', true );

// Loads the application environment and template.
require( dirname( __FILE__ ) . '/app-blog-header.php' );