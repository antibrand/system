<?php
/**
 * General settings administration panel.
 *
 * @package App_Package
 * @subpackage Administration
 */

use \AppNamespace\Backend as Backend;

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

// Stop here if the current user is not allowed.
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'Sorry, you are not allowed to manage settings for this site.' ) );
}

// Instance of the general settings class.
$class = Backend\Settings_General :: instance();

// Page identification.
$parent_file = $class->parent;
$title       = $class->title();
$description = $class->description();

// Get page header.
include( APP_ADMIN_PATH . '/admin-header.php' );

?>
<div class="wrap">

	<h1><?php echo esc_html( $title ); ?></h1>
	<?php echo $description; ?>

	<?php $class->render_form(); ?>

</div>
<?php

// Get page footer.
include( APP_ADMIN_PATH . '/admin-footer.php' );
