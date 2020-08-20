<?php

/**
 * Disable error reporting
 *
 * Set this to error_reporting( -1 ) for debugging
 */
error_reporting(0);

// Absolute path to the system directory.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( dirname( __FILE__ ) ) . '/' );
}

define( 'APP_INC', 'app-includes' );

require( APP_INC_PATH . '/backend/noop.php' );
require( APP_INC_PATH . '/script-loader.php' );
require( APP_INC_PATH . '/version.php' );

$load = $_GET['load'];
if ( is_array( $load ) ) {
	$load = implode( '', $load );
}
$load = preg_replace( '/[^a-z0-9,_-]+/i', '', $load );
$load = array_unique( explode( ',', $load ) );

if ( empty( $load ) ) {
	exit;
}

$rtl = ( isset( $_GET['dir'] ) && 'rtl' == $_GET['dir'] );

// 1 year.
$expires_offset = 31536000;
$out = '';

$wp_styles = new WP_Styles();
wp_default_styles( $wp_styles );

if ( isset( $_SERVER['HTTP_IF_NONE_MATCH'] ) && stripslashes( $_SERVER['HTTP_IF_NONE_MATCH'] ) === $app_version ) {

	$protocol = $_SERVER['SERVER_PROTOCOL'];
	if ( ! in_array( $protocol, [ 'HTTP/1.1', 'HTTP/2', 'HTTP/2.0' ] ) ) {
		$protocol = 'HTTP/1.0';
	}

	header( "$protocol 304 Not Modified" );

	exit();
}

foreach ( $load as $handle ) {

	if ( ! array_key_exists( $handle, $wp_styles->registered ) ) {
		continue;
	}

	$style = $wp_styles->registered[$handle];

	if ( empty( $style->src ) ) {
		continue;
	}

	$path = ABSPATH . $style->src;

	if ( $rtl && ! empty( $style->extra['rtl'] ) ) {

		// All default styles have fully independent RTL files.
		$path = str_replace( '.min.css', '-rtl.min.css', $path );
	}

	$content = get_file( $path ) . "\n";

	if ( strpos( $style->src, '/' . APP_ASSETS . '/includes/css/' ) === 0 ) {

		$content = str_replace( '../images/', '../' . APP_ASSETS . '/includes/images/', $content );
		$content = str_replace( '../js/tinymce/', '../' . APP_ASSETS . '/includes/js/tinymce/', $content );
		$content = str_replace( '../fonts/', '../' . APP_ASSETS . '/includes/fonts/', $content );
		$out    .= $content;

	} else {
		$out .= str_replace( '../images/', 'images/', $content );
	}
}

header( "Etag: $app_version" );
header( 'Content-Type: text/css; charset=UTF-8' );
header( 'Expires: ' . gmdate( "D, d M Y H:i:s", time() + $expires_offset ) . ' GMT' );
header( "Cache-Control: public, max-age=$expires_offset" );

echo $out;

exit;
