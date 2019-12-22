<?php
/**
 * About This Version administration panel.
 *
 * @package App_Package
 * @subpackage Administration
 */

/** Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

$title = __( 'Content Options' );

include( ABSPATH . 'wp-admin/admin-header.php' );

?>
<div class="wrap">

	<h1><?php _e( 'Content Options' ); ?></h1>
	<p class="description"><?php printf( __( 'Content types, taxonomies, and author management.' ) ); ?></p>

	<p><?php _e( 'This page and submenu pages may not be used. This is a placholder while content options are explored.' ); ?></p>

</div>
<?php

include( ABSPATH . 'wp-admin/admin-footer.php' );