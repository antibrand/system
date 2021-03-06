<?php
/**
 * Tools administration screen
 *
 * This page is not used by this website management system
 * but is here for compatibility with plugins that rely on
 * the URL for their UI pages.
 *
 * @package App_Package
 * @subpackage Administration
 */

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

// Load the administration environment.
require_once( APP_INC_PATH . '/backend/app-admin.php' );

$title = __( 'Tools' );

// Get the admin page header.
include( APP_VIEWS_PATH . '/backend/header/admin-header.php' );

?>
<main id="app-body" class="main admin-main" role="main">

	<div class="wrap">

		<h1><?php echo esc_html( $title ); ?></h1>
		<p class="description"><?php _e( 'Website management system toolbox.' ); ?></p>

		<p><?php _e( 'This page is here for compatibility with plugins that rely it.' ); ?></p>

		<?php echo sprintf(
			'<p>%1s <code>%2s</code></p>',
			__( 'The menu entry can be restored by uncommenting it at' ),
			APP_ADMIN_DIR . '/menu.php'
		); ?>

		<?php
			/**
			 * Tool box
			 *
			 * Fires at the end of the tools administration screen
			 *
			 * @since Previous 2.8.0
			 */
			do_action( 'tool_box' );
		?>

	</div>

</main>
<?php

// Get the admin page footer.
include( APP_VIEWS_PATH . '/backend/footer/admin-footer.php' );
