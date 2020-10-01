<?php
/**
 * New User Administration Screen.
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
$page = Backend\Admin_Account_New :: instance();

// Page identification.
$parent_file = $page->parent;
$screen      = $page->screen();
$title       = $page->title();
$description = $page->description();

if ( is_network() ) {
	add_filter( 'wpmu_signup_user_notification_email', 'admin_created_user_email' );
}

if ( isset( $_REQUEST['action'] ) && 'adduser' == $_REQUEST['action'] ) {

	check_admin_referer( 'add-user', '_wpnonce_add-user' );

	$user_details = null;
	$user_email   = wp_unslash( $_REQUEST['email'] );

	if ( false !== strpos( $user_email, '@' ) ) {
		$user_details = get_user_by( 'email', $user_email );
	} else {
		if ( current_user_can( 'manage_network_users' ) ) {
			$user_details = get_user_by( 'login', $user_email );
		} else {
			wp_redirect( add_query_arg( [ 'update' => 'enter_email' ], 'user-new.php' ) );
			die();
		}
	}

	if ( ! $user_details ) {
		wp_redirect( add_query_arg( [ 'update' => 'does_not_exist' ], 'user-new.php' ) );

		die();
	}

	if ( ! current_user_can( 'promote_user', $user_details->ID ) ) {
		wp_die(
			'<h1>' . __( 'You need a higher level of permission.' ) . '</h1>' .
			'<p>' . __( 'Sorry, you are not allowed to add users to this network.' ) . '</p>',
			403
		);
	}

	// Adding an existing user to this blog.
	$new_user_email = $user_details->user_email;
	$redirect       = 'user-new.php';
	$username       = $user_details->user_login;
	$user_id        = $user_details->ID;

	if ( $username != null && array_key_exists( $blog_id, get_blogs_of_user( $user_id ) ) ) {
		$redirect = add_query_arg( [ 'update' => 'addexisting' ], 'user-new.php' );
	} else {

		if ( isset( $_POST[ 'noconfirmation' ] ) && current_user_can( 'manage_network_users' ) ) {
			$result = add_existing_user_to_blog( [ 'user_id' => $user_id, 'role' => $_REQUEST['role'] ] );

			if ( ! is_wp_error( $result ) ) {
				$redirect = add_query_arg( [ 'update' => 'addnoconfirmation', 'user_id' => $user_id ], 'user-new.php' );
			} else {
				$redirect = add_query_arg( [ 'update' => 'could_not_add' ], 'user-new.php' );
			}

		} else {

			$newuser_key = wp_generate_password( 20, false );

			add_option( 'new_user_' . $newuser_key, [ 'user_id' => $user_id, 'email' => $user_details->user_email, 'role' => $_REQUEST['role'] ] );

			$roles = get_editable_roles();
			$role  = $roles[ $_REQUEST['role'] ];

			/**
			 * Fires immediately after a user is invited to join a site, but before the notification is sent.
			 *
			 * @since Previous 4.4.0
			 * @param int    $user_id     The invited user's ID.
			 * @param array  $role        The role of invited user.
			 * @param string $newuser_key The key of the invitation.
			 */
			do_action( 'invite_user', $user_id, $role, $newuser_key );

			$switched_locale = switch_to_locale( get_user_locale( $user_details ) );

			$message = __( 'Hi,

You\'ve been invited to join \'%1$s\' at
%2$s with the role of %3$s.

Please click the following link to confirm the invite:
%4$s' );
			wp_mail( $new_user_email, sprintf( __( '[%s] Joining confirmation' ), wp_specialchars_decode( get_option( 'blogname' ) ) ), sprintf( $message, get_option( 'blogname' ), home_url(), wp_specialchars_decode( translate_user_role( $role['name'] ) ), home_url( "/newbloguser/$newuser_key/" ) ) );

			if ( $switched_locale ) {
				restore_previous_locale();
			}

			$redirect = add_query_arg( [ 'update' => 'add' ], 'user-new.php' );
		}
	}

	wp_redirect( $redirect );

	die();

} elseif ( isset( $_REQUEST['action'] ) && 'createuser' == $_REQUEST['action'] ) {

	check_admin_referer( 'create-user', '_wpnonce_create-user' );

	if ( ! current_user_can( 'create_users' ) ) {
		wp_die(
			'<h1>' . __( 'You need a higher level of permission.' ) . '</h1>' .
			'<p>' . __( 'Sorry, you are not allowed to create users.' ) . '</p>',
			403
		);
	}

	if ( ! is_network() ) {

		$user_id = edit_user();

		if ( is_wp_error( $user_id ) ) {
			$add_user_errors = $user_id;

		} else {

			if ( current_user_can( 'list_users' ) ) {
				$redirect = 'users.php?update=add&id=' . $user_id;
			} else {
				$redirect = add_query_arg( 'update', 'add', 'user-new.php' );
			}

			wp_redirect( $redirect );

			die();
		}

	} else {

		// Adding a new user to this site.
		$new_user_email = wp_unslash( $_REQUEST['email'] );
		$user_details   = wpmu_validate_user_signup( $_REQUEST['user_login'], $new_user_email );

		if ( is_wp_error( $user_details['errors'] ) && ! empty( $user_details['errors']->errors ) ) {
			$add_user_errors = $user_details['errors'];
		} else {

			/**
			 * Filters the user_login, also known as the username, before it is added to the site.
			 *
			 * @since Previous 2.0.3
			 * @param string $user_login The sanitized username.
			 */
			$new_user_login = apply_filters( 'pre_user_login', sanitize_user( wp_unslash( $_REQUEST['user_login'] ), true ) );

			if ( isset( $_POST['noconfirmation'] ) && current_user_can( 'manage_network_users' ) ) {

				// Disable confirmation email.
				add_filter( 'wpmu_signup_user_notification', '__return_false' );

				// Disable welcome email.
				add_filter( 'wpmu_welcome_user_notification', '__return_false' );
			}

			wpmu_signup_user( $new_user_login, $new_user_email, [ 'add_to_blog' => get_current_blog_id(), 'new_role' => $_REQUEST['role'] ] );
			if ( isset( $_POST[ 'noconfirmation' ] ) && current_user_can( 'manage_network_users' ) ) {

				$key = $wpdb->get_var( $wpdb->prepare( "SELECT activation_key FROM {$wpdb->signups} WHERE user_login = %s AND user_email = %s", $new_user_login, $new_user_email ) );

				$new_user = wpmu_activate_signup( $key );

				if ( is_wp_error( $new_user ) ) {
					$redirect = add_query_arg( [ 'update' => 'addnoconfirmation' ], 'user-new.php' );
				} elseif ( ! is_user_member_of_blog( $new_user['user_id'] ) ) {
					$redirect = add_query_arg( [ 'update' => 'created_could_not_add' ], 'user-new.php' );
				} else {
					$redirect = add_query_arg( [ 'update' => 'addnoconfirmation', 'user_id' => $new_user['user_id'] ], 'user-new.php' );
				}
			} else {
				$redirect = add_query_arg( [ 'update' => 'newuserconfirmation' ], 'user-new.php' );
			}

			wp_redirect( $redirect );

			die();
		}
	}
}

$do_both = false;

if ( is_network() && current_user_can( 'promote_users' ) && current_user_can( 'create_users' ) ) {
	$do_both = true;
}

/**
 * Filters whether to enable user auto-complete for non-super admins in network.
 *
 * @since Previous 3.4.0
 * @param bool $enable Whether to enable auto-complete for non-super admins. Default false.
 */
if ( is_network() && current_user_can( 'promote_users' ) && ! wp_is_large_network( 'users' )
	&& ( current_user_can( 'manage_network_users' ) || apply_filters( 'autocomplete_users_for_site_admins', false ) )
) {
	wp_enqueue_script( 'user-suggest' );
}

// Get the admin page header.
include( APP_VIEWS_PATH . '/backend/header/admin-header.php' );

if ( isset( $_GET['update'] ) ) {

	$messages = [];

	if ( is_network() ) {

		$edit_link = '';

		if ( ( isset( $_GET['user_id'] ) ) ) {

			$user_id_new = absint( $_GET['user_id'] );

			if ( $user_id_new ) {
				$edit_link = esc_url( add_query_arg( 'wp_http_referer', urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), get_edit_user_link( $user_id_new ) ) );
			}
		}

		switch ( $_GET['update'] ) {

			case "newuserconfirmation":
				$messages[] = __( 'Invitation email sent to new user. A confirmation link must be clicked before their account is created.' );
				break;
			case "add":
				$messages[] = __( 'Invitation email sent to user. A confirmation link must be clicked for them to be added to your site.' );
				break;
			case "addnoconfirmation":
				if ( empty( $edit_link ) ) {
					$messages[] = __( 'User has been added to your site.' );
				} else {
					/* translators: %s: edit page url */
					$messages[] = sprintf( __( 'User has been added to your site. <a href="%s">Edit user</a>' ), $edit_link );
				}
				break;
			case "addexisting":
				$messages[] = __( 'That user is already a member of this site.' );
				break;
			case "could_not_add":
				$add_user_errors = new WP_Error( 'could_not_add', __( 'That user could not be added to this site.' ) );
				break;
			case "created_could_not_add":
				$add_user_errors = new WP_Error( 'created_could_not_add', __( 'User has been created, but could not be added to this site.' ) );
				break;
			case "does_not_exist":
				$add_user_errors = new WP_Error( 'does_not_exist', __( 'The requested user does not exist.' ) );
				break;
			case "enter_email":
				$add_user_errors = new WP_Error( 'enter_email', __( 'Please enter a valid email address.' ) );
				break;
		}
	} else {
		if ( 'add' == $_GET['update'] )
			$messages[] = __( 'User added.' );
	}
}
?>
<div class="wrap">

	<h1><?php echo esc_html( $title ); ?></h1>
	<?php echo $description; ?>

<?php if ( isset( $errors ) && is_wp_error( $errors ) ) : ?>
	<div class="error">
		<ul>
		<?php
			foreach ( $errors->get_error_messages() as $err )
				echo "<li>$err</li>\n";
		?>
		</ul>
	</div>
<?php endif;

if ( ! empty( $messages ) ) {
	foreach ( $messages as $msg ) {
		echo '<div id="message" class="updated notice is-dismissible"><p>' . $msg . '</p></div>';
	}
} ?>

<?php if ( isset( $add_user_errors ) && is_wp_error( $add_user_errors ) ) { ?>
	<div class="error">
		<?php
			foreach ( $add_user_errors->get_error_messages() as $message ) {
				echo "<p>$message</p>";
			}
		?>
	</div>
<?php } ?>
<div id="ajax-response"></div>

<?php
if ( is_network() && current_user_can( 'promote_users' ) ) {
	if ( $do_both )
		echo '<h2 id="add-existing-user">' . __( 'Add Existing User' ) . '</h2>';
	if ( ! current_user_can( 'manage_network_users' ) ) {
		echo '<p>' . __( 'Enter the email address of an existing user on this network to invite them to this site. That person will be sent an email asking them to confirm the invite.' ) . '</p>';
		$label = __( 'Email' );
		$type  = 'email';
	} else {
		echo '<p>' . __( 'Enter the email address or username of an existing user on this network to invite them to this site. That person will be sent an email asking them to confirm the invite.' ) . '</p>';
		$label = __( 'Email or Username' );
		$type  = 'text';
	}
?>
<form method="post" name="adduser" id="adduser" class="validate" novalidate="novalidate" <?php do_action( 'user_new_form_tag' ); ?>>

	<input name="action" type="hidden" value="adduser" />
	<?php wp_nonce_field( 'add-user', '_wpnonce_add-user' ) ?>

	<p class="form-field form-required"><label for="adduser-email"><?php echo $label; ?></label>
	<br /><input name="email" type="<?php echo $type; ?>" id="adduser-email" class="wp-suggest-user" value="" /></p>

	<p class="form-field"><label for="adduser-role"><?php _e( 'Role' ); ?></label>
	<br /><select name="role" id="adduser-role">
		<?php wp_dropdown_roles( get_option( 'default_role' ) ); ?>
	</select></p>

	<?php if ( current_user_can( 'manage_network_users' ) ) { ?>

	<h3><?php _e( 'Skip Confirmation Email' ); ?></h3>

	<p>
		<label for="adduser-noconfirmation"><input type="checkbox" name="noconfirmation" id="adduser-noconfirmation" value="1" />
			<?php _e( 'Add the user without sending an email that requires their confirmation.' ); ?>
		</label>
	</p>

	<?php }

	/**
	 * Fires at the end of the new user form.
	 *
	 * Passes a contextual string to make both types of new user forms
	 * uniquely targetable. Contexts are 'add-existing-user' (network),
	 * and 'add-new-user' (single site and network admin).
	 *
	 * @since Previous 3.7.0
	 *
	 * @param string $type A contextual string specifying which type of new user form the hook follows.
	 */
	do_action( 'user_new_form', 'add-existing-user' );

	submit_button( __( 'Add Existing User' ), 'primary', 'adduser', true, [ 'id' => 'addusersub' ] ); ?>
</form>
<?php
} // is_network()

if ( current_user_can( 'create_users' ) ) {

	if ( $do_both ) {
		echo '<h2 id="create-new-user">' . __( 'Add New User' ) . '</h2>';
	}
?>
<form method="post" name="createuser" id="createuser" class="validate" novalidate="novalidate"<?php do_action( 'user_new_form_tag' ); ?>>

	<input name="action" type="hidden" value="createuser" />
	<?php wp_nonce_field( 'create-user', '_wpnonce_create-user' );

// Load up the passed data, else set to a default.
$creating = isset( $_POST['createuser'] );

if ( $creating && isset( $_POST['user_login'] ) ) {
	$new_user_login = wp_unslash( $_POST['user_login'] );
} else {
	$new_user_login = '';
}

if ( $creating && isset( $_POST['first_name'] ) ) {
	$new_user_firstname = wp_unslash( $_POST['first_name'] );
} else {
	$new_user_firstname = '';
}

if ( $creating && isset( $_POST['last_name'] ) ) {
	$new_user_lastname = wp_unslash( $_POST['last_name'] );
} else {
	$new_user_lastname = '';
}

if ( $creating && isset( $_POST['email'] ) ) {
	$new_user_email = wp_unslash( $_POST['email'] );
} else {
	$new_user_email = '';
}

if ( $creating && isset( $_POST['url'] ) ) {
	$new_user_uri = wp_unslash( $_POST['url'] );
} else {
	$new_user_uri = '';
}

if ( $creating && isset( $_POST['role'] ) ) {
	$new_user_role = wp_unslash( $_POST['role'] );
} else {
	$new_user_role = '';
}

if ( $creating && ! isset( $_POST['send_user_notification'] ) ) {
	$new_user_send_notification = false;
} else {
	$new_user_send_notification = true;
}

if ( $creating && isset( $_POST['noconfirmation'] ) ) {
	$new_user_ignore_pass = wp_unslash( $_POST['noconfirmation'] );
} else {
	$new_user_ignore_pass = '';
}

?>
	<fieldset id="new-account-login-info">

		<legend class="screen-reader-text"><?php _e( 'Login Information' ); ?></legend>

		<p class="form-field form-required"><label for="user_login"><?php _e( 'Username' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
		<br /><input name="user_login" type="text" id="user_login" value="<?php echo esc_attr( $new_user_login ); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" /></p>

		<p class="form-field form-required"><label for="email"><?php _e( 'Email' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
		<br /><input name="email" type="email" id="email" value="<?php echo esc_attr( $new_user_email ); ?>" /></p>
	</fieldset>

	<?php if ( ! is_network() ) { ?>
	<fieldset id="new-account-login-info">

		<legend class="screen-reader-text"><?php _e( 'User Information' ); ?></legend>

		<p class="form-field"><label for="first_name"><?php _e( 'First Name' ); ?> </label>
		<br /><input name="first_name" type="text" id="first_name" value="<?php echo esc_attr( $new_user_firstname ); ?>" /></p>

		<p class="form-field"><label for="last_name"><?php _e( 'Last Name' ); ?> </label>
		<br /><input name="last_name" type="text" id="last_name" value="<?php echo esc_attr( $new_user_lastname ); ?>" /></p>

		<p class="form-field"><label for="url"><?php _e( 'Website' ); ?></label>
		<br /><input name="url" type="url" id="url" class="code" value="<?php echo esc_attr( $new_user_uri ); ?>" /></p>

		<p class="form-field form-required user-pass1-wrap"><label for="pass1"><?php _e( 'Password' ); ?> <span class="description hide-if-js"><?php _e( '(required)' ); ?></span></label></p>

		<div class="form-field form-required user-pass1-wrap">
			<input class="hidden" value=" " /><!-- #24364 workaround -->
			<button type="button" class="button wp-generate-pw hide-if-no-js"><?php _e( 'Show password' ); ?></button>
			<div class="wp-pwd hide-if-js">
				<?php $initial_password = wp_generate_password( 24 ); ?>
				<span class="password-input-wrapper">
					<input type="password" name="pass1" id="pass1" class="regular-text" autocomplete="off" data-reveal="1" data-pw="<?php echo esc_attr( $initial_password ); ?>" aria-describedby="pass-strength-result" />
				</span>
				<button type="button" class="button wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Hide password' ); ?>">
					<span class="dashicons dashicons-hidden"></span>
					<span class="text"><?php _e( 'Hide' ); ?></span>
				</button>
				<button type="button" class="button wp-cancel-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Cancel password change' ); ?>">
					<span class="text"><?php _e( 'Cancel' ); ?></span>
				</button>
				<div style="display:none" id="pass-strength-result" aria-live="polite"></div>
			</div>
		</div>

		<p class="form-field form-required user-pass2-wrap hide-if-js"><label for="pass2"><?php _e( 'Repeat Password' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
		<br /><input name="pass2" type="password" id="pass2" autocomplete="off" /></p>

		<p class="password-weak"><label for="pw_weak"><?php _e( 'Confirm Password' ); ?></label>
		<br /><input type="checkbox" name="pw_weak" class="pw-checkbox" /> <?php _e( 'Confirm the use of this password' ); ?></p>

		<p><label for="send_user_notification"><?php _e( 'Send User Notification' ); ?></label>
		<br /><input type="checkbox" name="send_user_notification" id="send_user_notification" value="1" <?php checked( $new_user_send_notification ); ?> /> <?php _e( 'Send the new user an email about their account.' ); ?></p>

		<p><label for="role"><?php _e( 'Role' ); ?></label>
			<br />
			<select name="role" id="role">
				<?php
				if ( ! $new_user_role ) {
					$new_user_role = ! empty( $current_role ) ? $current_role : get_option( 'default_role' );
				}
				wp_dropdown_roles( $new_user_role );
				?>
			</select>
		</p>

	<?php } // ! is_network ?>
	<?php if ( is_network() && current_user_can( 'manage_network_users' ) ) { ?>

		<p><label for="noconfirmation"><?php _e( 'Skip Confirmation Email' ); ?></label>
		<br /><input type="checkbox" name="noconfirmation" id="noconfirmation" value="1" <?php checked( $new_user_ignore_pass ); ?> /> <?php _e( 'Add the user without sending an email that requires their confirmation.' ); ?></p>
	<?php } ?>

	</fieldset>

<?php
/** This action is documented in APP_ADMIN_DIR/user-new.php */
do_action( 'user_new_form', 'add-new-user' );
?>

<?php submit_button( __( 'Add New User' ), 'primary', 'createuser', true, [ 'id' => 'createusersub' ] ); ?>

</form>
<?php } // current_user_can( 'create_users' ) ?>
</div>
<?php

// Get the admin page footer.
include( APP_VIEWS_PATH . '/backend/footer/admin-footer.php' );
