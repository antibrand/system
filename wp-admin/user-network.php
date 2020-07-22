<?php
/**
 * Network Sites dashboard.
 *
 * @package App_Package
 * @subpackage Network
 * @since Previous 3.0.0
 */

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! is_network() ) {
	wp_die( __( 'Network support is not enabled.' ) );
}

if ( ! current_user_can( 'read' ) ) {
	wp_die( __( 'Sorry, you are not allowed to access this page.' ) );
}

if ( isset( $_POST['action'] ) ) {
	$action = $_POST['action'];
} else {
	$action = 'splash';
}

$blogs   = get_blogs_of_user( $current_user->ID );
$updated = false;

if ( 'updateblogsettings' == $action && isset( $_POST['primary_blog'] ) ) {

	check_admin_referer( 'update-user-network' );

	$blog = get_site( (int) $_POST['primary_blog'] );

	if ( $blog && isset( $blog->domain ) ) {

		update_user_option( $current_user->ID, 'primary_blog', (int) $_POST['primary_blog'], true );

		$updated = true;

	} else {
		wp_die( __( 'The primary site you chose does not exist.' ) );
	}
}

$title       = __( 'Network Sites' );
$parent_file = 'index.php';

$help_overview = sprintf(
	'<h3>%1s</h3>',
	__( 'Overview' )
);

$help_overview .= sprintf(
	'<p>%1s</p>',
	__( 'This screen shows an individual user all of their sites in this network, and also allows that user to set a primary site. They can use the links under each site to visit either the front end or the dashboard for that site.' )
);

$help_overview = apply_filters( 'help_overview_network_sites', $help_overview );

get_current_screen()->add_help_tab( [
	'id'      => 'overview',
	'title'   => __( 'Overview' ),
	'content' => $help_overview
] );

/**
 * Help sidebar content
 *
 * This system adds no content to the help sidebar
 * but there is a filter applied for adding content.
 *
 * @since 1.0.0
 */
$set_help_sidebar = apply_filters( 'set_help_sidebar_network_sites', '' );
get_current_screen()->set_help_sidebar( $set_help_sidebar );

require_once( ABSPATH . 'wp-admin/admin-header.php' );

if ( $updated ) { ?>
	<div id="message" class="updated notice is-dismissible">
		<p><?php _e( 'Settings saved.' ); ?></p>
	</div>
<?php } ?>

<div class="wrap">

	<h1><?php esc_html_e( $title ); ?></h1>

	<p class="description"><?php _e( 'Manage your sites in this network.' ); ?></p>

	<?php if ( in_array( get_site_option( 'registration' ), [ 'all', 'blog' ] ) ) {

		// This filter is documented in app-login.php.
		$sign_up_url = apply_filters( 'wp_signup_location', network_site_url( 'app-signup.php' ) );
		printf( ' <p><a href="%s" class="button">%s</a></p>', esc_url( $sign_up_url ), esc_html_x( 'Add New', 'site' ) );
	}

	if ( empty( $blogs ) ) :

		echo sprintf(
			'<p>%1s</p>',
			__( 'You must be a member of at least one site to use this page.' )
		);

	else : ?>

	<form id="myblogs" method="post">
		<?php

		choose_primary_blog();

		/**
		 * Fires before the sites list on the Network Sites screen.
		 *
		 * @since Previous 3.0.0
		 */
		do_action( 'myblogs_allblogs_options' );

		?>
		<ul class="my-sites striped">
		<?php
		/**
		 * Enable the Global Settings section on the Network Sites screen
		 *
		 * By default, the Global Settings section is hidden. Passing a non-empty
		 * string to this filter will enable the section, and allow new settings
		 * to be added, either globally or for specific sites.
		 *
		 * @since Previous 3.0.0
		 * @param string $settings_html The settings HTML markup. Default empty.
		 * @param object $context Context of the setting (global or site-specific). Default 'global'.
		 */
		$settings_html = apply_filters( 'myblogs_options', '', 'global' );

		if ( $settings_html != '' ) {

			echo '<h3>' . __( 'Global Settings' ) . '</h3>';
			echo $settings_html;
		}

		reset( $blogs );

		foreach ( $blogs as $user_blog ) {

			switch_to_blog( $user_blog->userblog_id );

			echo "<li>";
			echo "<h3>{$user_blog->blogname}</h3>";

			$actions = "<a href='" . esc_url( home_url() ). "' class='button'>" . __( 'Visit' ) . '</a>';

			if ( current_user_can( 'read' ) ) {
				$actions .= " <a href='" . esc_url( admin_url() ) . "' class='button'>" . __( 'Dashboard' ) . '</a>';
			}

			/**
			 * Filters the row links displayed for each site on the Network Sites screen.
			 *
			 * @since Previous 3.0.0
			 * @param string $actions The HTML site link markup.
			 * @param object $user_blog An object containing the site data.
			 */
			$actions = apply_filters( 'myblogs_blog_actions', $actions, $user_blog );

			echo "<p class='user-network-actions'>" . $actions . '</p>';

			// This filter is documented in wp-admin/user-network.php.
			echo apply_filters( 'myblogs_options', '', $user_blog );
			echo "</li>";

			restore_current_blog();
		} ?>
		</ul>
		<?php
		if ( count( $blogs ) > 1 || has_action( 'myblogs_allblogs_options' ) || has_filter( 'myblogs_options' ) ) {

		?>
		<input type="hidden" name="action" value="updateblogsettings" />

		<?php wp_nonce_field( 'update-user-network' ); ?>

		<p><?php submit_button(); ?></p>

		<?php } ?>
	</form>
	<?php endif; ?>
</div>
<?php

include( ABSPATH . 'wp-admin/admin-footer.php' );
