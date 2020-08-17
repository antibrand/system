<?php
/**
 * Not writable message for the config page
 *
 * @package App_Package
 * @subpackage Administration
 */

?>
<div class="setup-install-wrap setup-install-no-write">

	<main class="config-content">

		<?php
			echo sprintf(
				'<p>%1s <code>%2s</code> %3s</p>',
				__( 'The' ),
				'app-config.php',
				__( 'file cannot be written as needed. You can manually create the file in the root directory of the website management system and paste the following code into it.' )
			);
		?>

		<p><?php _e( 'When the config file is ready, click the "Run Installation" button.' ); ?></p>

		<p class="copy-sample-config">
			<button class="button" onclick="selectText( 'sample-config' )"><?php _e( 'Select All to Copy' ); ?></button>
			<a href="<?php echo esc_url( $install ); ?>" class="button"><?php _e( 'Run Installation' ); ?></a>
		</p>

		<pre id="sample-config" class="sample-config-code">
&lt;?php
/**
 * Base configuration for the website management system
 *
 * The configuration file creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to the configuration file and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @package App_Package
 */

/**
 * MySQL settings
 *
 * You can get this info from your web host.
 */

// Database name.
define( 'DB_NAME', 'database_name_here' );

// Database username.
define( 'DB_USER', 'username_here' );

// Database password.
define( 'DB_PASSWORD', 'password_here' );

// Database hostname.
define( 'DB_HOST', 'localhost' );

// Database charset to use in creating database tables.
define( 'DB_CHARSET', 'utf8' );

// Database collate type. Don't change this if in doubt.
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 */
define( 'AUTH_KEY',         'Put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'Put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'Put your unique phrase here' );
define( 'NONCE_KEY',        'Put your unique phrase here' );
define( 'AUTH_SALT',        'Put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'Put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'Put your unique phrase here' );
define( 'NONCE_SALT',       'Put your unique phrase here' );

/**
 * Database table prefix
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'app_';

/**
 * User login directory
 *
 * The default login directory is `/login`. You can
 * rename the directory and define the directory
 * name here.
 */
define( 'APP_LOGIN', 'login' );

/**
 * Disable automatic updates.
 *
 * This will prevent WordPress overwriting files.
 *
 * @todo Review this if updates are removed entirely.
 */
define( 'automatic_updater_disabled', true );
define( 'wp_auto_update_core', false );

// PHP memory limit for this site.
// define( 'APP_MEMORY_LIMIT', '256M' );

// Increase admin-side memory limit.
// define( 'APP_MAX_MEMORY_LIMIT', '256M' );

/**
 * Site development
 *
 * It is strongly recommended that plugin and theme developers use
 * APP_DEBUG in their development environments.
 */
define( 'APP_DEV_MODE', false );
define( 'APP_DEBUG', false );
define( 'APP_DEBUG_LOG', false );
define( 'APP_DEBUG_DISPLAY', true );

// Switch for local dev
define( 'WP_LOCAL_DEV', false );

// Use local URL if WP_LOCAL_DEV is true.
if ( defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV ) {
	// update_option( 'siteurl', 'https://local.example.dev' );
	// update_option( 'home', 'https://local.example.dev' );
}

// Set false to load scripts & styles separately.
// define( 'CONCATENATE_SCRIPTS', true );

/**
 * Network sites
 *
 * This is provided for reference. To begin network
 * activation change `APP_ALLOW_NETWORK` to `true`.
 *
 * Once acivated the user interface will provide
 * definitions with which to replace those
 * following `APP_ALLOW_NETWORK`.
 */
// define( 'APP_ALLOW_NETWORK', true );
// define( 'APP_NETWORK', false );
// define( 'SUBDOMAIN_INSTALL', '' );
// define( 'DOMAIN_CURRENT_SITE', '' );
// define( 'PATH_CURRENT_SITE', '' );
// define( 'SITE_ID_CURRENT_SITE', 1 );
// define( 'BLOG_ID_CURRENT_SITE', 1 );

// Force SSL for registration & login only.
// define( 'FORCE_SSL_LOGIN', false );

// Force SSL for the entire admin.
// define( 'FORCE_SSL_ADMIN', false );

// Disable the file editors.
// define( 'DISALLOW_FILE_EDIT', true );

// Don't allow users to update core, plugins, or themes.
// define( 'DISALLOW_FILE_MODS', true );

// Allow editing images to replace the originals.
// define( 'IMAGE_EDIT_OVERWRITE', true );

// Use unminified scripts.
// define( 'SCRIPT_DEBUG', true );

// Require analyzing the global $wpdb object.
// define( 'SAVEQUERIES', true );

// Site compression.
// define( 'COMPRESS_SCRIPTS', false );
// define( 'COMPRESS_CSS', false );
// define( 'ENFORCE_GZIP', false );

/**
 * End customization
 *
 * Do not add or edit anything below this comment block.
 */

// Absolute path to the app directory.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/**
 * User login path
 *
 * The default login directory is `/login`. You can
 * rename the directory and define the directory
 * name here.
 */
define( 'APP_LOGIN', '/' . 'login' . '/' );

/**
 * System translation
 *
 * The following two translation definitions
 * are provided for reference while local
 * translations are worked out. The were
 * deprecated in WP 4.0.0.
 */

// Default language.
// define( 'APP_LANG', 'en_US' );

// Default language directory.
// define( 'APP_LANG_DIR', ABSPATH . 'app-languages' );

// Sets up vars and included files.
require( ABSPATH . 'app-settings.php' );

		</pre>

		<p>
			<button class="button" onclick="selectText( 'sample-config' )"><?php _e( 'Select All to Copy' ); ?></button>
			<a href="<?php echo esc_url( $install ); ?>" class="button"><?php _e( 'Run Installation' ); ?></a>
		</p>

	</main>
</div>

<script>
// Select sample code when button focused.
function selectText( containerid ) {

	if ( document.selection ) {
		var range = document.body.createTextRange();
		range.moveToElementText( document.getElementById( containerid ) );
		range.select();

	} else if ( window.getSelection ) {
		var range = document.createRange();
		range.selectNode( document.getElementById( containerid ) );
		window.getSelection().addRange( range );
	}
}
</script>
