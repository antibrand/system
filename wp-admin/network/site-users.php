<?php
/**
 * Edit Site Users Administration Screen
 *
 * @package App_Package
 * @subpackage Network
 * @since Previous 3.1.0
 */

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! current_user_can( 'manage_sites' ) ) {
	wp_die( __( 'Sorry, you are not allowed to edit this site.' ), 403 );
}

$wp_list_table = _get_list_table( 'AppNamespace\Backend\Users_List_Table' );
$wp_list_table->prepare_items();

get_current_screen()->add_help_tab( get_site_screen_help_tab_args() );
get_current_screen()->set_help_sidebar( get_site_screen_help_sidebar_content() );

get_current_screen()->set_screen_reader_content( [
	'heading_views'      => __( 'Filter site users list' ),
	'heading_pagination' => __( 'Site users list navigation' ),
	'heading_list'       => __( 'Site users list' ),
] );

$_SERVER['REQUEST_URI'] = remove_query_arg( 'update', $_SERVER['REQUEST_URI'] );
$referer = remove_query_arg( 'update', wp_get_referer() );

if ( ! empty( $_REQUEST['paged'] ) ) {
	$referer = add_query_arg( 'paged', (int) $_REQUEST['paged'], $referer );
}

$id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;

if ( ! $id ) {
	wp_die( __( 'Invalid site ID.' ) );
}

$details = get_site( $id );
if ( ! $details ) {
	wp_die( __( 'The requested site does not exist.' ) );
}

if ( ! can_edit_network( $details->site_id ) ) {
	wp_die( __( 'Sorry, you are not allowed to access this page.' ), 403 );
}

$is_main_site = is_main_site( $id );

switch_to_blog( $id );

$action = $wp_list_table->current_action();

if ( $action ) {

	switch ( $action ) {

		case 'newuser' :

			check_admin_referer( 'add-user', '_wpnonce_add-new-user' );
			$user = $_POST['user'];

			if ( ! is_array( $_POST['user'] ) || empty( $user['username'] ) || empty( $user['email'] ) ) {
				$update = 'err_new';

			} else {

				$password = wp_generate_password( 12, false );
				$user_id  = wpmu_create_user( esc_html( strtolower( $user['username'] ) ), $password, esc_html( $user['email'] ) );

				if ( false === $user_id ) {
		 			$update = 'err_new_dup';
				} else {
					$result = add_user_to_blog( $id, $user_id, $_POST['new_role'] );

					if ( is_wp_error( $result ) ) {
						$update = 'err_add_fail';
					} else {
						$update = 'newuser';
						/**
						  * Fires after a user has been created via the network site-users.php page.
						  *
						  * @since Previous 4.4.0
						  * @param int $user_id ID of the newly created user.
						  */
						do_action( 'network_site_users_created_user', $user_id );
					}
				}
			}

			break;

		case 'adduser' :

			check_admin_referer( 'add-user', '_wpnonce_add-user' );

			if ( ! empty( $_POST['newuser'] ) ) {

				$update  = 'adduser';
				$newuser = $_POST['newuser'];
				$user    = get_user_by( 'login', $newuser );

				if ( $user && $user->exists() ) {

					if ( ! is_user_member_of_blog( $user->ID, $id ) ) {

						$result = add_user_to_blog( $id, $user->ID, $_POST['new_role'] );

						if ( is_wp_error( $result ) ) {
							$update = 'err_add_fail';
						}

					} else {
						$update = 'err_add_member';
					}

				} else {
					$update = 'err_add_notfound';
				}

			} else {
				$update = 'err_add_notfound';
			}

			break;

		case 'remove' :

			if ( ! current_user_can( 'remove_users' ) ) {
				wp_die( __( 'Sorry, you are not allowed to remove users.' ), 403 );
			}

			check_admin_referer( 'bulk-users' );

			$update = 'remove';

			if ( isset( $_REQUEST['users'] ) ) {

				$userids = $_REQUEST['users'];

				foreach ( $userids as $user_id ) {
					$user_id = (int) $user_id;
					remove_user_from_blog( $user_id, $id );
				}

			} elseif ( isset( $_GET['user'] ) ) {
				remove_user_from_blog( $_GET['user'] );
			} else {
				$update = 'err_remove';
			}

			break;

		case 'promote' :

			check_admin_referer( 'bulk-users' );

			$editable_roles = get_editable_roles();
			$role           = false;

			if ( ! empty( $_REQUEST['new_role2'] ) ) {
				$role = $_REQUEST['new_role2'];
			} elseif ( ! empty( $_REQUEST['new_role'] ) ) {
				$role = $_REQUEST['new_role'];
			}

			if ( empty( $editable_roles[ $role ] ) ) {
				wp_die( __( 'Sorry, you are not allowed to give users that role.' ), 403 );
			}

			if ( isset( $_REQUEST['users'] ) ) {

				$userids = $_REQUEST['users'];
				$update  = 'promote';

				foreach ( $userids as $user_id ) {

					$user_id = (int) $user_id;

					// If the user doesn't already belong to the blog, bail.
					if ( ! is_user_member_of_blog( $user_id ) ) {
						wp_die(
							'<h1>' . __( 'Something went wrong.' ) . '</h1>' .
							'<p>' . __( 'One of the selected users is not a member of this site.' ) . '</p>',
							403
						);
					}

					$user = get_userdata( $user_id );
					$user->set_role( $role );
				}

			} else {
				$update = 'err_promote';
			}

			break;

		default :

			if ( ! isset( $_REQUEST['users'] ) ) {
				break;
			}

			check_admin_referer( 'bulk-users' );

			$userids = $_REQUEST['users'];

			// This action is documented in APP_ADMIN_DIR/network/site-themes.php.
			$referer = apply_filters( 'handle_network_bulk_actions-' . get_current_screen()->id, $referer, $action, $userids, $id );
			$update  = $action;

			break;
	}

	wp_safe_redirect( add_query_arg( 'update', $update, $referer ) );

	exit();
}

restore_current_blog();

if ( isset( $_GET['action'] ) && 'update-site' == $_GET['action'] ) {
	wp_safe_redirect( $referer );
	exit();
}

add_screen_option( 'per_page' );

$title        = sprintf( __( 'Edit Site: %s' ), esc_html( $details->blogname ) );
$parent_file  = 'sites.php';
$submenu_file = 'sites.php';

/**
 * Filters whether to show the Add Existing User form on the network users screen.
 *
 * @since Previous 3.1.0
 * @param bool $bool Whether to show the Add Existing User form. Default true.
 */
if ( ! wp_is_large_network( 'users' ) && apply_filters( 'show_network_site_users_add_existing_form', true ) ) {
	wp_enqueue_script( 'user-suggest' );
}

require( APP_ADMIN_PATH . '/admin-header.php' ); ?>

<script type="text/javascript">
var current_site_id = <?php echo $id; ?>;
</script>

<script>
// Toggle the plugin upload interface.
jQuery(document).ready( function($) {
	$( '#add-new-user-toggle' ).click( function() {
		$(this).text( $(this).text() == "<?php _e( 'Add New' ); ?>" ? "<?php _e( 'Close Form' ); ?>" : "<?php _e( 'Add New' ); ?>" );
		$( '#add-existing-user-toggle' ).text( "<?php _e( 'Add Existing' ); ?>" );
		$( '#add-existing-user-form' ).removeClass( 'open' );
		$( '#add-new-user-form' ).toggleClass( 'open' );
	});
	$( '#add-existing-user-toggle' ).click( function() {
		$(this).text( $(this).text() == "<?php _e( 'Add Existing' ); ?>" ? "<?php _e( 'Close Form' ); ?>" : "<?php _e( 'Add Existing' ); ?>" );
		$( '#add-new-user-toggle' ).text( "<?php _e( 'Add New' ); ?>" );
		$( '#add-new-user-form' ).removeClass( 'open' );
		$( '#add-existing-user-form' ).toggleClass( 'open' );
	});
});
</script>

<div class="wrap">

	<h1 id="edit-site"><?php echo $title; ?></h1>

	<nav role="navigation">
		<ul class="network-site-settings-nav top">
			<li><a href="<?php echo esc_url( get_home_url( $id, '/' ) ); ?>" class="button"><?php _e( 'Visit' ); ?></a>
			<li><a href="<?php echo esc_url( get_admin_url( $id ) ); ?>" class="button"><?php _e( 'Dashboard' ); ?></a>
		</ul>
	</nav>

	<div class="list-table-action-form-wrap">

		<div id="add-new-user-form" class="list-table-action-form">
			<?php
			/**
			 * Filters whether to show the Add New User form on the network users screen.
			 *
			 * @since Previous 3.1.0
			 * @param bool $bool Whether to show the Add New User form. Default true.
			 */
			if ( current_user_can( 'create_users' ) && apply_filters( 'show_network_site_users_add_new_form', true ) ) :

			?>
			<h2><?php _e( 'Add New User' ); ?></h2>

			<form action="<?php echo network_admin_url( 'site-users.php?action=newuser' ); ?>" id="newuser" method="post">

				<input type="hidden" name="id" value="<?php echo esc_attr( $id ) ?>" />

				<p>
					<label for="user_username"><?php _e( 'Username' ) ?></label>
					<br /><input type="text" class="regular-text" name="user[username]" id="user_username" />
				</p>

				<p>
					<label for="user_email"><?php _e( 'Email' ) ?></label>
					<br /><input type="text" class="regular-text" name="user[email]" id="user_email" />
				</p>

				<p>
					<label for="new_role_newuser"><?php _e( 'Role' ); ?></label>
					<br /><select name="new_role" id="new_role_newuser">
					<?php
						switch_to_blog( $id );
						wp_dropdown_roles( get_option( 'default_role' ) );
						restore_current_blog();
					?>
					</select>
				</p>

				<p><?php _e( 'A password reset link will be sent to the user via email.' ) ?></p>

				<?php wp_nonce_field( 'add-user', '_wpnonce_add-new-user' ) ?>

				<p><?php submit_button( __( 'Add New User' ), 'primary', 'add-user', true, [ 'id' => 'submit-add-user' ] ); ?></p>

			</form>
			<?php endif; ?>
		</div>
	</div>

	<div class="list-table-action-form-wrap">

		<div id="add-existing-user-form" class="list-table-action-form">
			<?php
			/**
			 * Fires after the list table on the Users screen in the network Admin.
			 *
			 * @since Previous 3.1.0
			 */
			do_action( 'network_site_users_after_list_table' );

			/** This filter is documented in APP_ADMIN_DIR/network/site-users.php */
			if ( current_user_can( 'promote_users' ) && apply_filters( 'show_network_site_users_add_existing_form', true ) ) :

			?>
			<h2 id="add-existing-user"><?php _e( 'Add Existing User' ); ?></h2>

			<form action="site-users.php?action=adduser" id="adduser" method="post">

				<input type="hidden" name="id" value="<?php echo esc_attr( $id ) ?>" />

				<p>
					<label for="newuser"><?php _e( 'Username' ); ?></label>
					<br /><input type="text" class="regular-text wp-suggest-user" name="newuser" id="newuser" />
				</p>

				<p>
					<label for="new_role_adduser"><?php _e( 'Role' ); ?></label>
					<br /><select name="new_role" id="new_role_adduser">
						<?php
						switch_to_blog( $id );
						wp_dropdown_roles( get_option( 'default_role' ) );
						restore_current_blog();
						?>
					</select>
				</p>

				<?php wp_nonce_field( 'add-user', '_wpnonce_add-user' ) ?>

				<p><?php submit_button( __( 'Add User' ), 'primary', 'add-user', true, [ 'id' => 'submit-add-existing-user' ] ); ?></p>

			</form>
			<?php endif; ?>
		</div>
	</div>

	<?php

	network_edit_site_nav( [
		'blog_id'  => $id,
		'selected' => 'site-users'
	] );

	if ( isset( $_GET['update'] ) ) :

		switch( $_GET['update'] ) {

			case 'adduser' :
				echo '<div id="message" class="updated notice is-dismissible"><p>' . __( 'User added.' ) . '</p></div>';
				break;

			case 'err_add_member' :
				echo '<div id="message" class="error notice is-dismissible"><p>' . __( 'User is already a member of this site.' ) . '</p></div>';
				break;

			case 'err_add_fail' :
				echo '<div id="message" class="error notice is-dismissible"><p>' . __( 'User could not be added to this site.' ) . '</p></div>';
				break;

			case 'err_add_notfound' :
				echo '<div id="message" class="error notice is-dismissible"><p>' . __( 'Enter the username of an existing user.' ) . '</p></div>';
				break;

			case 'promote' :
				echo '<div id="message" class="updated notice is-dismissible"><p>' . __( 'Changed roles.' ) . '</p></div>';
				break;

			case 'err_promote' :
				echo '<div id="message" class="error notice is-dismissible"><p>' . __( 'Select a user to change role.' ) . '</p></div>';
				break;

			case 'remove' :
				echo '<div id="message" class="updated notice is-dismissible"><p>' . __( 'User removed from this site.' ) . '</p></div>';
				break;

			case 'err_remove' :
				echo '<div id="message" class="error notice is-dismissible"><p>' . __( 'Select a user to remove.' ) . '</p></div>';
				break;

			case 'newuser' :
				echo '<div id="message" class="updated notice is-dismissible"><p>' . __( 'User created.' ) . '</p></div>';
				break;

			case 'err_new' :
				echo '<div id="message" class="error notice is-dismissible"><p>' . __( 'Enter the username and email.' ) . '</p></div>';
				break;

			case 'err_new_dup' :
				echo '<div id="message" class="error notice is-dismissible"><p>' . __( 'Duplicated username or email address.' ) . '</p></div>';
				break;
		}
	endif; ?>

	<div class="list-table-top">

		<?php $wp_list_table->views(); ?>

		<form class="search-form" method="get">
			<?php $wp_list_table->search_box( __( 'Search Users' ), 'user' ); ?>
			<input type="hidden" name="id" value="<?php echo esc_attr( $id ) ?>" />
		</form>

	</div>

	<form method="post" action="site-users.php?action=update-site">

		<input type="hidden" name="id" value="<?php echo esc_attr( $id ) ?>" />

		<?php $wp_list_table->display(); ?>

	</form>

</div>
<?php

require( APP_ADMIN_PATH . '/admin-footer.php' );
