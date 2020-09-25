<?php
/**
 * About page
 *
 * User information about the website management system.
 * This is intentionally incomplete, designed for you to
 * develop for your unique version of this app.
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

// Instance of the page class.
$page = Backend\Admin_About :: instance();

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

	<?php $page->render_tabs(); ?>

</div>
<?php

// Get the admin page footer.
include( APP_VIEWS_PATH . '/backend/footer/admin-footer.php' );
