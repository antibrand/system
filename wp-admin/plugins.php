<?php
/**
 * Plugins administration panel.
 *
 * @package App_Package
 * @subpackage Administration
 */

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! current_user_can( 'activate_plugins' ) ) {
	wp_die( __( 'Sorry, you are not allowed to manage plugins for this site.' ) );
}

$wp_list_table = _get_list_table( 'AppNamespace\Backend\Plugins_List_Table' );
$pagenum       = $wp_list_table->get_pagenum();

$action = $wp_list_table->current_action();

$plugin = isset( $_REQUEST['plugin'] ) ? wp_unslash( $_REQUEST['plugin'] ) : '';
$s      = isset( $_REQUEST['s'] ) ? urlencode( wp_unslash( $_REQUEST['s'] ) ) : '';

// Clean up request URI from temporary args for screen options/paging uri's to work as expected.
$_SERVER['REQUEST_URI'] = remove_query_arg(
	[
		'error',
		'deleted',
		'activate',
		'activate-multi',
		'deactivate',
		'deactivate-multi',
		'_error_nonce'
	],
	$_SERVER['REQUEST_URI']
);

wp_enqueue_script( 'updates' );
// wp_enqueue_script( 'plugin-install' );

if ( $action ) {

	switch ( $action ) {

		case 'activate':
			if ( ! current_user_can( 'activate_plugin', $plugin ) ) {
				wp_die( __( 'Sorry, you are not allowed to activate this plugin.' ) );
			}

			if ( is_network() && ! is_network_admin() && is_network_only_plugin( $plugin ) ) {
				wp_redirect( self_admin_url( "plugins.php?plugin_status=$status&paged=$page&s=$s" ) );

				exit;
			}

			check_admin_referer( 'activate-plugin_' . $plugin );

			$result = activate_plugin( $plugin, self_admin_url( 'plugins.php?error=true&plugin=' . urlencode( $plugin ) ), is_network_admin() );

			if ( is_wp_error( $result ) ) {

				if ( 'unexpected_output' == $result->get_error_code() ) {

					$redirect = self_admin_url( 'plugins.php?error=true&charsout=' . strlen( $result->get_error_data() ) . '&plugin=' . urlencode( $plugin ) . "&plugin_status=$status&paged=$page&s=$s");
					wp_redirect( add_query_arg( '_error_nonce', wp_create_nonce( 'plugin-activation-error_' . $plugin ), $redirect ) );

					exit;

				} else {
					wp_die( $result );
				}
			}

			if ( ! is_network_admin() ) {

				$recent = (array) get_option( 'recently_activated' );
				unset( $recent[ $plugin ] );
				update_option( 'recently_activated', $recent );
			} else {

				$recent = (array) get_site_option( 'recently_activated' );
				unset( $recent[ $plugin ] );
				update_site_option( 'recently_activated', $recent );
			}

			if ( isset( $_GET['from'] ) && 'import' == $_GET['from'] ) {

				// Overrides the ?error=true one above and redirects to the Imports page, stripping the -importer suffix.
				wp_redirect( self_admin_url( "import.php?import=" . str_replace( '-importer', '', dirname( $plugin ) ) ) );

			} elseif ( isset( $_GET['from'] ) && 'press-this' == $_GET['from'] ) {
				wp_redirect( self_admin_url( 'press-this.php' ) );
			} else {

				// Overrides the ?error=true one above.
				wp_redirect( self_admin_url( "plugins.php?activate=true&plugin_status=$status&paged=$page&s=$s" ) );
			}
			exit;

		case 'activate-selected':

			if ( ! current_user_can( 'activate_plugins' ) ) {
				wp_die( __( 'Sorry, you are not allowed to activate plugins for this site.' ) );
			}

			check_admin_referer('bulk-plugins');

			$plugins = isset( $_POST['checked'] ) ? (array) wp_unslash( $_POST['checked'] ) : [];

			if ( is_network_admin() ) {

				foreach ( $plugins as $i => $plugin ) {

					// Only activate plugins which are not already network activated.
					if ( is_plugin_active_for_network( $plugin ) ) {
						unset( $plugins[ $i ] );
					}
				}
			} else {

				foreach ( $plugins as $i => $plugin ) {

					// Only activate plugins which are not already active and are not network-only when on network.
					if ( is_plugin_active( $plugin ) || ( is_network() && is_network_only_plugin( $plugin ) ) ) {
						unset( $plugins[ $i ] );
					}

					// Only activate plugins which the user can activate.
					if ( ! current_user_can( 'activate_plugin', $plugin ) ) {
						unset( $plugins[ $i ] );
					}
				}
			}

			if ( empty( $plugins ) ) {
				wp_redirect( self_admin_url( "plugins.php?plugin_status=$status&paged=$page&s=$s" ) );

				exit;
			}

			activate_plugins( $plugins, self_admin_url( 'plugins.php?error=true' ), is_network_admin() );

			if ( ! is_network_admin() ) {
				$recent = (array) get_option('recently_activated' );
			} else {
				$recent = (array) get_site_option('recently_activated' );
			}

			foreach ( $plugins as $plugin ) {
				unset( $recent[ $plugin ] );
			}

			if ( ! is_network_admin() ) {
				update_option( 'recently_activated', $recent );
			} else {
				update_site_option( 'recently_activated', $recent );
			}

			wp_redirect( self_admin_url( "plugins.php?activate-multi=true&plugin_status=$status&paged=$page&s=$s" ) );

			exit;

		case 'update-selected' :

			check_admin_referer( 'bulk-plugins' );

			if ( isset( $_GET['plugins'] ) ) {
				$plugins = explode( ',', wp_unslash( $_GET['plugins'] ) );
			} elseif ( isset( $_POST['checked'] ) ) {
				$plugins = (array) wp_unslash( $_POST['checked'] );
			} else {
				$plugins = [];
			}

			$title       = __( 'Update Plugins' );
			$parent_file = 'plugins.php';

			wp_enqueue_script( 'updates' );
			require_once( ABSPATH . 'wp-admin/admin-header.php' );

			echo '<div class="wrap">';
			echo '<h1>' . esc_html( $title ) . '</h1>';

			$url = self_admin_url( 'update.php?action=update-selected&amp;plugins=' . urlencode( join( ',', $plugins ) ) );
			$url = wp_nonce_url( $url, 'bulk-update-plugins' );

			echo "<iframe src='$url' style='width: 100%; height:100%; min-height:850px;'></iframe>";
			echo '</div>';
			require_once( ABSPATH . 'wp-admin/admin-footer.php' );
			exit;

		case 'error_scrape':
			if ( ! current_user_can( 'activate_plugin', $plugin ) ) {
				wp_die( __( 'Sorry, you are not allowed to activate this plugin.' ) );
			}

			check_admin_referer( 'plugin-activation-error_' . $plugin );

			$valid = validate_plugin( $plugin );

			if ( is_wp_error( $valid ) ) {
				wp_die( $valid );
			}

			if ( ! APP_DEBUG ) {
				error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
			}

			//Ensure that Fatal errors are displayed.
			@ini_set('display_errors', true);

			// Go back to "sandbox" scope so we get the same errors as before.
			plugin_sandbox_scrape( $plugin );

			// This action is documented in wp-admin/includes/plugin.php.
			do_action( "activate_{$plugin}" );
			exit;

		case 'deactivate':

			if ( ! current_user_can( 'deactivate_plugin', $plugin ) ) {
				wp_die( __( 'Sorry, you are not allowed to deactivate this plugin.' ) );
			}

			check_admin_referer('deactivate-plugin_' . $plugin);

			if ( ! is_network_admin() && is_plugin_active_for_network( $plugin ) ) {

				wp_redirect( self_admin_url( "plugins.php?plugin_status=$status&paged=$page&s=$s" ) );

				exit;
			}

			deactivate_plugins( $plugin, false, is_network_admin() );

			if ( ! is_network_admin() ) {
				update_option( 'recently_activated', [ $plugin => time() ] + (array) get_option( 'recently_activated' ) );
			} else {
				update_site_option( 'recently_activated', [ $plugin => time() ] + (array) get_site_option( 'recently_activated' ) );
			}

			if ( headers_sent() ) {
				echo "<meta http-equiv='refresh' content='" . esc_attr( "0;url=plugins.php?deactivate=true&plugin_status=$status&paged=$page&s=$s" ) . "' />";
			} else {
				wp_redirect( self_admin_url( "plugins.php?deactivate=true&plugin_status=$status&paged=$page&s=$s" ) );
			}

			exit;

		case 'deactivate-selected':

			if ( ! current_user_can( 'deactivate_plugins' ) ) {
				wp_die( __( 'Sorry, you are not allowed to deactivate plugins for this site.' ) );
			}

			check_admin_referer( 'bulk-plugins' );

			$plugins = isset( $_POST['checked'] ) ? (array) wp_unslash( $_POST['checked'] ) : [];

			// Do not deactivate plugins which are already deactivated.
			if ( is_network_admin() ) {
				$plugins = array_filter( $plugins, 'is_plugin_active_for_network' );

			} else {
				$plugins = array_filter( $plugins, 'is_plugin_active' );
				$plugins = array_diff( $plugins, array_filter( $plugins, 'is_plugin_active_for_network' ) );

				foreach ( $plugins as $i => $plugin ) {

					// Only deactivate plugins which the user can deactivate.
					if ( ! current_user_can( 'deactivate_plugin', $plugin ) ) {
						unset( $plugins[ $i ] );
					}
				}

			}
			if ( empty( $plugins ) ) {
				wp_redirect( self_admin_url( "plugins.php?plugin_status=$status&paged=$page&s=$s" ) );
				exit;
			}

			deactivate_plugins( $plugins, false, is_network_admin() );

			$deactivated = [];

			foreach ( $plugins as $plugin ) {
				$deactivated[ $plugin ] = time();
			}

			if ( ! is_network_admin() ) {
				update_option( 'recently_activated', $deactivated + (array) get_option( 'recently_activated' ) );
			} else {
				update_site_option( 'recently_activated', $deactivated + (array) get_site_option( 'recently_activated' ) );
			}

			wp_redirect( self_admin_url( "plugins.php?deactivate-multi=true&plugin_status=$status&paged=$page&s=$s" ) );

			exit;

		case 'delete-selected':

			if ( ! current_user_can( 'delete_plugins' ) ) {
				wp_die( __( 'Sorry, you are not allowed to delete plugins for this site.' ) );
			}

			check_admin_referer( 'bulk-plugins' );

			//$_POST = from the plugin form; $_GET = from the FTP details screen.
			$plugins = isset( $_REQUEST['checked'] ) ? (array) wp_unslash( $_REQUEST['checked'] ) : [];

			if ( empty( $plugins ) ) {
				wp_redirect( self_admin_url( "plugins.php?plugin_status=$status&paged=$page&s=$s" ) );

				exit;
			}

			// Do not allow to delete Activated plugins.
			$plugins = array_filter( $plugins, 'is_plugin_inactive' );

			if ( empty( $plugins ) ) {
				wp_redirect( self_admin_url( "plugins.php?error=true&main=true&plugin_status=$status&paged=$page&s=$s" ) );

				exit;
			}

			// Bail on all if any paths are invalid.
			// validate_file() returns truthy for invalid files
			$invalid_plugin_files = array_filter( $plugins, 'validate_file' );

			if ( $invalid_plugin_files ) {
				wp_redirect( self_admin_url( "plugins.php?plugin_status=$status&paged=$page&s=$s" ) );

				exit;
			}

			include( ABSPATH . 'wp-admin/update.php' );

			$parent_file = 'plugins.php';

			if ( ! isset( $_REQUEST['verify-delete'] ) ) {

				wp_enqueue_script( 'jquery' );
				require_once( ABSPATH . 'wp-admin/admin-header.php' );

				?>
			<div class="wrap">
				<?php
					$plugin_info = [];
					$have_non_network_plugins = false;

					foreach ( (array) $plugins as $plugin ) {
						$plugin_slug = dirname( $plugin );

						if ( '.' == $plugin_slug ) {

							if ( $data = get_plugin_data( APP_PLUGIN_DIR . '/' . $plugin ) ) {

								$plugin_info[ $plugin ] = $data;
								$plugin_info[ $plugin ]['is_uninstallable'] = is_uninstallable_plugin( $plugin );

								if ( ! $plugin_info[ $plugin ]['Network'] ) {
									$have_non_network_plugins = true;
								}
							}
						} else {

							// Get plugins list from that folder.
							if ( $folder_plugins = get_plugins( '/' . $plugin_slug ) ) {

								foreach ( $folder_plugins as $plugin_file => $data ) {

									$plugin_info[ $plugin_file ] = _get_plugin_data_markup_translate( $plugin_file, $data );
									$plugin_info[ $plugin_file ]['is_uninstallable'] = is_uninstallable_plugin( $plugin );

									if ( ! $plugin_info[ $plugin_file ]['Network'] ) {
										$have_non_network_plugins = true;
									}
								}
							}
						}
					}
					$plugins_to_delete = count( $plugin_info );
				?>
				<?php if ( 1 == $plugins_to_delete ) : ?>

					<h1><?php _e( 'Delete Plugin' ); ?></h1>

					<?php if ( $have_non_network_plugins && is_network_admin() ) : ?>
						<div class="error"><p><strong><?php _e( 'Caution:' ); ?></strong> <?php _e( 'This plugin may be active on other sites in the network.' ); ?></p></div>
					<?php endif; ?>

					<p><?php _e( 'You are about to remove the following plugin:' ); ?></p>
				<?php else: ?>

					<h1><?php _e( 'Delete Plugins' ); ?></h1>

					<?php if ( $have_non_network_plugins && is_network_admin() ) : ?>
						<div class="error"><p><strong><?php _e( 'Caution:' ); ?></strong> <?php _e( 'These plugins may be active on other sites in the network.' ); ?></p></div>
					<?php endif; ?>

					<p><?php _e( 'You are about to remove the following plugins:' ); ?></p>

				<?php endif; ?>
					<ul class="ul-disc">
						<?php
						$data_to_delete = false;

						foreach ( $plugin_info as $plugin ) {

							if ( $plugin['is_uninstallable'] ) {

								// Translators: 1: plugin name, 2: plugin author.
								echo '<li>', sprintf( __( '%1$s by %2$s (will also <strong>delete its data</strong>)' ), '<strong>' . $plugin['Name'] . '</strong>', '<em>' . $plugin['AuthorName'] . '</em>' ), '</li>';
								$data_to_delete = true;

							} else {

								// Translators: 1: plugin name, 2: plugin author.
								echo '<li>', sprintf( _x( '%1$s by %2$s', 'plugin' ), '<strong>' . $plugin['Name'] . '</strong>', '<em>' . $plugin['AuthorName'] ) . '</em>', '</li>';
							}
						}
						?>
					</ul>
				<p><?php
				if ( $data_to_delete ) {
					_e( 'Are you sure you wish to delete these files and data?' );
				} else {
					_e( 'Are you sure you wish to delete these files?' );
				}
				?></p>

				<form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" style="display:inline;">

					<input type="hidden" name="verify-delete" value="1" />
					<input type="hidden" name="action" value="delete-selected" />
					<?php
						foreach ( (array) $plugins as $plugin ) {
							echo '<input type="hidden" name="checked[]" value="' . esc_attr( $plugin ) . '" />';
						}
					?>
					<?php wp_nonce_field( 'bulk-plugins' ) ?>
					<?php submit_button( $data_to_delete ? __( 'Yes, delete these files and data' ) : __( 'Yes, delete these files' ), '', 'submit', false ); ?>

				</form>
				<?php
				$referer = wp_get_referer();
				?>
				<form method="post" action="<?php echo $referer ? esc_url( $referer ) : ''; ?>" style="display:inline;">
					<?php submit_button( __( 'No, return me to the plugin list' ), '', 'submit', false ); ?>
				</form>
			</div>
				<?php
				require_once( ABSPATH . 'wp-admin/admin-footer.php' );

				exit;

			} else {
				$plugins_to_delete = count( $plugins );
			} // End if verify-delete.

			$delete_result = delete_plugins( $plugins );

			// Store the result in a cache rather than a URL param due to object type & length.
			set_transient( 'plugins_delete_result_' . $user_ID, $delete_result );
			wp_redirect( self_admin_url( "plugins.php?deleted=$plugins_to_delete&plugin_status=$status&paged=$page&s=$s" ) );

			exit;

		case 'clear-recent-list':

			if ( ! is_network_admin() ) {
				update_option( 'recently_activated', [] );
			} else {
				update_site_option( 'recently_activated', [] );
			}

			break;

		default:

			if ( isset( $_POST['checked'] ) ) {

				check_admin_referer('bulk-plugins');

				$plugins = isset( $_POST['checked'] ) ? (array) wp_unslash( $_POST['checked'] ) : [];
				$sendback = wp_get_referer();

				// This action is documented in wp-admin/edit-comments.php.
				$sendback = apply_filters( 'handle_bulk_actions-' . get_current_screen()->id, $sendback, $action, $plugins );
				wp_safe_redirect( $sendback );

				exit;
			}

			break;
	}

}

$wp_list_table->prepare_items();

wp_enqueue_script( 'plugin-install' );
add_thickbox();

add_screen_option( 'per_page', [ 'default' => 999 ] );

$help_overview = sprintf(
	'<h3>%1s</h3>',
	__( 'Overview' )
);

$help_overview .= sprintf(
	'<p>%1s</p>',
	__( 'Plugins extend and expand functionality. Once a plugin is installed, you may activate it or deactivate it here.' )
);

$help_overview .= sprintf(
	'<p>%1s <span id="live-search-desc" class="hide-if-no-js">%2s</span></p>',
	__( 'The search for installed plugins will search for terms in their name, description, or author.' ),
	__( 'The search results will be updated as you type.' )
);

$help_overview = apply_filters( 'help_plugins_overview', $help_overview );

get_current_screen()->add_help_tab( [
	'id'		=> 'overview',
	'title'		=> __( 'Overview' ),
	'content'	=> $help_overview
] );

$help_troubleshooting = sprintf(
	'<h3>%1s</h3>',
	__( 'Troubleshooting' )
);

$help_troubleshooting .= sprintf(
	'<p>%1s</p>',
	__( 'Most of the time, plugins play nicely with the core of the application and with other plugins. Sometimes, though, a plugin&#8217;s code will get in the way of another plugin, causing compatibility issues. If your site starts doing strange things, this may be the problem. Try deactivating all your plugins and re-activating them in various combinations until you isolate which one(s) caused the issue.' )
);

$help_troubleshooting .= sprintf(
	'<p>%1s <code>%2s</code> %3s</p>',
	__( 'If something goes wrong with a plugin and you can&#8217;t use the application, delete or rename that file in the' ),
	APP_PLUGIN_DIR,
	__( 'directory and it will be automatically deactivated.' )
);

$help_troubleshooting = apply_filters( 'help_plugins_troubleshooting', $help_troubleshooting );

get_current_screen()->add_help_tab( [
	'id'		=> 'compatibility-problems',
	'title'		=> __( 'Troubleshooting' ),
	'content'	=> $help_troubleshooting
] );

/**
 * Help sidebar content
 *
 * This system adds no content to the help sidebar
 * but there is a filter applied for adding content.
 *
 * @since 1.0.0
 */
$set_help_sidebar = apply_filters( 'set_help_sidebar_plugins', '' );
get_current_screen()->set_help_sidebar( $set_help_sidebar );

get_current_screen()->set_screen_reader_content( [
	'heading_views'      => __( 'Filter plugins list' ),
	'heading_pagination' => __( 'Plugins list navigation' ),
	'heading_list'       => __( 'Plugins list' ),
] );

$title       = __( 'Manage Plugins' );
$parent_file = 'plugins.php';

require_once( ABSPATH . 'wp-admin/admin-header.php' );

$invalid = validate_active_plugins();

if ( ! empty( $invalid ) ) {

	foreach ( $invalid as $plugin_file => $error ) {
		echo '<div id="message" class="error"><p>';
		printf(
			/* translators: 1: plugin file 2: error message */
			__( 'The plugin %1$s has been <strong>deactivated</strong> due to an error: %2$s' ),
			'<code>' . esc_html( $plugin_file ) . '</code>',
			$error->get_error_message() );
		echo '</p></div>';
	}
}
?>

<?php if ( isset( $_GET['error'] ) ) :

	if ( isset( $_GET['main'] ) ) {
		$errmsg = __( 'You cannot delete a plugin while it is active on the main site.' );
	} elseif ( isset( $_GET['charsout'] ) ) {
		$errmsg = sprintf( __( 'The plugin generated %d characters of <strong>unexpected output</strong> during activation. If you notice &#8220;headers already sent&#8221; messages, problems with syndication feeds or other issues, try deactivating or removing this plugin.' ), $_GET['charsout'] );
	} else {
		$errmsg = __( 'Plugin could not be activated because it triggered a <strong>fatal error</strong>.' );
	}
	?>
	<div id="message" class="error"><p><?php echo $errmsg; ?></p>
	<?php
		if ( ! isset( $_GET['main'] ) && ! isset( $_GET['charsout'] ) && wp_verify_nonce( $_GET['_error_nonce'], 'plugin-activation-error_' . $plugin ) ) {
			$iframe_url = add_query_arg(
				[
					'action'   => 'error_scrape',
					'plugin'   => urlencode( $plugin ),
					'_wpnonce' => urlencode( $_GET['_error_nonce'] ),
				],
				admin_url( 'plugins.php' ) );
		?>
		<iframe style="border:0" width="100%" height="70px" src="<?php echo esc_url( $iframe_url ); ?>"></iframe>
	<?php
		}
	?>
	</div>
<?php elseif ( isset( $_GET['deleted'] ) ) :

		$delete_result = get_transient( 'plugins_delete_result_' . $user_ID );

		// Delete it once we're done.
		delete_transient( 'plugins_delete_result_' . $user_ID );

		if ( is_wp_error($delete_result) ) : ?>
		<div id="message" class="error notice is-dismissible"><p><?php printf( __( 'Plugin could not be deleted due to an error: %s' ), $delete_result->get_error_message() ); ?></p></div>
		<?php else : ?>
		<div id="message" class="updated notice is-dismissible">
			<p>
				<?php
				if ( 1 == (int) $_GET['deleted'] ) {
					_e( 'The selected plugin has been <strong>deleted</strong>.' );
				} else {
					_e( 'The selected plugins have been <strong>deleted</strong>.' );
				}
				?>
			</p>
		</div>
		<?php endif; ?>
<?php elseif ( isset( $_GET['activate'] ) ) : ?>
	<div id="message" class="updated notice is-dismissible"><p><?php _e( 'Plugin <strong>activated</strong>.' ) ?></p></div>
<?php elseif ( isset( $_GET['activate-multi'] ) ) : ?>
	<div id="message" class="updated notice is-dismissible"><p><?php _e( 'Selected plugins <strong>activated</strong>.' ); ?></p></div>
<?php elseif ( isset( $_GET['deactivate'] ) ) : ?>
	<div id="message" class="updated notice is-dismissible"><p><?php _e( 'Plugin <strong>deactivated</strong>.' ) ?></p></div>
<?php elseif ( isset( $_GET['deactivate-multi'] ) ) : ?>
	<div id="message" class="updated notice is-dismissible"><p><?php _e( 'Selected plugins <strong>deactivated</strong>.' ); ?></p></div>
<?php elseif ( 'update-selected' == $action ) : ?>
	<div id="message" class="updated notice is-dismissible"><p><?php _e( 'All selected plugins are up to date.' ); ?></p></div>
<?php endif;

?>
	<div class="wrap">
		<script>
		// Toggle the plugin upload interface.
		jQuery(document).ready( function($) {
			$( '#upload-plugin-toggle' ).click( function() {
				$(this).text( $(this).text() == "<?php _e( 'Upload Plugin' ); ?>" ? "<?php _e( 'Close Upload' ); ?>" : "<?php _e( 'Upload Plugin' ); ?>" );
				$( '#upload-plugin' ).toggleClass( 'upload-plugin-open' );
			});

		});
		</script>
		<h1><?php echo esc_html( $title ); ?></h1>

		<p class="description"><?php _e( 'Plugins extend the functionality of the website management system.' ); ?></p>

		<div class="upload-plugin-wrap">

			<div id="upload-plugin" class="upload-plugin">

				<p class="install-help"><?php _e( 'If you have a plugin in a .zip format, you may install it by uploading it here.' ); ?></p>

				<form method="post" enctype="multipart/form-data" class="wp-upload-form" action="<?php echo self_admin_url( 'update.php?action=upload-plugin' ); ?>">

					<?php wp_nonce_field( 'plugin-upload' ); ?>
					<label class="screen-reader-text" for="pluginzip"><?php _e( 'Plugin zip file' ); ?></label>
					<input type="file" id="pluginzip" name="pluginzip" />
					<?php submit_button( __( 'Install Now' ), '', 'install-plugin-submit', false ); ?>

				</form>
			</div>
		</div>

		<?php
		if ( strlen( $s ) ) {

			// Translators: %s: search keywords
			printf( '<p class="subtitle">' . __( 'Search results for &#8220;%s&#8221;' ) . '</p>', esc_html( urldecode( $s ) ) );
		}

		/**
		 * Fires before the plugins list table is rendered.
		 *
		 * This hook also fires before the plugins list table is rendered in the Network Admin.
		 *
		 * Please note: The 'active' portion of the hook name does not refer to whether the current
		 * view is for active plugins, but rather all plugins actively-installed.
		 *
		 * @since Before 3.0.0
		 *
		 * @param array $plugins_all An array containing all installed plugins.
		 */
		do_action( 'pre_current_active_plugins', $plugins['all'] ); ?>

		<div class="list-table-top">

			<?php $wp_list_table->views(); ?>

			<form class="search-form search-plugins" method="get">
				<?php $wp_list_table->search_box( __( 'Search Installed Plugins' ), 'plugin' ); ?>
			</form>

		</div>

		<form id="bulk-action-form" class="list-table-form" method="post">

			<input type="hidden" name="plugin_status" value="<?php echo esc_attr( $status ) ?>" />
			<input type="hidden" name="paged" value="<?php echo esc_attr( $page ) ?>" />

			<?php $wp_list_table->display(); ?>

		</form>

		<span class="spinner"></span>

	</div><!-- .wrap -->

<?php
wp_print_request_filesystem_credentials_modal();
wp_print_admin_notice_templates();
wp_print_update_row_templates();

include( ABSPATH . 'wp-admin/admin-footer.php' );