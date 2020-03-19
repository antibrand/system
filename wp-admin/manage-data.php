<?php
/**
 * Import Administration Screen
 *
 * @package App_Package
 * @subpackage Administration
 */

define( 'WP_LOAD_IMPORTERS', true );

/** Load the website management system */
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! current_user_can( 'import' ) ) {
	wp_die( __( 'Sorry, you are not allowed to import content.' ) );
}

$title = __( 'Database Tools' );

require_once( ABSPATH . 'wp-admin/admin-header.php' );
$parent_file = 'privacy.php';
?>

<div class="wrap">
	<h1><?php echo esc_html( $title ); ?></h1>
	<p class="description"><?php _e( 'Manage the database, import and export content' ); ?></p>

	<nav class="nav-tab-wrapper wp-clearfix">
		<a class="nav-tab nav-tab-active" href="#"><?php _e( 'System Info' ); ?></a>
		<a class="nav-tab" href="#"><?php _e( 'Import Data' ); ?></a>
		<a class="nav-tab" href="#"><?php _e( 'Export Data' ); ?></a>
	</nav>
</div>

<?php
include( ABSPATH . 'wp-admin/admin-footer.php' );