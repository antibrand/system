<?php
/**
 * Database Repair and Optimization Script.
 *
 * @package App_Package
 * @subpackage Database
 */

define( 'WP_REPAIRING', true );

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

// Load the administration environment.
require_once( APP_INC_PATH . '/backend/app-admin.php' );

header( 'Content-Type: text/html; charset=utf-8' );

// Page identification.
$parent_file = 'tools.php';
$title       = __( 'Database Repair' );

// Get the admin page header.
include( APP_VIEWS_PATH . '/backend/header/admin-header.php' );

?>
<div class="wrap">
<?php

if ( ! defined( 'APP_ALLOW_REPAIR' ) || ( defined( 'APP_ALLOW_REPAIR' ) && ! APP_ALLOW_REPAIR ) ) {

	echo sprintf(
		'<h1>%1s</h1>',
		$title
	);

	echo '<p>';
	printf(
		__( 'To allow use of this page to automatically repair database problems, please add the following line to your %s file. Once this line is added to your config, reload this page.' ),
		'<code>app-config.php</code>'
	);
	echo "</p><p><code>define( 'APP_ALLOW_REPAIR', true );</code></p>";

	$default_key     = 'put your unique phrase here';
	$missing_key     = false;
	$duplicated_keys = array();

	foreach ( array( 'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT' ) as $key ) {

		if ( defined( $key ) ) {

			// Check for unique values of each key.
			$duplicated_keys[ constant( $key ) ] = isset( $duplicated_keys[ constant( $key ) ] );

		} else {
			// If a constant is not defined, it's missing.
			$missing_key = true;
		}
	}

	// If at least one key uses the default value, consider it duplicated.
	if ( isset( $duplicated_keys[ $default_key ] ) ) {
		$duplicated_keys[ $default_key ] = true;
	}

	// Weed out all unique, non-default values.
	$duplicated_keys = array_filter( $duplicated_keys );

	if ( $duplicated_keys || $missing_key ) {

		echo '<h2 class="screen-reader-text">' . __( 'Check secret keys' ) . '</h2>';

		echo sprintf(
			'<p>%1s <code>%2s</code> %3s</p>',
			__( 'While you are editing your' ),
			esc_html( 'app-config.php' ),
			__( 'file, take a moment to make sure you have all 8 keys and that they are unique.' )
		);
	}

} elseif ( isset( $_GET['repair'] ) ) {

	echo sprintf(
		'<h1>%1s</h1>',
		$title
	);

	$optimize = 2 == $_GET['repair'];
	$okay     = true;
	$problems = [];

	$tables = $wpdb->tables();

	// Sitecategories may not exist if global terms are disabled.
	$query = $wpdb->prepare( "SHOW TABLES LIKE %s", $wpdb->esc_like( $wpdb->sitecategories ) );

	if ( is_network() && ! $wpdb->get_var( $query ) ) {
		unset( $tables['sitecategories'] );
	}

	/**
	 * Filters additional database tables to repair.
	 *
	 * @since Previous 3.0.0
	 * @param array $tables Array of prefixed table names to be repaired.
	 */
	$tables = array_merge( $tables, (array) apply_filters( 'tables_to_repair', array() ) );

	// Loop over the tables, checking and repairing as needed.
	foreach ( $tables as $table ) {

		$check = $wpdb->get_row( "CHECK TABLE $table" );

		echo '<p>';

		if ( 'OK' == $check->Msg_text ) {
			printf( __( 'The %s table is okay.' ), "<code>$table</code>" );

		} else {

			printf( __( 'The %1$s table is not okay. It is reporting the following error: %2$s. The application will attempt to repair this table&hellip;' ) , "<code>$table</code>", "<code>$check->Msg_text</code>" );

			$repair = $wpdb->get_row( "REPAIR TABLE $table" );

			echo '<br />&nbsp;&nbsp;&nbsp;&nbsp;';

			if ( 'OK' == $check->Msg_text ) {
				printf( __( 'Successfully repaired the %s table.' ), "<code>$table</code>" );

			} else {

				echo sprintf( __( 'Failed to repair the %1$s table. Error: %2$s' ), "<code>$table</code>", "<code>$check->Msg_text</code>" ) . '<br />';
				$problems[$table] = $check->Msg_text;
				$okay = false;
			}
		}

		if ( $okay && $optimize ) {

			$check = $wpdb->get_row( "ANALYZE TABLE $table" );

			echo '<br />&nbsp;&nbsp;&nbsp;&nbsp;';

			if ( 'Table is already up to date' == $check->Msg_text )  {
				printf( __( 'The %s table is already optimized.' ), "<code>$table</code>" );

			} else {

				$check = $wpdb->get_row( "OPTIMIZE TABLE $table" );

				echo '<br />&nbsp;&nbsp;&nbsp;&nbsp;';
				if ( 'OK' == $check->Msg_text || 'Table is already up to date' == $check->Msg_text ) {
					printf( __( 'Successfully optimized the %s table.' ), "<code>$table</code>" );
				} else {
					printf( __( 'Failed to optimize the %1$s table. Error: %2$s' ), "<code>$table</code>", "<code>$check->Msg_text</code>" );
				}
			}
		}
		echo '</p>';
	}

	if ( $problems ) {

		printf( '<p>' . __( 'Some database problems could not be repaired.' ) . '</p>' );
		$problem_output = '';

		foreach ( $problems as $table => $problem ) {
			$problem_output .= "$table: $problem\n";
		}

		echo '<p><textarea name="errors" id="errors" rows="20" cols="60">' . esc_textarea( $problem_output ) . '</textarea></p>';

	} else {
		echo '<p>' . __( 'Repairs complete. Please remove the following line from the configuration file to prevent this page from being used by unauthorized users.' ) . "</p><p><code>define( 'APP_ALLOW_REPAIR', true );</code></p>";

		echo sprintf(
			'<p><a href="%1s" class="button">%2s</a></p>',
			esc_url( 'repair.php' ),
			__( 'Back to Repair' )
		);
	}

} else {

	echo sprintf(
		'<h1>%1s</h1>',
		$title
	);

	if ( isset( $_GET['referrer'] ) && 'is_blog_installed' == $_GET['referrer'] ) {
		echo '<p>' . __( 'One or more database tables are unavailable. To attempt to repair these tables, press the &#8220;Repair Database&#8221; button. Repairing can take a while, so please be patient.' ) . '</p>';
	} else {
		echo '<p>' . __( 'The application can automatically look for some common database problems and repair them. Repairing can take a while, so please be patient.' ) . '</p>';
	}
?>
	<p class="step"><a class="button button-large" href="repair.php?repair=1"><?php _e( 'Repair Database' ); ?></a></p>

	<p><?php _e( 'The application can also attempt to optimize the database. This improves performance in some situations. Repairing and optimizing the database can take a long time and the database will be locked while optimizing.' ); ?></p>

	<p class="step"><a class="button button-large" href="repair.php?repair=2"><?php _e( 'Repair and Optimize Database' ); ?></a></p>
<?php
}
?>
</div><!-- End .wrap -->
<?php

// Get the admin page footer.
include( APP_VIEWS_PATH . '/backend/footer/admin-footer.php' );
