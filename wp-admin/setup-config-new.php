<?php
/**
 * Retrieves and creates the app-config.php file.
 *
 * The permissions for the base directory must allow for writing files in order
 * for the app-config.php to be created using this page.
 *
 * @package WMS
 * @subpackage Administration
 */

// We are installing.
define( 'WP_INSTALLING', true );

// We are unaware of anything.
define( 'WP_SETUP_CONFIG', true );

/**
 * Disable error reporting
 *
 * Set this to error_reporting( -1 ) for debugging.
 */
error_reporting( 0 );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( dirname( __FILE__ ) ) . '/' );
}

ob_start();

require( ABSPATH . 'wp-settings.php' );

// Load administration upgrade API.
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

// Load translation installation API.
require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );

nocache_headers();

// Support app-config.sample.php one level up.
if ( file_exists( ABSPATH . 'app-config.sample.php' ) ) {
	$config_file = file( ABSPATH . 'app-config.sample.php' );
} elseif ( file_exists( dirname( ABSPATH ) . '/app-config.sample.php' ) ) {
	$config_file = file( dirname( ABSPATH ) . '/app-config.sample.php' );
} else {
	wp_die( sprintf(
		/* translators: %s: app-config.sample.php */
		__( 'Sorry, we need a %s file to work from. Please re-upload this file to the root directory of your installation.' ),
		'<code>app-config.sample.php</code>'
	) );
}

// Check if app-config.php has been created.
if ( file_exists( ABSPATH . 'app-config.php' ) ) {
	wp_die( '<p>' . sprintf(
			/* translators: 1: app-config.php 2: install.php */
			__( 'The file %1$s already exists. If you need to reset any of the configuration items in this file, please delete it first. You may try <a href="%2$s">installing now</a>.' ),
			'<code>app-config.php</code>',
			'install.php'
		) . '</p>'
	);
}

// Check if app-config.php exists above the root directory but is not part of another installation.
if ( @file_exists( ABSPATH . '../app-config.php' ) && ! @file_exists( ABSPATH . '../wp-settings.php' ) ) {
	wp_die( '<p>' . sprintf(
			/* translators: 1: app-config.php 2: install.php */
			__( 'The file %1$s already exists one level above your installation. If you need to reset any of the configuration items in this file, please delete it first. You may try <a href="%2$s">installing now</a>.' ),
			'<code>app-config.php</code>',
			'install.php'
		) . '</p>'
	);
}

if ( isset( $_GET['step'] ) ) {
	$step = (int) $_GET['step'];
} else {
	$step = 0;
}

$language = '';

if ( ! empty( $_REQUEST['language'] ) ) {
	$language = preg_replace( '/[^a-zA-Z0-9_]/', '', $_REQUEST['language'] );
} elseif ( isset( $GLOBALS['wp_local_package'] ) ) {
	$language = $GLOBALS['wp_local_package'];
}

switch( $step ) {

	case 0 :
		include_once( ABSPATH . 'wp-admin/partials/header/config-install.php' );
		$step_1 = 'setup-config-new.php?step=1';
?>
	<div class="setup-install-wrap setup-install-introduction">
		<main class="setup-config-content global-wrapper">
			<header>
				<h2><?php _e( 'Setup Introduction' ); ?></h2>
			</header>
			<p><?php _e( 'For the installation process you will need to know the following information.' ); ?></p>
			<ol class="setup-config-database-info-list">
				<li><?php _e( 'Database name' ); ?></li>
				<li><?php _e( 'Database username' ); ?></li>
				<li><?php _e( 'Database password' ); ?></li>
				<li><?php _e( 'Database host' ); ?></li>
			</ol>
			<p><?php _e( 'If you don&#8217;t have this information then you will need to contact your web host.' ); ?></p>
			<p><?php
				/* translators: 1: app-config.sample.php, 2: app-config.php */
				printf( __( 'This information is needed to create a configuration file. If for any reason this automatic file creation doesn&#8217;t work then find the %1$s file in the root directory, open it in a text editor, fill in your information, and save it as %2$s in the same directory.' ),
					'<code>app-config.sample.php</code>',
					'<code>app-config.php</code>'
				);
			?></p>
			<p class="step"><a href="<?php echo $step_1; ?>" class="button button-large"><?php _e( 'Begin Installation' ); ?></a></p>
		</main>
		<?php include_once( ABSPATH . 'wp-admin/partials/footer/config-install.php' ); ?>
	</div>
<?php
break;

case 1 :
	load_default_textdomain( $language );
	$GLOBALS['wp_locale'] = new WP_Locale();

	include_once( ABSPATH . 'wp-admin/partials/header/config-install.php' );
?>
	<div class="setup-install-wrap setup-install-installation">
		<main class="setup-config-content global-wrapper">
			<header>
				<h2><?php _e( 'Installation' ); ?></h2>
			</header>
			<form class="setup-config-form" method="post" action="setup-config-new.php?step=2">
				<fieldset>
					<legend><?php _e( 'White Label' ); ?></legend>
					<p><?php _e( 'Enter an application name to be used throughout the website management system. This allows you to "white label" the application and can be changed at any time in the <code>app-config</code> file.' ); ?></p>
					<p class="setup-config-field setup-config-app-name">
						<label for="app_name"><?php _e( 'Application Name' ); ?></label>
						<br /><span class="setup-config-field-description"><?php _e( 'Enter the name to use for your website management system.' ); ?></span>
						<br /><input name="app_name" id="app_name" type="text" size="25" value="<?php echo htmlspecialchars( _x( 'Website Management System', 'example name for the website management system' ), ENT_QUOTES ); ?>" placeholder="<?php echo htmlspecialchars( _x( 'Website Management System', 'example name for the website management system' ), ENT_QUOTES ); ?>" />
					</p>
				</fieldset>
				<fieldset>
					<legend><?php _e( 'Database Connection' ); ?></legend>
					<p><?php _e( 'Enter your database connection details below. If you&#8217;re not sure about these, contact your host.' ); ?></p>
					<p><?php _e( 'Unique database table prefixes are needed if you want to run more than one installation with a single database. For security purposes a random prefix has been generated. You can void this by entering your own prefix. It is recommended for legibility that the prefix ends in an underscore.' ); ?></p>
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
								/* translators: %s: localhost */
								printf( __( 'You should be able to get this info from your web host, if %s doesn&#8217;t work.' ),'<code>localhost</code>' );
							?></td>
						</tr>
						<tr>
							<th scope="row"><label for="app_db_prefix"><?php _e( 'Database Prefix' ); ?></label></th>
							<td><input name="app_db_prefix" id="app_db_prefix" type="text" value="app_<?php echo esc_attr( md5( time() ) ); ?>_" placeholder="app_" size="25" /></td>
							<td><?php echo sprintf(
								'%1s <em>app_%2s_</em><br />%3s',
								esc_html__( 'Random table prefix is:' ),
								md5( time() ),
								esc_html__( 'Change this if you want to define your own prefix.' )

							); ?></td>
						</tr>
					</table>
				</fieldset>
				<?php if ( isset( $_GET['noapi'] ) ) { ?><input name="noapi" type="hidden" value="1" /><?php } ?>
				<input type="hidden" name="language" value="<?php echo esc_attr( $language ); ?>" />
				<p class="step"><input name="submit" type="submit" value="<?php echo htmlspecialchars( __( 'Submit Information' ), ENT_QUOTES ); ?>" class="button button-large" /></p>
			</form>
		</main>
		<?php include_once( ABSPATH . 'wp-admin/partials/footer/config-install.php' ); ?>
	</div>
<?php
	break;

	case 2 :
	load_default_textdomain( $language );
	$GLOBALS['wp_locale'] = new WP_Locale();

	$app_name        = trim( wp_unslash( $_POST['app_name'] ) );
	$app_db_name     = trim( wp_unslash( $_POST['app_db_name'] ) );
	$app_db_user     = trim( wp_unslash( $_POST['app_db_user'] ) );
	$app_db_password = trim( wp_unslash( $_POST['app_db_password'] ) );
	$app_db_host     = trim( wp_unslash( $_POST['app_db_host'] ) );
	$app_db_prefix   = trim( wp_unslash( $_POST['app_db_prefix'] ) );

	$step_1  = 'setup-config-new.php?step=1';
	$install = 'install.php';
	if ( isset( $_REQUEST['noapi'] ) ) {
		$step_1 .= '&amp;noapi';
	}

	if ( ! empty( $language ) ) {
		$step_1  .= '&amp;language=' . $language;
		$install .= '?language=' . $language;
	} else {
		$install .= '?language=en_US';
	}

	$tryagain_link = '</p><p class="step"><a href="' . $step_1 . '" onclick="javascript:history.go(-1);return false;" class="button button-large">' . __( 'Try again' ) . '</a>';

	if ( empty( $app_db_prefix ) ) {
		wp_die( __( '<strong>ERROR</strong>: "Table Prefix" must not be empty.' . $tryagain_link ) );
	}

	// Validate $app_db_prefix: it can only contain letters, numbers and underscores.
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

	// Re-construct $wpdb with these new values.
	unset( $wpdb );
	require_wp_db();

	/*
	 * The wpdb constructor bails when WP_SETUP_CONFIG is set, so we must
	 * fire this manually. We'll fail here if the values are no good.
	 */
	$wpdb->db_connect();

	if ( ! empty( $wpdb->error ) ) {
		wp_die( $wpdb->error->get_error_message() . $tryagain_link );
	}

	$errors = $wpdb->hide_errors();
	$wpdb->query( "SELECT $app_db_prefix" );
	$wpdb->show_errors( $errors );

	if ( ! $wpdb->last_error ) {
		// MySQL was able to parse the prefix as a value, which we don't want. Bail.
		wp_die( __( '<strong>ERROR</strong>: "Table Prefix" is invalid.' ) );
	}

	// Generate keys and salts using secure CSPRNG.
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
	$max   = strlen( $chars ) - 1;
	for ( $i = 0; $i < 8; $i++ ) {
		$key = '';
		for ( $j = 0; $j < 64; $j++ ) {
			$key .= substr( $chars, random_int( 0, $max ), 1 );
		}
		$secret_keys[] = $key;
	}

	$key = 0;
	foreach ( $config_file as $line_num => $line ) {
		if ( '$table_prefix  =' == substr( $line, 0, 16 ) ) {
			$config_file[ $line_num ] = '$table_prefix  = \'' . addcslashes( $app_db_prefix, "\\'" ) . "';\r\n";
			continue;
		}

		// Replace the sample definitions with user-provided definitions.
		if ( ! preg_match( '/^define\(\s*\'([A-Z_]+)\',([ ]+)/', $line, $match ) ) {
			continue;
		}

		$constant = $match[1];
		$padding  = $match[2];

		switch ( $constant ) {
			case 'DB_NAME'     :
			case 'DB_USER'     :
			case 'DB_PASSWORD' :
			case 'DB_HOST'     :
				$config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'" . addcslashes( constant( $constant ), "\\'" ) . "' );\r\n";
				break;
			case 'DB_CHARSET'  :
				if ( 'utf8mb4' === $wpdb->charset || ( ! $wpdb->charset && $wpdb->has_cap( 'utf8mb4' ) ) ) {
					$config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'utf8mb4' );\r\n";
				}
				break;
			case 'AUTH_KEY'         :
			case 'SECURE_AUTH_KEY'  :
			case 'LOGGED_IN_KEY'    :
			case 'NONCE_KEY'        :
			case 'AUTH_SALT'        :
			case 'SECURE_AUTH_SALT' :
			case 'LOGGED_IN_SALT'   :
			case 'NONCE_SALT'       :
				$config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'" . $secret_keys[$key++] . "' );\r\n";
				break;
			case 'APP_NAME'     :
				$config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'" . $app_name . "' );\r\n";
				break;
		}
	}
	unset( $line );

	if ( ! is_writable( ABSPATH ) ) :
		include_once( ABSPATH . 'wp-admin/partials/header/config-install.php' );
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
				foreach ( $config_file as $line ) {
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
	else :
		/*
		 * If this file doesn't exist, then we are using the app-config.sample.php
		 * file one level up, which is for the develop repo.
		 */
		if ( file_exists( ABSPATH . 'app-config.sample.php' ) )
			$path_to_wp_config = ABSPATH . 'app-config.php';
		else
			$path_to_wp_config = dirname( ABSPATH ) . '/app-config.php';

		$handle = fopen( $path_to_wp_config, 'w' );
		foreach ( $config_file as $line ) {
			fwrite( $handle, $line );
		}
		fclose( $handle );
		chmod( $path_to_wp_config, 0666 );
		include_once( ABSPATH . 'wp-admin/partials/header/config-install.php' );
?>
	<div class="setup-install-wrap setup-install-connection-success">
		<main class="setup-config-content global-wrapper">
			<header>
				<h2><?php _e( 'Successful Database Connection' ); ?></h2>
			</header>
			<p><?php _e( 'The website management system can now communicate with your database.' ); ?></p>
			<p class="step"><a href="<?php echo $install; ?>" class="button button-large"><?php _e( 'Run the installation' ); ?></a></p>
		</main>
		<?php include_once( ABSPATH . 'wp-admin/partials/footer/config-install.php' ); ?>
	</div>
<?php
	endif;
	break;
}
?>
<?php // wp_print_scripts( 'language-chooser' ); ?>
</body>
</html>
<?php ob_end_flush();