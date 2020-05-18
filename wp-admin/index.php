<?php
/**
 * Dashboard administration screen
 *
 * @package App_Package
 * @subpackage Administration
 */

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

// Instance of the dashboard class.
\AppNamespace\Backend\Dashboard :: instance();

// Script for AJAX, drag & drop, show/hide.
wp_enqueue_script( 'dashboard' );

// Script for tabbed content.
wp_enqueue_script( 'app-tabs' );

// Script for media uploads.
if ( current_user_can( 'upload_files' ) ) {
	wp_enqueue_script( 'media-upload' );
}

// Script for modal content.
add_thickbox();

// Script for mobile devices.
if ( wp_is_mobile() ) {
	wp_enqueue_script( 'jquery-touch-punch' );
}

/**
 * Not using this.
 *
 * @todo Remove when added to "WP Plugins" plugin.
 */
if ( current_user_can( 'install_plugins' ) ) {
	// wp_enqueue_script( 'plugin-install' );
	// wp_enqueue_script( 'updates' );
}

$title       = __( 'Dashboard' );
$parent_file = 'index.php';

// User option for layout in the widgets tab.
if ( is_user_admin() ) {
	add_screen_option( 'layout_columns', [ 'max' => 4, 'default' => 3 ] );
} else {
	add_screen_option( 'layout_columns', [ 'max' => 4, 'default' => 2 ] );
}

// Get the page header template.
include( ABSPATH . 'wp-admin/admin-header.php' );

// Begin page content.
?>
	<div class="wrap">

		<h1><?php echo esc_html( $title ); ?></h1>

		

	<?php

		// Tabbed content.
		echo get_current_screen()->render_content_tabs();


		if ( has_action( 'dashboard_add_content' ) || has_action( 'welcome_panel' ) ) :

			// Get the user preference.
			$option  = get_user_meta( get_current_user_id(), 'show_top_panel', true );

			// Top panel base class.
			$classes = 'dashboard-add-content';

			/**
			 * Hidden class
			 *
			 * Add `.hidden` class if the user wants to hide the top panel.
			 * 0 = hide, 1 = toggled to show or single site creator, 2 = multisite site owner.
			 */
			$hide = '0' === $option || ( '2' === $option && wp_get_current_user()->user_email != get_option( 'admin_email' ) );

			if ( $hide ) {
				$classes .= ' hidden';
			}

			?>
			<div id="top-panel" class="<?php echo esc_attr( $classes ); ?>">
				<?php

				// User display preference nonce for the top panel.
				wp_nonce_field( 'top-panel-nonce', 'toppanelnonce', false );
				/**
				 * Additional dashboard content
				 *
				 * Plugins & themes may use this to add content below
				 * the included top panel.
				 *
				 * @since 1.0.0
				 */
				// do_action( 'dashboard_add_content' );

				/**
				 * Deprecated hook
				 *
				 * @since      WP 3.5.0
				 * @deprecated 1.0.0 Not used by this website management system.
				 */
				do_action( 'welcome_panel' );
				?>
			</div>
		<?php endif; ?>
	</div><!-- .wrap -->
<?php

// Get the page footer template.
require( ABSPATH . 'wp-admin/admin-footer.php' );