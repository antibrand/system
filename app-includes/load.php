<?php
/**
 * These functions are needed to load the application
 *
 * @package App_Package
 */

/**
 * Return the HTTP protocol sent by the server.
 *
 * @since 4.4.0
 *
 * @return string The HTTP protocol. Default: HTTP/1.0.
 */
function wp_get_server_protocol() {
	$protocol = $_SERVER['SERVER_PROTOCOL'];
	if ( ! in_array( $protocol, array( 'HTTP/1.1', 'HTTP/2', 'HTTP/2.0' ) ) ) {
		$protocol = 'HTTP/1.0';
	}
	return $protocol;
}

/**
 * Turn register globals off.
 *
 * @since 2.1.0
 * @access private
 */
function wp_unregister_GLOBALS() {
	if ( !ini_get( 'register_globals' ) )
		return;

	if ( isset( $_REQUEST['GLOBALS'] ) )
		die( 'GLOBALS overwrite attempt detected' );

	// Variables that shouldn't be unset
	$no_unset = array( 'GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES', 'table_prefix' );

	$input = array_merge( $_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset( $_SESSION ) && is_array( $_SESSION ) ? $_SESSION : array() );
	foreach ( $input as $k => $v )
		if ( !in_array( $k, $no_unset ) && isset( $GLOBALS[$k] ) ) {
			unset( $GLOBALS[$k] );
		}
}

/**
 * Fix `$_SERVER` variables for various setups.
 *
 * @since 3.0.0
 * @access private
 *
 * @global string $PHP_SELF The filename of the currently executing script,
 *                          relative to the document root.
 */
function wp_fix_server_vars() {
	global $PHP_SELF;

	$default_server_values = array(
		'SERVER_SOFTWARE' => '',
		'REQUEST_URI' => '',
	);

	$_SERVER = array_merge( $default_server_values, $_SERVER );

	// Fix for IIS when running with PHP ISAPI
	if ( empty( $_SERVER['REQUEST_URI'] ) || ( PHP_SAPI != 'cgi-fcgi' && preg_match( '/^Microsoft-IIS\//', $_SERVER['SERVER_SOFTWARE'] ) ) ) {

		// IIS Mod-Rewrite
		if ( isset( $_SERVER['HTTP_X_ORIGINAL_URL'] ) ) {
			$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
		}
		// IIS Isapi_Rewrite
		elseif ( isset( $_SERVER['HTTP_X_REWRITE_URL'] ) ) {
			$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
		} else {
			// Use ORIG_PATH_INFO if there is no PATH_INFO
			if ( !isset( $_SERVER['PATH_INFO'] ) && isset( $_SERVER['ORIG_PATH_INFO'] ) )
				$_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO'];

			// Some IIS + PHP configurations puts the script-name in the path-info (No need to append it twice)
			if ( isset( $_SERVER['PATH_INFO'] ) ) {
				if ( $_SERVER['PATH_INFO'] == $_SERVER['SCRIPT_NAME'] )
					$_SERVER['REQUEST_URI'] = $_SERVER['PATH_INFO'];
				else
					$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . $_SERVER['PATH_INFO'];
			}

			// Append the query string if it exists and isn't null
			if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
				$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
			}
		}
	}

	// Fix for PHP as CGI hosts that set SCRIPT_FILENAME to something ending in php.cgi for all requests
	if ( isset( $_SERVER['SCRIPT_FILENAME'] ) && ( strpos( $_SERVER['SCRIPT_FILENAME'], 'php.cgi' ) == strlen( $_SERVER['SCRIPT_FILENAME'] ) - 7 ) )
		$_SERVER['SCRIPT_FILENAME'] = $_SERVER['PATH_TRANSLATED'];

	// Fix for Dreamhost and other PHP as CGI hosts
	if ( strpos( $_SERVER['SCRIPT_NAME'], 'php.cgi' ) !== false )
		unset( $_SERVER['PATH_INFO'] );

	// Fix empty PHP_SELF
	$PHP_SELF = $_SERVER['PHP_SELF'];
	if ( empty( $PHP_SELF ) )
		$_SERVER['PHP_SELF'] = $PHP_SELF = preg_replace( '/(\?.*)?$/', '', $_SERVER["REQUEST_URI"] );
}

/**
 * Check for the required PHP version, and the MySQL extension or
 * a database drop-in.
 *
 * Dies if requirements are not met.
 *
 * @since 3.0.0
 * @access private
 *
 * @global string $required_php_version The required PHP version string.
 * @global string $app_version The version string.
 */
function wp_check_php_mysql_versions() {
	global $required_php_version, $app_version;
	$php_version = phpversion();

	if ( version_compare( $required_php_version, $php_version, '>' ) ) {
		wp_load_translations_early();

		$protocol = wp_get_server_protocol();
		header( sprintf( '%s 500 Internal Server Error', $protocol ), true, 500 );
		header( 'Content-Type: text/html; charset=utf-8' );
		/* translators: 1: Current PHP version number, 2:  version number, 3: Minimum required PHP version number */
		die( sprintf( __( 'Your server is running PHP version %1$s but the application %2$s requires at least %3$s.' ), $php_version, $app_version, $required_php_version ) );
	}

	if ( ! extension_loaded( 'mysql' ) && ! extension_loaded( 'mysqli' ) && ! extension_loaded( 'mysqlnd' ) && ! file_exists( APP_VIEWS_PATH . '/db.php' ) ) {
		wp_load_translations_early();

		$protocol = wp_get_server_protocol();
		header( sprintf( '%s 500 Internal Server Error', $protocol ), true, 500 );
		header( 'Content-Type: text/html; charset=utf-8' );
		die( __( 'Your PHP installation appears to be missing the MySQL extension which is required by the application.' ) );
	}
}

/**
 * Don't load all of the application when handling a favicon.ico request.
 *
 * Instead, send the headers for a zero-length favicon and bail.
 *
 * @since 3.0.0
 */
function wp_favicon_request() {
	if ( '/favicon.ico' == $_SERVER['REQUEST_URI'] ) {
		header('Content-Type: image/vnd.microsoft.icon');
		exit;
	}
}

/**
 * Die with a maintenance message when conditions are met.
 *
 * Checks for a file in the root directory named ".maintenance".
 * This file will contain the variable $upgrading, set to the time the file
 * was created. If the file was created less than 10 minutes ago, the application
 * enters maintenance mode and displays a message.
 *
 * The default message can be replaced by using a drop-in (maintenance.php in
 * the wp-content directory).
 *
 * @since 3.0.0
 * @access private
 *
 * @global int $upgrading the unix timestamp marking when upgrading began.
 */
function wp_maintenance() {
	if ( ! file_exists( ABSPATH . '.maintenance' ) || wp_installing() )
		return;

	global $upgrading;

	include( ABSPATH . '.maintenance' );
	// If the $upgrading timestamp is older than 10 minutes, don't die.
	if ( ( time() - $upgrading ) >= 600 )
		return;

	/**
	 * Filters whether to enable maintenance mode.
	 *
	 * This filter runs before it can be used by plugins. It is designed for
	 * non-web runtimes. If this filter returns true, maintenance mode will be
	 * active and the request will end. If false, the request will be allowed to
	 * continue processing even if maintenance mode should be active.
	 *
	 * @since 4.6.0
	 *
	 * @param bool $enable_checks Whether to enable maintenance mode. Default true.
	 * @param int  $upgrading     The timestamp set in the .maintenance file.
	 */
	if ( ! apply_filters( 'enable_maintenance_mode', true, $upgrading ) ) {
		return;
	}

	if ( file_exists( APP_VIEWS_PATH . '/maintenance.php' ) ) {
		require_once( APP_VIEWS_PATH . '/maintenance.php' );
		die();
	}

	wp_load_translations_early();

	$protocol = wp_get_server_protocol();
	header( "$protocol 503 Service Unavailable", true, 503 );
	header( 'Content-Type: text/html; charset=utf-8' );
	header( 'Retry-After: 600' );
?>
	<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml"<?php if ( is_rtl() ) echo ' dir="rtl"'; ?>>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php _e( 'Maintenance' ); ?></title>

	</head>
	<body>
		<h1><?php _e( 'Briefly unavailable for scheduled maintenance. Check back in a minute.' ); ?></h1>
	</body>
	</html>
<?php
	die();
}

/**
 * Start the micro-timer.
 *
 * @since 0.71
 * @access private
 *
 * @global float $timestart Unix timestamp set at the beginning of the page load.
 * @see timer_stop()
 *
 * @return bool Always returns true.
 */
function timer_start() {
	global $timestart;
	$timestart = microtime( true );
	return true;
}

/**
 * Retrieve or display the time from the page start to when function is called.
 *
 * @since 0.71
 *
 * @global float   $timestart Seconds from when timer_start() is called.
 * @global float   $timeend   Seconds from when function is called.
 *
 * @param int|bool $display   Whether to echo or return the results. Accepts 0|false for return,
 *                            1|true for echo. Default 0|false.
 * @param int      $precision The number of digits from the right of the decimal to display.
 *                            Default 3.
 * @return string The "second.microsecond" finished time calculation. The number is formatted
 *                for human consumption, both localized and rounded.
 */
function timer_stop( $display = 0, $precision = 3 ) {
	global $timestart, $timeend;
	$timeend = microtime( true );
	$timetotal = $timeend - $timestart;
	$r = ( function_exists( 'number_format_i18n' ) ) ? number_format_i18n( $timetotal, $precision ) : number_format( $timetotal, $precision );
	if ( $display )
		echo $r;
	return $r;
}

/**
 * Set PHP error reporting based on debug settings.
 *
 * Uses three constants: `APP_DEBUG`, `APP_DEBUG_DISPLAY`, and `APP_DEBUG_LOG`.
 * All three can be defined in the configuration file. By default,
 * `APP_DEBUG` and `APP_DEBUG_LOG` are set to false, and
 * `APP_DEBUG_DISPLAY` is set to true.
 *
 * When `APP_DEBUG` is true, all PHP notices are reported. The application will also
 * display internal notices: when a deprecated function, function
 * argument, or file is used. Deprecated code may be removed from a later
 * version.
 *
 * It is strongly recommended that plugin and theme developers use `APP_DEBUG`
 * in their development environments.
 *
 * `APP_DEBUG_DISPLAY` and `APP_DEBUG_LOG` perform no function unless `APP_DEBUG`
 * is true.
 *
 * When `APP_DEBUG_DISPLAY` is true, the application will force errors to be displayed.
 * `APP_DEBUG_DISPLAY` defaults to true. Defining it as null prevents the application
 * from changing the global configuration setting. Defining `APP_DEBUG_DISPLAY`
 * as false will force errors to be hidden.
 *
 * When `APP_DEBUG_LOG` is true, errors will be logged to debug.log in the content
 * directory.
 *
 * Errors are never displayed for XML-RPC, REST, and Ajax requests.
 *
 * @since 3.0.0
 * @access private
 */
function app_debug_mode() {
	/**
	 * Filters whether to allow the debug mode check to occur.
	 *
	 * This filter runs before it can be used by plugins. It is designed for
	 * non-web run-times. Returning false causes the `APP_DEBUG` and related
	 * constants to not be checked and the default php values for errors
	 * will be used unless you take care to update them yourself.
	 *
	 * @since 4.6.0
	 *
	 * @param bool $enable_debug_mode Whether to enable debug mode checks to occur. Default true.
	 */
	if ( ! apply_filters( 'enable_app_debug_mode_checks', true ) ){
		return;
	}

	if ( APP_DEV_MODE || APP_DEBUG ) {
		error_reporting( E_ALL );

		if ( APP_DEBUG_DISPLAY )
			ini_set( 'display_errors', 1 );
		elseif ( null !== APP_DEBUG_DISPLAY )
			ini_set( 'display_errors', 0 );

		if ( APP_DEBUG_LOG ) {
			ini_set( 'log_errors', 1 );
			ini_set( 'error_log', APP_VIEWS_PATH . '/debug.log' );
		}
	} else {
		error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
	}

	if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'APP_INSTALLING' ) && APP_INSTALLING ) || wp_doing_ajax() ) {
		@ini_set( 'display_errors', 0 );
	}
}

/**
 * Set the location of the language directory.
 *
 * To set directory manually, define the `APP_LANG_DIR` constant
 * in the configuration file.
 *
 * If the language directory exists within `APP_VIEWS_PATH`, it
 * is used. Otherwise the language directory is assumed to live
 * in `APP_INC`.
 *
 * @since 3.0.0
 * @access private
 */
function wp_set_lang_dir() {

	if ( ! defined( 'APP_LANG_DIR' ) ) {

		if ( file_exists( ABSPATH . 'app-languages' ) && @is_dir( ABSPATH . 'app-languages' ) || !@is_dir( APP_INC_PATH . '/languages' ) ) {

			/**
			 * Server path of the language directory.
			 *
			 * No leading slash, no trailing slash, full path, not relative to ABSPATH
			 *
			 * @since 2.1.0
			 */
			define( 'APP_LANG_DIR', ABSPATH . 'app-languages' );

			if ( ! defined( 'LANGDIR' ) ) {

				// Old static relative path maintained for limited backward compatibility - won't work in some cases.
				define( 'LANGDIR', ABSPATH . 'app-languages' );
			}

		} else {

			/**
			 * Server path of the language directory.
			 *
			 * No leading slash, no trailing slash, full path, not relative to `ABSPATH`.
			 *
			 * @since 2.1.0
			 */
			define( 'APP_LANG_DIR', APP_INC_PATH . '/languages' );

			if ( ! defined( 'LANGDIR' ) ) {

				// Old relative path maintained for backward compatibility.
				define( 'LANGDIR', APP_INC_DIR . '/languages' );
			}
		}
	}
}

/**
 * Load the database class file and instantiate the `$wpdb` global.
 *
 * @since 2.5.0
 *
 * @global wpdb $wpdb The Database class.
 */
function require_wp_db() {
	global $wpdb;

	require_once( APP_INC_PATH . '/app-db.php' );
	if ( file_exists( APP_VIEWS_PATH . '/db.php' ) )
		require_once( APP_VIEWS_PATH . '/db.php' );

	if ( isset( $wpdb ) ) {
		return;
	}

	$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
}

/**
 * Set the database table prefix and the format specifiers for database
 * table columns.
 *
 * Columns not listed here default to `%s`.
 *
 * @since 3.0.0
 * @access private
 *
 * @global wpdb $wpdb The Database class.
 * @global string $table_prefix The database table prefix.
 */
function wp_set_wpdb_vars() {
	global $wpdb, $table_prefix;
	if ( !empty( $wpdb->error ) )
		dead_db();

	$wpdb->field_types = array( 'post_author' => '%d', 'post_parent' => '%d', 'menu_order' => '%d', 'term_id' => '%d', 'term_group' => '%d', 'term_taxonomy_id' => '%d',
		'parent' => '%d', 'count' => '%d','object_id' => '%d', 'term_order' => '%d', 'ID' => '%d', 'comment_ID' => '%d', 'comment_post_ID' => '%d', 'comment_parent' => '%d',
		'user_id' => '%d', 'link_id' => '%d', 'link_owner' => '%d', 'link_rating' => '%d', 'option_id' => '%d', 'blog_id' => '%d', 'meta_id' => '%d', 'post_id' => '%d',
		'user_status' => '%d', 'umeta_id' => '%d', 'comment_karma' => '%d', 'comment_count' => '%d',
		// network:
		'active' => '%d', 'cat_id' => '%d', 'deleted' => '%d', 'lang_id' => '%d', 'mature' => '%d', 'public' => '%d', 'site_id' => '%d', 'spam' => '%d',
	);

	$prefix = $wpdb->set_prefix( $table_prefix );

	if ( is_wp_error( $prefix ) ) {
		wp_load_translations_early();
		wp_die(
			sprintf( __( '<strong>ERROR</strong>: %1$s in %2$s can only contain numbers, letters, and underscores.' ),
				'<code>$table_prefix</code>',
				'<code>app-config.php</code>'
			)
		);
	}
}

/**
 * Toggle `$_wp_using_ext_object_cache` on and off without directly
 * touching global.
 *
 * @since 3.7.0
 *
 * @global bool $_wp_using_ext_object_cache
 *
 * @param bool $using Whether external object cache is being used.
 * @return bool The current 'using' setting.
 */
function wp_using_ext_object_cache( $using = null ) {
	global $_wp_using_ext_object_cache;
	$current_using = $_wp_using_ext_object_cache;
	if ( null !== $using )
		$_wp_using_ext_object_cache = $using;
	return $current_using;
}

/**
 * Start the object cache.
 *
 * If an object-cache.php file exists in the wp-content directory,
 * it uses that drop-in as an external object cache.
 *
 * @since 3.0.0
 * @access private
 *
 * @global array $wp_filter Stores all of the filters.
 */
function wp_start_object_cache() {
	global $wp_filter;

	$first_init = false;
 	if ( ! function_exists( 'wp_cache_init' ) ) {
		if ( file_exists( APP_VIEWS_PATH . '/object-cache.php' ) ) {
			require_once ( APP_VIEWS_PATH . '/object-cache.php' );
			if ( function_exists( 'wp_cache_init' ) ) {
				wp_using_ext_object_cache( true );
			}

			// Re-initialize any hooks added manually by object-cache.php
			if ( $wp_filter ) {
				$wp_filter = WP_Hook::build_preinitialized_hooks( $wp_filter );
			}
		}

		$first_init = true;
	} elseif ( ! wp_using_ext_object_cache() && file_exists( APP_VIEWS_PATH . '/object-cache.php' ) ) {
		/*
		 * Sometimes advanced-cache.php can load object-cache.php before
		 * it is loaded here. This breaks the function_exists check above
		 * and can result in `$_wp_using_ext_object_cache` being set
		 * incorrectly. Double check if an external cache exists.
		 */
		wp_using_ext_object_cache( true );
	}

	if ( ! wp_using_ext_object_cache() ) {
		require_once ( APP_INC_PATH . '/cache.php' );
	}

	/*
	 * If cache supports reset, reset instead of init if already
	 * initialized. Reset signals to the cache that global IDs
	 * have changed and it may need to update keys and cleanup caches.
	 */
	if ( ! $first_init && function_exists( 'wp_cache_switch_to_blog' ) ) {
		wp_cache_switch_to_blog( get_current_blog_id() );
	} elseif ( function_exists( 'wp_cache_init' ) ) {
		wp_cache_init();
	}

	if ( function_exists( 'wp_cache_add_global_groups' ) ) {
		wp_cache_add_global_groups( array( 'users', 'userlogins', 'usermeta', 'user_meta', 'useremail', 'userslugs', 'site-transient', 'site-options', 'blog-lookup', 'blog-details', 'site-details', 'rss', 'global-posts', 'blog-id-cache', 'networks', 'sites' ) );
		wp_cache_add_non_persistent_groups( array( 'counts', 'plugins' ) );
	}
}

/**
 * Redirect to the installer
 *
 * Redirects if the application is not installed.
 * Dies with an error message when network is enabled.
 *
 * @since  3.0.0
 * @access private
 * @return void
 */
function app_not_installed() {

	if ( is_network() ) {

		if ( ! is_blog_installed() && ! wp_installing() ) {

			nocache_headers();

			wp_die( __( 'The site you have requested is not installed properly. Please contact the system administrator.' ) );
		}

	} elseif ( ! is_blog_installed() && ! wp_installing() ) {

		nocache_headers();

		require( APP_INC_PATH . '/kses.php' );
		require( APP_INC_PATH . '/pluggable.php' );
		require( APP_INC_PATH . '/formatting.php' );

		$link = wp_guess_url() . '/app-views/includes/install.php';

		wp_redirect( $link );

		die();
	}
}

/**
 * Retrieve an array of must-use plugin files.
 *
 * The default directory is wp-content/mu-plugins. To change the default
 * directory manually, define `APP_EXTENSIONS_PATH` and `APP_EXTEND_URL`
 * in the configuration file.
 *
 * @since 3.0.0
 * @access private
 *
 * @return array Files to include.
 */
function wp_get_mu_plugins() {

	$mu_plugins = [];

	if ( ! is_dir( APP_EXTENSIONS_PATH ) ) {
		return $mu_plugins;
	}

	if ( ! $dh = opendir( APP_EXTENSIONS_PATH ) ) {
		return $mu_plugins;
	}

	while ( ( $plugin = readdir( $dh ) ) !== false ) {

		if ( substr( $plugin, -4 ) == '.php' ) {
			$mu_plugins[] = APP_EXTENSIONS_PATH . '/' . $plugin;
		}
	}

	closedir( $dh );
	sort( $mu_plugins );

	return $mu_plugins;
}

/**
 * Retrieve an array of active and valid plugin files.
 *
 * While upgrading or installing, no plugins are returned.
 *
 * The default directory is wp-content/plugins. To change the default
 * directory manually, define `APP_PLUGINS_PATH` and `APP_PLUGIN_URL`
 * in the configuration file.
 *
 * @since 3.0.0
 * @access private
 *
 * @return array Files.
 */
function wp_get_active_and_valid_plugins() {
	$plugins = array();
	$active_plugins = (array) get_option( 'active_plugins', array() );

	// Check for hacks file if the option is enabled
	if ( get_option( 'hack_file' ) && file_exists( ABSPATH . 'my-hacks.php' ) ) {
		_deprecated_file( 'my-hacks.php', '1.5.0' );
		array_unshift( $plugins, ABSPATH . 'my-hacks.php' );
	}

	if ( empty( $active_plugins ) || wp_installing() )
		return $plugins;

	$network_plugins = is_network() ? wp_get_active_network_plugins() : false;

	foreach ( $active_plugins as $plugin ) {
		if ( ! validate_file( $plugin ) // $plugin must validate as file
			&& '.php' == substr( $plugin, -4 ) // $plugin must end with '.php'
			&& file_exists( APP_PLUGINS_PATH . '/' . $plugin ) // $plugin must exist
			// not already included as a network plugin
			&& ( ! $network_plugins || ! in_array( APP_PLUGINS_PATH . '/' . $plugin, $network_plugins ) )
			)
		$plugins[] = APP_PLUGINS_PATH . '/' . $plugin;
	}
	return $plugins;
}

/**
 * Set internal encoding.
 *
 * In most cases the default internal encoding is latin1, which is
 * of no use, since we want to use the `mb_` functions for `utf-8` strings.
 *
 * @since 3.0.0
 * @access private
 */
function wp_set_internal_encoding() {
	if ( function_exists( 'mb_internal_encoding' ) ) {
		$charset = get_option( 'blog_charset' );
		if ( ! $charset || ! @mb_internal_encoding( $charset ) )
			mb_internal_encoding( 'UTF-8' );
	}
}

/**
 * Add magic quotes to `$_GET`, `$_POST`, `$_COOKIE`, and `$_SERVER`.
 *
 * Also forces `$_REQUEST` to be `$_GET + $_POST`. If `$_SERVER`,
 * `$_COOKIE`, or `$_ENV` are needed, use those superglobals directly.
 *
 * @since 3.0.0
 * @access private
 */
function wp_magic_quotes() {

	// Escape with wpdb.
	$_GET    = add_magic_quotes( $_GET    );
	$_POST   = add_magic_quotes( $_POST   );
	$_COOKIE = add_magic_quotes( $_COOKIE );
	$_SERVER = add_magic_quotes( $_SERVER );

	// Force REQUEST to be GET + POST.
	$_REQUEST = array_merge( $_GET, $_POST );
}

/**
 * Runs just before PHP shuts down execution.
 *
 * @since 1.2.0
 * @access private
 */
function shutdown_action_hook() {
	/**
	 * Fires just before PHP shuts down execution.
	 *
	 * @since 1.2.0
	 */
	do_action( 'shutdown' );

	wp_cache_close();
}

/**
 * Copy an object.
 *
 * @since 2.7.0
 * @deprecated 3.2.0
 *
 * @param object $object The object to clone.
 * @return object The cloned object.
 */
function wp_clone( $object ) {
	// Use parens for clone to accommodate PHP 4. See #17880
	return clone( $object );
}

/**
 * Whether the current request is for an administrative interface page.
 *
 * Does not check if the user is an administrator; current_user_can()
 * for checking roles and capabilities.
 *
 * @since 1.5.1
 *
 * @global WP_Screen $current_screen
 *
 * @return bool True if inside the administration interface, false otherwise.
 */
function is_admin() {
	if ( isset( $GLOBALS['current_screen'] ) )
		return $GLOBALS['current_screen']->in_admin();
	elseif ( defined( 'APP_ADMIN' ) )
		return APP_ADMIN;

	return false;
}

/**
 * Whether the current request is for a site's admininstrative interface.
 *
 * e.g. `/wp-admin/`
 *
 * Does not check if the user is an administrator; current_user_can()
 * for checking roles and capabilities.
 *
 * @since 3.1.0
 *
 * @global WP_Screen $current_screen
 *
 * @return bool True if inside the blog administration pages.
 */
function is_blog_admin() {
	if ( isset( $GLOBALS['current_screen'] ) )
		return $GLOBALS['current_screen']->in_admin( 'site' );
	elseif ( defined( 'WP_BLOG_ADMIN' ) )
		return WP_BLOG_ADMIN;

	return false;
}

/**
 * Whether the current request is for the network administrative interface.
 *
 * e.g. `/wp-admin/network/`
 *
 * Does not check if the user is an administrator; current_user_can()
 * for checking roles and capabilities.
 *
 * @since 3.1.0
 *
 * @global WP_Screen $current_screen
 *
 * @return bool True if inside the network administration pages.
 */
function is_network_admin() {
	if ( isset( $GLOBALS['current_screen'] ) )
		return $GLOBALS['current_screen']->in_admin( 'network' );
	elseif ( defined( 'APP_NETWORK_ADMIN' ) )
		return APP_NETWORK_ADMIN;

	return false;
}

/**
 * Whether the current request is for a user admin screen.
 *
 * e.g. `/wp-admin/user/`
 *
 * Does not inform on whether the user is an admin! Use capability
 * checks to tell if the user should be accessing a section or not
 * current_user_can().
 *
 * @since 3.1.0
 *
 * @global WP_Screen $current_screen
 *
 * @return bool True if inside the user administration pages.
 */
function is_user_admin() {
	if ( isset( $GLOBALS['current_screen'] ) )
		return $GLOBALS['current_screen']->in_admin( 'user' );
	elseif ( defined( 'WP_USER_ADMIN' ) )
		return WP_USER_ADMIN;

	return false;
}

/**
 * If network is enabled
 *
 * @since  1.0.0
 * @return bool Returns true if network is enabled, false otherwise.
 */
function is_network() {

	if ( defined( 'APP_NETWORK' ) ) {
		return APP_NETWORK;
	}

	if ( defined( 'SUBDOMAIN_INSTALL' ) || defined( 'VHOST' ) || defined( 'SUNRISE' ) ) {
		return true;
	}

	return false;
}

/**
 * Retrieve the current site ID.
 *
 * @since 3.1.0
 *
 * @global int $blog_id
 *
 * @return int Site ID.
 */
function get_current_blog_id() {
	global $blog_id;
	return absint($blog_id);
}

/**
 * Retrieves the current network ID.
 *
 * @since 4.6.0
 *
 * @return int The ID of the current network.
 */
function get_current_network_id() {
	if ( ! is_network() ) {
		return 1;
	}

	$current_network = get_network();

	if ( ! isset( $current_network->id ) ) {
		return get_main_network_id();
	}

	return absint( $current_network->id );
}

/**
 * Attempt an early load of translations.
 *
 * Used for errors encountered during the initial loading process, before
 * the locale has been properly detected and loaded.
 *
 * Designed for unusual load sequences (like config.php) or for when
 * the script will then terminate with an error, otherwise there is a risk
 * that a file can be double-included.
 *
 * @since 3.4.0
 * @access private
 *
 * @global WP_Locale $wp_locale The date and time locale object.
 *
 * @staticvar bool $loaded
 */
function wp_load_translations_early() {
	global $wp_locale;

	static $loaded = false;
	if ( $loaded )
		return;
	$loaded = true;

	if ( function_exists( 'did_action' ) && did_action( 'init' ) )
		return;

	// We need $wp_local_package
	require APP_INC_PATH . '/version.php';

	// Translation and localization
	require_once APP_INC_PATH . '/pomo/mo.php';
	require_once APP_INC_PATH . '/l10n.php';
	require_once APP_INC_PATH . '/classes/includes/class-app-locale.php';
	require_once APP_INC_PATH . '/classes/includes/class-app-locale-switcher.php';

	// General libraries
	require_once APP_INC_PATH . '/plugin.php';

	$locales = $locations = array();

	while ( true ) {
		if ( defined( 'APP_LANG' ) ) {
			if ( '' == APP_LANG )
				break;
			$locales[] = APP_LANG;
		}

		if ( isset( $wp_local_package ) )
			$locales[] = $wp_local_package;

		if ( ! $locales )
			break;

		if ( defined( 'APP_LANG_DIR' ) && @is_dir( APP_LANG_DIR ) )
			$locations[] = APP_LANG_DIR;

		if ( defined( 'APP_VIEWS_PATH' ) && @is_dir( APP_VIEWS_PATH . '/languages' ) )
			$locations[] = APP_VIEWS_PATH . '/languages';

		if ( @is_dir( ABSPATH . 'app-languages' ) )
			$locations[] = ABSPATH . 'app-languages';

		if ( @is_dir( APP_INC_PATH . '/languages' ) )
			$locations[] = APP_INC_PATH . '/languages';

		if ( ! $locations )
			break;

		$locations = array_unique( $locations );

		foreach ( $locales as $locale ) {
			foreach ( $locations as $location ) {
				if ( file_exists( $location . '/' . $locale . '.mo' ) ) {
					load_textdomain( 'default', $location . '/' . $locale . '.mo' );
					if ( defined( 'WP_SETUP_CONFIG' ) && file_exists( $location . '/admin-' . $locale . '.mo' ) )
						load_textdomain( 'default', $location . '/admin-' . $locale . '.mo' );
					break 2;
				}
			}
		}

		break;
	}

	$wp_locale = new WP_Locale();
}

/**
 * Check or set whether the application is in "installation" mode.
 *
 * If the `APP_INSTALLING` constant is defined during the bootstrap, `wp_installing()` will default to `true`.
 *
 * @since 4.4.0
 *
 * @staticvar bool $installing
 *
 * @param bool $is_installing Optional. True to set WP into Installing mode, false to turn Installing mode off.
 *                            Omit this parameter if you only want to fetch the current status.
 * @return bool True if WP is installing, otherwise false. When a `$is_installing` is passed, the function will
 *              report whether WP was in installing mode prior to the change to `$is_installing`.
 */
function wp_installing( $is_installing = null ) {
	static $installing = null;

	// Support for the `APP_INSTALLING` constant, defined before WP is loaded.
	if ( is_null( $installing ) ) {
		$installing = defined( 'APP_INSTALLING' ) && APP_INSTALLING;
	}

	if ( ! is_null( $is_installing ) ) {
		$old_installing = $installing;
		$installing = $is_installing;
		return (bool) $old_installing;
	}

	return (bool) $installing;
}

/**
 * Determines if SSL is used.
 *
 * @since 2.6.0
 * @since 4.6.0 Moved from functions.php to load.php.
 *
 * @return bool True if SSL, otherwise false.
 */
function is_ssl() {
	if ( isset( $_SERVER['HTTPS'] ) ) {
		if ( 'on' == strtolower( $_SERVER['HTTPS'] ) ) {
			return true;
		}

		if ( '1' == $_SERVER['HTTPS'] ) {
			return true;
		}
	} elseif ( isset($_SERVER['SERVER_PORT'] ) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
		return true;
	}
	return false;
}

/**
 * Converts a shorthand byte value to an integer byte value.
 *
 * @since 2.3.0
 * @since 4.6.0 Moved from media.php to load.php.
 *
 * @link https://secure.php.net/manual/en/function.ini-get.php
 * @link https://secure.php.net/manual/en/faq.using.php#faq.using.shorthandbytes
 *
 * @param string $value A (PHP ini) byte value, either shorthand or ordinary.
 * @return int An integer byte value.
 */
function app_convert_hr_to_bytes( $value ) {
	$value = strtolower( trim( $value ) );
	$bytes = (int) $value;

	if ( false !== strpos( $value, 'g' ) ) {
		$bytes *= GB_IN_BYTES;
	} elseif ( false !== strpos( $value, 'm' ) ) {
		$bytes *= MB_IN_BYTES;
	} elseif ( false !== strpos( $value, 'k' ) ) {
		$bytes *= KB_IN_BYTES;
	}

	// Deal with large (float) values which run into the maximum integer size.
	return min( $bytes, PHP_INT_MAX );
}

/**
 * Determines whether a PHP ini value is changeable at runtime.
 *
 * @since 4.6.0
 *
 * @staticvar array $ini_all
 *
 * @link https://secure.php.net/manual/en/function.ini-get-all.php
 *
 * @param string $setting The name of the ini setting to check.
 * @return bool True if the value is changeable at runtime. False otherwise.
 */
function app_is_ini_value_changeable( $setting ) {
	static $ini_all;

	if ( ! isset( $ini_all ) ) {
		$ini_all = false;
		// Sometimes `ini_get_all()` is disabled via the `disable_functions` option for "security purposes".
		if ( function_exists( 'ini_get_all' ) ) {
			$ini_all = ini_get_all();
		}
 	}

	// Bit operator to workaround https://bugs.php.net/bug.php?id=44936 which changes access level to 63 in PHP 5.2.6 - 5.2.17.
	if ( isset( $ini_all[ $setting ]['access'] ) && ( INI_ALL === ( $ini_all[ $setting ]['access'] & 7 ) || INI_USER === ( $ini_all[ $setting ]['access'] & 7 ) ) ) {
		return true;
	}

	// If we were unable to retrieve the details, fail gracefully to assume it's changeable.
	if ( ! is_array( $ini_all ) ) {
		return true;
	}

	return false;
}

/**
 * Determines whether the current request is a Ajax request.
 *
 * @since 4.7.0
 *
 * @return bool True if it's an Ajax request, false otherwise.
 */
function wp_doing_ajax() {
	/**
	 * Filters whether the current request is a Ajax request.
	 *
	 * @since 4.7.0
	 *
	 * @param bool $wp_doing_ajax Whether the current request is a Ajax request.
	 */
	return apply_filters( 'wp_doing_ajax', defined( 'DOING_AJAX' ) && DOING_AJAX );
}

/**
 * Determines whether the current request is a cron request.
 *
 * @since 4.8.0
 *
 * @return bool True if it's a cron request, false otherwise.
 */
function wp_doing_cron() {
	/**
	 * Filters whether the current request is a cron request.
	 *
	 * @since 4.8.0
	 *
	 * @param bool $wp_doing_cron Whether the current request is a cron request.
	 */
	return apply_filters( 'wp_doing_cron', defined( 'DOING_CRON' ) && DOING_CRON );
}

/**
 * Check whether variable is a Error.
 *
 * Returns true if $thing is an object of the WP_Error class.
 *
 * @since 2.1.0
 *
 * @param mixed $thing Check if unknown variable is a WP_Error object.
 * @return bool True, if WP_Error. False, if not WP_Error.
 */
function is_wp_error( $thing ) {
	return ( $thing instanceof WP_Error );
}

/**
 * Determines whether file modifications are allowed.
 *
 * @since 4.8.0
 *
 * @param string $context The usage context.
 * @return bool True if file modification is allowed, false otherwise.
 */
function wp_is_file_mod_allowed( $context ) {
	/**
	 * Filters whether file modifications are allowed.
	 *
	 * @since 4.8.0
	 *
	 * @param bool   $file_mod_allowed Whether file modifications are allowed.
	 * @param string $context          The usage context.
	 */
	return apply_filters( 'file_mod_allowed', ! defined( 'DISALLOW_FILE_MODS' ) || ! DISALLOW_FILE_MODS, $context );
}

/**
 * Start scraping edited file errors.
 *
 * @since 4.9.0
 */
function wp_start_scraping_edited_file_errors() {
	if ( ! isset( $_REQUEST['wp_scrape_key'] ) || ! isset( $_REQUEST['wp_scrape_nonce'] ) ) {
		return;
	}
	$key = substr( sanitize_key( wp_unslash( $_REQUEST['wp_scrape_key'] ) ), 0, 32 );
	$nonce = wp_unslash( $_REQUEST['wp_scrape_nonce'] );

	if ( get_transient( 'scrape_key_' . $key ) !== $nonce ) {
		echo "###### wp_scraping_result_start:$key ######";
		echo wp_json_encode( array(
			'code' => 'scrape_nonce_failure',
			'message' => __( 'Scrape nonce check failed. Please try again.' ),
		) );
		echo "###### wp_scraping_result_end:$key ######";
		die();
	}
	register_shutdown_function( 'wp_finalize_scraping_edited_file_errors', $key );
}

/**
 * Finalize scraping for edited file errors.
 *
 * @since 4.9.0
 *
 * @param string $scrape_key Scrape key.
 */
function wp_finalize_scraping_edited_file_errors( $scrape_key ) {
	$error = error_get_last();
	echo "\n###### wp_scraping_result_start:$scrape_key ######\n";
	if ( ! empty( $error ) && in_array( $error['type'], array( E_CORE_ERROR, E_COMPILE_ERROR, E_ERROR, E_PARSE, E_USER_ERROR, E_RECOVERABLE_ERROR ), true ) ) {
		$error = str_replace( ABSPATH, '', $error );
		echo wp_json_encode( $error );
	} else {
		echo wp_json_encode( true );
	}
	echo "\n###### wp_scraping_result_end:$scrape_key ######\n";
}

/**
 * Defines initial constants
 *
 * @since  Previous 3.0.0
 * @global int $blog_id The current site ID.
 * @return void
 */
function app_initial_constants() {

	global $blog_id;

	/**#@+
	 * Constants for expressing human-readable data sizes in their respective number of bytes.
	 *
	 * @since Previous 4.4.0
	 */
	define( 'KB_IN_BYTES', 1024 );
	define( 'MB_IN_BYTES', 1024 * KB_IN_BYTES );
	define( 'GB_IN_BYTES', 1024 * MB_IN_BYTES );
	define( 'TB_IN_BYTES', 1024 * GB_IN_BYTES );
	/**#@-*/

	$current_limit     = @ini_get( 'memory_limit' );
	$current_limit_int = app_convert_hr_to_bytes( $current_limit );

	// Define memory limits.
	if ( ! defined( 'APP_MEMORY_LIMIT' ) ) {

		if ( false === app_is_ini_value_changeable( 'memory_limit' ) ) {
			define( 'APP_MEMORY_LIMIT', $current_limit );
		} elseif ( is_network() ) {
			define( 'APP_MEMORY_LIMIT', '64M' );
		} else {
			define( 'APP_MEMORY_LIMIT', '40M' );
		}
	}

	if ( ! defined( 'APP_MAX_MEMORY_LIMIT' ) ) {

		if ( false === app_is_ini_value_changeable( 'memory_limit' ) ) {
			define( 'APP_MAX_MEMORY_LIMIT', $current_limit );
		} elseif ( -1 === $current_limit_int || $current_limit_int > 268435456 /* = 256M */ ) {
			define( 'APP_MAX_MEMORY_LIMIT', $current_limit );
		} else {
			define( 'APP_MAX_MEMORY_LIMIT', '256M' );
		}
	}

	// Set memory limits.
	$app_limit_int = app_convert_hr_to_bytes( APP_MEMORY_LIMIT );

	if ( -1 !== $current_limit_int && ( -1 === $app_limit_int || $app_limit_int > $current_limit_int ) ) {
		@ini_set( 'memory_limit', APP_MEMORY_LIMIT );
	}

	if ( ! isset( $blog_id ) ) {
		$blog_id = 1;
	}



	/**#@+
	 * Constants for expressing human-readable intervals
	 * in their respective number of seconds.
	 *
	 * Please note that these values are approximate and are provided for convenience.
	 * For example, MONTH_IN_SECONDS wrongly assumes every month has 30 days and
	 * YEAR_IN_SECONDS does not take leap years into account.
	 *
	 * If you need more accuracy consider using the DateTime class.
	 * @link https://secure.php.net/manual/en/class.datetime.php
	 *
	 * @since Previous 3.5.0
	 * @since Previous 4.4.0 Introduced `MONTH_IN_SECONDS`.
	 */
	define( 'MINUTE_IN_SECONDS', 60 );
	define( 'HOUR_IN_SECONDS', 60 * MINUTE_IN_SECONDS );
	define( 'DAY_IN_SECONDS', 24 * HOUR_IN_SECONDS );
	define( 'WEEK_IN_SECONDS', 7 * DAY_IN_SECONDS );
	define( 'MONTH_IN_SECONDS', 30 * DAY_IN_SECONDS );
	define( 'YEAR_IN_SECONDS', 365 * DAY_IN_SECONDS );
	/**#@-*/
}

/**
 * Defines cookie related constants
 *
 * Defines constants after network is loaded.
 * @since  Previous 3.0.0
 * @return void
 */
function app_cookie_constants() {

	/**
	 * Used to guarantee unique hash cookies
	 *
	 * @since Previous 1.5.0
	 */
	if ( ! defined( 'COOKIEHASH' ) ) {

		$siteurl = get_site_option( 'siteurl' );

		if ( $siteurl ) {
			define( 'COOKIEHASH', md5( $siteurl ) );
		} else {
			define( 'COOKIEHASH', '' );
		}
	}

	/**
	 * @since Previous 2.0.0
	 */
	if ( ! defined( 'USER_COOKIE' ) ) {
		define( 'USER_COOKIE', 'wordpressuser_' . COOKIEHASH );
	}

	/**
	 * @since Previous 2.0.0
	 */
	if ( ! defined( 'PASS_COOKIE' ) ) {
		define( 'PASS_COOKIE', 'wordpresspass_' . COOKIEHASH );
	}

	/**
	 * @since Previous 2.5.0
	 */
	if ( ! defined( 'AUTH_COOKIE' ) ) {
		define( 'AUTH_COOKIE', 'wordpress_' . COOKIEHASH );
	}

	/**
	 * @since Previous 2.6.0
	 */
	if ( ! defined( 'SECURE_AUTH_COOKIE' ) ) {
		define( 'SECURE_AUTH_COOKIE', 'wordpress_sec_' . COOKIEHASH );
	}

	/**
	 * @since Previous 2.6.0
	 */
	if ( ! defined( 'LOGGED_IN_COOKIE' ) ) {
		define( 'LOGGED_IN_COOKIE', 'wordpress_logged_in_' . COOKIEHASH );
	}

	/**
	 * @since Previous 2.3.0
	 */
	if ( ! defined( 'TEST_COOKIE' ) ) {
		define( 'TEST_COOKIE', 'wordpress_test_cookie' );
	}

	/**
	 * @since Previous 1.2.0
	 */
	if ( ! defined( 'COOKIEPATH' ) ) {
		define( 'COOKIEPATH', preg_replace( '|https?://[^/]+|i', '', get_option( 'home' ) . '/' ) );
	}

	/**
	 * @since Previous 1.5.0
	 */
	if ( ! defined( 'SITECOOKIEPATH' ) ) {
		define( 'SITECOOKIEPATH', preg_replace( '|https?://[^/]+|i', '', get_option( 'siteurl' ) . '/' ) );
	}

	/**
	 * @since Previous 2.6.0
	 */
	if ( ! defined( 'ADMIN_COOKIE_PATH' ) ) {
		define( 'ADMIN_COOKIE_PATH', SITECOOKIEPATH . 'wp-admin' );
	}

	/**
	 * @since Previous 2.6.0
	 */
	if ( ! defined( 'PLUGINS_COOKIE_PATH' ) ) {

		if ( defined( 'APP_EXTEND_DIR' ) && defined( 'APP_PLUGINS_DIR' ) ) {
			$url = get_option( 'siteurl' ) . '/' . APP_EXTEND_DIR . '/' . APP_PLUGINS_DIR;
		} else {
			$url = get_option( 'siteurl' ) . '/app-extend/plugins';
		}

		define( 'PLUGINS_COOKIE_PATH', preg_replace( '|https?://[^/]+|i', '', $url ) );
	}

	/**
	 * @since Previous 2.0.0
	 */
	if ( ! defined( 'COOKIE_DOMAIN' ) ) {
		define( 'COOKIE_DOMAIN', false );
	}
}

/**
 * Defines cookie related constants
 *
 * @since  Previous 3.0.0
 * @return void
 */
function app_ssl_constants() {

	/**
	 * @since Previous 2.6.0
	 */
	if ( ! defined( 'FORCE_SSL_ADMIN' ) ) {

		if ( 'https' === parse_url( get_option( 'siteurl' ), PHP_URL_SCHEME ) ) {
			define( 'FORCE_SSL_ADMIN', true );
		} else {
			define( 'FORCE_SSL_ADMIN', false );
		}
	}

	force_ssl_admin( FORCE_SSL_ADMIN );
}

/**
 * Defines functionality related constants
 *
 * @since  Previous 3.0.0
 * @return void
 */
function app_functionality_constants() {

	/**
	 * Autosave
	 *
	 * @since Previous 2.5.0
	 * @var   integer
	 */
	if ( ! defined( 'AUTOSAVE_INTERVAL' ) ) {
		define( 'AUTOSAVE_INTERVAL', 60 );
	}

	/**
	 * Empty trash
	 *
	 * @since Previous 2.9.0
	 * @var   integer
	 */
	if ( ! defined( 'EMPTY_TRASH_DAYS' ) ) {
		define( 'EMPTY_TRASH_DAYS', 30 );
	}

	/**
	 * Post revisions
	 *
	 * @since 1.0.0
	 * @var   boolean
	 */
	if ( ! defined( 'APP_POST_REVISIONS' ) ) {
		define( 'APP_POST_REVISIONS', true );
	}

	/**
	 * Chron lock time out
	 *
	 * Define in seconds.
	 *
	 * @since Previous 3.3.0
	 * @var   integer
	 */
	if ( ! defined( 'APP_CRON_LOCK_TIMEOUT' ) ) {
		define( 'APP_CRON_LOCK_TIMEOUT', 60 );
	}
}

/**
 * Defines templating related constants
 *
 * @since  Previous 3.0.0
 * @return void
 */
function app_templating_constants() {

	/**
	 * Filesystem path to the current active template directory
	 * @since Previous 1.5.0
	 */
	define( 'TEMPLATEPATH', get_template_directory() );

	/**
	 * Filesystem path to the current active template stylesheet directory
	 * @since Previous 2.1.0
	 */
	define( 'STYLESHEETPATH', get_stylesheet_directory() );

}
