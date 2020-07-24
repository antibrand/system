<?php
/**
 * Used to set up and fix common variables and include
 * the procedural and class library.
 *
 * Allows for some configuration in app-config.php (see app-includes/constants-default.php)
 *
 * @package App_Package
 */

// Store the locations of functions, classes, and core content.
define( 'APPINC', 'app-includes' );

// HTML templates directory name.
if ( ! defined( 'APP_VIEWS' ) ) {
	define( 'APP_VIEWS', 'app-views' );
}

// Path to HTML templates.
if ( ! defined( 'APP_VIEWS_PATH' ) ) {
	define( 'APP_VIEWS_PATH', ABSPATH . APP_VIEWS . '/' );
}

define( 'WPINC', 'wp-includes' );

/**
 * Class autoloader
 *
 * @link https://www.php.net/manual/en/language.oop5.autoload.php
 *
 * @since 1.0.0
 */
require( ABSPATH . APPINC . '/autoloader.php' );

require( ABSPATH . APPINC . '/deprecated-versions.php' );
require( ABSPATH . APPINC . '/deprecated-branded.php' );

// Include files required for initialization.
require( ABSPATH . APPINC . '/load.php' );
require( ABSPATH . APPINC . '/constants-default.php' );
require_once( ABSPATH . APPINC . '/plugin.php' );

/**
 * These can't be directly globalized in version.php. When updating,
 * we're including version.php from another installation and don't want
 * these values to be overridden if already set.
 */
global $app_version, $wp_version, $wp_db_version, $tinymce_version, $required_php_version, $required_mysql_version, $wp_local_package;
require( ABSPATH . WPINC . '/version.php' );

/**
 * If not already configured, `$blog_id` will default to 1 in a single site
 * configuration. In network, it will be overridden by default in network-settings.php.
 *
 * @global int $blog_id
 * @since Previous 2.0.0
 */
global $blog_id;

// Set initial default constants including WP_MEMORY_LIMIT, WP_MAX_MEMORY_LIMIT, APP_DEBUG, SCRIPT_DEBUG, WP_CONTENT_DIR and WP_CACHE.
wp_initial_constants();

// Check for the required PHP version and for the MySQL extension or a database drop-in.
wp_check_php_mysql_versions();

// Disable magic quotes at runtime. Magic quotes are added using wpdb later in app-settings.php.
@ini_set( 'magic_quotes_runtime', 0 );
@ini_set( 'magic_quotes_sybase',  0 );

// Calculate offsets from UTC.
date_default_timezone_set( 'UTC' );

// Turn register_globals off.
wp_unregister_GLOBALS();

// Standardize $_SERVER variables across setups.
wp_fix_server_vars();

// Check if we have received a request due to missing favicon.ico
wp_favicon_request();

// Check if we're in maintenance mode.
wp_maintenance();

// Start loading timer.
timer_start();

// Check if we're in APP_DEBUG mode.
app_debug_mode();

/**
 * Filters whether to enable loading of the advanced-cache.php drop-in.
 *
 * This filter runs before it can be used by plugins. It is designed for non-web
 * run-times. If false is returned, advanced-cache.php will never be loaded.
 *
 * @since Previous 4.6.0
 *
 * @param bool $enable_advanced_cache Whether to enable loading advanced-cache.php (if present).
 *                                    Default true.
 */
if ( WP_CACHE && apply_filters( 'enable_loading_advanced_cache_dropin', true ) ) {

	// For an advanced caching plugin to use. Uses a static drop-in because you would only want one.
	if ( APP_DEV_MODE || APP_DEBUG ) {
		include( WP_CONTENT_DIR . '/advanced-cache.php' );
	} else {
		@include( WP_CONTENT_DIR . '/advanced-cache.php' );
	}

	// Re-initialize any hooks added manually by advanced-cache.php.
	if ( $wp_filter ) {
		$wp_filter = WP_Hook::build_preinitialized_hooks( $wp_filter );
	}
}

// Define APP_LANG_DIR if not set.
wp_set_lang_dir();

// Load early files.
require( ABSPATH . APPINC . '/compat.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-list-util.php' );
require( ABSPATH . APPINC . '/functions.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-matchesmapregex.php' );
require( ABSPATH . APPINC . '/classes/includes/class-system-app.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-error.php' );
require( ABSPATH . APPINC . '/pomo/mo.php' );

// Include the wpdb class and, if present, a db.php database drop-in.
global $wpdb;
require_wp_db();

// Set the database table prefix and the format specifiers for database table columns.
$GLOBALS['table_prefix'] = $table_prefix;
wp_set_wpdb_vars();

// Start the object cache, or an external object cache if the drop-in is present.
wp_start_object_cache();

// Attach the default filters.
require( ABSPATH . APPINC . '/default-filters.php' );

// Initialize network if enabled.
if ( is_network() ) {
	require( ABSPATH . APPINC . '/classes/includes/class-app-site-query.php' );
	require( ABSPATH . APPINC . '/classes/includes/class-app-network-query.php' );
	require( ABSPATH . APPINC . '/network-blogs.php' );
	require( ABSPATH . APPINC . '/network-settings.php' );
} elseif ( ! defined( 'APP_NETWORK' ) ) {
	define( 'APP_NETWORK', false );
}

register_shutdown_function( 'shutdown_action_hook' );

// Stop most of the application from being loaded if we just want the basics.
if ( SHORTINIT ) {
	return false;
}

// Load the L10n library.
require_once( ABSPATH . APPINC . '/l10n.php' );
require_once( ABSPATH . APPINC . '/classes/includes/class-app-locale.php' );
require_once( ABSPATH . APPINC . '/classes/includes/class-app-locale-switcher.php' );

// Run the installer if the application is not installed.
app_not_installed();

// Load most of the application.
require( ABSPATH . APPINC . '/classes/includes/class-app-ajax-response.php' );
require( ABSPATH . APPINC . '/formatting.php' );
require( ABSPATH . APPINC . '/capabilities.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-roles.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-role.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-user.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-query.php' );
require( ABSPATH . APPINC . '/query.php' );
require( ABSPATH . APPINC . '/date.php' );
require( ABSPATH . WPINC . '/theme.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-theme.php' );
require( ABSPATH . WPINC . '/template.php' );
require( ABSPATH . WPINC . '/user.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-user-query.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-session-tokens.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-user-meta-session-tokens.php' );
require( ABSPATH . APPINC . '/meta.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-meta-query.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-metadata-lazyloader.php' );
require( ABSPATH . APPINC . '/general-template.php' );
require( ABSPATH . APPINC . '/link-template.php' );
require( ABSPATH . APPINC . '/author-template.php' );
require( ABSPATH . APPINC . '/post.php' );
require( ABSPATH . APPINC . '/classes/includes/class-walker-page-dropdown.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-post-type.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-post.php' );
require( ABSPATH . APPINC . '/post-template.php' );
require( ABSPATH . APPINC . '/revision.php' );
require( ABSPATH . APPINC . '/post-formats.php' );
require( ABSPATH . APPINC . '/post-thumbnail-template.php' );
require( ABSPATH . APPINC . '/category.php' );
require( ABSPATH . APPINC . '/category-template.php' );
require( ABSPATH . APPINC . '/comment.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-comment.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-comment-query.php' );
require( ABSPATH . APPINC . '/classes/includes/class-walker-comment.php' );
require( ABSPATH . APPINC . '/comment-template.php' );
require( ABSPATH . WPINC . '/rewrite.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-rewrite.php' );
require( ABSPATH . APPINC . '/feed.php' );
require( ABSPATH . APPINC . '/bookmark.php' );
require( ABSPATH . APPINC . '/bookmark-template.php' );
require( ABSPATH . APPINC . '/kses.php' );
require( ABSPATH . APPINC . '/cron.php' );
require( ABSPATH . WPINC . '/script-loader.php' );
require( ABSPATH . WPINC . '/taxonomy.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-taxonomy.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-term.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-term-query.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-tax-query.php' );
require( ABSPATH . WPINC . '/update.php' );
require( ABSPATH . APPINC . '/canonical.php' );
require( ABSPATH . WPINC . '/shortcodes.php' );
require( ABSPATH . APPINC . '/embed.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-embed.php' );
require( ABSPATH . APPINC . '/classes/includes/class-oembed.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-oembed-controller.php' );
require( ABSPATH . APPINC . '/media.php' );
require( ABSPATH . APPINC . '/http.php' );
require( ABSPATH . APPINC . '/classes/includes/class-http.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-http-streams.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-http-curl.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-http-proxy.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-http-cookie.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-http-encoding.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-http-response.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-http-requests-response.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-http-requests-hooks.php' );
require( ABSPATH . WPINC . '/widgets.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-widget.php' );
require( ABSPATH . APPINC . '/classes/includes/class-app-widget-factory.php' );
require( ABSPATH . APPINC . '/nav-menu.php' );
require( ABSPATH . APPINC . '/nav-menu-template.php' );
require( APP_VIEWS_PATH . '/includes/user-toolbar.php' );
require( ABSPATH . APPINC . '/rest-api.php' );
require( ABSPATH . APPINC . '/rest-api/class-wp-rest-server.php' );
require( ABSPATH . APPINC . '/rest-api/class-wp-rest-response.php' );
require( ABSPATH . APPINC . '/rest-api/class-wp-rest-request.php' );
require( ABSPATH . APPINC . '/rest-api/endpoints/class-wp-rest-controller.php' );
require( ABSPATH . APPINC . '/rest-api/endpoints/class-wp-rest-posts-controller.php' );
require( ABSPATH . APPINC . '/rest-api/endpoints/class-wp-rest-attachments-controller.php' );
require( ABSPATH . APPINC . '/rest-api/endpoints/class-wp-rest-post-types-controller.php' );
require( ABSPATH . APPINC . '/rest-api/endpoints/class-wp-rest-post-statuses-controller.php' );
require( ABSPATH . APPINC . '/rest-api/endpoints/class-wp-rest-revisions-controller.php' );
require( ABSPATH . APPINC . '/rest-api/endpoints/class-wp-rest-taxonomies-controller.php' );
require( ABSPATH . APPINC . '/rest-api/endpoints/class-wp-rest-terms-controller.php' );
require( ABSPATH . APPINC . '/rest-api/endpoints/class-wp-rest-users-controller.php' );
require( ABSPATH . APPINC . '/rest-api/endpoints/class-wp-rest-comments-controller.php' );
require( ABSPATH . APPINC . '/rest-api/endpoints/class-wp-rest-settings-controller.php' );
require( ABSPATH . APPINC . '/rest-api/fields/class-wp-rest-meta-fields.php' );
require( ABSPATH . APPINC . '/rest-api/fields/class-wp-rest-comment-meta-fields.php' );
require( ABSPATH . APPINC . '/rest-api/fields/class-wp-rest-post-meta-fields.php' );
require( ABSPATH . APPINC . '/rest-api/fields/class-wp-rest-term-meta-fields.php' );
require( ABSPATH . APPINC . '/rest-api/fields/class-wp-rest-user-meta-fields.php' );

$GLOBALS['wp_embed'] = new WP_Embed();

// Load network-specific files.
if ( is_network() ) {
	require( ABSPATH . APPINC . '/network-functions.php' );
	require( ABSPATH . APPINC . '/network-default-filters.php' );
	require( ABSPATH . APPINC . '/network-deprecated.php' );
}

/**
 * Define constants that rely on the API to obtain the default value.
 * Define must-use plugin directory constants, which may be overridden in the sunrise.php drop-in.
 */
wp_plugin_directory_constants();

$GLOBALS['wp_plugin_paths'] = [];

// Load must-use plugins.
foreach ( wp_get_mu_plugins() as $mu_plugin ) {
	include_once( $mu_plugin );
}
unset( $mu_plugin );

// Load network activated plugins.
if ( is_network() ) {
	foreach ( wp_get_active_network_plugins() as $network_plugin ) {
		wp_register_plugin_realpath( $network_plugin );
		include_once( $network_plugin );
	}
	unset( $network_plugin );
}

// Fires once all must-use and network-activated plugins have loaded.
do_action( 'muplugins_loaded' );

if ( is_network() ) {
	ms_cookie_constants();
}

// Define constants after network is loaded.
wp_cookie_constants();

// Define and enforce our SSL constants.
wp_ssl_constants();

// Create common globals.
require( ABSPATH . WPINC . '/vars.php' );

/**
 * Make taxonomies and posts available to plugins and themes.
 *
 * Plugin authors: warning, these get registered again on the init hook.
 */
create_initial_taxonomies();
create_initial_post_types();

wp_start_scraping_edited_file_errors();

// Register the default theme directory root.
register_theme_directory( get_theme_root() );

// Load active plugins.
foreach ( wp_get_active_and_valid_plugins() as $plugin ) {
	wp_register_plugin_realpath( $plugin );
	include_once( $plugin );
}
unset( $plugin );

// Load pluggable functions.
require( ABSPATH . APPINC . '/pluggable.php' );
require( ABSPATH . APPINC . '/pluggable-deprecated.php' );

// Set internal encoding.
wp_set_internal_encoding();

// Run wp_cache_postload() if object cache is enabled and the function exists.
if ( WP_CACHE && function_exists( 'wp_cache_postload' ) ) {
	wp_cache_postload();
}

/**
 * Fires once activated plugins have loaded.
 *
 * Pluggable functions are also available at this point in the loading order.
 *
 * @since Previous 1.5.0
 */
do_action( 'plugins_loaded' );

// Define constants which affect functionality if not already defined.
wp_functionality_constants();

// Add magic quotes and set up $_REQUEST ( $_GET + $_POST )
wp_magic_quotes();

/**
 * Fires when comment cookies are sanitized.
 *
 * @since Previous 2.0.11
 */
do_action( 'sanitize_comment_cookies' );

/**
 * Query object
 * @global WP_Query $wp_the_query
 * @since Previous 2.0.0
 */
$GLOBALS['wp_the_query'] = new WP_Query();

/**
 * Holds the reference to @see $wp_the_query
 * Use this global for queries
 * @global WP_Query $wp_query
 * @since Previous 1.5.0
 */
$GLOBALS['wp_query'] = $GLOBALS['wp_the_query'];

/**
 * Holds the Rewrite object for creating pretty URLs
 * @global WP_Rewrite $wp_rewrite
 * @since Previous 1.5.0
 */
$GLOBALS['wp_rewrite'] = new WP_Rewrite();

/**
 * Object
 * @global WP $wp
 * @since Previous 2.0.0
 */
$GLOBALS['wp'] = new System_App();

/**
 * Widget Factory Object
 * @global WP_Widget_Factory $wp_widget_factory
 * @since Previous 2.8.0
 */
$GLOBALS['wp_widget_factory'] = new WP_Widget_Factory();

/**
 * User Roles
 * @global WP_Roles $wp_roles
 * @since Previous 2.0.0
 */
$GLOBALS['wp_roles'] = new WP_Roles();

/**
 * Fires before the theme is loaded.
 *
 * @since Previous 2.6.0
 */
do_action( 'setup_theme' );

// Define the template related constants.
wp_templating_constants(  );

// Load the default text localization domain.
load_default_textdomain();

$locale      = get_locale();
$locale_file = APP_LANG_DIR . "/$locale.php";

if ( ( 0 === validate_file( $locale ) ) && is_readable( $locale_file ) ) {
	require( $locale_file );
}

unset( $locale_file );

/**
 * Locale object for loading locale domain date and various strings.
 * @global WP_Locale $wp_locale
 * @since Previous 2.1.0
 */
$GLOBALS['wp_locale'] = new WP_Locale();

/**
 *  Locale Switcher object for switching locales.
 *
 * @since Previous 4.7.0
 *
 * @global WP_Locale_Switcher $wp_locale_switcher locale switcher object.
 */
$GLOBALS['wp_locale_switcher'] = new WP_Locale_Switcher();
$GLOBALS['wp_locale_switcher']->init();

// Load the functions for the active theme, for both parent and child theme if applicable.
if ( ! wp_installing() || 'app-activate.php' === $pagenow ) {

	if ( TEMPLATEPATH !== STYLESHEETPATH && file_exists( STYLESHEETPATH . '/functions.php' ) ) {
		include( STYLESHEETPATH . '/functions.php' );
	}

	if ( file_exists( TEMPLATEPATH . '/functions.php' ) ) {
		include( TEMPLATEPATH . '/functions.php' );
	}
}

/**
 * Fires after the theme is loaded.
 *
 * @since Previous 3.0.0
 */
do_action( 'after_setup_theme' );

// Set up current user.
$GLOBALS['wp']->init();

/**
 * Fires after the application has finished loading but before any headers are sent.
 *
 * Most of WP is loaded at this stage, and the user is authenticated. WP continues
 * to load on the {@see 'init'} hook that follows (e.g. widgets), and many plugins instantiate
 * themselves on it for all sorts of reasons (e.g. they need a user, a taxonomy, etc.).
 *
 * If you wish to plug an action once WP is loaded, use the {@see 'wp_loaded'} hook below.
 *
 * @since Previous 1.5.0
 */
do_action( 'init' );

// Check site status
if ( is_network() ) {
	if ( true !== ( $file = ms_site_check() ) ) {
		require( $file );
		die();
	}
	unset( $file );
}

/**
 * This hook is fired once WP, all plugins, and the theme are fully loaded and instantiated.
 *
 * Ajax requests should use wp-admin/admin-ajax.php. admin-ajax.php can handle requests for
 * users not logged in.
 *
 * @since Previous 3.0.0
 */
do_action( 'wp_loaded' );