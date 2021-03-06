<?php
/**
 * Extensions administration page
 *
 * @package App_Package
 * @subpackage Administration
 */

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

// Load the administration environment.
require_once( APP_INC_PATH . '/backend/app-admin.php' );

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'Sorry, you are not allowed to manage extensions for this site.' ) );
}

$wp_list_table = _get_list_table( 'AppNamespace\Backend\Extensions_List_Table' );
$pagenum       = $wp_list_table->get_pagenum();

$wp_list_table->prepare_items();

/**
 * add_screen_option( 'per_page', [ 'default' => 20 ] );
 *
 * @todo Fix this screen option or remove it.
 */

$title       = __( 'System Extensions' );
$parent_file = 'plugins.php';

// Get the admin page header.
include( APP_VIEWS_PATH . '/backend/header/admin-header.php' );

$invalid = validate_active_plugins();

?>
	<div class="wrap">

		<h1><?php echo esc_html( $title ); ?></h1>

		<p class="description"><?php _e( 'Extensions are custom modifications to the functionality of the website management system.' ); ?></p>

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
		 * @since 1.0.0
		 * @param array $plugins_all An array containing all installed plugins.
		 */
		do_action( 'pre_current_active_plugins', $plugins['extension'] ); ?>

		<?php

		echo '<p>' . sprintf( __( 'Files in the %s directory are executed automatically and cannot be disabled.' ),
				'<code>' . str_replace( ABSPATH, '', APP_EXTENSIONS_PATH ) . '</code>'
		) . '</p>';

		$wp_list_table->display(); ?>

	</div><!-- .wrap -->

<?php

// Get the admin page footer.
include( APP_VIEWS_PATH . '/backend/footer/admin-footer.php' );
