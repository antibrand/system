<?php
/**
 * Front to the application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells the application to load the theme.
 *
 * @package WMS
 */

/**
 * Tells the application to load the theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the application environment and template */
require( dirname( __FILE__ ) . '/wp-blog-header.php' );
