<?php
/**
 * Network administration panel.
 *
 * @package App_Package
 * @subpackage Network
 * @since 3.0.0
 */

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

// Instance of the dashboard class.
\AppNamespace\Backend\Dashboard :: instance();

if ( ! current_user_can( 'manage_network' ) )
	wp_die( __( 'Sorry, you are not allowed to access this page.' ), 403 );

$title = __( 'Dashboard' );
$parent_file = 'index.php';

$overview = '<p>' . __( 'Welcome to your Network Admin. This area of the Administration Screens is used for managing all aspects of your network.' ) . '</p>';
$overview .= '<p>' . __( 'From here you can:' ) . '</p>';
$overview .= '<ul><li>' . __( 'Add and manage sites or users' ) . '</li>';
$overview .= '<li>' . __( 'Install and activate themes or plugins' ) . '</li>';
$overview .= '<li>' . __( 'Update your network' ) . '</li>';
$overview .= '<li>' . __( 'Modify global network settings' ) . '</li></ul>';

get_current_screen()->add_help_tab( array(
	'id'      => 'overview',
	'title'   => __( 'Overview' ),
	'content' => $overview
) );

$quick_tasks = '<p>' . __( 'The Right Now widget on this screen provides current user and site counts on your network.' ) . '</p>';
$quick_tasks .= '<ul><li>' . __( 'To add a new user, <strong>click Create a New User</strong>.' ) . '</li>';
$quick_tasks .= '<li>' . __( 'To add a new site, <strong>click Create a New Site</strong>.' ) . '</li></ul>';
$quick_tasks .= '<p>' . __( 'To search for a user or site, use the search boxes.' ) . '</p>';
$quick_tasks .= '<ul><li>' . __( 'To search for a user, <strong>enter an email address or username</strong>. Use a wildcard to search for a partial username, such as user&#42;.' ) . '</li>';
$quick_tasks .= '<li>' . __( 'To search for a site, <strong>enter the path or domain</strong>.' ) . '</li></ul>';

get_current_screen()->add_help_tab( array(
	'id'      => 'quick-tasks',
	'title'   => __( 'Quick Tasks' ),
	'content' => $quick_tasks
) );

get_current_screen()->set_help_sidebar( '' );

// Script for AJAX, drag & drop, show/hide.
wp_enqueue_script( 'dashboard' );

// Script for tabbed content.
wp_enqueue_script( 'app-tabs' );

// Script for media uploads.
if ( current_user_can( 'upload_files' ) ) {
	wp_enqueue_script( 'media-upload' );
}

// Script for modal content.
add_thickbox();

// Script for mobile devices.
if ( wp_is_mobile() ) {
	wp_enqueue_script( 'jquery-touch-punch' );
}

require_once( ABSPATH . 'wp-admin/admin-header.php' );

?>

<div class="wrap">

	<h1><?php echo esc_html( $title ); ?></h1>

	<div id="dashboard-tabs">
		<?php

		// Tabbed content.
		echo get_current_screen()->render_content_tabs();

		?>
	</div>

</div><!-- wrap -->

<?php
include( ABSPATH . 'wp-admin/admin-footer.php' );
