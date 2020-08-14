<?php
/**
 * Website management system installer
 *
 * @package App_Package
 * @subpackage Administration
 */

// If a PHP server is not running.
if ( false ) {
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Error: PHP is not running</title>
</head>
<body class="app-core-ui">
	<h1>Error: PHP is not running</h1>
	<p>The website management system requires that your web server is running PHP. Your server does not have PHP installed, or PHP is turned off.</p>
</body>
</html>
<?php

return;
}

use \AppNamespace\Includes as Includes;

/**
 * Install the website management system
 *
 * @since 1.0.0
 * @var bool
 */
define( 'APP_INSTALLING', true );

// Load the website management system.
require_once( dirname( dirname( dirname( __FILE__ ) ) ) . '/app-load.php' );

// New instance of the App_Install class.
$app_install = new Includes\App_Install;

// Load Administration Upgrade API.
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

// Load Translation Install API.
require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );

// Load wpdb.
require_once( ABSPATH . APP_INC . '/app-db.php' );

/**
 * Set the headers to prevent caching
 *
 * Different browsers support different nocache headers,
 * so several headers must be sent so that all of them
 * get the point that no caching should occur.
 */
nocache_headers();

if ( isset( $_GET['step'] ) ) {
	$step = (int) $_GET['step'];
} else {
	$step = 0;
}

// Application name.
if ( defined( 'APP_NAME' ) && APP_NAME ) {
	$app_name = APP_NAME;
} else {
	$app_name = 'system';
}

$app_install->display_setup_form();

// Let's check to make sure WP isn't already installed.
if ( is_blog_installed() ) {

	// Get the page header.
	include_once( APP_VIEWS_DIR . '/includes/partials/header/config-install.php' );

	// Get the message content.
	include_once( APP_VIEWS_DIR . '/includes/partials/content/install-exists.php' );

	// Get the page footer.
	include( APP_VIEWS_DIR . '/includes/partials/footer/config-install.php' );

	return;
}

/**
 * @global string $app_version
 * @global string $wp_version
 * @global string $required_php_version
 * @global string $required_mysql_version
 * @global wpdb   $wpdb
 */
global $app_version, $wp_version, $required_php_version, $required_mysql_version;

$php_version   = phpversion();
$mysql_version = $wpdb->db_version();
$php_compat    = version_compare( $php_version, $required_php_version, '>=' );
$mysql_compat  = version_compare( $mysql_version, $required_mysql_version, '>=' ) || file_exists( APP_CONTENT_DIR . '/db.php' );

if ( ! $mysql_compat && !$php_compat ) {

	$compat = sprintf( __( 'You cannot install because %1$s %2$s requires PHP version %3$s or higher and MySQL version %4$s or higher. You are running PHP version %4$s and MySQL version %5$s.' ), APP_NAME, $app_version, $required_php_version, $required_mysql_version, $php_version, $mysql_version );

} elseif ( ! $php_compat ) {

	$compat = sprintf( __( 'You cannot install because %1$s %2$s requires PHP version %3$s or higher. You are running version %4$s.' ), APP_NAME, $app_version, $required_php_version, $php_version );

} elseif ( ! $mysql_compat ) {

	$compat = sprintf( __( 'You cannot install because %1$s %2$s requires MySQL version %3$s or higher. You are running version %4$s.' ), APP_NAME, $app_version, $required_mysql_version, $mysql_version );
}

if ( ! $mysql_compat || ! $php_compat ) {

	// Get the page header.
	include_once( APP_VIEWS_DIR . '/includes/partials/header/config-install.php' );

	die( '<h1>' . __( 'Insufficient Requirements' ) . '</h1><p>' . $compat . '</p></body></html>' );
}

if ( ! is_string( $wpdb->base_prefix ) || '' === $wpdb->base_prefix ) {

	// Get the page header.
	include_once( APP_VIEWS_DIR . '/includes/partials/header/config-install.php' );

	die(
		'<h1>' . __( 'Configuration Error' ) . '</h1>' .
		'<p>' . sprintf(
			/* translators: %s: app-config.php */
			__( 'Your %s file has an empty database table prefix, which is not supported.' ),
			'<code>app-config.php</code>'
		) . '</p></body></html>'
	);
}

// Set error message if DO_NOT_UPGRADE_GLOBAL_TABLES isn't set as it will break install.
if ( defined( 'DO_NOT_UPGRADE_GLOBAL_TABLES' ) ) {

	// Get the page header.
	include_once( APP_VIEWS_DIR . '/includes/partials/header/config-install.php' );

	die(
		'<h1>' . __( 'Configuration Error' ) . '</h1>' .
		'<p>' . sprintf(
			/* translators: %s: DO_NOT_UPGRADE_GLOBAL_TABLES */
			__( 'The constant %s cannot be defined when installing the website management system.' ),
			'<code>DO_NOT_UPGRADE_GLOBAL_TABLES</code>'
		) . '</p></body></html>'
	);
}

/**
 * @global string    $wp_local_package
 * @global WP_Locale $wp_locale
 */
$language = '';

if ( ! empty( $_REQUEST['language'] ) ) {
	$language = preg_replace( '/[^a-zA-Z0-9_]/', '', $_REQUEST['language'] );

} elseif ( isset( $GLOBALS['wp_local_package'] ) ) {
	$language = $GLOBALS['wp_local_package'];
}

$scripts_to_print = [ 'jquery' ];

switch( $step ) {

	case 0 : // Step 0

		if ( wp_can_install_language_pack() && empty( $language ) && ( $languages = wp_get_available_translations() ) ) {

			$scripts_to_print[] = 'language-chooser';

			// Get the page header.
			include_once( APP_VIEWS_DIR . '/includes/partials/header/config-install.php' );

			echo '<form id="setup" method="post" action="?step=1">';
			wp_install_language_form( $languages );
			echo '</form>';

			break;
		}

		// Deliberately fall through if we can't reach the translations API.

	// Step 1, direct link or from language chooser.
	case 1 :

		if ( ! empty( $language ) ) {

			$loaded_language = wp_download_language_pack( $language );

			if ( $loaded_language ) {
				load_default_textdomain( $loaded_language );
				$GLOBALS['wp_locale'] = new WP_Locale();
			}
		}

		$scripts_to_print[] = 'user-profile';

		// Get the page header.
		include_once( APP_VIEWS_DIR . '/includes/partials/header/config-install.php' );
?>
	<div class="setup-install-wrap setup-install-introduction">
		<h1><?php _e( 'Information Needed' ); ?></h1>
		<p><?php _e( 'Please provide the following information. You can always change these settings later.' ); ?></p>
	</div>

<?php
		$app_install->display_setup_form();

		break;

	case 2 :

		if ( ! empty( $language ) && load_default_textdomain( $language ) ) {
			$loaded_language      = $language;
			$GLOBALS['wp_locale'] = new WP_Locale();

		} else {
			$loaded_language = 'en_US';
		}

		if ( ! empty( $wpdb->error ) ) {
			wp_die( $wpdb->error->get_error_message() );
		}

		$scripts_to_print[] = 'user-profile';

		// Get the page header.
		include_once( APP_VIEWS_DIR . '/includes/partials/header/config-install.php' );

		// Fill in the data we gathered.
		$website_title        = isset( $_POST['website_title'] ) ? trim( wp_unslash( $_POST['website_title'] ) ) : '';
		$website_description  = isset( $_POST['website_description'] ) ? trim( wp_unslash( $_POST['website_description'] ) ) : '';
		$user_name            = isset( $_POST['user_name'] ) ? trim( wp_unslash( $_POST['user_name'] ) ) : '';
		$admin_password       = isset( $_POST['admin_password'] ) ? wp_unslash( $_POST['admin_password'] ) : '';
		$admin_password_check = isset( $_POST['admin_password2'] ) ? wp_unslash( $_POST['admin_password2'] ) : '';
		$admin_email          = isset( $_POST['admin_email'] ) ?trim( wp_unslash( $_POST['admin_email'] ) ) : '';
		$public               = isset( $_POST['blog_public'] ) ? (int) $_POST['blog_public'] : 1;

		// Check email address.
		$error = false;

		if ( empty( $user_name ) ) {

			$app_install->display_setup_form( __( 'Please provide a valid username.' ) );
			$error = true;

		} elseif ( $user_name != sanitize_user( $user_name, true ) ) {

			$app_install->display_setup_form( __( 'The username you provided has invalid characters.' ) );
			$error = true;

		} elseif ( $admin_password != $admin_password_check ) {

			$app_install->display_setup_form( __( 'Your passwords do not match. Please try again.' ) );
			$error = true;

		} elseif ( empty( $admin_email ) ) {

			$app_install->display_setup_form( __( 'You must provide an email address.' ) );
			$error = true;

		} elseif ( ! is_email( $admin_email ) ) {

			$app_install->display_setup_form( __( 'Sorry, that isn&#8217;t a valid email address. Email addresses look like <code>username@example.com</code>.' ) );
			$error = true;
		}

		if ( $error === false ) {
			$wpdb->show_errors();
			$result = wp_install( $website_title, $website_description, $user_name, $admin_email, $public, '', wp_slash( $admin_password ), $loaded_language );

			// Get the successful installation message content.
			include_once( APP_VIEWS_DIR . '/includes/partials/content/install-success.php' );

		}

		break;
}

if ( ! wp_is_mobile() ) {
	?>
<script type="text/javascript">var t = document.getElementById( 'website_title' ); if (t){ t.focus(); }</script>
	<?php
}

wp_print_scripts( $scripts_to_print );

// Get the page footer.
include( APP_VIEWS_DIR . '/includes/partials/footer/config-install.php' );

?>
</body>
</html>
