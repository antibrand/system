<?php
/**
 * Discussion settings administration panel.
 *
 * @package App_Package
 * @subpackage Administration
 */

use \AppNamespace\Backend as Backend;

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );
}

// Instance of the general settings class.
$class = Backend\Settings_Discussion :: instance();

// Page identification.
$parent_file = $class->parent;
$title       = $class->title();
$description = $class->description();

// Get the admin page header.
include( APP_VIEWS_PATH . '/backend/header/admin-header.php' );

?>
<div class="wrap">

	<h1><?php echo esc_html( $title ); ?></h1>
	<?php echo $description; ?>

	<?php $class->render_form(); ?>

</div><!-- .wrap -->
<?php

// Get page footer.
include( APP_ADMIN_PATH . '/admin-footer.php' );
