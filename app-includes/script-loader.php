<?php
/**
 * Scripts and styles default loader.
 *
 * Several constants are used to manage the loading, concatenating and compression of scripts and CSS:
 * define( 'SCRIPT_DEBUG', true); loads the development (non-minified) versions of all scripts and CSS, and disables compression and concatenation,
 * define( 'CONCATENATE_SCRIPTS', false); disables compression and concatenation of scripts and CSS,
 * define( 'COMPRESS_SCRIPTS', false); disables compression of scripts,
 * define( 'COMPRESS_CSS', false); disables compression of CSS,
 * define( 'ENFORCE_GZIP', true); forces gzip for compression (default is deflate).
 *
 * The globals $concatenate_scripts, $compress_scripts and $compress_css can be set by plugins
 * to temporarily override the above settings. Also a compression test is run once and the result is saved
 * as option 'can_compress_scripts' (0/1). The test will run again if that option is deleted.
 *
 * @package App_Package
 */

// Dependency class.
require( APP_INC_PATH . '/classes/includes/class-app-dependency.php' );

// Dependencies class.
require( APP_INC_PATH . '/classes/includes/class.app-dependencies.php' );

// Scripts class.
require( APP_INC_PATH . '/classes/includes/class.app-scripts.php' );

// Scripts functions.
require( APP_INC_PATH . '/functions.app-scripts.php' );

// Styles class.
require( APP_INC_PATH . '/classes/includes/class.app-styles.php' );

// Styles functions.
require( APP_INC_PATH . '/functions.app-styles.php' );

/**
 * Register all scripts
 *
 * Localizes some of them.
 *
 * args order: `$scripts->add( 'handle', 'url', 'dependencies', 'query-string', 1 );`
 * when last arg === 1 queues the script for the footer
 *
 * @since Previous 2.6.0
 * @param WP_Scripts $scripts WP_Scripts object.
 */
function wp_default_scripts( &$scripts ) {

	// Include an unmodified $app_version'
	include( APP_INC_PATH . '/version.php' );

	$develop_src = false !== strpos( $app_version, '-src' );

	if ( ! defined( 'SCRIPT_DEBUG' ) ) {
		define( 'SCRIPT_DEBUG', $develop_src );
	}

	if ( ! $guessurl = site_url() ) {
		$guessed_url = true;
		$guessurl    = wp_guess_url();
	}

	$scripts->base_url        = $guessurl;
	$scripts->content_url     = function_exists( 'content_url' ) ? content_url() : '';
	$scripts->default_version = get_bloginfo( 'version' );
	$scripts->default_dirs    = [ '/app-assets/js/admin/', '/app-assets/js/includes/' ];

	$minify     = SCRIPT_DEBUG ? '' : '.min';
	$dev_minify = $develop_src ? '' : '.min';

	$scripts->add( 'utils', "/app-assets/js/includes/utils$minify.js" );

	did_action( 'init' ) && $scripts->localize( 'utils', 'userSettings', [
		'url'    => (string) SITECOOKIEPATH,
		'uid'    => (string) get_current_user_id(),
		'time'   => (string) time(),
		'secure' => (string) ( 'https' === parse_url( site_url(), PHP_URL_SCHEME ) ),
	] );

	$scripts->add( 'common', "/app-assets/js/admin/common$minify.js", [ 'jquery', 'hoverIntent', 'utils' ], false, 1 );

	did_action( 'init' ) && $scripts->localize( 'common', 'commonL10n', [
		'warnDelete'   => __( "You are about to permanently delete these items from your site.\nThis action cannot be undone.\n 'Cancel' to stop, 'OK' to delete." ),
		'dismiss'      => __( 'Dismiss this notice.' ),
		'collapseMenu' => __( 'Collapse Main menu' ),
		'expandMenu'   => __( 'Expand Main menu' ),
	] );

	$scripts->add( 'wp-a11y', "/app-assets/js/includes/wp-a11y$minify.js", [ 'jquery' ], false, 1 );
	$scripts->add( 'sack', "/app-assets/js/includes/tw-sack$minify.js", [], '1.6.1', 1 );
	$scripts->add( 'quicktags', "/app-assets/js/includes/quicktags$minify.js", [], false, 1 );

	did_action( 'init' ) && $scripts->localize( 'quicktags', 'quicktagsL10n', [
		'closeAllOpenTags'      => __( 'Close all open tags' ),
		'closeTags'             => __( 'close tags' ),
		'enterURL'              => __( 'Enter the URL' ),
		'enterImageURL'         => __( 'Enter the URL of the image' ),
		'enterImageDescription' => __( 'Enter a description of the image' ),
		'textdirection'         => __( 'text direction' ),
		'toggleTextdirection'   => __( 'Toggle Editor Text Direction' ),
		'dfw'                   => __( 'Distraction-free writing mode' ),
		'strong'          => __( 'Bold' ),
		'strongClose'     => __( 'Close bold tag' ),
		'em'              => __( 'Italic' ),
		'emClose'         => __( 'Close italic tag' ),
		'link'            => __( 'Insert link' ),
		'blockquote'      => __( 'Blockquote' ),
		'blockquoteClose' => __( 'Close blockquote tag' ),
		'del'             => __( 'Deleted text (strikethrough)' ),
		'delClose'        => __( 'Close deleted text tag' ),
		'ins'             => __( 'Inserted text' ),
		'insClose'        => __( 'Close inserted text tag' ),
		'image'           => __( 'Insert image' ),
		'ul'              => __( 'Bulleted list' ),
		'ulClose'         => __( 'Close bulleted list tag' ),
		'ol'              => __( 'Numbered list' ),
		'olClose'         => __( 'Close numbered list tag' ),
		'li'              => __( 'List item' ),
		'liClose'         => __( 'Close list item tag' ),
		'code'            => __( 'Code' ),
		'codeClose'       => __( 'Close code tag' ),
		'more'            => __( 'Insert Read More tag' ),
	] );

	$scripts->add( 'colorpicker', "/app-assets/js/includes/colorpicker$minify.js", [ 'prototype' ], '3517m' );
	$scripts->add( 'editor', "/app-assets/js/admin/editor$minify.js", [ 'utils','jquery' ], false, 1 );
	$scripts->add( 'wp-ajax-response', "/app-assets/js/includes/wp-ajax-response$minify.js", [ 'jquery' ], false, 1 );

	did_action( 'init' ) && $scripts->localize( 'wp-ajax-response', 'wpAjax', [
		'noPerm' => __( 'Sorry, you are not allowed to do that.' ),
		'broken' => __( 'Something went wrong.' )
	] );

	$scripts->add( 'wp-api-request', "/app-assets/js/includes/api-request$minify.js", [ 'jquery' ], false, 1 );

	// `wpApiSettings` is also used by `wp-api`, which depends on this script.
	did_action( 'init' ) && $scripts->localize( 'wp-api-request', 'wpApiSettings', [
		'root'          => esc_url_raw( get_rest_url() ),
		'nonce'         => ( wp_installing() && ! is_network() ) ? '' : wp_create_nonce( 'wp_rest' ),
		'versionString' => 'wp/v2/',
	] );

	$scripts->add( 'wp-pointer', "/app-assets/js/includes/wp-pointer$minify.js", [ 'jquery-ui-widget', 'jquery-ui-position' ], '20111129a', 1 );

	did_action( 'init' ) && $scripts->localize( 'wp-pointer', 'wpPointerL10n', [
		'dismiss' => __( 'Dismiss' ),
	] );

	$scripts->add( 'autosave', "/app-assets/js/includes/autosave$minify.js", [ 'heartbeat' ], false, 1 );
	$scripts->add( 'heartbeat', "/app-assets/js/includes/heartbeat$minify.js", [ 'jquery' ], false, 1 );

	did_action( 'init' ) && $scripts->localize( 'heartbeat', 'heartbeatSettings',

		/**
		 * Filters the Heartbeat settings.
		 *
		 * @since Previous 3.6.0
		 * @param array $settings Heartbeat settings array.
		 */
		apply_filters( 'heartbeat_settings', array() )
	);

	$scripts->add( 'wp-auth-check', "/app-assets/js/includes/wp-auth-check$minify.js", [ 'heartbeat' ], false, 1 );

	did_action( 'init' ) && $scripts->localize( 'wp-auth-check', 'authcheckL10n', [

		'beforeunload' => __( 'Your session has expired. You can log in again from this page or go to the login page.' ),

		/**
		 * Filters the authentication check interval.
		 *
		 * @since Previous 3.6.0
		 * @param int $interval The interval in which to check a user's authentication.
		 *                      Default 3 minutes in seconds, or 180.
		 */
		'interval' => apply_filters( 'wp_auth_check_interval', 3 * MINUTE_IN_SECONDS ),
	] );

	$scripts->add( 'wp-lists', "/app-assets/js/includes/wp-lists$minify.js", [ 'wp-ajax-response', 'jquery-color' ], false, 1 );

	// No longer uses or bundles Prototype or script.aculo.us. These are now pulled from an external source.
	$scripts->add( 'prototype', 'https://ajax.googleapis.com/ajax/libs/prototype/1.7.1.0/prototype.js', [], '1.7.1' );
	$scripts->add( 'scriptaculous-root', 'https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js', [ 'prototype' ], '1.9.0' );
	$scripts->add( 'scriptaculous-builder', 'https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/builder.js', [ 'scriptaculous-root' ], '1.9.0' );
	$scripts->add( 'scriptaculous-dragdrop', 'https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/dragdrop.js', [ 'scriptaculous-builder', 'scriptaculous-effects' ], '1.9.0' );
	$scripts->add( 'scriptaculous-effects', 'https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/effects.js', [ 'scriptaculous-root' ], '1.9.0' );
	$scripts->add( 'scriptaculous-slider', 'https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/slider.js', [ 'scriptaculous-effects' ], '1.9.0' );
	$scripts->add( 'scriptaculous-sound', 'https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/sound.js', [ 'scriptaculous-root' ], '1.9.0' );
	$scripts->add( 'scriptaculous-controls', 'https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/controls.js', [ 'scriptaculous-root' ], '1.9.0' );
	$scripts->add( 'scriptaculous', false, [ 'scriptaculous-dragdrop', 'scriptaculous-slider', 'scriptaculous-controls' ] );

	// Not used in core, replaced by Jcrop.js.
	$scripts->add( 'cropper', '/app-assets/js/includes/crop/cropper.js', [ 'scriptaculous-dragdrop' ] );

	// jQuery.
	$scripts->add( 'jquery', false, [ 'jquery-core' ], '3.5.1' );
	$scripts->add( 'jquery-core', "/app-assets/js/includes/jquery/jquery$minify.js", [], '3.5.1' );

	// full jQuery UI.
	$scripts->add( 'jquery-ui-core', "/app-assets/js/includes/jquery/ui/core$dev_minify.js", [ 'jquery' ], '1.11.4', 1 );
	$scripts->add( 'jquery-effects-core', "/app-assets/js/includes/jquery/ui/effect$dev_minify.js", [ 'jquery' ], '1.11.4', 1 );
	$scripts->add( 'jquery-effects-blind', "/app-assets/js/includes/jquery/ui/effect-blind$dev_minify.js", [ 'jquery-effects-core' ], '1.11.4', 1 );
	$scripts->add( 'jquery-effects-bounce', "/app-assets/js/includes/jquery/ui/effect-bounce$dev_minify.js", [ 'jquery-effects-core' ], '1.11.4', 1 );
	$scripts->add( 'jquery-effects-clip', "/app-assets/js/includes/jquery/ui/effect-clip$dev_minify.js", [ 'jquery-effects-core' ], '1.11.4', 1 );
	$scripts->add( 'jquery-effects-drop', "/app-assets/js/includes/jquery/ui/effect-drop$dev_minify.js", [ 'jquery-effects-core' ], '1.11.4', 1 );
	$scripts->add( 'jquery-effects-explode', "/app-assets/js/includes/jquery/ui/effect-explode$dev_minify.js", [ 'jquery-effects-core' ], '1.11.4', 1 );
	$scripts->add( 'jquery-effects-fade', "/app-assets/js/includes/jquery/ui/effect-fade$dev_minify.js", [ 'jquery-effects-core' ], '1.11.4', 1 );
	$scripts->add( 'jquery-effects-fold', "/app-assets/js/includes/jquery/ui/effect-fold$dev_minify.js", [ 'jquery-effects-core' ], '1.11.4', 1 );
	$scripts->add( 'jquery-effects-highlight', "/app-assets/js/includes/jquery/ui/effect-highlight$dev_minify.js", [ 'jquery-effects-core' ], '1.11.4', 1 );
	$scripts->add( 'jquery-effects-puff', "/app-assets/js/includes/jquery/ui/effect-puff$dev_minify.js", [ 'jquery-effects-core', 'jquery-effects-scale' ], '1.11.4', 1 );
	$scripts->add( 'jquery-effects-pulsate', "/app-assets/js/includes/jquery/ui/effect-pulsate$dev_minify.js", [ 'jquery-effects-core' ], '1.11.4', 1 );
	$scripts->add( 'jquery-effects-scale', "/app-assets/js/includes/jquery/ui/effect-scale$dev_minify.js", [ 'jquery-effects-core', 'jquery-effects-size' ], '1.11.4', 1 );
	$scripts->add( 'jquery-effects-shake', "/app-assets/js/includes/jquery/ui/effect-shake$dev_minify.js", [ 'jquery-effects-core' ], '1.11.4', 1 );
	$scripts->add( 'jquery-effects-size', "/app-assets/js/includes/jquery/ui/effect-size$dev_minify.js", [ 'jquery-effects-core' ], '1.11.4', 1 );
	$scripts->add( 'jquery-effects-slide', "/app-assets/js/includes/jquery/ui/effect-slide$dev_minify.js", [ 'jquery-effects-core' ], '1.11.4', 1 );
	$scripts->add( 'jquery-effects-transfer', "/app-assets/js/includes/jquery/ui/effect-transfer$dev_minify.js", [ 'jquery-effects-core' ], '1.11.4', 1 );

	$scripts->add( 'jquery-ui-accordion', "/app-assets/js/includes/jquery/ui/accordion$dev_minify.js", [ 'jquery-ui-core', 'jquery-ui-widget' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-autocomplete', "/app-assets/js/includes/jquery/ui/autocomplete$dev_minify.js", [ 'jquery-ui-menu', 'wp-a11y' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-button', "/app-assets/js/includes/jquery/ui/button$dev_minify.js", [ 'jquery-ui-core', 'jquery-ui-widget' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-datepicker', "/app-assets/js/includes/jquery/ui/datepicker$dev_minify.js", [ 'jquery-ui-core' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-dialog', "/app-assets/js/includes/jquery/ui/dialog$dev_minify.js", [ 'jquery-ui-resizable', 'jquery-ui-draggable', 'jquery-ui-button', 'jquery-ui-position' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-draggable', "/app-assets/js/includes/jquery/ui/draggable$dev_minify.js", [ 'jquery-ui-mouse' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-droppable', "/app-assets/js/includes/jquery/ui/droppable$dev_minify.js", [ 'jquery-ui-draggable' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-menu', "/app-assets/js/includes/jquery/ui/menu$dev_minify.js", [ 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-position' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-mouse', "/app-assets/js/includes/jquery/ui/mouse$dev_minify.js", [ 'jquery-ui-core', 'jquery-ui-widget' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-position', "/app-assets/js/includes/jquery/ui/position$dev_minify.js", [ 'jquery' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-progressbar', "/app-assets/js/includes/jquery/ui/progressbar$dev_minify.js", [ 'jquery-ui-core', 'jquery-ui-widget' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-resizable', "/app-assets/js/includes/jquery/ui/resizable$dev_minify.js", [ 'jquery-ui-mouse' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-selectable', "/app-assets/js/includes/jquery/ui/selectable$dev_minify.js", [ 'jquery-ui-mouse' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-selectmenu', "/app-assets/js/includes/jquery/ui/selectmenu$dev_minify.js", [ 'jquery-ui-menu' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-slider', "/app-assets/js/includes/jquery/ui/slider$dev_minify.js", [ 'jquery-ui-mouse' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-sortable', "/app-assets/js/includes/jquery/ui/sortable$dev_minify.js", [ 'jquery-ui-mouse' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-spinner', "/app-assets/js/includes/jquery/ui/spinner$dev_minify.js", [ 'jquery-ui-button' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-tabs', "/app-assets/js/includes/jquery/ui/tabs$dev_minify.js", [ 'jquery-ui-core', 'jquery-ui-widget' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-tooltip', "/app-assets/js/includes/jquery/ui/tooltip$dev_minify.js", [ 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-position' ], '1.11.4', 1 );
	$scripts->add( 'jquery-ui-widget', "/app-assets/js/includes/jquery/ui/widget$dev_minify.js", [ 'jquery' ], '1.11.4', 1 );

	// jQuery plugins, non-jQuery UI.
	$scripts->add( 'app-tabs', "/app-assets/js/includes/app-tabs$dev_minify.js", [ 'jquery' ], '', 1 );

	// Strings for 'jquery-ui-autocomplete' live region messages.
	did_action( 'init' ) && $scripts->localize( 'jquery-ui-autocomplete', 'uiAutocompleteL10n', [
		'noResults'    => __( 'No results found.' ),
		'oneResult'    => __( '1 result found. Use up and down arrow keys to navigate.' ),
		'manyResults'  => __( '%d results found. Use up and down arrow keys to navigate.' ),
		'itemSelected' => __( 'Item selected.' ),
	] );

	// jQuery plugins
	$scripts->add( 'jquery-color', "/app-assets/js/includes/jquery/jquery.color.min.js", [ 'jquery' ], '2.1.1', 1 );
	$scripts->add( 'schedule', '/app-assets/js/includes/jquery/jquery.schedule.js', [ 'jquery' ], '20m', 1 );
	$scripts->add( 'jquery-query', "/app-assets/js/includes/jquery/jquery.query.js", [ 'jquery' ], '2.1.7', 1 );
	$scripts->add( 'jquery-serialize-object', "/app-assets/js/includes/jquery/jquery.serialize-object.js", [ 'jquery' ], '0.2', 1 );
	$scripts->add( 'jquery-hotkeys', "/app-assets/js/includes/jquery/jquery.hotkeys$minify.js", [ 'jquery' ], '0.0.2m', 1 );
	$scripts->add( 'jquery-table-hotkeys', "/app-assets/js/includes/jquery/jquery.table-hotkeys$minify.js", [ 'jquery', 'jquery-hotkeys' ], false, 1 );
	$scripts->add( 'jquery-touch-punch', "/app-assets/js/includes/jquery/jquery.ui.touch-punch.js", [ 'jquery-ui-widget', 'jquery-ui-mouse' ], '0.2.2', 1 );

	// Not used any more, registered for backwards compatibility.
	$scripts->add( 'suggest', "/app-assets/js/includes/jquery/suggest$minify.js", [ 'jquery' ], '1.1-20110113', 1 );

	// Masonry v2 depended on jQuery. v3 does not. The older jquery-masonry handle is a shiv.
	// It sets jQuery as a dependency, as the theme may have been implicitly loading it this way.
	$scripts->add( 'imagesloaded', "/app-assets/js/includes/imagesloaded.min.js", [], '3.2.0', 1 );
	$scripts->add( 'masonry', "/app-assets/js/includes/masonry.min.js", [ 'imagesloaded' ], '3.3.2', 1 );
	$scripts->add( 'jquery-masonry', "/app-assets/js/includes/jquery/jquery.masonry$dev_minify.js", [ 'jquery', 'masonry' ], '3.1.2b', 1 );

	$scripts->add( 'thickbox', "/app-assets/js/includes/thickbox/thickbox.js", [ 'jquery' ], '3.1-20121105', 1 );

	did_action( 'init' ) && $scripts->localize( 'thickbox', 'thickboxL10n', [
		'next'             => __( 'Next &gt;' ),
		'prev'             => __( '&lt; Prev' ),
		'image'            => __( 'Image' ),
		'of'               => __( 'of' ),
		'close'            => __( 'Close' ),
		'noiframes'        => __( 'This feature requires inline frames. You have iframes disabled or your browser does not support them.' ),
		'loadingAnimation' => app_assets_url( 'js/includes/thickbox/loadingAnimation.gif' ),
	] );

	$scripts->add( 'swfobject', "/app-assets/js/includes/swfobject.js", [], '2.2-20120417' );

	// Error messages for Plupload.
	$uploader_l10n = [
		'queue_limit_exceeded'      => __( 'You have attempted to queue too many files.' ),
		'file_exceeds_size_limit'   => __( '%s exceeds the maximum upload size for this site.' ),
		'zero_byte_file'            => __( 'This file is empty. Please try another.' ),
		'invalid_filetype'          => __( 'Sorry, this file type is not permitted for security reasons.' ),
		'not_an_image'              => __( 'This file is not an image. Please try another.' ),
		'image_memory_exceeded'     => __( 'Memory exceeded. Please try another smaller file.' ),
		'image_dimensions_exceeded' => __( 'This is larger than the maximum size. Please try another.' ),
		'default_error'             => __( 'An error occurred in the upload. Please try again later.' ),
		'missing_upload_url'        => __( 'There was a configuration error. Please contact the server administrator.' ),
		'upload_limit_exceeded'     => __( 'You may only upload 1 file.' ),
		'http_error'                => __( 'HTTP error.' ),
		'upload_failed'             => __( 'Upload failed.' ),
		'big_upload_failed'         => __( 'Please try uploading this file with the %1$sbrowser uploader%2$s.' ),
		'big_upload_queued'         => __( '%s exceeds the maximum upload size for the multi-file uploader when used in your browser.' ),
		'io_error'                  => __( 'IO error.' ),
		'security_error'            => __( 'Security error.' ),
		'file_cancelled'            => __( 'File canceled.' ),
		'upload_stopped'            => __( 'Upload stopped.' ),
		'dismiss'                   => __( 'Dismiss' ),
		'crunching'                 => __( 'Crunching&hellip;' ),
		'deleted'                   => __( 'moved to the trash.' ),
		'error_uploading'           => __( '&#8220;%s&#8221; has failed to upload.' )
	];

	$scripts->add( 'moxiejs', "/app-assets/js/includes/plupload/moxie$minify.js", [], '1.3.5' );
	$scripts->add( 'plupload', "/app-assets/js/includes/plupload/plupload$minify.js", [ 'moxiejs' ], '2.1.9' );
	$scripts->add( 'plupload-handlers', "/app-assets/js/includes/plupload/handlers$minify.js", [ 'plupload', 'jquery' ] );

	did_action( 'init' ) && $scripts->localize( 'plupload-handlers', 'pluploadL10n', $uploader_l10n );

	$scripts->add( 'wp-plupload', "/app-assets/js/includes/plupload/wp-plupload$minify.js", [ 'plupload', 'jquery', 'json2', 'media-models' ], false, 1 );

	did_action( 'init' ) && $scripts->localize( 'wp-plupload', 'pluploadL10n', $uploader_l10n );

	// Keep 'swfupload' for back-compat.
	$scripts->add( 'swfupload', '/app-assets/js/includes/swfupload/swfupload.js', [], '2201-20110113' );
	$scripts->add( 'swfupload-all', false, [ 'swfupload' ], '2201' );
	$scripts->add( 'swfupload-handlers', "/app-assets/js/includes/swfupload/handlers$minify.js", [ 'swfupload-all', 'jquery' ], '2201-20110524' );

	did_action( 'init' ) && $scripts->localize( 'swfupload-handlers', 'swfuploadL10n', $uploader_l10n );

	$scripts->add( 'comment-reply', "/app-assets/js/includes/comment-reply$minify.js", [], false, 1 );
	$scripts->add( 'json2', "/app-assets/js/includes/json2$minify.js", [], '2015-05-03' );
	$scripts->add( 'underscore', "/app-assets/js/includes/underscore$dev_minify.js", [], '1.8.3', 1 );
	$scripts->add( 'backbone', "/app-assets/js/includes/backbone$dev_minify.js", [ 'underscore','jquery' ], '1.2.3', 1 );
	$scripts->add( 'wp-util', "/app-assets/js/includes/wp-util$minify.js", [ 'underscore', 'jquery' ], false, 1 );

	did_action( 'init' ) && $scripts->localize( 'wp-util', '_wpUtilSettings', [
		'ajax' => [
			'url' => admin_url( 'admin-ajax.php', 'relative' ),
		],
	] );

	$scripts->add( 'wp-sanitize', "/app-assets/js/includes/wp-sanitize$minify.js", [ 'jquery' ], false, 1 );
	$scripts->add( 'wp-backbone', "/app-assets/js/includes/wp-backbone$minify.js", [ 'backbone', 'wp-util' ], false, 1 );
	$scripts->add( 'revisions', "/app-assets/js/admin/revisions$minify.js", [ 'wp-backbone', 'jquery-ui-slider', 'hoverIntent' ], false, 1 );
	$scripts->add( 'imgareaselect', "/app-assets/js/includes/imgareaselect/jquery.imgareaselect$minify.js", [ 'jquery' ], false, 1 );
	$scripts->add( 'mediaelement', false, [ 'jquery', 'mediaelement-core', 'mediaelement-migrate' ], '4.2.6-78496d1' );
	$scripts->add( 'mediaelement-core', "/app-assets/js/includes/mediaelement/mediaelement-and-player$minify.js", [], '4.2.6-78496d1', 1 );
	$scripts->add( 'mediaelement-migrate', "/app-assets/js/includes/mediaelement/mediaelement-migrate$minify.js", [], false, 1);

	did_action( 'init' ) && $scripts->add_inline_script( 'mediaelement-core', sprintf( 'var mejsL10n = %s;', wp_json_encode( [

		'language' => strtolower( strtok( is_admin() ? get_user_locale() : get_locale(), '_-' ) ),
		'strings'  => [
			'mejs.install-flash'       => __( 'You are using a browser that does not have Flash player enabled or installed. Please turn on your Flash player plugin or download the latest version from https://get.adobe.com/flashplayer/' ),
			'mejs.fullscreen-off'      => __( 'Turn off Fullscreen' ),
			'mejs.fullscreen-on'       => __( 'Go Fullscreen' ),
			'mejs.download-video'      => __( 'Download Video' ),
			'mejs.fullscreen'          => __( 'Fullscreen' ),
			'mejs.time-jump-forward'   => [
				__( 'Jump forward 1 second' ),
				__( 'Jump forward %1 seconds' )
			],
			'mejs.loop'                => __( 'Toggle Loop' ),
			'mejs.play'                => __( 'Play' ),
			'mejs.pause'               => __( 'Pause' ),
			'mejs.close'               => __( 'Close' ),
			'mejs.time-slider'         => __( 'Time Slider' ),
			'mejs.time-help-text'      => __( 'Use Left/Right Arrow keys to advance one second, Up/Down arrows to advance ten seconds.' ),
			'mejs.time-skip-back'      => [
				__( 'Skip back 1 second' ),
				__( 'Skip back %1 seconds' )
			],
			'mejs.captions-subtitles'  => __( 'Captions/Subtitles' ),
			'mejs.captions-chapters'   => __( 'Chapters' ),
			'mejs.none'                => __( 'None' ),
			'mejs.mute-toggle'         => __( 'Mute Toggle' ),
			'mejs.volume-help-text'    => __( 'Use Up/Down Arrow keys to increase or decrease volume.' ),
			'mejs.unmute'              => __( 'Unmute' ),
			'mejs.mute'                => __( 'Mute' ),
			'mejs.volume-slider'       => __( 'Volume Slider' ),
			'mejs.video-player'        => __( 'Video Player' ),
			'mejs.audio-player'        => __( 'Audio Player' ),
			'mejs.ad-skip'             => __( 'Skip ad' ),
			'mejs.ad-skip-info'        => [
				__( 'Skip in 1 second' ),
				__( 'Skip in %1 seconds' )
			],
			'mejs.source-chooser'      => __( 'Source Chooser' ),
			'mejs.stop'                => __( 'Stop' ),
			'mejs.speed-rate'          => __( 'Speed Rate' ),
			'mejs.live-broadcast'      => __( 'Live Broadcast' ),
			'mejs.afrikaans'           => __( 'Afrikaans' ),
			'mejs.albanian'            => __( 'Albanian' ),
			'mejs.arabic'              => __( 'Arabic' ),
			'mejs.belarusian'          => __( 'Belarusian' ),
			'mejs.bulgarian'           => __( 'Bulgarian' ),
			'mejs.catalan'             => __( 'Catalan' ),
			'mejs.chinese'             => __( 'Chinese' ),
			'mejs.chinese-simplified'  => __( 'Chinese (Simplified)' ),
			'mejs.chinese-traditional' => __( 'Chinese (Traditional)' ),
			'mejs.croatian'            => __( 'Croatian' ),
			'mejs.czech'               => __( 'Czech' ),
			'mejs.danish'              => __( 'Danish' ),
			'mejs.dutch'               => __( 'Dutch' ),
			'mejs.english'             => __( 'English' ),
			'mejs.estonian'            => __( 'Estonian' ),
			'mejs.filipino'            => __( 'Filipino' ),
			'mejs.finnish'             => __( 'Finnish' ),
			'mejs.french'              => __( 'French' ),
			'mejs.galician'            => __( 'Galician' ),
			'mejs.german'              => __( 'German' ),
			'mejs.greek'               => __( 'Greek' ),
			'mejs.haitian-creole'      => __( 'Haitian Creole' ),
			'mejs.hebrew'              => __( 'Hebrew' ),
			'mejs.hindi'               => __( 'Hindi' ),
			'mejs.hungarian'           => __( 'Hungarian' ),
			'mejs.icelandic'           => __( 'Icelandic' ),
			'mejs.indonesian'          => __( 'Indonesian' ),
			'mejs.irish'               => __( 'Irish' ),
			'mejs.italian'             => __( 'Italian' ),
			'mejs.japanese'            => __( 'Japanese' ),
			'mejs.korean'              => __( 'Korean' ),
			'mejs.latvian'             => __( 'Latvian' ),
			'mejs.lithuanian'          => __( 'Lithuanian' ),
			'mejs.macedonian'          => __( 'Macedonian' ),
			'mejs.malay'               => __( 'Malay' ),
			'mejs.maltese'             => __( 'Maltese' ),
			'mejs.norwegian'           => __( 'Norwegian' ),
			'mejs.persian'             => __( 'Persian' ),
			'mejs.polish'              => __( 'Polish' ),
			'mejs.portuguese'          => __( 'Portuguese' ),
			'mejs.romanian'            => __( 'Romanian' ),
			'mejs.russian'             => __( 'Russian' ),
			'mejs.serbian'             => __( 'Serbian' ),
			'mejs.slovak'              => __( 'Slovak' ),
			'mejs.slovenian'           => __( 'Slovenian' ),
			'mejs.spanish'             => __( 'Spanish' ),
			'mejs.swahili'             => __( 'Swahili' ),
			'mejs.swedish'             => __( 'Swedish' ),
			'mejs.tagalog'             => __( 'Tagalog' ),
			'mejs.thai'                => __( 'Thai' ),
			'mejs.turkish'             => __( 'Turkish' ),
			'mejs.ukrainian'           => __( 'Ukrainian' ),
			'mejs.vietnamese'          => __( 'Vietnamese' ),
			'mejs.welsh'               => __( 'Welsh' ),
			'mejs.yiddish'             => __( 'Yiddish' ),
		],
	] ) ), 'before' );


	$scripts->add( 'mediaelement-vimeo', "/app-assets/js/includes/mediaelement/renderers/vimeo.min.js", [ 'mediaelement' ], '4.2.6-78496d1', 1 );
	$scripts->add( 'wp-mediaelement', "/app-assets/js/includes/mediaelement/wp-mediaelement$minify.js", [ 'mediaelement' ], false, 1 );

	$mejs_settings = [
		'pluginPath'  => app_assets_url( 'js/includes/mediaelement/', 'relative' ),
		'classPrefix' => 'mejs-',
		'stretching'  => 'responsive',
	];

	did_action( 'init' ) && $scripts->localize( 'mediaelement', '_wpmejsSettings',

		/**
		 * Filters the MediaElement configuration settings.
		 *
		 * @since Previous 4.4.0
		 * @param array $mejs_settings MediaElement settings array.
		 */
		apply_filters( 'mejs_settings', $mejs_settings )
	);

	$scripts->add( 'app-codemirror', '/app-assets/js/includes/vendor/codemirror/codemirror.min.js', [], '5.29.1-alpha-ee20357' );
	$scripts->add( 'csslint', '/app-assets/js/includes/vendor/codemirror/csslint.js', [], '1.0.5' );
	$scripts->add( 'jshint', '/app-assets/js/includes/vendor/codemirror/jshint.js', [], '2.9.5.999' );
	$scripts->add( 'jsonlint', '/app-assets/js/includes/vendor/codemirror/jsonlint.js', [], '1.6.2' );
	$scripts->add( 'htmlhint', '/app-assets/js/includes/vendor/codemirror/htmlhint.js', [], '0.9.14-xwp' );
	$scripts->add( 'htmlhint-kses', '/app-assets/js/includes/vendor/codemirror/htmlhint-kses.js', [ 'htmlhint' ] );
	$scripts->add( 'code-editor', "/app-assets/js/admin/code-editor$minify.js", [ 'jquery', 'app-codemirror', 'underscore' ] );
	$scripts->add( 'wp-theme-plugin-editor', "/app-assets/js/admin/theme-plugin-editor$minify.js", [ 'wp-util', 'wp-sanitize', 'jquery', 'jquery-ui-core', 'wp-a11y', 'underscore' ] );

	did_action( 'init' ) && $scripts->add_inline_script( 'wp-theme-plugin-editor', sprintf( 'wp.themePluginEditor.l10n = %s;', wp_json_encode( [

		'saveAlert' => __( 'The changes you made will be lost if you navigate away from this page.' ),
		'saveError' => __( 'Something went wrong. Your change may not have been saved. Please try again. There is also a chance that you may need to manually fix and upload the file over FTP.' ),
		'lintError' => [
			'singular' => _n( 'There is %d error which must be fixed before you can update this file.', 'There are %d errors which must be fixed before you can update this file.', 1 ),
			'plural'   => _n( 'There is %d error which must be fixed before you can update this file.', 'There are %d errors which must be fixed before you can update this file.', 2 )
		]
	] ) ) );

	$scripts->add( 'wp-playlist', "/app-assets/js/includes/mediaelement/wp-playlist$minify.js", [ 'wp-util', 'backbone', 'mediaelement' ], false, 1 );
	$scripts->add( 'zxcvbn-async', "/app-assets/js/includes/zxcvbn-async$minify.js", [], '1.0' );

	did_action( 'init' ) && $scripts->localize( 'zxcvbn-async', '_zxcvbnSettings', [
		'src' => empty( $guessed_url ) ? app_assets_url( 'js/includes/zxcvbn.min.js' ) : $scripts->base_url . '/app-assets/js/includes/zxcvbn.min.js',
	] );

	$scripts->add( 'password-strength-meter', "/app-assets/js/admin/password-strength-meter$minify.js", [ 'jquery', 'zxcvbn-async' ], false, 1 );

	did_action( 'init' ) && $scripts->localize( 'password-strength-meter', 'pwsL10n', [
		'unknown'  => _x( 'Password strength unknown', 'password strength' ),
		'short'    => _x( 'Very weak', 'password strength' ),
		'bad'      => _x( 'Weak', 'password strength' ),
		'good'     => _x( 'Medium', 'password strength' ),
		'strong'   => _x( 'Strong', 'password strength' ),
		'mismatch' => _x( 'Mismatch', 'password mismatch' ),
	] );

	$scripts->add( 'user-profile', "/app-assets/js/admin/user-profile$minify.js", [ 'jquery', 'password-strength-meter', 'wp-util' ], false, 1 );

	did_action( 'init' ) && $scripts->localize( 'user-profile', 'userProfileL10n', [
		'warn'     => __( 'Your new password has not been saved.' ),
		'warnWeak' => __( 'Confirm use of weak password' ),
		'show'     => __( 'Show' ),
		'hide'     => __( 'Hide' ),
		'cancel'   => __( 'Cancel' ),
		'ariaShow' => esc_attr__( 'Show password' ),
		'ariaHide' => esc_attr__( 'Hide password' ),
	] );

	$scripts->add( 'language-chooser', "/app-assets/js/admin/language-chooser$minify.js", [ 'jquery' ], false, 1 );
	$scripts->add( 'user-suggest', "/app-assets/js/admin/user-suggest$minify.js", [ 'jquery-ui-autocomplete' ], false, 1 );
	$scripts->add( 'admin-bar', "/app-assets/js/includes/admin-bar$minify.js", [], false, 1 );
	$scripts->add( 'wplink', "/app-assets/js/includes/wplink$minify.js", [ 'jquery', 'wp-a11y' ], false, 1 );

	did_action( 'init' ) && $scripts->localize( 'wplink', 'wpLinkL10n', [
		'title'          => __( 'Insert/edit link' ),
		'update'         => __( 'Update' ),
		'save'           => __( 'Add Link' ),
		'noTitle'        => __( '(no title)' ),
		'noMatchesFound' => __( 'No results found.' ),
		'linkSelected'   => __( 'Link selected.' ),
		'linkInserted'   => __( 'Link inserted.' ),
	] );

	$scripts->add( 'wpdialogs', "/app-assets/js/includes/wpdialog$minify.js", [ 'jquery-ui-dialog' ], false, 1 );
	$scripts->add( 'word-count', "/app-assets/js/admin/word-count$minify.js", [], false, 1 );
	$scripts->add( 'media-upload', "/app-assets/js/admin/media-upload$minify.js", [ 'thickbox', 'shortcode' ], false, 1 );
	$scripts->add( 'hoverIntent', "/app-assets/js/includes/hoverIntent$minify.js", [ 'jquery' ], '1.8.1', 1 );
	$scripts->add( 'customize-base',     "/app-assets/js/includes/customize-base$minify.js", [ 'jquery', 'json2', 'underscore' ], false, 1 );
	$scripts->add( 'customize-loader',   "/app-assets/js/includes/customize-loader$minify.js", [ 'customize-base' ], false, 1 );
	$scripts->add( 'customize-preview',  "/app-assets/js/includes/customize-preview$minify.js", [ 'wp-a11y', 'customize-base' ], false, 1 );
	$scripts->add( 'customize-models',   "/app-assets/js/includes/customize-models.js", [ 'underscore', 'backbone' ], false, 1 );
	$scripts->add( 'customize-views',    "/app-assets/js/includes/customize-views.js", [ 'jquery', 'underscore', 'imgareaselect', 'customize-models', 'media-editor', 'media-views' ], false, 1 );
	$scripts->add( 'customize-controls', "/app-assets/js/admin/customize-controls$minify.js", [ 'customize-base', 'wp-a11y', 'wp-util', 'jquery-ui-core' ], false, 1 );

	did_action( 'init' ) && $scripts->localize( 'customize-controls', '_wpCustomizeControlsL10n', [
		'activate'           => __( 'Activate &amp; Publish' ),
		'save'               => __( 'Save &amp; Publish' ),
		'publish'            => __( 'Publish' ),
		'published'          => __( 'Published' ),
		'saveDraft'          => __( 'Save Draft' ),
		'draftSaved'         => __( 'Draft Saved' ),
		'updating'           => __( 'Updating' ),
		'schedule'           => _x( 'Schedule', 'customizer changeset action/button label' ),
		'scheduled'          => _x( 'Scheduled', 'customizer changeset status' ),
		'invalid'            => __( 'Invalid' ),
		'saveBeforeShare'    => __( 'Please save your changes in order to share the preview.' ),
		'futureDateError'    => __( 'You must supply a future date to schedule.' ),
		'saveAlert'          => __( 'The changes you made will be lost if you navigate away from this page.' ),
		'saved'              => __( 'Saved' ),
		'cancel'             => __( 'Cancel' ),
		'close'              => __( 'Close' ),
		'action'             => __( 'Action' ),
		'discardChanges'     => __( 'Discard changes' ),
		'cheatin'            => __( 'Something went wrong.' ),
		'notAllowedHeading'  => __( 'You need a higher level of permission.' ),
		'notAllowed'         => __( 'Sorry, you are not allowed to customize this site.' ),
		'previewIframeTitle' => __( 'Site Preview' ),
		'loginIframeTitle'   => __( 'Session expired' ),
		'collapseSidebar'    => _x( 'Hide Controls', 'label for hide controls button without length constraints' ),
		'expandSidebar'      => _x( 'Show Controls', 'label for hide controls button without length constraints' ),
		'untitledBlogName'   => __( '(Untitled)' ),
		'unknownRequestFail' => __( 'Looks like something&#8217;s gone wrong. Wait a couple seconds, and then try again.' ),
		'themeDownloading'   => __( 'Downloading your new theme&hellip;' ),
		'themePreviewWait'   => __( 'Setting up your live preview. This may take a bit.' ),
		'revertingChanges'   => __( 'Reverting unpublished changes&hellip;' ),
		'trashConfirm'       => __( 'Are you sure you&#8217;d like to discard your unpublished changes?' ),
		'takenOverMessage'   => __( '%s has taken over and is currently customizing.' ),
		'autosaveNotice'     => __( 'There is a more recent autosave of your changes than the one you are previewing. <a href="%s">Restore the autosave</a>' ),
		'videoHeaderNotice'  => __( 'This theme doesn&#8217;t support video headers on this page. Navigate to the front page or another page that supports video headers.' ),

		// Used for overriding the file types allowed in plupload.
		'allowedFiles'   => __( 'Allowed Files' ),
		'customCssError' => [
			'singular' => _n( 'There is %d error which must be fixed before you can save.', 'There are %d errors which must be fixed before you can save.', 1 ),
			'plural'   => _n( 'There is %d error which must be fixed before you can save.', 'There are %d errors which must be fixed before you can save.', 2 )
		],
		'pageOnFrontError' => __( 'Homepage and posts page must be different.' ),
		'saveBlockedError' => [
			'singular' => _n( 'Unable to save due to %s invalid setting.', 'Unable to save due to %s invalid settings.', 1 ),
			'plural'   => _n( 'Unable to save due to %s invalid setting.', 'Unable to save due to %s invalid settings.', 2 )
		],
		'scheduleDescription'     => __( 'Schedule your customization changes to publish ("go live") at a future date.' ),
		'themePreviewUnavailable' => __( 'Sorry, you can&#8217;t preview new themes when you have changes scheduled or saved as a draft. Please publish your changes, or wait until they publish to preview new themes.' ),

		'themeInstallUnavailable' => sprintf(
			__( 'You won&#8217;t be able to install new themes from here yet since your install requires SFTP credentials. For now, please <a href="%s">add themes in the admin</a>.' ),
			esc_url( admin_url( 'theme-install.php' ) )
		),
		'publishSettings' => __( 'Publish Settings' ),
		'invalidDate'     => __( 'Invalid date.' ),
		'invalidValue'    => __( 'Invalid value.' ),
	] );

	$scripts->add( 'customize-selective-refresh', "/app-assets/js/includes/customize-selective-refresh$minify.js", [ 'jquery', 'wp-util', 'customize-preview' ], false, 1 );
	$scripts->add( 'customize-widgets', "/app-assets/js/admin/customize-widgets$minify.js", [ 'jquery', 'jquery-ui-sortable', 'jquery-ui-droppable', 'wp-backbone', 'customize-controls' ], false, 1 );
	$scripts->add( 'customize-preview-widgets', "/app-assets/js/includes/customize-preview-widgets$minify.js", [ 'jquery', 'wp-util', 'customize-preview', 'customize-selective-refresh' ], false, 1 );
	$scripts->add( 'customize-nav-menus', "/app-assets/js/admin/customize-nav-menus$minify.js", [ 'jquery', 'wp-backbone', 'customize-controls', 'accordion', 'nav-menu' ], false, 1 );
	$scripts->add( 'customize-preview-nav-menus', "/app-assets/js/includes/customize-preview-nav-menus$minify.js", [ 'jquery', 'wp-util', 'customize-preview', 'customize-selective-refresh' ], false, 1 );
	$scripts->add( 'wp-custom-header', "/app-assets/js/includes/wp-custom-header$minify.js", [ 'wp-a11y' ], false, 1 );
	$scripts->add( 'accordion', "/app-assets/js/admin/accordion$minify.js", [ 'jquery' ], false, 1 );
	$scripts->add( 'shortcode', "/app-assets/js/includes/shortcode$minify.js", [ 'underscore' ], false, 1 );
	$scripts->add( 'media-models', "/app-assets/js/includes/media-models$minify.js", [ 'wp-backbone' ], false, 1 );

	did_action( 'init' ) && $scripts->localize( 'media-models', '_wpMediaModelsL10n', [
		'settings' => [
			'ajaxurl' => admin_url( 'admin-ajax.php', 'relative' ),
			'post'    => array( 'id' => 0 )
		],
	] );

	$scripts->add( 'wp-embed', "/app-assets/js/includes/wp-embed$minify.js" );

	// To enqueue media-views or media-editor, call wp_enqueue_media().
	// Both rely on numerous settings, styles, and templates to operate correctly.
	$scripts->add( 'media-views',  "/app-assets/js/includes/media-views$minify.js",  [ 'utils', 'media-models', 'wp-plupload', 'jquery-ui-sortable', 'wp-mediaelement', 'wp-api-request' ], false, 1 );
	$scripts->add( 'media-editor', "/app-assets/js/includes/media-editor$minify.js", [ 'shortcode', 'media-views' ], false, 1 );
	$scripts->add( 'media-audiovideo', "/app-assets/js/includes/media-audiovideo$minify.js", [ 'media-editor' ], false, 1 );
	$scripts->add( 'mce-view', "/app-assets/js/includes/mce-view$minify.js", [ 'shortcode', 'jquery', 'media-views', 'media-audiovideo' ], false, 1 );
	$scripts->add( 'wp-api', "/app-assets/js/includes/wp-api$minify.js", [ 'jquery', 'backbone', 'underscore', 'wp-api-request' ], false, 1 );

	if ( is_admin() ) {

		$scripts->add( 'admin-tags', "/app-assets/js/admin/tags$minify.js", [ 'jquery', 'wp-ajax-response' ], false, 1 );

		did_action( 'init' ) && $scripts->localize( 'admin-tags', 'tagsl10n', [
			'noPerm' => __( 'Sorry, you are not allowed to do that.' ),
			'broken' => __( 'Something went wrong.' )
		] );

		$scripts->add( 'admin-comments', "/app-assets/js/admin/edit-comments$minify.js", [ 'wp-lists', 'quicktags', 'jquery-query' ], false, 1 );

		did_action( 'init' ) && $scripts->localize( 'admin-comments', 'adminCommentsL10n', [
			'hotkeys_highlight_first' => isset( $_GET['hotkeys_highlight_first']),
			'hotkeys_highlight_last'  => isset( $_GET['hotkeys_highlight_last']),
			'replyApprove'            => __( 'Approve and Reply' ),
			'reply'                   => __( 'Reply' ),
			'warnQuickEdit'           => __( "Are you sure you want to edit this comment?\nThe changes you made will be lost." ),
			'warnCommentChanges'      => __( "Are you sure you want to do this?\nThe comment changes you made will be lost." ),
			'docTitleComments'        => __( 'Comments' ),
			'docTitleCommentsCount' => __( 'Comments (%s)' )
		] );

		$scripts->add( 'xfn', "/app-assets/js/admin/xfn$minify.js", [ 'jquery' ], false, 1 );

		did_action( 'init' ) && $scripts->localize(
			'xfn', 'privacyToolsL10n', [
				'noDataFound'     => __( 'No personal data was found for this user.' ),
				'foundAndRemoved' => __( 'All of the personal data found for this user was erased.' ),
				'noneRemoved'     => __( 'Personal data was found for this user but was not erased.' ),
				'someNotRemoved'  => __( 'Personal data was found for this user but some of the personal data found was not erased.' ),
				'removalError'    => __( 'An error occurred while attempting to find and erase personal data.' ),
				'noExportFile'    => __( 'No personal data export file was generated.' ),
				'exportError'     => __( 'An error occurred while attempting to export personal data.' ),
			]
		);

		$scripts->add( 'postbox', "/app-assets/js/admin/postbox$minify.js", [ 'jquery-ui-sortable' ], false, 1 );

		did_action( 'init' ) && $scripts->localize( 'postbox', 'postBoxL10n', [
			'postBoxEmptyString' => __( 'Drag boxes here' ),
		] );

		$scripts->add( 'tags-box', "/app-assets/js/admin/tags-box$minify.js", [ 'jquery', 'tags-suggest' ], false, 1 );
		$scripts->add( 'tags-suggest', "/app-assets/js/admin/tags-suggest$minify.js", [ 'jquery-ui-autocomplete', 'wp-a11y' ], false, 1 );

		did_action( 'init' ) && $scripts->localize( 'tags-suggest', 'tagsSuggestL10n', [
			'tagDelimiter' => _x( ',', 'tag delimiter' ),
			'removeTerm'   => __( 'Remove term:' ),
			'termSelected' => __( 'Term selected.' ),
			'termAdded'    => __( 'Term added.' ),
			'termRemoved'  => __( 'Term removed.' ),
		] );

		$scripts->add( 'post', "/app-assets/js/admin/post$minify.js", [ 'suggest', 'wp-lists', 'postbox', 'tags-box', 'underscore', 'word-count', 'wp-a11y' ], false, 1 );

		did_action( 'init' ) && $scripts->localize( 'post', 'postL10n', [
			'ok'                 => __( 'OK' ),
			'cancel'             => __( 'Cancel' ),
			'publishOn'          => __( 'Publish on:' ),
			'publishOnFuture'    =>  __( 'Schedule for:' ),
			'publishOnPast'      => __( 'Published on:' ),
			'dateFormat'         => __( '%1$s %2$s, %3$s @ %4$s:%5$s' ),
			'showcomm'           => __( 'Show more comments' ),
			'endcomm'            => __( 'No more comments found.' ),
			'publish'            => __( 'Publish' ),
			'schedule'           => _x( 'Schedule', 'post action/button label' ),
			'update'             => __( 'Update' ),
			'savePending'        => __( 'Save as Pending' ),
			'saveDraft'          => __( 'Save Draft' ),
			'private'            => __( 'Private' ),
			'public'             => __( 'Public' ),
			'publicSticky'       => __( 'Public, Sticky' ),
			'password'           => __( 'Password Protected' ),
			'privatelyPublished' => __( 'Privately Published' ),
			'published'          => __( 'Published' ),
			'saveAlert'          => __( 'The changes you made will be lost if you navigate away from this page.' ),
			'savingText'         => __( 'Saving Draft&#8230;' ),
			'permalinkSaved'     => __( 'Permalink saved' ),
		] );

		$scripts->add( 'editor-expand', "/app-assets/js/admin/editor-expand$minify.js", [ 'jquery', 'underscore' ], false, 1 );
		$scripts->add( 'link', "/app-assets/js/admin/link$minify.js", [ 'wp-lists', 'postbox' ], false, 1 );
		$scripts->add( 'comment', "/app-assets/js/admin/comment$minify.js", [ 'jquery', 'postbox' ] );
		$scripts->add_data( 'comment', 'group', 1 );

		did_action( 'init' ) && $scripts->localize( 'comment', 'commentL10n', [
			'submittedOn' => __( 'Submitted on:' ),
			'dateFormat' => __( '%1$s %2$s, %3$s @ %4$s:%5$s' )
		] );

		$scripts->add( 'admin-gallery', "/app-assets/js/admin/gallery$minify.js", [ 'jquery-ui-sortable' ] );
		$scripts->add( 'admin-widgets', "/app-assets/js/admin/widgets$minify.js", [ 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable' ], false, 1 );

		did_action( 'init' ) && $scripts->add_inline_script( 'admin-widgets', sprintf( 'wpWidgets.l10n = %s;', wp_json_encode( [
			'save'      => __( 'Save' ),
			'saved'     => __( 'Saved' ),
			'saveAlert' => __( 'The changes you made will be lost if you navigate away from this page.' ),
		] ) ) );

		$scripts->add( 'media-widgets', "/app-assets/js/admin/widgets/media-widgets$minify.js", [ 'jquery', 'media-models', 'media-views', 'wp-api-request' ] );
		$scripts->add_inline_script( 'media-widgets', 'wp.mediaWidgets.init();', 'after' );

		$scripts->add( 'media-audio-widget', "/app-assets/js/admin/widgets/media-audio-widget$minify.js", [ 'media-widgets', 'media-audiovideo' ] );
		$scripts->add( 'media-image-widget', "/app-assets/js/admin/widgets/media-image-widget$minify.js", [ 'media-widgets' ] );
		$scripts->add( 'media-gallery-widget', "/app-assets/js/admin/widgets/media-gallery-widget$minify.js", [ 'media-widgets' ] );
		$scripts->add( 'media-video-widget', "/app-assets/js/admin/widgets/media-video-widget$minify.js", [ 'media-widgets', 'media-audiovideo', 'wp-api-request' ] );
		$scripts->add( 'text-widgets', "/app-assets/js/admin/widgets/text-widgets$minify.js", [ 'jquery', 'backbone', 'editor', 'wp-util', 'wp-a11y' ] );
		$scripts->add( 'custom-html-widgets', "/app-assets/js/admin/widgets/custom-html-widgets$minify.js", [ 'jquery', 'backbone', 'wp-util', 'jquery-ui-core', 'wp-a11y' ] );
		$scripts->add( 'theme', "/app-assets/js/admin/theme$minify.js", [ 'wp-backbone', 'wp-a11y', 'customize-base' ], false, 1 );
		$scripts->add( 'inline-edit-post', "/app-assets/js/admin/inline-edit-post$minify.js", [ 'jquery', 'tags-suggest', 'wp-a11y' ], false, 1 );

		did_action( 'init' ) && $scripts->localize( 'inline-edit-post', 'inlineEditL10n', [
			'error'      => __( 'Error while saving the changes.' ),
			'ntdeltitle' => __( 'Remove From Bulk Edit' ),
			'notitle'    => __( '(no title)' ),
			'comma'      => trim( _x( ',', 'tag delimiter' ) ),
			'saved'      => __( 'Changes saved.' ),
		] );

		$scripts->add( 'inline-edit-tax', "/app-assets/js/admin/inline-edit-tax$minify.js", [ 'jquery', 'wp-a11y' ], false, 1 );

		did_action( 'init' ) && $scripts->localize( 'inline-edit-tax', 'inlineEditL10n', [
			'error' => __( 'Error while saving the changes.' ),
			'saved' => __( 'Changes saved.' ),
		] );

		$scripts->add( 'plugin-install', "/app-assets/js/admin/plugin-install$minify.js", [ 'jquery', 'jquery-ui-core', 'thickbox' ], false, 1 );

		did_action( 'init' ) && $scripts->localize( 'plugin-install', 'plugininstallL10n', [
			'plugin_information' => __( 'Plugin:' ),
			'plugin_modal_label' => __( 'Plugin details' ),
			'ays' => __( 'Are you sure you want to install this plugin?' )
		] );

		$scripts->add( 'updates', "/app-assets/js/admin/updates$minify.js", [ 'jquery', 'wp-util', 'wp-a11y' ], false, 1 );

		did_action( 'init' ) && $scripts->localize( 'updates', '_wpUpdatesSettings', [

			'ajax_nonce' => wp_create_nonce( 'updates' ),
			'l10n'       => [
				'searchResults'              => __( 'Search results for &#8220;%s&#8221;' ),
				'searchResultsLabel'         => __( 'Search Results' ),
				'noPlugins'                  => __( 'You do not appear to have any plugins available at this time.' ),
				'noItemsSelected'            => __( 'Please select at least one item to perform this action on.' ),
				'updating'                   => __( 'Updating...' ),
				'pluginUpdated'              => _x( 'Updated!', 'plugin' ),
				'themeUpdated'               => _x( 'Updated!', 'theme' ),
				'update'                     => __( 'Update' ),
				'updateNow'                  => __( 'Update Now' ),
				'pluginUpdateNowLabel'       => _x( 'Update %s now', 'plugin' ),
				'updateFailedShort'          => __( 'Update Failed!' ),
				'updateFailed'               => __( 'Update Failed: %s' ),
				'pluginUpdatingLabel'        => _x( 'Updating %s...', 'plugin' ),
				'pluginUpdatedLabel'         => _x( '%s updated!', 'plugin' ),
				'pluginUpdateFailedLabel'    => _x( '%s update failed', 'plugin' ),
				'updatingMsg'                => __( 'Updating... please wait.' ),
				'updatedMsg'                 => __( 'Update completed successfully.' ),
				'updateCancel'               => __( 'Update canceled.' ),
				'beforeunload'               => __( 'Updates may not complete if you navigate away from this page.' ),
				'installNow'                 => __( 'Install Now' ),
				'pluginInstallNowLabel'      => _x( 'Install %s now', 'plugin' ),
				'installing'                 => __( 'Installing...' ),
				'pluginInstalled'            => _x( 'Installed!', 'plugin' ),
				'themeInstalled'             => _x( 'Installed!', 'theme' ),
				'installFailedShort'         => __( 'Installation Failed!' ),
				'installFailed'              => __( 'Installation failed: %s' ),
				'pluginInstallingLabel'      => _x( 'Installing %s...', 'plugin' ),
				'themeInstallingLabel'       => _x( 'Installing %s...', 'theme' ),
				'pluginInstalledLabel'       => _x( '%s installed!', 'plugin' ),
				'themeInstalledLabel'        => _x( '%s installed!', 'theme' ),
				'pluginInstallFailedLabel'   => _x( '%s installation failed', 'plugin' ),
				'themeInstallFailedLabel'    => _x( '%s installation failed', 'theme' ),
				'installingMsg'              => __( 'Installing... please wait.' ),
				'installedMsg'               => __( 'Installation completed successfully.' ),
				'importerInstalledMsg'       => __( 'Importer installed successfully. <a href="%s">Run importer</a>' ),
				'aysDelete'                  => __( 'Are you sure you want to delete %s?' ),
				'aysDeleteUninstall'         => __( 'Are you sure you want to delete %s and its data?' ),
				'aysBulkDelete'              => __( 'Are you sure you want to delete the selected plugins and their data?' ),
				'aysBulkDeleteThemes'        => __( 'Caution: These themes may be active on other sites in the network. Are you sure you want to proceed?' ),
				'deleting'                   => __( 'Deleting...' ),
				'deleteFailed'               => __( 'Deletion failed: %s' ),
				'pluginDeleted'              => _x( 'Deleted!', 'plugin' ),
				'themeDeleted'               => _x( 'Deleted!', 'theme' ),
				'livePreview'                => __( 'Live Preview' ),
				'activatePlugin'             => is_network_admin() ? __( 'Network Activate' ) : __( 'Activate' ),
				'activateTheme'              => is_network_admin() ? __( 'Network Enable' ) : __( 'Activate' ),
				'activatePluginLabel'        => is_network_admin() ? _x( 'Network Activate %s', 'plugin' ) : _x( 'Activate %s', 'plugin' ),
				'activateThemeLabel'         => is_network_admin() ? _x( 'Network Activate %s', 'theme' ) : _x( 'Activate %s', 'theme' ),
				'activateImporter'           => __( 'Run Importer' ),
				'activateImporterLabel'      => __( 'Run %s' ),
				'unknownError'               => __( 'Something went wrong.' ),
				'connectionError'            => __( 'Connection lost or the server is busy. Please try again later.' ),
				'nonceError'                 => __( 'An error has occurred. Please reload the page and try again.' ),
				'pluginsFound'               => __( 'Number of plugins found: %d' ),
				'noPluginsFound'             => __( 'No plugins found. Try a different search.' ),
			],
		] );

		$scripts->add( 'iris', '/app-assets/js/admin/iris.min.js', [ 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ], '1.0.7', 1 );
		$scripts->add( 'wp-color-picker', "/app-assets/js/admin/color-picker$minify.js", [ 'iris' ], false, 1 );

		did_action( 'init' ) && $scripts->localize( 'wp-color-picker', 'wpColorPickerL10n', [
			'clear'            => __( 'Clear' ),
			'clearAriaLabel'   => __( 'Clear color' ),
			'defaultString'    => __( 'Default' ),
			'defaultAriaLabel' => __( 'Select default color' ),
			'pick'             => __( 'Select Color' ),
			'defaultLabel'     => __( 'Color value' ),
		] );

		$scripts->add( 'dashboard', "/app-assets/js/admin/dashboard$minify.js", [ 'jquery', 'admin-comments', 'postbox', 'wp-util', 'wp-a11y' ], false, 1 );
		$scripts->add( 'list-revisions', "/app-assets/js/includes/wp-list-revisions$minify.js" );
		$scripts->add( 'media-grid', "/app-assets/js/includes/media-grid$minify.js", [ 'media-editor' ], false, 1 );
		$scripts->add( 'media', "/app-assets/js/admin/media$minify.js", [ 'jquery' ], false, 1 );

		did_action( 'init' ) && $scripts->localize( 'media', 'attachMediaBoxL10n', [
			'error' => __( 'An error has occurred. Please reload the page and try again.' ),
		] );

		$scripts->add( 'image-edit', "/app-assets/js/admin/image-edit$minify.js", [ 'jquery', 'json2', 'imgareaselect' ], false, 1 );

		did_action( 'init' ) && $scripts->localize( 'image-edit', 'imageEditL10n', [
			'error' => __( 'Could not load the preview image. Please reload the page and try again.' )
		] );

		$scripts->add( 'set-post-thumbnail', "/app-assets/js/admin/set-post-thumbnail$minify.js", [ 'jquery' ], false, 1 );

		did_action( 'init' ) && $scripts->localize( 'set-post-thumbnail', 'setPostThumbnailL10n', [
			'setThumbnail' => __( 'Use as featured image' ),
			'saving'       => __( 'Saving...' ),
			'error'        => __( 'Could not set that as the thumbnail image. Try a different attachment.' ),
			'done'         => __( 'Done' )
		] );

		// Navigation menus.
		$scripts->add( 'nav-menu', "/app-assets/js/admin/nav-menu$minify.js", [ 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable', 'wp-lists', 'postbox', 'json2' ] );

		did_action( 'init' ) && $scripts->localize( 'nav-menu', 'navMenuL10n', [
			'noResultsFound' => __( 'No results found.' ),
			'warnDeleteMenu' => __( "You are about to permanently delete this menu. \n 'Cancel' to stop, 'OK' to delete." ),
			'saveAlert'      => __( 'The changes you made will be lost if you navigate away from this page.' ),
			'untitled'       => _x( '(no label)', 'missing menu item navigation label' )
		] );

		$scripts->add( 'custom-header', "/app-assets/js/admin/custom-header.js", [ 'jquery-masonry' ], false, 1 );
		$scripts->add( 'custom-background', "/app-assets/js/admin/custom-background$minify.js", [ 'wp-color-picker', 'media-views' ], false, 1 );
		$scripts->add( 'media-gallery', "/app-assets/js/admin/media-gallery$minify.js", [ 'jquery' ], false, 1 );
		$scripts->add( 'svg-painter', '/app-assets/js/admin/svg-painter.js', [ 'jquery' ], false, 1 );
	}
}

/**
 * Assign default styles to $styles object.
 *
 * Nothing is returned, because the $styles parameter is passed by reference.
 * Meaning that whatever object is passed will be updated without having to
 * reassign the variable that was passed back to the same value. This saves
 * memory.
 *
 * Adding default styles is not the only task, it also assigns the base_url
 * property, the default version, and text direction for the object.
 *
 * @since Previous 2.6.0
 * @param WP_Styles $styles
 */
function wp_default_styles( &$styles ) {

	// Include an unmodified $app_version.
	include( APP_INC_PATH . '/version.php' );

	if ( ! defined( 'SCRIPT_DEBUG' ) ) {
		define( 'SCRIPT_DEBUG', false !== strpos( $app_version, '-src' ) );
	}

	if ( ! $guessurl = site_url() ) {
		$guessurl = wp_guess_url();
	}

	if ( is_network() ) {
		$styles->base_url = network_site_url();
	} else {
		$styles->base_url = $guessurl;
	}

	$styles->content_url     = function_exists( 'content_url' ) ? content_url() : '';
	$styles->default_version = $app_version;
	$styles->text_direction  = function_exists( 'is_rtl' ) && is_rtl() ? 'rtl' : 'ltr';
	$styles->default_dirs    = [ 'app-assets/css/admin/', 'app-assets/css/includes/' ];

	// Register a stylesheet for the selected admin color scheme.
	$styles->add( 'colors', true, [ 'app-admin' ] );

	// Register a stylesheet for the selected user code theme.
	$styles->add( 'code-theme', true, [ 'app-codemirror' ], '' );

	// Load minified stylesheets unless SCRIPT_DEBUG is true.
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		$minify = '';
	} else {
		$minify = '.min';
	}

	// Admin stylesheets.
	$styles->add( 'admin',       "/app-assets/css/admin/admin$minify.css" );
	$styles->add( 'list-tables', "/app-assets/css/admin/screens/list-tables$minify.css" );
	$styles->add( 'edit',        "/app-assets/css/admin/screens/edit$minify.css" );
	$styles->add( 'revisions',   "/app-assets/css/admin/screens/revisions$minify.css" );
	$styles->add( 'media',       "/app-assets/css/admin/screens/media$minify.css" );
	$styles->add( 'themes',      "/app-assets/css/admin/screens/themes$minify.css" );
	$styles->add( 'plugins',     "/app-assets/css/admin/screens/plugins$minify.css" );
	$styles->add( 'nav-menus',   "/app-assets/css/admin/screens/nav-menus$minify.css" );
	$styles->add( 'widgets',     "/app-assets/css/admin/screens/widgets$minify.css", [ 'app-pointer' ] );
	$styles->add( 'profile',     "/app-assets/css/admin/screens/profile$minify.css" );
	$styles->add( 'site-icon',   "/app-assets/css/admin/screens/site-icon$minify.css" );
	$styles->add( 'code-editor', "/app-assets/css/admin/screens/code-editor$minify.css", [ 'app-codemirror', 'code-theme' ] );

	$styles->add( 'app-admin', false, [
		'admin',
		'list-tables',
		'edit',
		'revisions',
		'media',
		'themes',
		'plugins',
		'nav-menus',
		'widgets',
		'profile',
		'site-icon'
	] );

	$styles->add( 'customize-controls',  "/app-assets/css/admin/screens/customize-controls$minify.css", [ 'admin', 'colors', 'code-theme', 'imgareaselect' ] );
	$styles->add( 'customize-widgets',   "/app-assets/css/admin/screens/customize-widgets$minify.css", [ 'admin', 'colors' ] );
	$styles->add( 'customize-nav-menus', "/app-assets/css/admin/screens/customize-nav-menus$minify.css", [ 'admin', 'colors' ] );

	// Common stylesheets.
	$styles->add( 'dashicons', "/app-assets/css/includes/dashicons$minify.css", [ 'admin' ] );

	// Includes stylesheets.
	$styles->add( 'utility',               "/app-assets/css/includes/utility$minify.css" );
	$styles->add( 'install',               "/app-assets/css/includes/install$minify.css" );
	$styles->add( 'login',                 "/app-assets/css/includes/login$minify.css", [ 'dashicons' ] );
	$styles->add( 'user-toolbar',          "/app-assets/css/includes/user-toolbar$minify.css", [ 'dashicons' ] );
	$styles->add( 'app-auth-check',        "/app-assets/css/includes/app-auth-check$minify.css", [ 'dashicons' ] );
	$styles->add( 'editor-buttons',        "/app-assets/css/includes/editor$minify.css", [ 'dashicons' ] );
	$styles->add( 'media-views',           "/app-assets/css/includes/media-views$minify.css", [ 'dashicons', 'app-mediaelement' ] );
	$styles->add( 'app-pointer',           "/app-assets/css/includes/app-pointer$minify.css", [ 'dashicons' ] );
	$styles->add( 'customize-preview',     "/app-assets/css/includes/customize-preview$minify.css", [ 'dashicons' ] );
	$styles->add( 'app-embed-template-ie', "/app-assets/css/includes/app-embed-template-ie$minify.css" );

	// External library stylesheets.
	$styles->add( 'imgareaselect',        '/app-assets/js/includes/imgareaselect/imgareaselect.css', [], '0.9.8' );
	$styles->add( 'app-jquery-ui-dialog', "/app-assets/css/includes/jquery-ui-dialog$minify.css", [ 'dashicons' ] );
	$styles->add( 'mediaelement',         "/app-assets/js/includes/mediaelement/mediaelementplayer-legacy.min.css", [], '4.2.6-78496d1' );
	$styles->add( 'app-mediaelement',     "/app-assets/js/includes/mediaelement/wp-mediaelement$minify.css", [ 'mediaelement' ] );
	$styles->add( 'thickbox',             '/app-assets/js/includes/thickbox/thickbox.css', [ 'dashicons' ] );
	$styles->add( 'app-codemirror',       '/app-assets/css/includes/vendor/codemirror/codemirror.min.css', [], '4.9.8' );

	// RTL stylesheets.
	$rtl_styles = [

		// Admin stylesheets.
		'admin',
		// 'dashboard',
		'list-tables',
		'edit',
		'revisions',
		'media',
		'themes',
		'plugins',
		'nav-menus',
		'widgets',
		'profile',
		'site-icon',
		'customize-controls',
		'customize-widgets',
		'customize-nav-menus',
		'customize-preview',

		// Includes stylesheets.
		'utility',
		'install',
		'login',
		'user-toolbar',
		'app-auth-check',
		'editor-buttons',
		'media-views',
		'app-pointer',
		'app-jquery-ui-dialog'
	];


	foreach ( $rtl_styles as $rtl_style ) {

		$styles->add_data( $rtl_style, 'rtl', 'replace' );

		if ( $minify ) {
			$styles->add_data( $rtl_style, 'suffix', $minify );
		}
	}
}

/**
 * Reorder JavaScript scripts array to place prototype before jQuery.
 *
 * @since  Previous 2.3.1
 * @param  array $js_array JavaScript scripts array
 * @return array Reordered array, if needed.
 */
function wp_prototype_before_jquery( $js_array ) {

	if ( false === $prototype = array_search( 'prototype', $js_array, true ) ) {
		return $js_array;
	}

	if ( false === $jquery = array_search( 'jquery', $js_array, true ) ) {
		return $js_array;
	}

	if ( $prototype < $jquery ) {
		return $js_array;
	}

	unset( $js_array[$prototype] );

	array_splice( $js_array, $jquery, 0, 'prototype' );

	return $js_array;
}

/**
 * Load localized data on print rather than initialization.
 *
 * These localizations require information that may not be loaded even by init.
 *
 * @since Previous 2.5.0
 */
function wp_just_in_time_script_localization() {

	wp_localize_script( 'autosave', 'autosaveL10n', [
		'autosaveInterval' => AUTOSAVE_INTERVAL,
		'blog_id'          => get_current_blog_id(),
	] );

	wp_localize_script( 'mce-view', 'mceViewL10n', [
		'shortcodes' => ! empty( $GLOBALS['shortcode_tags'] ) ? array_keys( $GLOBALS['shortcode_tags'] ) : []
	] );

	wp_localize_script( 'word-count', 'wordCountL10n', [
		'type'       => _x( 'words', 'Word count type. Do not translate!' ),
		'shortcodes' => ! empty( $GLOBALS['shortcode_tags'] ) ? array_keys( $GLOBALS['shortcode_tags'] ) : []
	] );
}

/**
 * Localizes the jQuery UI datepicker.
 *
 * @link https://api.jqueryui.com/datepicker/#options
 *
 * @since  Previous 4.6.0
 * @global WP_Locale $wp_locale The date and time locale object.
 */
function wp_localize_jquery_ui_datepicker() {

	global $wp_locale;

	if ( ! wp_script_is( 'jquery-ui-datepicker', 'enqueued' ) ) {
		return;
	}

	// Convert the PHP date format into jQuery UI's format.
	$datepicker_date_format = str_replace(
		[
			'd', 'j', 'l', 'z', // Day.
			'F', 'M', 'n', 'm', // Month.
			'Y', 'y'            // Year.
		],
		[
			'dd', 'd', 'DD', 'o',
			'MM', 'M', 'm', 'mm',
			'yy', 'y'
		],
		get_option( 'date_format' )
	);

	$datepicker_defaults = wp_json_encode( [
		'closeText'       => __( 'Close' ),
		'currentText'     => __( 'Today' ),
		'monthNames'      => array_values( $wp_locale->month ),
		'monthNamesShort' => array_values( $wp_locale->month_abbrev ),
		'nextText'        => __( 'Next' ),
		'prevText'        => __( 'Previous' ),
		'dayNames'        => array_values( $wp_locale->weekday ),
		'dayNamesShort'   => array_values( $wp_locale->weekday_abbrev ),
		'dayNamesMin'     => array_values( $wp_locale->weekday_initial ),
		'dateFormat'      => $datepicker_date_format,
		'firstDay'        => absint( get_option( 'start_of_week' ) ),
		'isRTL'           => $wp_locale->is_rtl(),
	] );

	wp_add_inline_script( 'jquery-ui-datepicker', "jQuery(document).ready(function(jQuery){jQuery.datepicker.setDefaults({$datepicker_defaults});});" );
}

/**
 * Administration Screen CSS for changing the styles.
 *
 * If installing the 'app-assets/css/' directory will be replaced with './'.
 *
 * The $_wp_admin_css_colors global manages the Administration Screens CSS
 * stylesheet that is loaded. The option that is set is 'admin_color' and is the
 * color and key for the array. The value for the color key is an object with
 * a 'url' parameter that has the URL path to the CSS file.
 *
 * The query from $src parameter will be appended to the URL that is given from
 * the $_wp_admin_css_colors array value URL.
 *
 * @since  Previous 2.6.0
 * @global array $_wp_admin_css_colors
 * @param  string $src    Source URL.
 * @param  string $handle Either 'colors' or 'colors-rtl'.
 * @return string|false URL path to CSS stylesheet for Administration Screens.
 */
function app_style_loader_src( $src, $handle ) {

	global $_wp_admin_css_colors;

	if ( wp_installing() ) {
		return preg_replace( '#^app-assets/css/#', './', $src );
	}

	if ( 'colors' == $handle ) {

		$color = get_user_option( 'admin_color' );

		if ( empty( $color ) || ! isset( $_wp_admin_css_colors[$color] ) ) {
			$color = 'default';
		}

		$color = $_wp_admin_css_colors[$color];
		$url   = $color->url;

		if ( ! $url ) {
			return false;
		}

		$parsed = parse_url( $src );

		if ( isset( $parsed['query'] ) && $parsed['query'] ) {

			wp_parse_str( $parsed['query'], $qv );
			$url = add_query_arg( $qv, $url );
		}

		return $url;
	}

	return $src;
}

/**
 * Code editor CSS for changing the styles.
 *
 * If installing the 'app-assets/css/' directory will be replaced with './'.
 *
 * The $app_user_code_themes global manages the code editor CSS
 * stylesheet that is loaded. The option that is set is 'code_theme' and is the
 * color and key for the array. The value for the color key is an object with
 * a 'url' parameter that has the URL path to the CSS file.
 *
 * The query from $src parameter will be appended to the URL that is given from
 * the $app_user_code_themes array value URL.
 *
 * @since  Previous 2.6.0
 * @global array $app_user_code_themes
 * @param  string $src    Source URL.
 * @param  string $handle Either 'theme' or 'theme-rtl'.
 * @return string|false URL path to CSS stylesheet for Code editor.
 */
function app_code_theme_loader_src( $src, $handle ) {

	global $app_user_code_themes;

	if ( wp_installing() ) {
		return preg_replace( '#^app-assets/css/#', './', $src );
	}

	if ( 'code-theme' == $handle ) {

		$theme = get_user_option( 'code_theme' );

		if ( empty( $theme ) || ! isset( $app_user_code_themes[$theme] ) ) {
			$theme = 'default';
		}

		$theme = $app_user_code_themes[$theme];
		$url   = $theme->url;

		if ( ! $url ) {
			return false;
		}

		$parsed = parse_url( $src );

		if ( isset( $parsed['query'] ) && $parsed['query'] ) {

			wp_parse_str( $parsed['query'], $qv );
			$url = add_query_arg( $qv, $url );
		}

		return $url;
	}

	return $src;
}

/**
 * Prints the script queue in the HTML head on admin pages.
 *
 * Postpones the scripts that were queued for the footer.
 * print_footer_scripts() is called in the footer to print these scripts.
 *
 * @see wp_print_scripts()
 *
 * @since Previous 2.8.0
 * @global bool $concatenate_scripts
 * @return array
 */
function print_head_scripts() {

	global $concatenate_scripts;

	if ( ! did_action( 'wp_print_scripts' ) ) {
		do_action( 'wp_print_scripts' );
	}

	$wp_scripts = wp_scripts();

	script_concat_settings();
	$wp_scripts->do_concat = $concatenate_scripts;
	$wp_scripts->do_head_items();

	/**
	 * Filters whether to print the head scripts.
	 *
	 * @since Previous 2.8.0
	 * @param bool $print Whether to print the head scripts. Default true.
	 */
	if ( apply_filters( 'print_head_scripts', true ) ) {
		_print_scripts();
	}

	$wp_scripts->reset();

	return $wp_scripts->done;
}

/**
 * Prints the scripts that were queued for the footer or too late for the HTML head.
 *
 * @since Previous 2.8.0
 * @global WP_Scripts $wp_scripts
 * @global bool $concatenate_scripts
 * @return array
 */
function print_footer_scripts() {

	global $wp_scripts, $concatenate_scripts;

	// No need to run if not instantiated.
	if ( ! ( $wp_scripts instanceof WP_Scripts ) ) {
		return [];
	}

	script_concat_settings();
	$wp_scripts->do_concat = $concatenate_scripts;
	$wp_scripts->do_footer_items();

	/**
	 * Filters whether to print the footer scripts.
	 *
	 * @since Previous 2.8.0
	 * @param bool $print Whether to print the footer scripts. Default true.
	 */
	if ( apply_filters( 'print_footer_scripts', true ) ) {
		_print_scripts();
	}

	$wp_scripts->reset();

	return $wp_scripts->done;
}

/**
 * Print scripts (internal use only)
 *
 * @ignore
 *
 * @global WP_Scripts $wp_scripts
 * @global bool       $compress_scripts
 */
function _print_scripts() {

	global $wp_scripts, $compress_scripts;

	if ( $compress_scripts ) {
		$zip = 1;
	} else {
		$zip = 0;
	}

	if ( $zip && defined( 'ENFORCE_GZIP' ) && ENFORCE_GZIP ) {
		$zip = 'gzip';
	}

	if ( $concat = trim( $wp_scripts->concat, ', ' ) ) {

		if ( ! empty( $wp_scripts->print_code) ) {
			echo "\n<script type='text/javascript'>\n";
			echo "/* <![CDATA[ */\n"; // not needed in HTML 5
			echo $wp_scripts->print_code;
			echo "/* ]]> */\n";
			echo "</script>\n";
		}

		$concat = str_split( $concat, 128 );
		$concat = 'load%5B%5D=' . implode( '&load%5B%5D=', $concat );

		$src = $wp_scripts->base_url . '/' . APP_INC_DIR . "/load-scripts.php?c={$zip}&" . $concat . '&ver=' . $wp_scripts->default_version;
		echo "<script type='text/javascript' src='" . esc_attr( $src) . "'></script>\n";
	}

	if ( ! empty( $wp_scripts->print_html ) ) {
		echo $wp_scripts->print_html;
	}
}

/**
 * Prints the script queue in the HTML head on the front end.
 *
 * Postpones the scripts that were queued for the footer.
 * wp_print_footer_scripts() is called in the footer to print these scripts.
 *
 * @since  Previous 2.8.0
 * @global WP_Scripts $wp_scripts
 * @return array
 */
function wp_print_head_scripts() {

	if ( ! did_action( 'wp_print_scripts' ) ) {
		do_action( 'wp_print_scripts' );
	}

	global $wp_scripts;

	// No need to run if nothing is queued.
	if ( ! ( $wp_scripts instanceof WP_Scripts ) ) {
		return [];
	}

	return print_head_scripts();
}

/**
 * Private, for use in *_footer_scripts hooks
 *
 * @since Previous 3.3.0
 */
function _wp_footer_scripts() {
	print_late_styles();
	print_footer_scripts();
}

/**
 * Hooks to print the scripts and styles in the footer.
 *
 * @since Previous 2.8.0
 */
function wp_print_footer_scripts() {

	/**
	 * Fires when footer scripts are printed.
	 *
	 * @since Previous 2.8.0
	 */
	do_action( 'wp_print_footer_scripts' );
}

/**
 * Wrapper for do_action( 'wp_enqueue_scripts' )
 *
 * Allows plugins to queue scripts for the front end using wp_enqueue_script().
 * Runs first in wp_head() where all is_home(), is_page(), etc. functions are available.
 *
 * @since Previous 2.8.0
 */
function wp_enqueue_scripts() {

	/**
	 * Fires when scripts and styles are enqueued.
	 *
	 * @since Previous 2.8.0
	 */
	do_action( 'wp_enqueue_scripts' );
}

/**
 * Prints the styles queue in the HTML head on admin pages.
 *
 * @since Previous 2.8.0
 * @global bool $concatenate_scripts
 * @return array
 */
function print_admin_styles() {

	global $concatenate_scripts;

	$wp_styles = wp_styles();

	script_concat_settings();

	$wp_styles->do_concat = $concatenate_scripts;
	$wp_styles->do_items( false );

	/**
	 * Filters whether to print the admin styles.
	 *
	 * @since Previous 2.8.0
	 * @param bool $print Whether to print the admin styles. Default true.
	 */
	if ( apply_filters( 'print_admin_styles', true ) ) {
		_print_styles();
	}

	$wp_styles->reset();

	return $wp_styles->done;
}

/**
 * Prints the styles that were queued too late for the HTML head.
 *
 * @since Previous 3.3.0
 * @global WP_Styles $wp_styles
 * @global bool $concatenate_scripts
 * @return array|void
 */
function print_late_styles() {

	global $wp_styles, $concatenate_scripts;

	if ( ! ( $wp_styles instanceof WP_Styles ) ) {
		return;
	}

	script_concat_settings();

	$wp_styles->do_concat = $concatenate_scripts;
	$wp_styles->do_footer_items();

	/**
	 * Filters whether to print the styles queued too late for the HTML head.
	 *
	 * @since Previous 3.3.0
	 * @param bool $print Whether to print the 'late' styles. Default true.
	 */
	if ( apply_filters( 'print_late_styles', true ) ) {
		_print_styles();
	}

	$wp_styles->reset();

	return $wp_styles->done;
}

/**
 * Print styles (internal use only)
 *
 * @ignore
 * @since  Previous 3.3.0
 * @global bool $compress_css
 */
function _print_styles() {

	global $compress_css;

	$wp_styles = wp_styles();

	if ( $compress_css ) {
		$zip = 1;
	} else {
		$zip = 0;
	}

	if ( $zip && defined( 'ENFORCE_GZIP' ) && ENFORCE_GZIP ) {
		$zip = 'gzip';
	}

	if ( $concat = trim( $wp_styles->concat, ', ' ) ) {

		$dir    = $wp_styles->text_direction;
		$ver    = $wp_styles->default_version;
		$concat = str_split( $concat, 128 );
		$concat = 'load%5B%5D=' . implode( '&load%5B%5D=', $concat );
		$href   = $wp_styles->base_url . '/' . APP_INC_DIR . "/load-styles.php?c={$zip}&dir={$dir}&" . $concat . '&ver=' . $ver;

		echo "<link rel='stylesheet' href='" . esc_attr( $href ) . "' type='text/css' media='all' />\n";

		if ( ! empty( $wp_styles->print_code ) ) {
			echo "<style type='text/css'>\n";
			echo $wp_styles->print_code;
			echo "\n</style>\n";
		}
	}

	if ( ! empty( $wp_styles->print_html ) ) {
		echo $wp_styles->print_html;
	}
}

/**
 * Determine the concatenation and compression settings for scripts and styles.
 *
 * @since  Previous 2.8.0
 * @global bool $concatenate_scripts
 * @global bool $compress_scripts
 * @global bool $compress_css
 */
function script_concat_settings() {

	global $concatenate_scripts, $compress_scripts, $compress_css;

	$compressed_output = ( ini_get( 'zlib.output_compression' ) || 'ob_gzhandler' == ini_get( 'output_handler' ) );

	if ( ! isset( $concatenate_scripts) ) {

		$concatenate_scripts = defined( 'CONCATENATE_SCRIPTS' ) ? CONCATENATE_SCRIPTS : true;

		if ( ( ! is_admin() && ! did_action( 'login_init' ) ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) {
			$concatenate_scripts = false;
		}
	}

	if ( ! isset( $compress_scripts) ) {

		$compress_scripts = defined( 'COMPRESS_SCRIPTS' ) ? COMPRESS_SCRIPTS : true;

		if ( $compress_scripts && ( ! get_site_option( 'can_compress_scripts' ) || $compressed_output ) ) {
			$compress_scripts = false;
		}
	}

	if ( ! isset( $compress_css) ) {

		$compress_css = defined( 'COMPRESS_CSS' ) ? COMPRESS_CSS : true;

		if ( $compress_css && ( ! get_site_option( 'can_compress_scripts' ) || $compressed_output ) ) {
			$compress_css = false;
		}
	}
}
