<?php
/**
 * Themes administration panel.
 *
 * @package App_Package
 * @subpackage Administration
 */

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );
require( ABSPATH . 'wp-admin/includes/theme-install.php' );

if ( ! current_user_can( 'switch_themes' ) && ! current_user_can( 'edit_theme_options' ) ) {
	wp_die(
		'<h1>' . __( 'You need a higher level of permission.' ) . '</h1>' .
		'<p>' . __( 'Sorry, you are not allowed to edit theme options on this site.' ) . '</p>',
		403
	);
}

if ( current_user_can( 'switch_themes' ) && isset( $_GET['action'] ) ) {

	if ( 'activate' == $_GET['action'] ) {

		check_admin_referer( 'switch-theme_' . $_GET['stylesheet'] );
		$theme = wp_get_theme( $_GET['stylesheet'] );

		if ( ! $theme->exists() || ! $theme->is_allowed() ) {
			wp_die(
				'<h1>' . __( 'Something went wrong.' ) . '</h1>' .
				'<p>' . __( 'The requested theme does not exist.' ) . '</p>',
				403
			);
		}

		switch_theme( $theme->get_stylesheet() );
		wp_redirect( admin_url( 'themes.php?activated=true' ) );

		exit;

	} elseif ( 'delete' == $_GET['action'] ) {

		check_admin_referer( 'delete-theme_' . $_GET['stylesheet'] );
		$theme = wp_get_theme( $_GET['stylesheet'] );

		if ( ! current_user_can( 'delete_themes' ) ) {
			wp_die(
				'<h1>' . __( 'You need a higher level of permission.' ) . '</h1>' .
				'<p>' . __( 'Sorry, you are not allowed to delete this item.' ) . '</p>',
				403
			);
		}

		if ( ! $theme->exists() ) {
			wp_die(
				'<h1>' . __( 'Something went wrong.' ) . '</h1>' .
				'<p>' . __( 'The requested theme does not exist.' ) . '</p>',
				403
			);
		}

		$active = wp_get_theme();

		if ( $active->get( 'Template' ) == $_GET['stylesheet'] ) {
			wp_redirect( admin_url( 'themes.php?delete-active-child=true' ) );
		} else {
			delete_theme( $_GET['stylesheet'] );
			wp_redirect( admin_url( 'themes.php?deleted=true' ) );
		}

		exit;
	}
}

$title       = __( 'Manage Themes' );
$parent_file = 'themes.php';

// Help tab: Overview.
if ( current_user_can( 'switch_themes' ) ) {

	$help_overview = sprintf(
		'<h3>%1s</h3>',
		__( 'Overview' )
	);

	$help_overview  .= '<p>' . __( 'This screen is used for managing your installed themes. Aside from the default theme(s) included with your installation, themes are designed and developed by third parties.' ) . '</p>' .
		'<p>' . __( 'From this screen you can:' ) . '</p>' .
		'<ul><li>' . __( 'Hover or tap to see "Activate" and "Live Preview" buttons.' ) . '</li>' .
		'<li>' . __( 'Click on the theme to see the theme name, version, author, description, tags, and the delete button.' ) . '</li>' .
		'<li>' . __( 'Click "Customize" for the current theme or "Live Preview" for any other theme to see a live preview.' ) . '</li></ul>' .
		'<p>' . __( 'The current theme is displayed as the first theme in the list or grid.' ) . '</p>' .
		'<p>' . __( 'The search for installed themes will search for terms in their name, description, author, or tag.' ) . ' <span id="live-search-desc">' . __( 'The search results will be updated as you type.' ) . '</span></p>';

	get_current_screen()->add_help_tab( [
		'id'      => 'overview',
		'title'   => __( 'Overview' ),
		'content' => $help_overview
	] );
}

// Help tab: Adding Themes.
if ( current_user_can( 'install_themes' ) ) {

	if ( is_multisite() ) {

		$help_install = sprintf(
			'<h3>%1s</h3>',
			__( 'Adding Themes' )
		);

		$help_install .= '<p>' . __( 'Installing themes on a multisite network can only be done from the Network Admin section.' ) . '</p>';

		get_current_screen()->add_help_tab( [
			'id'      => 'adding-themes',
			'title'   => __( 'Adding Themes' ),
			'content' => $help_install
		] );

	} else {

		$help_install = sprintf(
			'<h3>%1s</h3>',
			__( 'Uploading Themes' )
		);

		$help_install .= '<p>' . __( 'Click or tap on the "Upload Theme" button to expose the upload form. Browse your device to find the location of a valid theme in a .zip folder, then choose "Install Now".' ) . '</p>';

		get_current_screen()->add_help_tab( [
			'id'      => 'adding-themes',
			'title'   => __( 'Uploading Themes' ),
			'content' => $help_install
		] );
	}

	/**
	 * Help sidebar if user can install themes
	 *
	 * Adds a notice about the available WordPress themes plugin.
	 * Also provides a link to the GitHub repository.description
	 *
	 * @todo Change the text when the plugin is ready to use.
	 * @todo Change the link when a page is available on the antibrand website.
	 * @todo Change this as needed for your fork of the system and/or
	 *       the themes plugin.
	 */
	$sidebar = sprintf(
		'<h4>%1s</h4>',
		__( 'Install from WordPress' )
	);

	$sidebar .= sprintf(
		'<p>%1s</p>',
		__( 'The antibrand project is working on a plugin that will restore the WordPress theme installation interface for those who wish to opt in for such service.' )
	);

	$sidebar .= sprintf(
		'<p><a href="%1s" target="_blank" rel="noindex, nofollow">%2s</a></p>',
		esc_url( 'https://github.com/antibrand/wp-themes' ),
		'(https://github.com/antibrand/wp-themes)'
	);
}

// Help tab: Previewing and Managing.
if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) {

	$help_customize = sprintf(
		'<h3>%1s</h3>',
		__( 'Previewing and Managing' )
	);

	$help_customize .=
		'<p>' . __( 'Tap or hover on any theme then click the "Live Preview button" to see a live preview of that theme and change theme options in a separate, full-screen view. You can also find a Live Preview button at the bottom of the theme details screen. Any installed theme can be previewed and customized in this way.' ) . '</p>'.
		'<p>' . __( 'The theme being previewed is fully interactive &mdash; navigate to different pages to see how the theme handles posts, archives, and other page templates. The settings may differ depending on what theme features the theme being previewed supports. To accept the new settings and activate the theme all in one step, click the "Publish &amp; Activate" button above the menu.' ) . '</p>' .
		'<p>' . __( 'When previewing on smaller monitors, you can use the collapse icon at the bottom of the left-hand pane. This will hide the pane, giving you more room to preview your site in the new theme. To bring the pane back, click on the collapse icon again.' ) . '</p>';

	get_current_screen()->add_help_tab( [
		'id'		=> 'customize-preview-themes',
		'title'		=> __( 'Previewing and Managing' ),
		'content'	=> $help_customize
	] );
}

/**
 * Help sidebar content
 *
 * This system adds no content to the help sidebar
 * but there is a filter applied for adding content.
 *
 * @since 1.0.0
 */
$set_help_sidebar = apply_filters( 'set_help_sidebar_themes', '' );
get_current_screen()->set_help_sidebar( $set_help_sidebar );

if ( current_user_can( 'switch_themes' ) ) {
	$themes = wp_prepare_themes_for_js();
} else {
	$themes = wp_prepare_themes_for_js( [ wp_get_theme() ] );
}
wp_reset_vars( [ 'theme', 'search' ] );

wp_localize_script( 'theme', '_wpThemeSettings', [
	'themes'   => $themes,
	'settings' => [
		'canInstall'    => ( ! is_multisite() && current_user_can( 'install_themes' ) ),
		'installURI'    => ( ! is_multisite() && current_user_can( 'install_themes' ) ) ? admin_url( 'theme-install.php' ) : null,
		'confirmDelete' => __( "Are you sure you want to delete this theme?\n\nClick 'Cancel' to go back, 'OK' to confirm the delete." ),
		'adminUrl'      => parse_url( admin_url(), PHP_URL_PATH ),
	],
 	'l10n' => [
 		'addNew'            => __( 'Add Theme from WordPress' ),
 		'search'            => __( 'Search Installed Themes' ),
 		'searchPlaceholder' => __( 'Search installed themes...' ),
		'themesFound'       => __( 'Number of Themes found: %d' ),
		'noThemesFound'     => __( 'No themes found. Try a different search.' ),
	 ],
] );

add_thickbox();
wp_enqueue_script( 'theme' );
wp_enqueue_script( 'updates' );

require_once( ABSPATH . 'wp-admin/admin-header.php' );

?>

	<div class="wrap">

		<script>
		// Toggle the theme upload interface.
		jQuery(document).ready( function($) {
			$( '#upload-theme-toggle' ).click( function() {
				$(this).text( $(this).text() == "<?php _e( 'Upload Theme' ); ?>" ? "<?php _e( 'Close Upload' ); ?>" : "<?php _e( 'Upload Theme' ); ?>" );
				$( '#upload-theme' ).toggleClass( 'upload-theme-open' );
			});

		});
		</script>

		<h1><?php echo esc_html( $title ); ?>
			<span class="theme-count screen-reader-text"><?php echo ! empty( $_GET['search'] ) ? __( '&hellip;' ) : count( $themes ); ?></span>
		</h1>
		<p class="description"><?php _e( 'Themes provide the public-facing content framework of the site, as well as the look & feel.' ); ?></p>

		<div class="add-theme-wrap">
			<?php if ( ! is_multisite() && current_user_can( 'install_themes' ) ) : ?>
				<button id="upload-theme-toggle" class="button page-title-action"><?php echo esc_html_x( 'Upload Theme', 'Upload new theme' ); ?></button>
			<?php endif; ?>

			<form class="search-form"></form>
		</div>

		<div class="upload-theme-wrap">
			<div id="upload-theme" class="upload-theme">
				<?php install_themes_upload(); ?>
			</div>
		</div>

	<?php if ( ! validate_current_theme() || isset( $_GET['broken'] ) ) : ?>
		<div id="message1" class="updated notice is-dismissible">
			<p><?php _e( 'The active theme is broken. Reverting to the default theme.' ); ?></p>
		</div>
	<?php elseif ( isset( $_GET['activated'] ) ) : ?>

		<?php if ( isset( $_GET['previewed'] ) ) { ?>
		<div id="message2" class="updated notice is-dismissible">
			<p><?php _e( 'Settings saved and theme activated.' ); ?> <a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Visit site' ); ?></a></p>
		</div>
		<?php } else { ?>
		<div id="message2" class="updated notice is-dismissible">
			<p><?php _e( 'New theme activated.' ); ?> <a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Visit site' ); ?></a></p>
		</div>
		<?php } ?>

	<?php elseif ( isset( $_GET['deleted'] ) ) : ?>
		<div id="message3" class="updated notice is-dismissible"><p><?php _e( 'Theme deleted.' ); ?></p></div>
	<?php elseif ( isset( $_GET['delete-active-child'] ) ) : ?>
		<div id="message4" class="error"><p><?php _e( 'You cannot delete a theme while it has an active child theme.' ); ?></p></div>
	<?php endif; ?>

	<?php $ct = wp_get_theme(); ?>

	<?php
	// Display error message if applicable.
	if ( $ct->errors() && ( ! is_multisite() || current_user_can( 'manage_network_themes' ) ) ) {
		echo '<div class="error"><p>' . __( 'ERROR:' ) . ' ' . $ct->errors()->get_error_message() . '</p></div>';
	}

		/**
		 * Current theme action links
		 *
		 * Checks for features that the current theme supports
		 * then adds links accordingly.
		 */
		$current_theme_action_links = '';

		// Only if the current user has customize permission.
		if ( current_user_can( 'customize' ) ) {

			// Live Manager link.
			$current_theme_action_links .= sprintf(
				'<a class="button hide-if-no-customize" href="%1s">%2s</a>',
				esc_url( admin_url( 'customize.php' ) ),
				__( 'Manage' )
			);

			// Logo link.
			if ( current_theme_supports( 'custom-logo' ) ) {
				$current_theme_action_links .= sprintf(
					'<a class="button hide-if-no-customize" href="%1s">%2s</a>',
					esc_url( admin_url( 'customize.php' ) . '?autofocus[control]=custom_logo' ),
					__( 'Identity' )
				);
			}

			// Menus link.
			if ( current_theme_supports( 'menus' ) ) {
				$current_theme_action_links .= sprintf(
					'<a class="button hide-if-no-customize" href="%1s">%2s</a>',
					esc_url( admin_url( 'customize.php' ) . '?autofocus[panel]=nav_menus' ),
					__( 'Menus' )
				);
			}

			// Widgets link.
			if ( current_theme_supports( 'widgets' ) ) {
				$current_theme_action_links .= sprintf(
					'<a class="button hide-if-no-customize" href="%1s">%2s</a>',
					esc_url( admin_url( 'customize.php' ) . '?autofocus[panel]=widgets' ),
					__( 'Widgets' )
				);
			}

			// Header image link.
			if ( current_theme_supports( 'custom-header' ) ) {
				$current_theme_action_links .= sprintf(
					'<a class="button hide-if-no-customize" href="%1s">%2s</a>',
					esc_url( admin_url( 'customize.php' ) . '?autofocus[control]=header_image' ),
					__( 'Header' )
				);
			}

			// Site background link.
			if ( current_theme_supports( 'custom-background' ) ) {
				$current_theme_action_links .= sprintf(
					'<a class="button hide-if-no-customize" href="%1s">%2s</a>',
					esc_url( admin_url( 'customize.php' ) . '?autofocus[control]=background_image' ),
					__( 'Background' )
				);
			}
		}

		// Apply a filter.
		$current_theme_action_links = apply_filters( 'current_theme_action_links', $current_theme_action_links );

	?>

	<?php
	$class_name = 'theme-browser';

	if ( ! empty( $_GET['search'] ) ) {
		$class_name .= ' search-loading';
	}
	?>
	<div class="<?php echo esc_attr( $class_name ); ?>">
		<div class="themes">
			<?php

			do_action( 'themes_list_after' );

			/*
			 * This PHP is synchronized with the tmpl-theme template below!
			 */

			foreach ( $themes as $theme ) :
				$aria_action = esc_attr( $theme['id'] . '-action' );
				$aria_name   = esc_attr( $theme['id'] . '-name' );
				?>
			<div class="theme<?php if ( $theme['active'] ) echo ' active'; ?>" tabindex="0" aria-describedby="<?php echo $aria_action . ' ' . $aria_name; ?>">
				<?php if ( ! empty( $theme['cover'][0] ) ) { ?>
					<figure class="theme-cover-image">
						<img src="<?php echo $theme['cover'][0]; ?>" alt="<?php _e( 'Theme cover image' ); ?>" width="640" height="480" />
						<figcaption class="screen-reader-text"><?php _e( 'Theme cover image' ); ?></figcaption>
					</figure>
				<?php } else { ?>
					<div class="theme-cover-image blank"></div>
				<?php } ?>

				<?php if ( $theme['hasUpdate'] ) : ?>
					<div class="update-message notice inline notice-warning notice-alt no-border">
					<?php if ( $theme['hasPackage'] ) : ?>
						<p><?php _e( 'New version available. <button class="button-link" type="button">Update now</button>' ); ?></p>
					<?php else : ?>
						<p><?php _e( 'New version available.' ); ?></p>
					<?php endif; ?>
					</div>
				<?php endif; ?>

				<span class="more-details" id="<?php echo $aria_action; ?>"><?php _e( 'Theme Details' ); ?></span>

				<div class="theme-author"><?php printf( __( 'By %s' ), $theme['author'] ); ?></div>

				<?php if ( $theme['name'] ) ?>

				<div class="theme-id-container">
					<?php if ( $theme['active'] ) { ?>
						<h2 class="theme-name" id="<?php echo $aria_name; ?>">
							<?php
							// Translators: %s: theme name.
							printf( __( '<span>Active:</span> %s' ), $theme['name'] );
							?>
						</h2>
					<?php } else { ?>
						<h2 class="theme-name" id="<?php echo $aria_name; ?>"><?php echo $theme['name']; ?></h2>
					<?php } ?>

					<div class="theme-actions">

					<?php if ( $theme['active'] ) { ?>

						<h2><?php _e( 'Manage This Theme' ); ?></h2>

						<div class="theme-action-buttons">
							<?php if ( $theme['actions']['customize'] && current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) { ?>
							<p><button type="button" class="button button-primary customize load-customize hide-if-no-customize" href="<?php echo $theme['actions']['customize']; ?>"><?php _e( 'Manage' ); ?></button></p>
							<?php } ?>
						</div>

					<?php } else { ?>

						<h2><?php _e( 'Manage This Theme' ); ?></h2>

						<div class="theme-action-buttons">
								<?php
								// Translators: %s: Theme name.
								$aria_label = sprintf( _x( 'Activate %s', 'theme' ), '{{ data.name }}' );
								?>
								<p>
									<button type="button" class="button activate" href="<?php echo $theme['actions']['activate']; ?>" aria-label="<?php echo esc_attr( $aria_label ); ?>"><?php _e( 'Activate' ); ?></button>
								<?php if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) { ?>
									<button type="button" class="button button-primary load-customize hide-if-no-customize" href="<?php echo $theme['actions']['customize']; ?>"><?php _e( 'Live Preview' ); ?></button>
								</p>
								<?php } ?>
						</div>
					<?php } ?>

					</div>
				</div>
			</div>
			<?php endforeach;

			do_action( 'themes_list_after' );
			?>
		</div>
	</div>
	<div class="theme-overlay" tabindex="0" role="dialog" aria-label="<?php esc_attr_e( 'Theme Details' ); ?>"></div>

	<p class="no-themes"><?php _e( 'No themes found. Try a different search.' ); ?></p>

	<?php
	// List broken themes, if any.
	if ( ! is_multisite() && current_user_can('edit_themes') && $broken_themes = wp_get_themes( [ 'errors' => true ] ) ) {
	?>

	<div class="broken-themes">
		<h3><?php _e( 'Broken Themes' ); ?></h3>
		<p><?php _e( 'The following themes are installed but incomplete.' ); ?></p>

		<?php
		$can_delete  = current_user_can( 'delete_themes' );
		$can_install = current_user_can( 'install_themes' );
		?>
		<table>
			<tr>
				<th><?php _ex( 'Name', 'theme name' ); ?></th>
				<th><?php _e( 'Description' ); ?></th>
				<?php if ( $can_delete ) { ?>
					<td></td>
				<?php } ?>
				<?php if ( $can_install ) { ?>
					<td></td>
				<?php } ?>
			</tr>
			<?php foreach ( $broken_themes as $broken_theme ) : ?>
				<tr>
					<td><?php echo $broken_theme->get( 'Name' ) ? $broken_theme->display( 'Name' ) : $broken_theme->get_stylesheet(); ?></td>
					<td><?php echo $broken_theme->errors()->get_error_message(); ?></td>
					<?php
					if ( $can_delete ) {

						$stylesheet = $broken_theme->get_stylesheet();
						$delete_url = add_query_arg(
							[
								'action'     => 'delete',
								'stylesheet' => urlencode( $stylesheet ),
							],
							admin_url( 'themes.php' )
						);

						$delete_url = wp_nonce_url( $delete_url, 'delete-theme_' . $stylesheet );
						?>
						<td><a href="<?php echo esc_url( $delete_url ); ?>" class="button delete-theme"><?php _e( 'Delete' ); ?></a></td>
						<?php
					}

					if ( $can_install && 'theme_no_parent' === $broken_theme->errors()->get_error_code() ) {

						$parent_theme_name = $broken_theme->get( 'Template' );
						$parent_theme      = themes_api( 'theme_information', [ 'slug' => urlencode( $parent_theme_name ) ] );

						if ( ! is_wp_error( $parent_theme ) ) {

							$install_url = add_query_arg(
								[
									'action' => 'install-theme',
									'theme'  => urlencode( $parent_theme_name ),
								],
								admin_url( 'update.php' )
							);

							$install_url = wp_nonce_url( $install_url, 'install-theme_' . $parent_theme_name );
							?>
							<td><a href="<?php echo esc_url( $install_url ); ?>" class="button install-theme"><?php _e( 'Install Parent Theme' ); ?></a></td>
							<?php
						}
					}
					?>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>

	<?php
	}
	?>
	</div><!-- .wrap -->

<?php
/*
 * The tmpl-theme template is synchronized with PHP above!
 */
?>
<script id="tmpl-theme" type="text/template">
	<# if ( data.cover[0] ) { #>
		<figure class="theme-cover-image">
			<img src="{{ data.cover[0] }}" alt="<?php _e( 'Theme cover image' ); ?>" width="640" height="480" />
			<figcaption class="screen-reader-text"><?php _e( 'Theme cover image' ); ?></figcaption>
		</figure>
	<# } else if ( data.screenshot[0] ) { #>
		<figure class="theme-cover-image">
			<img src="{{ data.screenshot[0] }}" alt="<?php _e( 'Theme screenshot' ); ?>" width="640" height="480" />
			<figcaption class="screen-reader-text"><?php _e( 'Theme screenshot' ); ?></figcaption>
		</figure>
	<# } else { #>
		<div class="theme-cover-image blank"></div>
	<# } #>

	<# if ( data.hasUpdate ) { #>
		<# if ( data.hasPackage ) { #>
			<div class="update-message notice inline notice-warning notice-alt no-border"><p><?php _e( 'New version available. <button class="button-link" type="button">Update now</button>' ); ?></p></div>
		<# } else { #>
			<div class="update-message notice inline notice-warning notice-alt no-border"><p><?php _e( 'New version available.' ); ?></p></div>
		<# } #>
	<# } #>

	<span class="more-details" id="{{ data.id }}-action"><?php _e( 'Theme Details' ); ?></span>
	<div class="theme-author">
		<?php
		/* translators: %s: Theme author name */
		printf( __( 'By %s' ), '{{{ data.author }}}' );
		?>
	</div>

	<div class="theme-id-container">
		<# if ( data.active ) { #>
			<h2 class="theme-name" id="{{ data.id }}-name">
				<?php
				/* translators: %s: Theme name */
				printf( __( '<span>Active:</span> %s' ), '{{{ data.name }}}' );
				?>
			</h2>
		<# } else { #>
			<h2 class="theme-name" id="{{ data.id }}-name">{{{ data.name }}}</h2>
		<# } #>

		<div class="theme-actions">
			<# if ( data.active ) { #>

				<h2><?php _e( 'Manage This Theme' ); ?></h2>

				<# if ( data.actions.customize ) { #>
					<div class="theme-action-buttons">
						<p>
							<a class="button button-primary customize load-customize hide-if-no-customize" href="{{{ data.actions.customize }}}"><?php _e( 'Manage' ); ?></a>
						</p>
					</div>
				<# } #>
			<# } else { #>

				<h2><?php _e( 'Manage This Theme' ); ?></h2>

				<div class="theme-action-buttons">
					<p>
						<?php
						/* translators: %s: Theme name */
						$aria_label = sprintf( _x( 'Activate %s', 'theme' ), '{{ data.name }}' );
						?>
						<a class="button activate" href="{{{ data.actions.activate }}}" aria-label="<?php echo $aria_label; ?>"><?php _e( 'Activate' ); ?></a>
						<a class="button button-primary load-customize hide-if-no-customize" href="{{{ data.actions.customize }}}"><?php _e( 'Live Preview' ); ?></a>
					</p>
				</div>
			<# } #>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-theme-single">
	<div class="theme-backdrop"></div>
	<div class="theme-wrap" role="document">
		<div class="theme-header">
			<button type="button" class="button theme-prev dashicons dashicons-no"><span class="screen-reader-text"><?php _e( 'Show previous theme' ); ?></span></button>
			<button type="button" class="button theme-next dashicons dashicons-no"><span class="screen-reader-text"><?php _e( 'Show next theme' ); ?></span></button>
			<button type="button" class="button close dashicons dashicons-no"><span class="screen-reader-text"><?php _e( 'Close details dialog' ); ?></span></button>
		</div>
		<div class="theme-about">
			<div class="theme-cover-wrap">
			<# if ( data.cover[0] ) { #>
				<figure class="theme-cover-image">
					<img src="{{ data.cover[0] }}" alt="<?php _e( 'Theme cover image' ); ?>" width="1200" height="900" />
					<figcaption class="screen-reader-text"><?php _e( 'Theme cover image' ); ?></figcaption>
				</figure>
			<# } else if ( data.screenshot[0] ) { #>
				<figure class="theme-cover-image">
					<img src="{{ data.screenshot[0] }}" alt="<?php _e( 'Theme screenshot' ); ?>" width="1200" height="900" />
					<figcaption class="screen-reader-text"><?php _e( 'Theme screenshot' ); ?></figcaption>
				</figure>
			<# } else { #>
				<div class="theme-cover-image blank"></div>
			<# } #>
			</div>

			<div class="theme-info">
				<# if ( data.active ) { #>
					<span class="current-label"><?php _e( 'Current Theme' ); ?></span>
				<# } #>
				<h2 class="theme-name">{{{ data.name }}}<span class="theme-version"><?php printf( __( 'Version: %s' ), '{{ data.version }}' ); ?></span></h2>
				<# if ( data.authorAndUri ) { #>
				<p class="theme-author"><?php printf( __( '%s' ), '{{{ data.authorAndUri }}}' ); ?></p>
				<# } #>

				<# if ( data.hasUpdate ) { #>
				<div class="notice notice-warning notice-alt notice-large no-border">
					<h3 class="notice-title"><?php _e( 'Update Available' ); ?></h3>
					{{{ data.update }}}
				</div>
				<# } #>
				<p class="theme-description">{{{ data.description }}}</p>

				<# if ( data.parent ) { #>
					<p class="parent-theme"><?php printf( __( 'This is a child theme of %s.' ), '<strong>{{{ data.parent }}}</strong>' ); ?></p>
				<# } #>

				<# if ( data.tags ) { #>
					<?php
					$get_theme    = wp_get_theme();
					$get_tags     = $get_theme->get( 'Tags' );
					$count_tags   = count($get_tags);
  					$tags_counted = 0;
					?>
					<p class="theme-tags"><span><?php _e( 'Features: ' ); ?></span>
						<?php
						foreach ( $get_tags as $get_tag ) {
							echo ucwords( str_replace( '-', ' ', $get_tag ) );
							$tags_counted = $tags_counted + 1;

							if ( $tags_counted < $count_tags ) {
								echo ', ';
							}
						}
						?>
					</p>
				<# } #>
			</div>
		</div>

		<div class="theme-actions">
			<div class="active-theme">

				<h2><?php _e( 'Manage This Theme' ); ?></h2>

				<div class="theme-action-buttons">
					<p><?php echo $current_theme_action_links; ?></p>
				</div>
			</div>
			<div class="inactive-theme">

				<h2><?php _e( 'Manage This Theme' ); ?></h2>

				<div class="theme-action-buttons">
					<p>
						<?php
						/* translators: %s: Theme name */
						$aria_label = sprintf( _x( 'Activate %s', 'theme' ), '{{ data.name }}' );
						?>
						<# if ( data.actions.activate ) { #>
							<a href="{{{ data.actions.activate }}}" class="button activate" aria-label="<?php echo $aria_label; ?>"><?php _e( 'Activate' ); ?></a>
						<# } #>
						<a href="{{{ data.actions.customize }}}" class="button button-primary load-customize hide-if-no-customize"><?php _e( 'Live Preview' ); ?></a>
					</p>
					<p>
						<# if ( ! data.active && data.actions['delete'] ) { #>
							<a href="{{{ data.actions['delete'] }}}" class="button delete-theme"><?php _e( 'Delete' ); ?></a>
						<# } #>
					</p>
				</div>
			</div>
		</div>
	</div>
</script>

<?php
wp_print_request_filesystem_credentials_modal();
wp_print_admin_notice_templates();
wp_print_update_row_templates();

wp_localize_script( 'updates', '_wpUpdatesItemCounts', [
	'totals'  => wp_get_update_data(),
] );

require( ABSPATH . 'wp-admin/admin-footer.php' );