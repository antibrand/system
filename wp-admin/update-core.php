<?php
/**
 * Update administration panel
 *
 * @package App_Package
 * @subpackage Administration
 */

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

wp_enqueue_style( 'plugin-install' );
wp_enqueue_script( 'plugin-install' );
wp_enqueue_script( 'updates' );
add_thickbox();

if ( is_multisite() && ! is_network_admin() ) {
	wp_redirect( network_admin_url( 'update-core.php' ) );

	exit();
}

if ( ! current_user_can( 'update_core' ) && ! current_user_can( 'update_themes' ) && ! current_user_can( 'update_plugins' ) && ! current_user_can( 'update_languages' ) ) {
	wp_die( __( 'Sorry, you are not allowed to update this site.' ) );
}

/**
 *
 * @global string $wp_local_package
 * @global wpdb   $wpdb
 *
 * @staticvar bool $first_pass
 *
 * @param object $update
 */
function list_core_update( $update ) {

 	global $wp_local_package, $wpdb;
  	static $first_pass = true;

	$wp_version = get_bloginfo( 'version' );

	if ( 'en_US' == $update->locale && 'en_US' == get_locale() ) {
		$version_string = $update->current;

 	// If the only available update is a partial builds, it doesn't need a language-specific version string.
	} elseif ( 'en_US' == $update->locale && $update->packages->partial && $wp_version == $update->partial_version && ( $updates = get_core_updates() ) && 1 == count( $updates ) ) {
		$version_string = $update->current;
	} else {
		$version_string = sprintf( "%s&ndash;<strong>%s</strong>", $update->current, $update->locale );
	}

	$current = false;

	if ( ! isset( $update->response ) || 'latest' == $update->response ) {
		$current = true;
	}

	$submit        = __( 'Update Now' );
	$form_action   = 'update-core.php?action=do-core-upgrade';
	$php_version   = phpversion();
	$mysql_version = $wpdb->db_version();
	$show_buttons  = true;

	if ( 'development' == $update->response ) {
		$message = __( 'You are using a development version of the website management system. You can update to the latest nightly build automatically:' );

	} else {

		if ( $current ) {
			$message     = sprintf( __( 'If you need to re-install version %s, you can do so here:' ), $version_string );
			$submit      = __( 'Re-install Now' );
			$form_action = 'update-core.php?action=do-core-reinstall';

		} else {

			$php_compat = version_compare( $php_version, $update->php_version, '>=' );

			if ( file_exists( WP_CONTENT_DIR . '/db.php' ) && empty( $wpdb->is_mysql ) ) {
				$mysql_compat = true;
			} else {
				$mysql_compat = version_compare( $mysql_version, $update->mysql_version, '>=' );
			}

			if ( ! $mysql_compat && ! $php_compat ) {

				$message = sprintf(
					__( 'You cannot update because %1s requires PHP version %2s or higher and MySQL version %3s or higher. You are running PHP version %4s and MySQL version %5s.' ),
					APP_NAME,
					$update->php_version,
					$update->mysql_version,
					$php_version,
					$mysql_version
				);

			} elseif ( ! $php_compat ) {

				$message = sprintf(
					__( 'You cannot update because %1s requires PHP version %2s or higher. You are running version %3s.' ),
					APP_NAME,
					$update->php_version,
					$php_version
				);

			} elseif ( ! $mysql_compat ) {

				$message = sprintf(
					__( 'You cannot update because %1s requires MySQL version %2s or higher. You are running version %3s.' ),
					APP_NAME,
					$update->mysql_version,
					$mysql_version
				);

			} else {

				$message = 	sprintf(
					__( 'You can update %1s automatically:' ),
					APP_NAME
				);
			}

			if ( ! $mysql_compat || !$php_compat ) {
				$show_buttons = false;
			}
		}
	}

	echo '<p>';
	echo $message;
	echo '</p>';
	echo '<form method="post" action="' . $form_action . '" name="upgrade" class="upgrade">';
	wp_nonce_field( 'upgrade-core' );
	echo '<p>';
	echo '<input name="version" value="'. esc_attr( $update->current ) .'" type="hidden"/>';
	echo '<input name="locale" value="'. esc_attr( $update->locale ) .'" type="hidden"/>';
	if ( $show_buttons ) {
		if ( $first_pass ) {
			submit_button( $submit, $current ? '' : 'primary regular', 'upgrade', false );
			$first_pass = false;
		} else {
			submit_button( $submit, '', 'upgrade', false );
		}
	}

	if ( 'en_US' != $update->locale ) {

		if ( ! isset( $update->dismissed ) || !$update->dismissed ) {
			submit_button( __( 'Hide this update' ), '', 'dismiss', false );
		} else {
			submit_button( __( 'Bring back this update' ), '', 'undismiss', false );
		}
	}

	echo '</p>';

	if ( 'en_US' != $update->locale && ( !isset($wp_local_package) || $wp_local_package != $update->locale ) ) {
		echo '<p class="hint">'.__('This localized version contains both the translation and various other localization fixes. You can skip upgrading if you want to keep your current translation.').'</p>';

	// Partial builds don't need language-specific warnings.
	} elseif ( 'en_US' == $update->locale && get_locale() != 'en_US' && ( ! $update->packages->partial && $wp_version == $update->partial_version ) ) {
	    echo '<p class="hint">'. __( 'You are about to install the website management <strong>in English (US).</strong> There is a chance this update will break your translation. You may prefer to wait for the localized version to be released.' ) .'</p>';
	}

	echo '</form>';

}

/**
 * Dismissed updates
 *
 * @since Before 2.7.0
 */
function dismissed_updates() {

	$dismissed = get_core_updates( [ 'dismissed' => true, 'available' => false ] );

	if ( $dismissed ) {

		$show_text = esc_js( __( 'Show hidden updates' ) );
		$hide_text = esc_js( __( 'Hide hidden updates' ) );
	?>
	<script type="text/javascript">
		jQuery( function($) {
			$( 'dismissed-updates' ).show();
			$( '#show-dismissed' ).toggle(
				function() {
					$(this).text( '<?php echo $hide_text; ?>' );
				},
				function() {
					$(this).text( '<?php echo $show_text; ?>' )
				}
			);
			$( '#show-dismissed' ).click( function() {
				$( '#dismissed-updates' ).toggle( 'slow' );
			});
		});
	</script>
	<?php
		echo '<p class="hide-if-no-js"><a id="show-dismissed" href="#">'.__('Show hidden updates').'</a></p>';
		echo '<ul id="dismissed-updates" class="core-updates dismissed">';
		foreach ( (array) $dismissed as $update) {
			echo '<li>';
			list_core_update( $update );
			echo '</li>';
		}
		echo '</ul>';
	}
}

/**
 * Display upgrade for downloading latest or upgrading automatically form
 *
 * @since Before 2.7.0
 * @global string $required_php_version
 * @global string $required_mysql_version
 */
function core_upgrade_preamble() {

	global $required_php_version, $required_mysql_version;

	$wp_version = get_bloginfo( 'version' );
	$updates    = get_core_updates();

	if ( ! isset( $updates[0]->response ) || 'latest' == $updates[0]->response ) {

		echo '<h2>';
		echo sprintf(
			'%1s %2s',
			__( 'You have the latest version of' ),
			APP_NAME
		);

		if ( wp_http_supports( array( 'ssl' ) ) ) {

			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

			$upgrader            = new WP_Automatic_Updater;
			$future_minor_update = (object) [
				'current'       => $wp_version . '.1.next.minor',
				'version'       => $wp_version . '.1.next.minor',
				'php_version'   => $required_php_version,
				'mysql_version' => $required_mysql_version,
			];
			$should_auto_update = $upgrader->should_update( 'core', $future_minor_update, ABSPATH );

			if ( $should_auto_update ) {
				echo ' ' . __( 'Future security updates will be applied automatically.' );
			}

		}

		echo '</h2>';

	} else {

		echo '<div class="notice notice-warning"><p>';
		_e('<strong>Important:</strong> Before updating, please back up your database and files.');
		echo '</p></div>';

		echo sprintf(
			'<h2 class="response">%1s %2s %3s</h2>',
			__( 'An updated version of' ),
			APP_NAME,
			__( 'is available.' )
		);
	}

	if ( isset( $updates[0] ) && $updates[0]->response == 'development' ) {

		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		$upgrader = new WP_Automatic_Updater;

		if ( wp_http_supports( 'ssl' ) && $upgrader->should_update( 'core', $updates[0], ABSPATH ) ) {

			echo '<div class="updated inline"><p>';
			echo '<strong>' . __( 'BETA TESTERS:' ) . '</strong> ' . __( 'This site is set up to install updates of future beta versions automatically.' );
			echo '</p></div>';
		}
	}

	echo '<ul class="core-updates">';

	foreach ( (array) $updates as $update ) {
		echo '<li>';
		list_core_update( $update );
		echo '</li>';
	}

	echo '</ul>';

	// Don't show the maintenance mode notice when we are only showing a single re-install option.
	if ( $updates && ( count( $updates ) > 1 || $updates[0]->response != 'latest' ) ) {

		echo '<p>' . __( 'While your site is being updated, it will be in maintenance mode. As soon as your updates are complete, your site will return to normal.' ) . '</p>';

	} elseif ( ! $updates ) {
		list( $normalized_version ) = explode( '-', $wp_version );
		echo '<p>' . sprintf( __( '<a href="%s">Learn more</a>.' ), esc_url( self_admin_url( 'about.php' ) ) ) . '</p>';
	}

	dismissed_updates();
}

function list_plugin_updates() {

	$wp_version     = get_bloginfo( 'version' );
	$cur_wp_version = preg_replace( '/-.*$/', '', $wp_version );

	require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

	$plugins = get_plugin_updates();

	if ( empty( $plugins ) ) {

		echo '<h2>' . __( 'Plugins' ) . '</h2>';
		echo '<p>' . __( 'Your plugins are all up to date.' ) . '</p>';

		return;
	}

	$form_action  = 'update-core.php?action=do-plugin-upgrade';
	$core_updates = get_core_updates();

	if ( ! isset( $core_updates[0]->response ) || 'latest' == $core_updates[0]->response || 'development' == $core_updates[0]->response || version_compare( $core_updates[0]->current, $cur_wp_version, '=') ) {
		$core_update_version = false;
	} else {
		$core_update_version = $core_updates[0]->current;
	}
?>
	<h2><?php _e( 'Plugins' ); ?></h2>

	<p><?php _e( 'The following plugins have new versions available. Check the ones you want to update and then click &#8220;Update Plugins&#8221;.' ); ?></p>

	<form method="post" action="<?php echo esc_url( $form_action ); ?>" name="upgrade-plugins" class="upgrade">

		<?php wp_nonce_field('upgrade-core'); ?>

		<p><input id="upgrade-plugins" class="button" type="submit" value="<?php esc_attr_e('Update Plugins'); ?>" name="upgrade" /></p>

		<table class="widefat updates-table" id="update-plugins-table">
			<thead>
			<tr>
				<td class="manage-column check-column"><input type="checkbox" id="plugins-select-all" /></td>
				<td class="manage-column"><label for="plugins-select-all"><?php _e( 'Select All' ); ?></label></td>
			</tr>
			</thead>

			<tbody class="plugins">
			<?php
			foreach ( (array) $plugins as $plugin_file => $plugin_data ) {

				$plugin_data     = (object) _get_plugin_data_markup_translate( $plugin_file, (array) $plugin_data, false, true );
				$icon            = '<span class="dashicons dashicons-admin-plugins"></span>';
				$preferred_icons = [ 'svg', '1x', '2x', 'default' ];

				foreach ( $preferred_icons as $preferred_icon ) {

					if ( ! empty( $plugin_data->update->icons[ $preferred_icon ] ) ) {

						$icon = '<img src="' . esc_url( $plugin_data->update->icons[ $preferred_icon ] ) . '" alt="" />';

						break;
					}
				}

				// Get plugin compat for running version.
				if ( isset( $plugin_data->update->tested ) && version_compare( $plugin_data->update->tested, $cur_wp_version, '>=' ) ) {

					$compat = '<br />' . sprintf( __( 'Compatibility with %1s %2s: 100%% (according to its author)' ), APP_NAME, $cur_wp_version );

				} elseif ( isset( $plugin_data->update->compatibility->{$cur_wp_version} ) ) {

					$compat = $plugin_data->update->compatibility->{$cur_wp_version};
					$compat = '<br />' . sprintf( __( 'Compatibility with %1s %2s: %2$d%% (%3$d "works" votes out of %4$d total)' ), APP_NAME, $cur_wp_version, $compat->percent, $compat->votes, $compat->total_votes );

				} else {
					$compat = '<br />' . sprintf( __( 'Compatibility with %1s %2s: Unknown' ), APP_NAME, $cur_wp_version );
				}

				// Get plugin compat for updated version.
				if ( $core_update_version ) {

					if ( isset( $plugin_data->update->tested ) && version_compare( $plugin_data->update->tested, $core_update_version, '>=' ) ) {

						$compat .= '<br />' . sprintf( __( 'Compatibility with %1s %2s: 100%% (according to its author)' ), APP_NAME, $core_update_version );

					} elseif ( isset( $plugin_data->update->compatibility->{$core_update_version} ) ) {

						$update_compat = $plugin_data->update->compatibility->{$core_update_version};
						$compat .= '<br />' . sprintf( __( 'Compatibility with %1s %2s: %2$d%% (%3$d "works" votes out of %4$d total)' ), APP_NAME, $core_update_version, $update_compat->percent, $update_compat->votes, $update_compat->total_votes );

					} else {
						$compat .= '<br />' . sprintf( __( 'Compatibility with %1s %2s: Unknown' ), APP_NAME, $core_update_version );
					}
				}

				// Get the upgrade notice for the new plugin version.
				if ( isset( $plugin_data->update->upgrade_notice ) ) {
					$upgrade_notice = '<br />' . strip_tags( $plugin_data->update->upgrade_notice );
				} else {
					$upgrade_notice = '';
				}

				$details_url = self_admin_url( 'plugin-install.php?tab=plugin-information&plugin=' . $plugin_data->update->slug . '&section=changelog&TB_iframe=true&width=640&height=662' );

				$details = sprintf(
					'<a href="%1$s" class="thickbox open-plugin-details-modal" aria-label="%2$s">%3$s</a>',
					esc_url( $details_url ),
					// Translators: 1: plugin name, 2: version number.
					esc_attr( sprintf( __( 'View %1$s version %2$s details' ), $plugin_data->Name, $plugin_data->update->new_version ) ),
					// Translators: %s: plugin version.
					sprintf( __( 'View version %s details.' ), $plugin_data->update->new_version )
				);

				$checkbox_id = "checkbox_" . md5( $plugin_data->Name );

				?>
				<tr>
					<td class="check-column">
						<input type="checkbox" name="checked[]" id="<?php echo $checkbox_id; ?>" value="<?php echo esc_attr( $plugin_file ); ?>" />
						<label for="<?php echo $checkbox_id; ?>" class="screen-reader-text"><?php
							// Translators: %s: plugin name.
							printf( __( 'Select %s' ),
								$plugin_data->Name
							);
						?></label>
					</td>
					<td class="plugin-title">
						<p>
							<?php echo $icon; ?>
							<strong><?php echo $plugin_data->Name; ?></strong>
							<?php
								// Translators: 1: plugin version, 2: new version.
								printf( __( 'You have version %1$s installed. Update to %2$s.' ),
									$plugin_data->Version,
									$plugin_data->update->new_version
								);
								echo ' ' . $details . $compat . $upgrade_notice;
							?>
						</p>
					</td>
				</tr>
				<?php
			}
		?>
			</tbody>

			<tfoot>
				<tr>
					<td class="manage-column check-column">
						<input type="checkbox" id="plugins-select-all-2" />
					</td>
					<td class="manage-column">
						<label for="plugins-select-all-2"><?php _e( 'Select All' ); ?></label>
					</td>
				</tr>
			</tfoot>
		</table>

		<p><input id="upgrade-plugins-2" class="button" type="submit" value="<?php esc_attr_e( 'Update Plugins' ); ?>" name="upgrade" /></p>
	</form>
<?php
}

/**
 * List theme updates
 *
 * @since Before 2.9.0
 */
function list_theme_updates() {

	$themes = get_theme_updates();

	if ( empty( $themes ) ) {

		echo '<h2>' . __( 'Themes' ) . '</h2>';
		echo '<p>' . __( 'Your themes are all up to date.' ) . '</p>';

		return;
	}

	$form_action = 'update-core.php?action=do-theme-upgrade';

?>
	<h2><?php _e( 'Themes' ); ?></h2>

	<p><?php _e( 'The following themes have new versions available. Check the ones you want to update and then click &#8220;Update Themes&#8221;.' ); ?></p>

	<p><?php printf( __( '<strong>Please Note:</strong> Any customizations you have made to theme files will be lost. Please consider using child themes for modifications.' ) ); ?></p>

	<form method="post" action="<?php echo esc_url( $form_action ); ?>" name="upgrade-themes" class="upgrade">

		<?php wp_nonce_field( 'upgrade-core' ); ?>

		<p><input id="upgrade-themes" class="button" type="submit" value="<?php esc_attr_e('Update Themes'); ?>" name="upgrade" /></p>

		<table class="widefat updates-table" id="update-themes-table">
			<thead>
			<tr>
				<td class="manage-column check-column"><input type="checkbox" id="themes-select-all" /></td>
				<td class="manage-column"><label for="themes-select-all"><?php _e( 'Select All' ); ?></label></td>
			</tr>
			</thead>

			<tbody class="plugins">
			<?php
			foreach ( $themes as $stylesheet => $theme ) {

				$checkbox_id = 'checkbox_' . md5( $theme->get( 'Name' ) );

				?>
				<tr>
					<td class="check-column">
						<input type="checkbox" name="checked[]" id="<?php echo $checkbox_id; ?>" value="<?php echo esc_attr( $stylesheet ); ?>" />
						<label for="<?php echo $checkbox_id; ?>" class="screen-reader-text"><?php
							// Translators: %s: theme name.
							printf( __( 'Select %s' ),
								$theme->display( 'Name' )
							);
						?></label>
					</td>
					<td class="plugin-title">
						<p>
							<img src="<?php echo esc_url( $theme->get_screenshot() ); ?>" width="85" height="64" class="updates-table-screenshot" alt="" />
							<strong><?php echo $theme->display( 'Name' ); ?></strong>
							<?php
								/* translators: 1: theme version, 2: new version */
								printf( __( 'You have version %1$s installed. Update to %2$s.' ),
									$theme->display( 'Version' ),
									$theme->update['new_version']
								);
							?>
						</p>
					</td>
				</tr>
				<?php
			}
		?>
			</tbody>

			<tfoot>
				<tr>
					<td class="manage-column check-column"><input type="checkbox" id="themes-select-all-2" /></td>
					<td class="manage-column"><label for="themes-select-all-2"><?php _e( 'Select All' ); ?></label></td>
				</tr>
			</tfoot>
		</table>

		<p><input id="upgrade-themes-2" class="button" type="submit" value="<?php esc_attr_e( 'Update Themes' ); ?>" name="upgrade" /></p>

	</form>
<?php
}

/**
 * List translation updates
 *
 * @since Before 3.7.0
 */
function list_translation_updates() {

	$updates = wp_get_translation_updates();

	if ( ! $updates ) {

		if ( 'en_US' != get_locale() ) {

			echo '<h2>' . __( 'Translations' ) . '</h2>';
			echo '<p>' . __( 'Your translations are all up to date.' ) . '</p>';
		}

		return;
	}

	$form_action = 'update-core.php?action=do-translation-upgrade';

	?>
	<h2><?php _e( 'Translations' ); ?></h2>

	<form method="post" action="<?php echo esc_url( $form_action ); ?>" name="upgrade-translations" class="upgrade">
		<p><?php _e( 'New translations are available.' ); ?></p>
		<?php wp_nonce_field( 'upgrade-translations' ); ?>
		<p><input class="button" type="submit" value="<?php esc_attr_e( 'Update Translations' ); ?>" name="upgrade" /></p>
	</form>
	<?php
}

/**
 * Upgrade core display
 *
 * @since Before 2.7.0
 * @global WP_Filesystem_Base $wp_filesystem Subclass
 * @param bool $reinstall
 */
function do_core_upgrade( $reinstall = false ) {

	global $wp_filesystem;

	include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

	if ( $reinstall ) {
		$url = 'update-core.php?action=do-core-reinstall';
	} else {
		$url = 'update-core.php?action=do-core-upgrade';
	}

	$url = wp_nonce_url( $url, 'upgrade-core' );

	$version = isset( $_POST['version'] ) ? $_POST['version'] : false;
	$locale  = isset( $_POST['locale'] ) ? $_POST['locale'] : 'en_US';
	$update  = find_core_update( $version, $locale );

	if ( ! $update ) {
		return;
	}

	/**
	 * Allow relaxed file ownership writes for User-initiated upgrades when
	 * the API specifies that it's safe to do so.
	 *
	 * This only happens when there are no new files to create.
	 */
	$allow_relaxed_file_ownership = ! $reinstall && isset( $update->new_files ) && ! $update->new_files;

?>
	<div class="wrap">
	<?php echo sprintf(
		'<h1>%1s %2s</h1>',
		__( 'Update' ),
		APP_NAME
	); ?>
<?php

	if ( false === ( $credentials = request_filesystem_credentials( $url, '', false, ABSPATH, [ 'version', 'locale' ], $allow_relaxed_file_ownership ) ) ) {
		echo '</div>';
		return;
	}

	if ( ! WP_Filesystem( $credentials, ABSPATH, $allow_relaxed_file_ownership ) ) {

		// Failed to connect, Error and request again.
		request_filesystem_credentials( $url, '', true, ABSPATH, [ 'version', 'locale' ], $allow_relaxed_file_ownership );
		echo '</div>';
		return;
	}

	if ( $wp_filesystem->errors->get_error_code() ) {
		foreach ( $wp_filesystem->errors->get_error_messages() as $message )
			show_message( $message );
		echo '</div>';
		return;
	}

	if ( $reinstall ) {
		$update->response = 'reinstall';
	}

	add_filter( 'update_feedback', 'show_message' );

	$upgrader = new Core_Upgrader();
	$result   = $upgrader->upgrade( $update, [
		'allow_relaxed_file_ownership' => $allow_relaxed_file_ownership
	] );

	if ( is_wp_error( $result ) ) {

		show_message( $result );

		if ( 'up_to_date' != $result->get_error_code() && 'locked' != $result->get_error_code() ) {
			show_message( __( 'Installation Failed' ) );
		}

		echo '</div>';

		return;
	}

	show_message( APP_NAME . __( ' updated successfully' ) );

	show_message( '<span class="hide-if-no-js">' . sprintf( __( 'You will be redirected to the About page. If not, click <a href="%1$s">here</a>.' ), esc_url( self_admin_url( 'about.php?updated' ) ) ) . '</span>' );

	show_message( '<span class="hide-if-js">' . sprintf( __( 'Visit the <a href="%2$s">About page</a>.' ), esc_url( self_admin_url( 'about.php?updated' ) ) ) . '</span>' );

	?>
	</div>
	<script type="text/javascript">
		window.location = '<?php echo self_admin_url( 'about.php?updated' ); ?>';
	</script>
	<?php
}

/**
 * Dismiss core updates
 *
 * @since Before 2.7.0
 */
function do_dismiss_core_update() {

	$version = isset( $_POST['version'] ) ? $_POST['version'] : false;
	$locale  = isset( $_POST['locale'] ) ? $_POST['locale'] : 'en_US';
	$update  = find_core_update( $version, $locale );

	if ( ! $update ) {
		return;
	}

	dismiss_core_update( $update );

	wp_redirect( wp_nonce_url( 'update-core.php?action=upgrade-core', 'upgrade-core' ) );

	exit;
}

/**
 * Undismiss core updates
 *
 * @since Before 2.7.0
 */
function do_undismiss_core_update() {

	$version = isset( $_POST['version'] ) ? $_POST['version'] : false;
	$locale  = isset( $_POST['locale'] ) ? $_POST['locale'] : 'en_US';
	$update  = find_core_update( $version, $locale );

	if ( ! $update ) {
		return;
	}

	undismiss_core_update( $version, $locale );

	wp_redirect( wp_nonce_url( 'update-core.php?action=upgrade-core', 'upgrade-core' ) );

	exit;
}

$action        = isset( $_GET['action'] ) ? $_GET['action'] : 'upgrade-core';
$upgrade_error = false;

if ( ( 'do-theme-upgrade' == $action || ( 'do-plugin-upgrade' == $action && ! isset( $_GET['plugins'] ) ) )
	&& ! isset( $_POST['checked'] ) ) {

	$upgrade_error = $action == 'do-theme-upgrade' ? 'themes' : 'plugins';
	$action = 'upgrade-core';
}

$title       = __( 'Manage Updates' );
$parent_file = 'index.php';

$updates_overview  = '<p>' . __( 'On this screen, you can update to the latest version of of the website management system, as well as update your themes, plugins, and translations from the source repositories.' ) . '</p>';

$updates_overview .= '<p>' . __( 'If an update is available, you&#8127;ll see a notification appear in the Toolbar and navigation menu.' ) . ' ' . __( 'Keeping your site updated is important for security. It also makes the internet a safer place for you and your readers.' ) . '</p>';

/*
get_current_screen()->add_help_tab( [
	'id'      => 'overview',
	'title'   => __( 'Overview' ),
	'content' => $updates_overview
] );
*/

$updates_howto  = '<p>' . __( 'Updating your installation is a simple one-click procedure: just <strong>click on the &#8220;Update Now&#8221; button</strong> when you are notified that a new version is available.' ) . ' ' . __( 'In most cases, the website management system will automatically apply maintenance and security updates in the background for you.' ) . '</p>';

$updates_howto .= '<p>' . __( '<strong>Themes and Plugins</strong> &mdash; To update individual themes or plugins from this screen, use the checkboxes to make your selection, then <strong>click on the appropriate &#8220;Update&#8221; button</strong>. To update all of your themes or plugins at once, you can check the box at the top of the section to select all before clicking the update button.' ) . '</p>';

if ( 'en_US' != get_locale() ) {
	$updates_howto .= '<p>' . __( '<strong>Translations</strong> &mdash; The files translating the website management system into your language are updated for you whenever any other updates occur. But if these files are out of date, you can <strong>click the &#8220;Update Translations&#8221;</strong> button.' ) . '</p>';
}

/*
get_current_screen()->add_help_tab( [
	'id'      => 'how-to-update',
	'title'   => __( 'How to Update' ),
	'content' => $updates_howto
] );
*/

// get_current_screen()->set_help_sidebar( '' );

if ( 'upgrade-core' == $action ) {

	// Force a update check when requested.
	$force_check = ! empty( $_GET['force-check'] );
	wp_version_check( [], $force_check );

	require_once( ABSPATH . 'wp-admin/admin-header.php' );

	?>
	<div class="wrap">

		<h1><?php _e( 'Manage Updates' ); ?></h1>

		<p style="max-width: 768px"><?php _e( 'This page currently does nothing. It shall remain in place as development progresses on the website management system. The current idea is to keep the core updates functionality intact so that it may be modified by those who use the system to create their own with their own update infrastructure.' ); ?></p>

		<?php
		if ( $upgrade_error ) {
			echo '<div class="error"><p>';
			if ( $upgrade_error == 'themes' )
				_e('Please select one or more themes to update.');
			else
				_e('Please select one or more plugins to update.');
			echo '</p></div>';
		}

		$last_update_check = false;
		$current = get_site_transient( 'update_core' );

		if ( $current && isset ( $current->last_checked ) )	{
			$last_update_check = $current->last_checked + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
		}

		// echo '<p>';
		// Translators: %1 date, %2 time.
		// printf( __( 'Last checked on %1$s at %2$s.' ), date_i18n( __( 'F j, Y' ), $last_update_check ), date_i18n( __( 'g:i a' ), $last_update_check ) );
		// echo ' &nbsp; <a class="button" href="' . esc_url( self_admin_url( 'update-core.php?force-check=1' ) ) . '">' . __( 'Check Again' ) . '</a>';
		// echo '</p>';

	if ( current_user_can( 'update_core' ) ) {
		// Uncomment to print the update markup.
		// core_upgrade_preamble();
	}

	if ( current_user_can( 'update_plugins' ) ) {
		// list_plugin_updates();
	}

	if ( current_user_can( 'update_themes' ) ) {
		// list_theme_updates();
	}

	if ( current_user_can( 'update_languages' ) ) {
		// list_translation_updates();
	}

	/**
	 * Fires after the core, plugin, and theme update tables.
	 *
	 * @since Before 2.9.0
	 */
	do_action( 'core_upgrade_preamble' );
	echo '</div>';

	wp_localize_script( 'updates', '_wpUpdatesItemCounts', array(
		'totals'  => wp_get_update_data(),
	) );

	include( ABSPATH . 'wp-admin/admin-footer.php' );

} elseif ( 'do-core-upgrade' == $action || 'do-core-reinstall' == $action ) {

	if ( ! current_user_can( 'update_core' ) ) {
		wp_die( __( 'Sorry, you are not allowed to update this site.' ) );
	}

	check_admin_referer( 'upgrade-core' );

	// Do the (un)dismiss actions before headers, so that they can redirect.
	if ( isset( $_POST['dismiss'] ) ) {
		do_dismiss_core_update();
	} elseif ( isset( $_POST['undismiss'] ) ) {
		do_undismiss_core_update();
	}

	require_once( ABSPATH . 'wp-admin/admin-header.php' );

	if ( 'do-core-reinstall' == $action ) {
		$reinstall = true;
	} else {
		$reinstall = false;
	}

	if ( isset( $_POST['upgrade'] ) ) {
		do_core_upgrade( $reinstall );
	}

	wp_localize_script( 'updates', '_wpUpdatesItemCounts', [
		'totals'  => wp_get_update_data(),
	] );

	include( ABSPATH . 'wp-admin/admin-footer.php' );

} elseif ( 'do-plugin-upgrade' == $action ) {

	if ( ! current_user_can( 'update_plugins' ) ) {
		wp_die( __( 'Sorry, you are not allowed to update this site.' ) );
	}

	check_admin_referer( 'upgrade-core' );

	if ( isset( $_GET['plugins'] ) ) {
		$plugins = explode( ',', $_GET['plugins'] );
	} elseif ( isset( $_POST['checked'] ) ) {
		$plugins = (array) $_POST['checked'];
	} else {
		wp_redirect( admin_url( 'update-core.php' ) );

		exit;
	}

	$url   = 'update.php?action=update-selected&plugins=' . urlencode( implode( ',', $plugins ) );
	$url   = wp_nonce_url( $url, 'bulk-update-plugins' );
	$title = __( 'Update Plugins' );

	require_once( ABSPATH . 'wp-admin/admin-header.php' );

	echo '<div class="wrap">';
	echo '<h1>' . __( 'Update Plugins' ) . '</h1>';
	echo '<iframe src="', $url, '" style="width: 100%; height: 100%; min-height: 750px;" frameborder="0" title="' . esc_attr__( 'Update progress' ) . '"></iframe>';
	echo '</div>';

	wp_localize_script( 'updates', '_wpUpdatesItemCounts', [
		'totals'  => wp_get_update_data(),
	] );

	include( ABSPATH . 'wp-admin/admin-footer.php' );

} elseif ( 'do-theme-upgrade' == $action ) {

	if ( ! current_user_can( 'update_themes' ) ) {
		wp_die( __( 'Sorry, you are not allowed to update this site.' ) );
	}

	check_admin_referer( 'upgrade-core' );

	if ( isset( $_GET['themes'] ) ) {
		$themes = explode( ',', $_GET['themes'] );
	} elseif ( isset( $_POST['checked'] ) ) {
		$themes = (array) $_POST['checked'];
	} else {
		wp_redirect( admin_url( 'update-core.php' ) );

		exit;
	}

	$url   = 'update.php?action=update-selected-themes&themes=' . urlencode( implode( ',', $themes ) );
	$url   = wp_nonce_url( $url, 'bulk-update-themes' );
	$title = __( 'Update Themes' );

	require_once( ABSPATH . 'wp-admin/admin-header.php' );

	?>
	<div class="wrap">
		<h1><?php _e( 'Update Themes' ); ?></h1>
		<iframe src="<?php echo $url ?>" style="width: 100%; height: 100%; min-height: 750px;" frameborder="0" title="<?php esc_attr_e( 'Update progress' ); ?>"></iframe>
	</div>
	<?php

	wp_localize_script( 'updates', '_wpUpdatesItemCounts', [
		'totals'  => wp_get_update_data(),
	] );

	include( ABSPATH . 'wp-admin/admin-footer.php' );

} elseif ( 'do-translation-upgrade' == $action ) {

	if ( ! current_user_can( 'update_languages' ) ) {
		wp_die( __( 'Sorry, you are not allowed to update this site.' ) );
	}

	check_admin_referer( 'upgrade-translations' );

	require_once( ABSPATH . 'wp-admin/admin-header.php' );
	include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

	$url     = 'update-core.php?action=do-translation-upgrade';
	$nonce   = 'upgrade-translations';
	$title   = __( 'Update Translations' );
	$context = WP_LANG_DIR;

	$upgrader = new Language_Pack_Upgrader( new Language_Pack_Upgrader_Skin( compact( 'url', 'nonce', 'title', 'context' ) ) );
	$result   = $upgrader->bulk_upgrade();

	wp_localize_script( 'updates', '_wpUpdatesItemCounts', [
		'totals'  => wp_get_update_data(),
	] );

	require_once( ABSPATH . 'wp-admin/admin-footer.php' );

} else {
	/**
	 * Fires for each custom update action on the Manage Updates screen.
	 *
	 * The dynamic portion of the hook name, `$action`, refers to the
	 * passed update action. The hook fires in lieu of all available
	 * default update actions.
	 *
	 * @since Before 3.2.0
	 */
	do_action( "update-core-custom_{$action}" );
}