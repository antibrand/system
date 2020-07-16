<?php
/**
 * Retrieve or create the configuration file
 *
 * The permissions for the base directory must allow for writing files in order
 * for the `app-config.php` file to be created using this page.
 *
 * @package App_Package
 * @subpackage Administration
 */

// Start with a clean slate.
define( 'APP_INSTALLING', true );
define( 'WP_SETUP_CONFIG', true );

/**
 * Disable error reporting
 *
 * Set this to `error_reporting( -1 )` for debugging.
 */
error_reporting( 0 );

// Absolute path to the app's root directory, two levels up from here.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( dirname( dirname( __FILE__ ) ) ) . '/' );
}

// HTML templates directory name.
if ( ! defined( 'APP_VIEWS' ) ) {
	define( 'APP_VIEWS', 'app-views' );
}

// Path to HTML templates.
if ( ! defined( 'APP_VIEWS_PATH' ) ) {
	define( 'APP_VIEWS_PATH', ABSPATH . APP_VIEWS . '/' );
}

// Load dependency files.
require( ABSPATH . 'app-settings.php' );
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );

// Get the identity image or white label logo.
$app_get_logo = dirname( dirname( dirname( $_SERVER['PHP_SELF'] ) ) ) . '/app-assets/images/app-icon.png';

/**
 * Set the headers to prevent caching
 *
 * Different browsers support different nocache headers,
 * so several headers must be sent so that all of them
 * get the point that no caching should occur.
 */
nocache_headers();

/**
 * Look for the `app-config.sample.php` file,
 * including one level up from the app root.
 */
if ( file_exists( ABSPATH . 'app-config.sample.php' ) ) :
	$app_config_file = file( ABSPATH . 'app-config.sample.php' );

elseif ( file_exists( dirname( ABSPATH ) . '/app-config.sample.php' ) ) :
	$app_config_file = file( dirname( ABSPATH ) . '/app-config.sample.php' );

/**
 * Stop the configuration process and display a message if the sample
 * configuration file is not found in the root directory.
 */
else :

// Get the page header.
include( APP_VIEWS_PATH . 'includes/partials/header/config-install.php' );

// Get the message content.
include_once( APP_VIEWS_PATH . 'includes/partials/content/config-sample-missing.php' );

// Get the page footer.
include( APP_VIEWS_PATH . 'includes/partials/footer/config-install.php' );

// Stop the configuration process.
return;

endif;

/**
 * Stop the configuration process and display a message if the
 * configuration file has been created.
 */
if ( file_exists( ABSPATH . 'app-config.php' ) ) :

// Get the page header.
include( APP_VIEWS_PATH . 'includes/partials/header/config-install.php' );

// Get the message content.
include_once( APP_VIEWS_PATH . 'includes/partials/content/config-exists.php' );

// Get the page footer.
include( APP_VIEWS_PATH . 'includes/partials/footer/config-install.php' );

// Stop the configuration process.
return;

endif;

/**
 * Stop the configuration process and display a message if the
 * configuration file exists above the root directory but is
 * not part of another installation.
 */
if ( @file_exists( ABSPATH . '../app-config.php' ) && ! @file_exists( ABSPATH . '../app-settings.php' ) ) :

// Get the page header.
include( APP_VIEWS_PATH . 'includes/partials/header/config-install.php' );

// Get the message content.
include_once( APP_VIEWS_PATH . 'includes/partials/content/config-exists-above.php' );

// Get the page footer.
include( APP_VIEWS_PATH . 'includes/partials/footer/config-install.php' );

// Stop the configuration process.
return;

endif;

/**
 * Look for the `id-config.sample.php` file,
 * including one level up from the app root.
 *
 * This is used to "white label" the website management
 * system with your own name or brand identity.
 */
if ( file_exists( ABSPATH . 'id-config.sample.php' ) ) {
	$id_config_file = file( ABSPATH . 'id-config.sample.php' );

} elseif ( file_exists( dirname( ABSPATH ) . '/id-config.sample.php' ) ) {
	$id_config_file = file( dirname( ABSPATH ) . '/id-config.sample.php' );
}

// Set URL "step" parameters for the paginated installation process.
if ( isset( $_GET['step'] ) ) {
	$step = $_GET['step'];
} else {
	$step = 'begin';
}

// Set a blank language variable.
$language = '';

// Look for a language preference.
if ( ! empty( $_REQUEST['language'] ) ) {
	$language = preg_replace( '/[^a-zA-Z0-9_]/', '', $_REQUEST['language'] );
} elseif ( isset( $GLOBALS['wp_local_package'] ) ) {
	$language = $GLOBALS['wp_local_package'];
}

// Get the page header.
include( APP_VIEWS_PATH . 'includes/partials/header/config-install.php' );

/**
 * Begin the paginated setup & config pages markup
 *
 * Uses template partials located in the `partials`
 * subdirectory of this directory.
 */
switch( $step ) :

	/**
	 * Case begin: Setup Introduction
	 *
	 * This template notifies the user of the information which is needed
	 * for installation of the website management system.
	 */
	case 'begin' :

	// Load the system language.
	load_default_textdomain( $language );
	$GLOBALS['wp_locale'] = new WP_Locale();

	?>
	<div class="setup-install-wrap">

		<main class="config-content">

			<form class="config-form" method="post" action="config.php?step=ready">

				<?php
				include_once( ABSPATH . 'app-views/includes/partials/content/config-intro.php' );
				include_once( ABSPATH . 'app-views/includes/partials/content/config-info-database.php' );
				include_once( ABSPATH . 'app-views/includes/partials/content/config-info-identity.php' );
				include_once( ABSPATH . 'app-views/includes/partials/content/config-database.php' );
				include_once( ABSPATH . 'app-views/includes/partials/content/config-identity.php' );
				?>
			</form>
		</main>
	</div>
	<?php

	// End case begin page content.
	break;

	/**
	 * Case ready: Create files
	 *
	 * This creates the system and identity configuration files if
	 * possible then moves on to the installation page template.
	 *
	 * This also displays a success message if a database connection
	 * was made or displays various error messages.
	 */
	case 'ready' :

	// Load the system language.
	load_default_textdomain( $language );
	$GLOBALS['wp_locale'] = new WP_Locale();

	// Get the identity fields.
	$app_name    = trim( wp_unslash( $_POST['app_name'] ) );
	$app_tagline = trim( wp_unslash( $_POST['app_tagline'] ) );
	$app_website = trim( wp_unslash( $_POST['app_website'] ) );
	$app_icon    = trim( wp_unslash( $_POST['app_icon'] ) );

	// Get the database fields.
	$app_db_name     = trim( wp_unslash( $_POST['app_db_name'] ) );
	$app_db_user     = trim( wp_unslash( $_POST['app_db_user'] ) );
	$app_db_password = trim( wp_unslash( $_POST['app_db_password'] ) );
	$app_db_host     = trim( wp_unslash( $_POST['app_db_host'] ) );
	$app_db_prefix   = trim( wp_unslash( $_POST['app_db_prefix'] ) );

	// Variable for the step begin parameter.
	$step_begin  = 'config.php?step=begin';

	// Variable for the installation page template.
	$install = 'install.php';
	if ( isset( $_REQUEST['noapi'] ) ) {
		$step_begin .= '&amp;noapi';
	}

	// Set up the URL parameter for the system language.
	if ( ! empty( $language ) ) {
		$step_begin  .= '&amp;language=' . $language;
		$install .= '?language=' . $language;
	} else {
		$install .= '';
	}

	// Variable for the button to try configuration again.
	$tryagain_link = '<p class="step"><a href="config.php" onclick="javascript:history.go(-1);return false;" class="button button-large">' . __( 'Try again' ) . '</a></p>';

	// Stop & display message if there is no database prefix available.
	if ( empty( $app_db_prefix ) ) {
		wp_die( __( '<strong>ERROR</strong>: "Table Prefix" must not be empty.' . $tryagain_link ) );
	}

	// Stop & display message if the database prefix cannot validate.
	if ( preg_match( '|[^a-z0-9_]|i', $app_db_prefix ) ) {
		wp_die( __( '<strong>ERROR</strong>: "Table Prefix" can only contain numbers, letters, and underscores.' . $tryagain_link ) );
	}

	// Test the db connection.
	/**#@+
	 * @ignore
	 */
	define( 'DB_NAME', $app_db_name );
	define( 'DB_USER', $app_db_user );
	define( 'DB_PASSWORD', $app_db_password );
	define( 'DB_HOST', $app_db_host );
	/**#@-*/

	// Re-construct the `wpdb` class with these new values.
	unset( $wpdb );
	require_wp_db();

	/**
	 * The `wpdb` constructor bails when `WP_SETUP_CONFIG` is set so this must
	 * fire manually. Fail here if the values are no good.
	 *
	 * @see wp-includes/wp-db.php
	 */
	$wpdb->db_connect();

	// Stop & display message with try again link if the database cannot be reached.
	if ( ! empty( $wpdb->error ) ) {
		wp_die( $wpdb->error->get_error_message() . $tryagain_link );
	}

	$errors = $wpdb->hide_errors();
	$wpdb->query( "SELECT $app_db_prefix" );
	$wpdb->show_errors( $errors );

	// Stop & display message if MySQL was able to parse the prefix as a value.
	if ( ! $wpdb->last_error ) {
		wp_die( __( '<strong>ERROR</strong>: "Table Prefix" is invalid.' ) );
	}

	/**
	 * Generate keys and salts using secure CSPRNG.
	 *
	 * @link https://www.php.net/manual/en/intro.csprng.php
	 */
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
	$max   = strlen( $chars ) - 1;

	for ( $i = 0; $i < 8; $i++ ) {

		$key = '';

		for ( $j = 0; $j < 64; $j++ ) {
			$key .= substr( $chars, random_int( 0, $max ), 1 );
		}

		$secret_keys[] = $key;
	}

	/**
	 * Begin replacing definition values found in the sample
	 * system configuration file with user-defined values.
	 */
	$key = 0;
	foreach ( $app_config_file as $line_num => $line ) :

		// Replace the table prefix.
		if ( '$table_prefix  =' == substr( $line, 0, 16 ) ) {
			$app_config_file[ $line_num ] = '$table_prefix  = \'' . addcslashes( $app_db_prefix, "\\'" ) . "';\r\n";
			continue;
		}

		// Replace the sample definition values with user-defined values.
		if ( ! preg_match( '/^define\(\s*\'([A-Z_]+)\',([ ]+)/', $line, $match ) ) {
			continue;
		}

		// Variable to match in the replace process.
		$constant = $match[1];
		$padding  = $match[2];

		// Cases to be replaced.
		switch ( $constant ) :

			// Database connection.
			case 'DB_NAME'     :
			case 'DB_USER'     :
			case 'DB_PASSWORD' :
			case 'DB_HOST'     :
				$app_config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'" . addcslashes( constant( $constant ), "\\'" ) . "' );\r\n";

				break;

			// Database character set.
			case 'DB_CHARSET' :
				if ( 'utf8mb4' === $wpdb->charset || ( ! $wpdb->charset && $wpdb->has_cap( 'utf8mb4' ) ) ) {
					$app_config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'utf8mb4' );\r\n";
				}

				break;

			// Security keys and salts.
			case 'AUTH_KEY'         :
			case 'SECURE_AUTH_KEY'  :
			case 'LOGGED_IN_KEY'    :
			case 'NONCE_KEY'        :
			case 'AUTH_SALT'        :
			case 'SECURE_AUTH_SALT' :
			case 'LOGGED_IN_SALT'   :
			case 'NONCE_SALT'       :
				$app_config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'" . $secret_keys[$key++] . "' );\r\n";

				break;

		// End cases to be replaced.
		endswitch;

	// End definition values.
	endforeach;

	// Unset the line variable from the foreach loop.
	unset( $line );

	/**
	 * Begin replacing definition values found in the sample
	 * identity configuration file with user-defined values.
	 */
	foreach ( $id_config_file as $line_num => $line ) :

		// Replace the sample definitions with user-provided definitions.
		if ( ! preg_match( '/^define\(\s*\'([A-Z_]+)\',([ ]+)/', $line, $match ) ) {
			continue;
		}

		// Variable to match in the replace process.
		$constant = $match[1];
		$padding  = $match[2];

		// Cases to be replaced.
		switch ( $constant ) :

			// White label definitions.
			case 'APP_NAME' :
				$id_config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'" . $app_name . "' );\r\n";

				break;

			case 'APP_TAGLINE' :
				$id_config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'" . $app_tagline . "' );\r\n";

				break;

			case 'APP_WEBSITE' :
				$id_config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'" . $app_website . "' );\r\n";

				break;

			case 'APP_IMAGE' :
				$id_config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'" . $app_icon . "' );\r\n";

				break;

		// End cases to be replaced.
		endswitch;

	// End definition values.
	endforeach;

	// Unset the line variable from the foreach loop.
	unset( $line );

	/**
	 * No write permissions
	 *
	 * This template is displayed if the root directory does not have
	 * the necessary permissions to write the new configuration files.
	 */
	if ( ! is_writable( ABSPATH ) ) :
		include_once( APP_VIEWS_PATH . 'includes/partials/content/config-not-writable.php' );

	/**
	 * Files can be written
	 *
	 * This template is displayed if the root directory has the necessary
	 * permissions to write the new configuration files and if the databse
	 * credentials are correct.
	 *
	 * Links to the installation template.
	 * @see wp-admin/install.php
	 */
	else :
		/*
		 * If the `app-config.php` file doesn't exist then use the `app-config.sample.php`
		 * file one level up, which is for development installations.
		 */
		if ( file_exists( ABSPATH . 'app-config.sample.php' ) ) {
			$path_to_app_config = ABSPATH . 'app-config.php';
		} else {
			$path_to_app_config = dirname( ABSPATH ) . '/app-config.php';
		}

		// Write the new `app-config.php` file.
		$app_handle = fopen( $path_to_app_config, 'w' );

		foreach ( $app_config_file as $line ) {
			fwrite( $app_handle, $line );
		}

		fclose( $app_handle );
		chmod( $path_to_app_config, 0666 );

		/*
		 * If the `id-config.php` file doesn't exist then use the `id-config.sample.php`
		 * file one level up, which is for development installations.
		 */
		if ( file_exists( ABSPATH . 'id-config.sample.php' ) ) {
			$path_to_id_config = ABSPATH . 'id-config.php';
		} else {
			$path_to_id_config = dirname( ABSPATH ) . '/id-config.php';
		}

		// Write the new `id-config.php` file.
		$id_handle = fopen( $path_to_id_config, 'w' );
		foreach ( $id_config_file as $line ) {
			fwrite( $id_handle, $line );
		}

		fclose( $id_handle );
		chmod( $path_to_id_config, 0666 );

		include_once( APP_VIEWS_PATH . 'includes/partials/content/config-database-success.php' );

	endif;

	// End case ready.
	break;

// End the paginated setup & config pages markup.
endswitch;

// Get the page footer.
include( APP_VIEWS_PATH . 'includes/partials/footer/config-install.php' );

wp_print_scripts( 'language-chooser' );

/**
 * wp_print_scripts( 'language-chooser' );
 *
 * @todo Remove if or when necessary.
 */


/**
 * jQuery navigation
 *
 * Shows the previous or next step in the configuration form
 *
 * @since 1.0.0
 */
?>
<!-- jQuery navigation -->
<script>
jQuery(document).ready( function($) {

	// Step to show first, by ID.
	var $current = $( '#config-intro' );

	// Hide all sections with the form-step class.
	$( 'section.form-step' ).hide();

	// Show the first form-step section.
	$current.show();

	$( '.prev' ).click( function() {
		$current = $current.prev();
		$( 'section.form-step' ).hide();
		$current.fadeIn(250);
	});

	$( '.next' ).click( function() {
		$current = $current.next();
		$( 'section.form-step' ).hide();
		$current.fadeIn(250);
	});
});
</script>

<!-- Remove hashtags from URL -->
<script>
jQuery(document).ready( function($) {

	$( 'a[href*="#"]' )

	// Ignore links that don't link to anything.
	.not( '[href="#"]' )
	.not( '[href="#0"]' )
	.click( function(event) {
		if (
			location.pathname.replace( /^\//, '' ) == this.pathname.replace( /^\//, '' )
			&&
			location.hostname == this.hostname
		) {
			var target = $( this.hash );

			if ( target.length ) {
				event.preventDefault();
			}
		}
	});
});
</script>

</body>
</html>
