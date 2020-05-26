<?php
/**
 * Import Administration Screen
 *
 * @package App_Package
 * @subpackage Administration
 */

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

$title       = __( 'Database Tools' );
$parent_file = 'options-general.php';

require_once( ABSPATH . 'wp-admin/admin-header.php' );

// Instance of the dashboard class.
\AppNamespace\Backend\Data_Page :: instance();

// Script for tabbed content.
wp_enqueue_script( 'app-tabs' );

?>

<div class="wrap">

	<h1><?php echo esc_html( $title ); ?></h1>
	<p class="description"><?php _e( 'Manage the database, import and export content' ); ?></p>

	<div id="data-page-tabs">
		<?php

		// Tabbed content.
		echo get_current_screen()->render_content_tabs();

		?>
	</div>
</div>

<?php
include( ABSPATH . 'wp-admin/admin-footer.php' );