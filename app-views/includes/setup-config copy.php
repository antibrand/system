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
define( 'WP_INSTALLING', true );
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

// Start output buffering.
ob_start();

// Load dependency files.
require( ABSPATH . 'app-settings.php' );
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );

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
if ( file_exists( ABSPATH . 'app-config.sample.php' ) ) {
	$app_config_file = file( ABSPATH . 'app-config.sample.php' );
} elseif ( file_exists( dirname( ABSPATH ) . '/app-config.sample.php' ) ) {
	$app_config_file = file( dirname( ABSPATH ) . '/app-config.sample.php' );

/**
 * Stop the configuration process and display a message if the sample
 * configuration file is not found in the root directory.
 */
} else {
	wp_die( '<p>' . sprintf(
		__( 'A %s file is needed from which to work. Please upload this file to the root directory of your installation.' ),
		'<code>app-config.sample.php</code>'
		) . '</p>'
	);
}

/**
 * Stop the configuration process and display a message if the
 * configuration file has been created.
 */
if ( file_exists( ABSPATH . 'app-config.php' ) ) {
	wp_die( '<p>' . sprintf(
			__( 'The file %1$s already exists. If you need to reset any of the configuration items in this file, please delete it first. You may try <a href="%2$s">installing now</a>.' ),
			'<code>app-config.php</code>',
			'install.php'
		) . '</p>'
	);
}

/**
 * Stop the configuration process and display a message if the
 * configuration file exists above the root directory but is
 * not part of another installation.
 */
if ( @file_exists( ABSPATH . '../app-config.php' ) && ! @file_exists( ABSPATH . '../app-settings.php' ) ) {
	wp_die( '<p>' . sprintf(
			__( 'The file %1$s already exists one level above your installation. If you need to reset any of the configuration items in this file, please delete it first. You may try <a href="%2$s">installing now</a>.' ),
			'<code>app-config.php</code>',
			'install.php'
		) . '</p>'
	);
}

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
	$step = (int) $_GET['step'];
} else {
	$step = 0;
}

// Set a blank language variable.
$language = '';

// Look for a language preference.
if ( ! empty( $_REQUEST['language'] ) ) {
	$language = preg_replace( '/[^a-zA-Z0-9_]/', '', $_REQUEST['language'] );
} elseif ( isset( $GLOBALS['wp_local_package'] ) ) {
	$language = $GLOBALS['wp_local_package'];
}

// Get the identity image or white label logo.
$app_get_logo = dirname( dirname( dirname( $_SERVER['PHP_SELF'] ) ) ) . '/app-assets/images/app-icon.png';

/**
 * Begin the paginated setup & config pages markup
 *
 * Uses template partials located in the `partials`
 * subdirectory of this directory.
 */
switch( $step ) :

	/**
	 * Case 0: Setup Introduction
	 *
	 * This template notifies the user of the information which is needed
	 * for installation of the website management system.
	 */
	case 0 :

	// Variable for the step 1 parameter.
	$step_1 = 'setup-config.php?step=1';

	// Get the page header.
	include( ABSPATH . 'app-views/includes/partials/header/config-install.php' );

	// Begin case 0 content.
?>
	<div class="setup-install-wrap setup-install-introduction">
		<main class="setup-config-content">

			<h2><?php _e( 'Setup Introduction' ); ?></h2>

			<p><?php _e( 'For the installation process you will need to know the following information.' ); ?></p>
			<ol id="setup-config-database-info-list">
				<li><?php _e( 'Database name' ); ?></li>
				<li><?php _e( 'Database username' ); ?></li>
				<li><?php _e( 'Database password' ); ?></li>
				<li><?php _e( 'Database host' ); ?></li>
			</ol>
			<p><?php _e( 'If you don&#8217;t have this information then you will need to contact your web host.' ); ?></p>
			<p><?php
				printf( __( 'This information is needed to create a configuration file. If for any reason this automatic file creation doesn&#8217;t work then find the %1$s file in the root directory, open it in a text editor, fill in your information, and save it as %2$s in the same directory.' ),
					'<code>app-config.sample.php</code>',
					'<code>app-config.php</code>'
				);
			?></p>

			<h2><?php _e( 'Optional Information' ); ?></h2>

			<p><?php _e( 'You have the option to give this website management system an identity of your own. This can be changed after installation but before installing you may want to consider a name for the system:' ); ?></p>

			<ul id="setup-config-identity-info-list">
				<li><?php _e( 'A name for the system' ); ?></li>
				<li><?php _e( 'A tagline or description' ); ?></li>
				<li><?php _e( 'An image that represents the name or identity' ); ?></li>
				<li><?php _e( 'Any website associated with the system' ); ?></li>
			</ul>

			<p class="step"><a href="<?php echo $step_1; ?>" class="button button-large"><?php _e( 'Begin Installation' ); ?></a></p>
		</main>
	</div>
	<?php
	// Get the page footer.
	include( ABSPATH . 'app-views/includes/partials/footer/config-install.php' );

	// End case 0 page content.
	break;

	/**
	 * Case 1: System Configuration
	 *
	 * This template includes forms for database information and
	 * credentials, and for system identity configuration.
	 */
	case 1 :

	// Load the system language.
	load_default_textdomain( $language );
	$GLOBALS['wp_locale'] = new WP_Locale();

	// Get the page header.
	include( ABSPATH . 'app-views/includes/partials/header/config-install.php' );

	// Begin case 1 content.
?>
	<div class="setup-install-wrap setup-install-installation">

		<main class="setup-config-content">

			<h2><?php _e( 'System Configuration' ); ?></h2>

			<form class="setup-config-form" method="post" action="setup-config.php?step=2">

				<fieldset class="tabbed-legend">

					<legend><?php _e( 'White Label' ); ?></legend>

					<div id="white-label-inputs-info" class="install-fieldset-section">

						<p><?php _e( 'Enter an application name to be used throughout the website management system. This allows you to "white label" the application and can be changed at any time in the <code>id-config</code> file in the application\'s root directory.' ); ?></p>

						<p class="setup-config-field setup-config-app-name">
							<label for="app_name"><?php _e( 'Application Name' ); ?></label>
							<br /><input name="app_name" id="app_name" type="text" size="35" value="" placeholder="<?php echo htmlspecialchars( _x( 'White Label System', 'example name for the website management system' ), ENT_QUOTES ); ?>" />
							<br /><span class="description setup-config-field-description"><?php _e( 'Enter the name to use for your website management system.' ); ?></span>
						</p>
						<p class="setup-config-field setup-config-app-tagline">
							<label for="app_tagline"><?php _e( 'Application Tagline/Description' ); ?></label>
							<br /><input name="app_tagline" id="app_tagline" type="text" size="55" value="" placeholder="<?php echo htmlspecialchars( _x( 'Your tagline or description', 'example tagline for the website management system' ), ENT_QUOTES ); ?>" />
							<br /><span class="description setup-config-field-description"><?php _e( 'Used in documentation, system status features, etc.' ); ?></span>
						</p>
						<p class="setup-config-field setup-config-app-website">
							<label for="app_website"><?php _e( 'Application Website' ); ?></label>
							<br /><input name="app_website" id="app_website" type="text" size="35" value="" placeholder="<?php echo esc_url( 'https://example.com/' ); ?>" />
							<br /><span class="description setup-config-field-description"><?php _e( 'Link users to your website for more information.' ); ?></span>
						</p>

					</div>

					<div id="white-label-inputs-info" class="install-fieldset-section">

						<p class="setup-config-field setup-config-app-logo">
							<label for="app_logo"><?php _e( 'Application Logo' ); ?></label>
							<br /><input name="app_logo" id="app_logo" type="file" accept="image/png, image/jpg, image/jpeg image/gif" />
							<br /><span class="description setup-config-field-description"><?php _e( 'Accepts .png, .jpg, .jpeg, .gif.' ); ?></span>
						</p>

					</div>

				</fieldset>

				<fieldset class="tabbed-legend">

					<legend><?php _e( 'Database Connection' ); ?></legend>

					<div>
						<p><?php _e( 'Enter your database connection details below. If you&#8217;re not sure about these, contact your host.' ); ?></p>

						<p><?php _e( 'Unique database table prefixes are needed if you want to run more than one installation with a single database. For security purposes a random prefix has been generated. You can void this by entering your own prefix. It is recommended for legibility that the prefix ends in an underscore.' ); ?></p>
					</div>

					<table class="form-table">
						<tr>
							<th scope="row"><label for="app_db_name"><?php _e( 'Database Name' ); ?></label></th>
							<td><input name="app_db_name" id="app_db_name" type="text" size="25" value="" placeholder="<?php echo htmlspecialchars( _x( 'name', 'example database name' ), ENT_QUOTES ); ?>" /></td>
							<td><?php _e( 'The name of the database you want to use.' ); ?></td>
						</tr>
						<tr>
							<th scope="row"><label for="app_db_user"><?php _e( 'Database User' ); ?></label></th>
							<td><input name="app_db_user" id="app_db_user" type="text" size="25" value="" placeholder="<?php echo htmlspecialchars( _x( 'root', 'example database user name' ), ENT_QUOTES ); ?>" /></td>
							<td><?php _e( 'Your database user name.' ); ?></td>
						</tr>
						<tr>
							<th scope="row"><label for="app_db_password"><?php _e( 'Database Password' ); ?></label></th>
							<td><input name="app_db_password" id="app_db_password" type="text" size="25" value="" placeholder="<?php echo htmlspecialchars( _x( 'mysql', 'example password' ), ENT_QUOTES ); ?>" autocomplete="off" /></td>
							<td><?php _e( 'Your database password.' ); ?></td>
						</tr>
						<tr>
							<th scope="row"><label for="app_db_host"><?php _e( 'Database Host' ); ?></label></th>
							<td><input name="app_db_host" id="app_db_host" type="text" size="25" value="localhost" placeholder="localhost" /></td>
							<td><?php
								printf( __( 'You should be able to get this info from your web host, if %s doesn&#8217;t work.' ),'<code>localhost</code>' );
							?></td>
						</tr>
						<tr>
							<th scope="row"><label for="app_db_prefix"><?php _e( 'Database Prefix' ); ?></label></th>
							<td>
								<input name="app_db_prefix" id="app_db_prefix" type="text" value="app_<?php echo esc_attr( md5( time() ) ); ?>_" placeholder="app_" size="25" />
								<?php echo sprintf(
									'<p class="description">%1s <code>%2s</code> %3s</p>',
									__( 'The random table prefix does not necessarily make the database more secure but the option is provided for those who wish to use it. You may want to use something simple, such as' ),
									'app_',
									__( '. But whatever you choose it is recommended that you end the prefix with an underscore to make the database more legible.' )
								); ?>
							</td>
							<td><?php echo sprintf(
								'%1s <code>app_%2s_</code><br />%3s',
								esc_html__( 'Random table prefix is:' ),
								md5( time() ),
								esc_html__( 'Change this if you want to define your own prefix.' )

							); ?></td>
						</tr>
					</table>

				</fieldset>

				<?php if ( isset( $_GET['noapi'] ) ) : ?>
				<input name="noapi" type="hidden" value="1" />
				<?php endif; ?>

				<input type="hidden" name="language" value="<?php echo esc_attr( $language ); ?>" />

				<p class="step"><input name="submit" type="submit" value="<?php echo htmlspecialchars( __( 'Submit Information' ), ENT_QUOTES ); ?>" class="button button-large" /></p>

			</form>
		</main>
	</div>
	<?php
	// Get the page footer.
	include( ABSPATH . 'app-views/includes/partials/footer/config-install.php' ); ?>
<?php
	// End case 1 page content.
	break;

	/**
	 * Case 2: Create files
	 *
	 * This creates the system and identity configuration files if
	 * possible then moves on to the installation page template.
	 *
	 * This also displays a success message if a database connection
	 * was made or displays various error messages.
	 */
	case 2 :

	// Load the system language.
	load_default_textdomain( $language );
	$GLOBALS['wp_locale'] = new WP_Locale();

	// Get the identity fields.
	$app_name    = trim( wp_unslash( $_POST['app_name'] ) );
	$app_tagline = trim( wp_unslash( $_POST['app_tagline'] ) );
	$app_website = trim( wp_unslash( $_POST['app_website'] ) );
	$app_logo    = trim( wp_unslash( $_POST['app_logo'] ) );

	// Get the database fields.
	$app_db_name     = trim( wp_unslash( $_POST['app_db_name'] ) );
	$app_db_user     = trim( wp_unslash( $_POST['app_db_user'] ) );
	$app_db_password = trim( wp_unslash( $_POST['app_db_password'] ) );
	$app_db_host     = trim( wp_unslash( $_POST['app_db_host'] ) );
	$app_db_prefix   = trim( wp_unslash( $_POST['app_db_prefix'] ) );

	// Variable for the step 1 parameter.
	$step_1  = 'setup-config.php?step=1';

	// Variable for the installation page template.
	$install = 'install.php';
	if ( isset( $_REQUEST['noapi'] ) ) {
		$step_1 .= '&amp;noapi';
	}

	// Set up the URL parameter for the system language.
	if ( ! empty( $language ) ) {
		$step_1  .= '&amp;language=' . $language;
		$install .= '?language=' . $language;
	} else {
		$install .= '';
	}

	// Variable for the button to try configuration again.
	$tryagain_link = '<p class="step"><a href="' . $step_1 . '" onclick="javascript:history.go(-1);return false;" class="button button-large">' . __( 'Try again' ) . '</a></p>';

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
			case 'APP_LOGO' :
				$id_config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'" . $app_logo . "' );\r\n";
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

		// Get the page header.
		include( ABSPATH . 'app-views/includes/partials/header/config-install.php' );
?>
	<div class="setup-install-wrap setup-install-no-write">
		<p><?php
			/* translators: %s: app-config.php */
			printf( __( 'Sorry, but I can&#8217;t write the %s file.' ), '<code>app-config.php</code>' );
		?></p>
		<p><?php
			/* translators: %s: app-config.php */
			printf( __( 'You can create the %s file manually and paste the following text into it.' ), '<code>app-config.php</code>' );
		?></p>
		<textarea id="app-config" cols="98" rows="15" class="code" readonly="readonly"><?php
				foreach ( $app_config_file as $line ) {
					echo htmlentities($line, ENT_COMPAT, 'UTF-8' );
				}
		?></textarea>
		<p><?php _e( 'After you&#8217;ve done that, click &#8220;Run the installation.&#8221;' ); ?></p>
		<p class="step"><a href="<?php echo $install; ?>" class="button button-large"><?php _e( 'Run the installation' ); ?></a></p>
	</div>
<script>
(function(){
if ( ! /iPad|iPod|iPhone/.test( navigator.userAgent ) ) {
	var el = document.getElementById( 'app-config' );
	el.focus();
	el.select();
}
})();
</script>
<?php
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

		// Get the page header.
		include( ABSPATH . 'app-views/includes/partials/header/config-install.php' );
?>
	<div class="setup-install-wrap setup-install-connection-success">
		<main class="setup-config-content global-wrapper">

			<h2><?php _e( 'Successful Database Connection' ); ?></h2>

			<p><?php _e( 'The website management system can now communicate with your database.' ); ?></p>
			<p class="step"><a href="<?php echo $install; ?>" class="button button-large"><?php _e( 'Run the installation' ); ?></a></p>
		</main>
	</div>
	<?php
	// Get the page footer.
	include( ABSPATH . 'app-views/includes/partials/footer/config-install.php' ); ?>
<?php
	endif;

	// End case 2 content.
	break;

// End the paginated setup & config pages markup.
endswitch;

/**
 * wp_print_scripts( 'language-chooser' );
 *
 * @todo Remove if or when necessary.
 */
?>
</body>
</html>
<?php

// End output buffering.
ob_end_flush();