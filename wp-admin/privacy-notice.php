<?php
/**
 * Your Rights administration panel.
 *
 * @package WMS
 * @subpackage Administration
 *
 * @todo Edit text when connection to WordPress is complete.
 */

/** WordPress Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

$title   = __( 'Privacy Notice' );
$version = get_bloginfo( 'version' );

include( ABSPATH . 'wp-admin/admin-header.php' );

?>
<div class="wrap not-using__about-wrap not-using__full-width-layout">

	<h1><?php printf( __( 'Welcome' ) ); ?></h1>
	<p class="description not-using__about-text"><?php printf( __( '' ) ); ?></p>

	<h2 class="nav-tab-wrapper wp-clearfix">
		<a href="about.php" class="nav-tab"><?php _e( 'About' ); ?></a>
		<a href="privacy-notice.php" class="nav-tab nav-tab-active"><?php _e( 'Privacy' ); ?></a>
	</h2>

</div>
<?php include( ABSPATH . 'wp-admin/admin-footer.php' ); ?>