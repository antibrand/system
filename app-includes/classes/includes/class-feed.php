<?php
/**
 * Feed API
 *
 * @package App_Package
 * @subpackage Feed
 */

_deprecated_file( basename( __FILE__ ), '4.7.0', 'fetch_feed()' );

if ( ! class_exists( 'SimplePie', false ) ) {
	require_once( ABSPATH . APPINC . '/classes/includes/class-simplepie.php' );
}

require_once( ABSPATH . APPINC . '/classes/includes/class-app-feed-cache.php' );
require_once( ABSPATH . APPINC . '/classes/includes/class-app-feed-cache-transient.php' );
require_once( ABSPATH . APPINC . '/classes/includes/class-app-simplepie-file.php' );
require_once( ABSPATH . APPINC . '/classes/includes/class-app-simplepie-sanitize-kses.php' );
