<?php
/**
 * Dashboard administration screen
 *
 * @package App_Package
 * @subpackage Administration
 */

// Alias namespaces.
use \AppNamespace\Backend as Backend;

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

// Load the administration environment.
require_once( APP_INC_PATH . '/backend/app-admin.php' );

// Instance of the dashboard class.
$page = Backend\Dashboard :: instance();

// Page identification.
$parent_file = $page->parent;
$screen      = $page->screen();
$title       = $page->title();
$description = $page->description();

// Get the admin page header.
include_once( APP_VIEWS_PATH . '/backend/header/admin-header.php' );

?>
	<div class="wrap">

		<h1><?php echo esc_html( $title ); ?></h1>
		<?php echo $description; ?>

		<div id="dashboard-tabs">
			<?php
			// Tabbed content.
			echo get_current_screen()->render_content_tabs(); ?>
		</div>
		<?php if ( has_action( 'dashboard_add_content' ) || has_action( 'welcome_panel' ) ) : ?>
		<div id="dashboard-add-content" class="dashboard-add-content">
			<?php
			/**
			 * Additional dashboard content
			 *
			 * Plugins & themes may use this to add content below
			 * the included top panel.
			 *
			 * @since 1.0.0
			 */
			do_action( 'dashboard_add_content' ); ?>
		</div>
		<?php endif; // End if has action. ?>
	</div><!-- .wrap -->
<?php

// Get the admin page footer.
include_once( APP_VIEWS_PATH . '/backend/footer/admin-footer.php' );
