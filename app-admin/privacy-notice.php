<?php
/**
 * Your Rights administration panel.
 *
 * @package App_Package
 * @subpackage Administration
 */

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

// Load the administration environment.
require_once( APP_INC_PATH . '/backend/app-admin.php' );

$title   = __( 'Privacy Notice' );
$name    = get_app_info( 'name' );
$version = get_app_info( 'version' );

// Get the admin page header.
include( APP_VIEWS_PATH . '/backend/header/admin-header.php' );

?>
<div class="wrap not-using__about-wrap not-using__full-width-layout">

<h1>
	<?php echo sprintf(
		'%1s %2s %3s',
		$name,
		$version,
		__( 'Privacy Notice' )
	); ?>
	</h1>
	<p class="description not-using__about-text"><?php printf( __( 'Tell folks about the privacy of your website management system.' ) ); ?></p>

	<h2 class="nav-tab-wrapper app-clearfix">
		<a href="about.php" class="nav-tab"><?php _e( 'Features' ); ?></a>
		<a href="privacy-notice.php" class="nav-tab nav-tab-active"><?php _e( 'Privacy' ); ?></a>
	</h2>

	<h3><?php _e( 'How Your Data Is Used' ); ?></h3>
	<p><?php _e( 'Add your content here.' ); ?></p>

</div>
<?php

// Get the admin page footer.
include( APP_VIEWS_PATH . '/backend/footer/admin-footer.php' );
