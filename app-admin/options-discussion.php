<?php
/**
 * Discussion settings administration panel.
 *
 * @package App_Package
 * @subpackage Administration
 */

use \AppNamespace\Backend as Backend;

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

// Load the administration environment.
require_once( APP_INC_PATH . '/backend/app-admin.php' );

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

// Get the admin page footer.
include( APP_VIEWS_PATH . '/backend/footer/admin-footer.php' );
