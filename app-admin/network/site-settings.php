<?php
/**
 * Edit Site Settings Administration Screen
 *
 * @package App_Package
 * @subpackage Network
 * @since Previous 3.1.0
 */

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! current_user_can( 'manage_sites' ) ) {
	wp_die( __( 'Sorry, you are not allowed to edit this site.' ) );
}

get_current_screen()->add_help_tab( get_site_screen_help_tab_args() );
get_current_screen()->set_help_sidebar( get_site_screen_help_sidebar_content() );

if ( isset( $_REQUEST['id'] ) ) {
	$id = intval( $_REQUEST['id'] );
} else {
	$id = 0;
}

if ( ! $id ) {
	wp_die( __( 'Invalid site ID.' ) );
}

$details = get_site( $id );
if ( ! $details ) {
	wp_die( __( 'The requested site does not exist.' ) );
}

if ( ! can_edit_network( $details->site_id ) ) {
	wp_die( __( 'Sorry, you are not allowed to access this page.' ), 403 );
}

$is_main_site = is_main_site( $id );

if ( isset( $_REQUEST['action'] ) && 'update-site' == $_REQUEST['action'] && is_array( $_POST['option'] ) ) {

	check_admin_referer( 'edit-site' );
	switch_to_blog( $id );

	// Don't update these options since they are handled elsewhere in the form.
	$skip_options = array( 'allowedthemes' );

	foreach ( (array) $_POST['option'] as $key => $val ) {

		$key = wp_unslash( $key );
		$val = wp_unslash( $val );

		// Avoids "0 is a protected WP option and may not be modified" error when edit blog options.
		if ( $key === 0 || is_array( $val ) || in_array($key, $skip_options) ) {
			continue;
		}

		update_option( $key, $val );
	}

	/**
	 * Fires after the site options are updated.
	 *
	 * @since Previous 3.0.0
	 * @since Previous 4.4.0 Added `$id` parameter.
	 * @param int $id The ID of the site being updated.
	 */
	do_action( 'wpmu_update_blog_options', $id );

	restore_current_blog();
	wp_redirect( add_query_arg( array( 'update' => 'updated', 'id' => $id ), 'site-settings.php' ) );
	exit;
}

if ( isset($_GET['update']) ) {
	$messages = array();
	if ( 'updated' == $_GET['update'] )
		$messages[] = __( 'Site options updated.' );
}

/* translators: %s: site name */
$title = sprintf( __( 'Site Settings: %s' ), esc_html( $details->blogname ) );

$parent_file  = 'sites.php';
$submenu_file = 'sites.php';

// Get the admin page header.
include( APP_VIEWS_PATH . '/backend/header/admin-header.php' );

?>

<div class="wrap">

	<h1 id="edit-site"><?php echo $title; ?></h1>

	<nav role="navigation">
		<ul class="network-site-settings-nav top">
			<li><a href="<?php echo esc_url( get_home_url( $id, '/' ) ); ?>" class="button"><?php _e( 'Visit' ); ?></a>
			<li><a href="<?php echo esc_url( get_admin_url( $id ) ); ?>" class="button"><?php _e( 'Dashboard' ); ?></a>
		</ul>
	</nav>

	<?php

	network_edit_site_nav( [
		'blog_id'  => $id,
		'selected' => 'site-settings'
	] );

	if ( ! empty( $messages ) ) {

		foreach ( $messages as $msg ) {
			echo '<div id="message" class="updated notice is-dismissible"><p>' . $msg . '</p></div>';
		}
	}

	?>
	<form method="post" action="site-settings.php?action=update-site">

		<?php wp_nonce_field( 'edit-site' ); ?>
		<input type="hidden" name="id" value="<?php echo esc_attr( $id ) ?>" />

		<table class="form-table">

			<?php
			$blog_prefix = $wpdb->get_blog_prefix( $id );
			$sql = "SELECT * FROM {$blog_prefix}options
				WHERE option_name NOT LIKE %s
				AND option_name NOT LIKE %s";
			$query = $wpdb->prepare( $sql,
				$wpdb->esc_like( '_' ) . '%',
				'%' . $wpdb->esc_like( 'user_roles' )
			);

			$options = $wpdb->get_results( $query );

			foreach ( $options as $option ) {

				if ( $option->option_name == 'default_role' ) {
					$editblog_default_role = $option->option_value;
				}

				/**
				 * Field labels
				 *
				 * Use specific text for select labels
				 * rather than the option name.
				 *
				 * @since 1.0.0
				 */
				if ( $option->option_name === 'siteurl' ) {
					$test = 'Site URL';
				} elseif ( $option->option_name === 'home' ) {
					$test = 'Home URL';
				} elseif ( $option->option_name === 'blogname' ) {
					$test = 'Site Name';
				} elseif ( $option->option_name === 'blogdescription' ) {
					$test = 'Site Description';
				} elseif ( $option->option_name === 'use_balanceTags' ) {
					$test = 'Use Balance Tags';
				} elseif ( $option->option_name === 'posts_per_rss' ) {
					$test = 'RSS Posts per Page';
				} elseif ( $option->option_name === 'rss_use_excerpt' ) {
					$test = 'Use Excerpts in RSS';
				} elseif ( $option->option_name === 'mailserver_url' ) {
					$test = 'Mail Server URL';
				} elseif ( $option->option_name === 'mailserver_pass' ) {
					$test = 'Mail Server Password';
				} elseif ( $option->option_name === 'gmt_offset' ) {
					$test = 'GMT Offset';
				} elseif ( $option->option_name === 'html_type' ) {
					$test = 'HTML Type';
				} elseif ( $option->option_name === 'db_version' ) {
					$test = 'Database Version';
				} elseif ( $option->option_name === 'upload_url_path' ) {
					$test = 'Upload URL Path';
				} else {
					$test = esc_html( ucwords( str_replace( "_", " ", $option->option_name ) ) );
				}

				$disabled = false;
				$class    = 'all-options';

				if ( is_serialized( $option->option_value ) ) {

					if ( is_serialized_string( $option->option_value ) ) {
						$option->option_value = esc_html( maybe_unserialize( $option->option_value ) );

					} else {

						$option->option_value = 'SERIALIZED DATA';

						$disabled = true;
						$class    = 'all-options disabled';
					}
				}

				if ( strpos( $option->option_value, "\n" ) !== false ) {

				?>
					<tr class="form-field">
						<th scope="row">
							<label for="<?php echo esc_attr( $option->option_name ) ?>"><?php echo ucwords( str_replace( "_", " ", $option->option_name ) ) ?></label>
						</th>
						<td>
							<textarea class="<?php echo $class; ?>" rows="5" cols="40" name="option[<?php echo esc_attr( $option->option_name ) ?>]" id="<?php echo esc_attr( $option->option_name ) ?>"<?php disabled( $disabled ) ?>><?php echo esc_textarea( $option->option_value ) ?></textarea>
						</td>
					</tr>
				<?php

				} else {

				?>
					<tr class="form-field">
						<th scope="row">
							<label for="<?php echo esc_attr( $option->option_name ) ?>"><?php echo $test; ?></label>
						</th>

						<?php

						if ( $is_main_site && in_array( $option->option_name, array( 'siteurl', 'home' ) ) ) { ?>
						<td>
							<code><?php echo esc_html( $option->option_value ) ?></code>
						</td>
						<?php

						} else {

						?>
						<td>
							<input class="<?php echo $class; ?>" name="option[<?php echo esc_attr( $option->option_name ) ?>]" type="text" id="<?php echo esc_attr( $option->option_name ) ?>" value="<?php echo esc_attr( $option->option_value ) ?>" size="40" <?php disabled( $disabled ) ?> />
						</td>
						<?php

						}

						?>
					</tr>
				<?php
				}

			} // End foreach
			/**
			 * Fires at the end of the Edit Site form, before the submit button.
			 *
			 * @since Previous 3.0.0
			 * @param int $id Site ID.
			 */
			do_action( 'wpmueditblogaction', $id );

			?>
		</table>

		<p><?php submit_button(); ?></p>

	</form>

</div>
<?php

// Get the admin page footer.
include( APP_VIEWS_PATH . '/backend/footer/admin-footer.php' );
