<?php
/**
 * General settings administration panel.
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

// Stop here if the current user is not allowed.
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'Sorry, you are not allowed to manage settings for this site.' ) );
}

// Instance of the general settings class.
$page = Backend\Settings_General :: instance();

// Page identification.
$parent_file = $page->parent;
$screen      = $page->screen();
$title       = $page->title();
$description = $page->description();

// Get the admin page header.
include( APP_VIEWS_PATH . '/backend/header/admin-header.php' );

?>
<div class="wrap">

	<h1><?php echo esc_html( $title ); ?></h1>
	<?php echo $description; ?>

	<?php $page->render_form(); ?>

</div>
<?php

// Get the admin page footer.
include( APP_VIEWS_PATH . '/backend/footer/admin-footer.php' );
