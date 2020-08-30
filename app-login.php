<?php
/**
 * User login page
 *
 * Handles authentication, registering, resetting passwords, forgot password,
 * and other user handling.
 *
 * @package App_Package
 */

// Alias namespaces.
use \AppNamespace\Includes as Includes;

// Make sure that the website management system bootstrap has run before continuing.
require( dirname( __FILE__ ) . '/app-load.php' );

// New instance of the User_Logging class.
$user_log = new Includes\User_Logging;

// Redirect to https login if forced to use SSL.
if ( force_ssl_admin() && ! is_ssl() ) {

	if ( 0 === strpos( $_SERVER['REQUEST_URI'], 'http' ) ) {
		wp_safe_redirect( set_url_scheme( $_SERVER['REQUEST_URI'], 'https' ) );
		exit();

	} else {
		wp_safe_redirect( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		exit();
	}
}

// New instance of the error message class.
$errors = new WP_Error();

if ( isset( $_REQUEST['action'] ) ) {
	$action = $_REQUEST['action'];
} else {
	$action = 'login';
}

if ( isset( $_GET['key'] ) ) {
	$action = 'resetpass';
}

// Validate action so as to default to the login screen.
if ( ! in_array( $action, [
		'postpass',
		'logout',
		'lostpassword',
		'retrievepassword',
		'resetpass',
		'rp',
		'register',
		'login',
		'confirmaction'
	], true )

&& false === has_filter( 'login_form_' . $action ) ) {
	$action = 'login';
}

nocache_headers();

header( 'Content-Type: ' . get_bloginfo( 'html_type' ) . '; charset=' . get_bloginfo( 'charset' ) );

// Move flag is set.
if ( defined( 'RELOCATE' ) && RELOCATE ) {

	if ( isset( $_SERVER['PATH_INFO'] ) && ( $_SERVER['PATH_INFO'] != $_SERVER['PHP_SELF'] ) ) {
		$_SERVER['PHP_SELF'] = str_replace( $_SERVER['PATH_INFO'], '', $_SERVER['PHP_SELF'] );
	}

	$url = dirname( set_url_scheme( 'http://' .  $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] ) );

	if ( $url != get_option( 'siteurl' ) ) {
		update_option( 'siteurl', $url );
	}
}

// Set a cookie now to see if they are supported by the browser.
$secure = ( 'https' === parse_url( wp_login_url(), PHP_URL_SCHEME ) );

setcookie( TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN, $secure );

if ( SITECOOKIEPATH != COOKIEPATH ) {
	setcookie( TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN, $secure );
}

if ( ! empty( $_GET['wp_lang'] ) ) {
	$lang = sanitize_text_field( $_GET['wp_lang'] );
} else {
	$lang = '';
}

$switched_locale = switch_to_locale( $lang );

/**
 * Fires when the login form is initialized.
 *
 * @since Previous 3.2.0
 */
do_action( 'login_init' );

/**
 * Fires before a specified login form action.
 *
 * The dynamic portion of the hook name, `$action`, refers to the action
 * that brought the visitor to the login form. Actions include 'postpass',
 * 'logout', 'lostpassword', etc.
 *
 * @since Previous 2.8.0
 */
do_action( "login_form_{$action}" );

$http_post     = ( 'POST' == $_SERVER['REQUEST_METHOD'] );
$interim_login = isset( $_REQUEST['interim-login'] );

switch ( $action ) {

	case 'postpass' :

		if ( ! array_key_exists( 'post_password', $_POST ) ) {

			wp_safe_redirect( wp_get_referer() );

			exit();
		}

		$hasher = new Includes\PasswordHash( 8, true );

		/**
		 * Filters the life span of the post password cookie.
		 *
		 * By default, the cookie expires 10 days from creation. To turn this
		 * into a session cookie, return 0.
		 *
		 * @since Previous 3.7.0
		 *
		 * @param int $expires The expiry time, as passed to setcookie().
		 */
		$expire  = apply_filters( 'post_password_expires', time() + 10 * DAY_IN_SECONDS );
		$referer = wp_get_referer();

		if ( $referer ) {
			$secure = ( 'https' === parse_url( $referer, PHP_URL_SCHEME ) );
		} else {
			$secure = false;
		}

		setcookie( 'wp-postpass_' . COOKIEHASH, $hasher->HashPassword( wp_unslash( $_POST['post_password'] ) ), $expire, COOKIEPATH, COOKIE_DOMAIN, $secure );

		if ( $switched_locale ) {
			restore_previous_locale();
		}

		wp_safe_redirect( wp_get_referer() );

		exit();

	case 'logout' :

		$user_log->logout_redirect();

		exit();

	case 'lostpassword' :
	case 'retrievepassword' :

		$user_log->password_assistance();
		break;

	case 'resetpass' :
	case 'rp' :

		$user_log->reset_password();

		break;

	case 'register' :

		if ( is_network() ) {
			/**
			 * Filters the network sign up URL.
			 *
			 * @since Previous 3.0.0
			 * @param string $sign_up_url The sign up URL.
			 */
			wp_redirect( apply_filters( 'wp_signup_location', network_site_url( 'app-signup.php' ) ) );

			exit;
		}

		if ( ! get_option( 'users_can_register' ) ) {

			wp_redirect( site_url( 'app-login.php?registration=disabled' ) );

			exit();
		}

		$user_login = '';
		$user_email = '';

		if ( $http_post ) {

			if ( isset( $_POST['user_login'] ) && is_string( $_POST['user_login'] ) ) {
				$user_login = $_POST['user_login'];
			}

			if ( isset( $_POST['user_email'] ) && is_string( $_POST['user_email'] ) ) {
				$user_email = wp_unslash( $_POST['user_email'] );
			}

			$errors = register_new_user( $user_login, $user_email );

			if ( ! is_wp_error( $errors ) ) {

				$redirect_to = ! empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : 'app-login.php?checkemail=registered';

				wp_safe_redirect( $redirect_to );

				exit();
			}
		}

		$registration_redirect = ! empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
		/**
		 * Filters the registration redirect URL.
		 *
		 * @since Previous 3.0.0
		 * @param string $registration_redirect The redirect destination URL.
		 */
		$redirect_to = apply_filters( 'registration_redirect', $registration_redirect );

		$user_log->login_header( __( 'Registration Form' ), '<p class="message register">' . __( 'Register For This Site' ) . '</p>', $errors );

		?>
		<form name="registerform" id="registerform" action="<?php echo esc_url( site_url( 'app-login.php?action=register', 'login_post' ) ); ?>" method="post" novalidate="novalidate">
			<p>
				<label for="user_login"><?php _e( 'Username' ) ?><br />
				<input type="text" name="user_login" id="user_login" class="input" value="<?php echo esc_attr( wp_unslash( $user_login ) ); ?>" size="20" /></label>
			</p>
			<p>
				<label for="user_email"><?php _e( 'Email' ) ?><br />
				<input type="email" name="user_email" id="user_email" class="input" value="<?php echo esc_attr( wp_unslash( $user_email ) ); ?>" size="25" /></label>
			</p>
			<?php
			/**
			 * Fires following the 'Email' field in the user registration form.
			 *
			 * @since Previous 2.1.0
			 */
			do_action( 'register_form' );

			?>
			<p id="reg_passmail"><?php _e( 'Registration confirmation will be emailed to you.' ); ?>
			<br /><input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" /></p>

			<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Register' ); ?>" /></p>

		</form>

		<p id="nav">
			<a href="<?php echo esc_url( wp_login_url() ); ?>"><?php _e( 'Log in' ); ?></a>
			<a href="<?php echo esc_url( app_lostpassword_url() ); ?>"><?php _e( 'Lost your password?' ); ?></a>
		</p>

		<?php

		$user_log->login_footer( 'user_login' );

		if ( $switched_locale ) {
			restore_previous_locale();
		}

		break;

	case 'confirmaction' :

		if ( ! isset( $_GET['request_id'] ) ) {
			wp_die( __( 'Invalid request.' ) );
		}

		$request_id = (int) $_GET['request_id'];

		if ( isset( $_GET['confirm_key'] ) ) {

			$key    = sanitize_text_field( wp_unslash( $_GET['confirm_key'] ) );
			$result = wp_validate_user_request_key( $request_id, $key );

		} else {
			$result = new WP_Error( 'invalid_key', __( 'Invalid key' ) );
		}

		if ( is_wp_error( $result ) ) {
			wp_die( $result );
		}

		/**
		 * Fires an action hook when the account action has been confirmed by the user.
		 *
		 * Using this you can assume the user has agreed to perform the action by
		 * clicking on the link in the confirmation email.
		 *
		 * After firing this action hook the page will redirect to wp-login a callback
		 * redirects or exits first.
		 *
		 * @param int $request_id Request ID.
		 */
		do_action( 'user_request_action_confirmed', $request_id );

		$message = app_privacy_account_request_confirmed_message( $request_id );

		$user_log->login_header( __( 'User action confirmed.' ), $message );

		$user_log->login_footer();

		exit;

	case 'login' :

		default :

			$secure_cookie   = '';
			$customize_login = isset( $_REQUEST['customize-login'] );

			if ( $customize_login ) {
				wp_enqueue_script( 'customize-base' );
			}

			// If the user wants ssl but the session is not ssl, force a secure cookie.
			if ( ! empty( $_POST['log'] ) && ! force_ssl_admin() ) {

				$user_name = sanitize_user( $_POST['log'] );
				$user      = get_user_by( 'login', $user_name );

				if ( ! $user && strpos( $user_name, '@' ) ) {
					$user = get_user_by( 'email', $user_name );
				}

				if ( $user ) {

					if ( get_user_option( 'use_ssl', $user->ID ) ) {
						$secure_cookie = true;
						force_ssl_admin( true );
					}
				}
			}

			if ( isset( $_REQUEST['redirect_to'] ) ) {

				$redirect_to = $_REQUEST['redirect_to'];

				if ( $secure_cookie && false !== strpos( $redirect_to, APP_ADMIN_DIR ) ) {
					$redirect_to = preg_replace( '|^http://|', 'https://', $redirect_to );
				}

			} else {
				$redirect_to = admin_url();
			}

			$reauth = empty( $_REQUEST['reauth'] ) ? false : true;

			$user = wp_signon( array(), $secure_cookie );

			if ( empty( $_COOKIE[ LOGGED_IN_COOKIE ] ) ) {

				if ( headers_sent() ) {

					$user = new WP_Error(
						'test_cookie', sprintf(
							__( '<strong>ERROR</strong>: Cookies are blocked due to unexpected output.' )
						)
					);

				} elseif ( isset( $_POST['testcookie'] ) && empty( $_COOKIE[ TEST_COOKIE ] ) ) {

					// If cookies are disabled we can't log in even with a valid user+pass.
					$user = new WP_Error(
						'test_cookie', sprintf(
							__( '<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must enable cookies to use this application.' )
						)
					);
				}
			}

			$requested_redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
			/**
			 * Filters the login redirect URL.
			 *
			 * @since Previous 3.0.0
			 * @param string           $redirect_to           The redirect destination URL.
			 * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
			 * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
			 */
			$redirect_to = apply_filters( 'login_redirect', $redirect_to, $requested_redirect_to, $user );

			if ( ! is_wp_error( $user ) && ! $reauth ) {

				if ( $interim_login ) {

					$message       = '<p class="message">' . __( 'You have logged in successfully.' ) . '</p>';
					$interim_login = 'success';

					$user_log->login_header( '', $message );

					?>
					</div>
					<?php

					// This action is documented in app-login.php.
					do_action( 'login_footer' );

					if ( $customize_login ) : ?>
						<script type="text/javascript">setTimeout( function(){ new wp.customize.Messenger({ url: '<?php echo wp_customize_url(); ?>', channel: 'login' } ).send( 'login' ) }, 1000 );</script>
					<?php endif; ?>
					</body></html>
					<?php

					exit;
				}

				if ( ( empty( $redirect_to ) || $redirect_to == APP_ADMIN_DIR . '/' || $redirect_to == admin_url() ) ) {

					/**
					 * If the user doesn't belong to a blog, send them to user admin.
					 * If the user can't edit posts, send them to their profile.
					 */
					if ( is_network() && ! get_active_blog_for_user( $user->ID ) && ! is_super_admin( $user->ID ) ) {
						$redirect_to = user_admin_url();

					} elseif ( is_network() && ! $user->has_cap( 'read' ) ) {
						$redirect_to = get_dashboard_url( $user->ID );

					} elseif ( !$user->has_cap( 'edit_posts' ) ) {
						$redirect_to = $user->has_cap( 'read' ) ? admin_url( 'profile.php' ) : home_url();
					}

					wp_redirect( $redirect_to );

					exit();
				}

				wp_safe_redirect( $redirect_to );

				exit();
			}

			$errors = $user;

			// Clear errors if loggedout is set.
			if ( ! empty( $_GET['loggedout'] ) || $reauth ) {
				$errors = new WP_Error();
			}

			if ( $interim_login ) {

				if ( ! $errors->get_error_code() ) {
					$errors->add( 'expired', __( 'Your session has expired. Please log in to continue where you left off.' ), 'message' );
				}

			} else {

				// Some parts of this script use the main login form to display a message.
				if ( isset( $_GET['loggedout'] ) && true == $_GET['loggedout'] ) {
					$errors->add( 'loggedout', __( 'You are now logged out.' ), 'message' );

				} elseif ( isset( $_GET['registration'] ) && 'disabled' == $_GET['registration'] ) {
					$errors->add( 'registerdisabled', __( 'User registration is currently not allowed.' ) );

				} elseif ( isset( $_GET['checkemail'] ) && 'confirm' == $_GET['checkemail'] ) {
					$errors->add( 'confirm', __( 'Check your email for the confirmation link.' ), 'message' );

				} elseif ( isset( $_GET['checkemail'] ) && 'newpass' == $_GET['checkemail'] ) {
					$errors->add( 'newpass', __( 'Check your email for your new password.' ), 'message' );

				} elseif ( isset( $_GET['checkemail'] ) && 'registered' == $_GET['checkemail'] ) {
					$errors->add( 'registered', __( 'Registration complete. Please check your email.' ), 'message' );

				} elseif ( strpos( $redirect_to, 'about.php?updated' ) ) {
					$errors->add( 'updated', __( '<strong>You have successfully updated the website management system.</strong> Please log back in to see what&#8217;s new.' ), 'message' );
				}
			}

			/**
			 * Filters the login page errors.
			 *
			 * @since Previous 3.6.0
			 * @param object $errors      WP Error object.
			 * @param string $redirect_to Redirect destination URL.
			 */
			$errors = apply_filters( 'wp_login_errors', $errors, $redirect_to );

			// Clear any stale cookies.
			if ( $reauth ) {
				wp_clear_auth_cookie();
			}

			$user_log->login_header( __( 'Log In' ), '', $errors );

			if ( isset( $_POST['log'] ) ) {
				$user_login = ( 'incorrect_password' == $errors->get_error_code() || 'empty_password' == $errors->get_error_code() ) ? esc_attr( wp_unslash( $_POST['log'] ) ) : '';
			}

			$rememberme = ! empty( $_POST['rememberme'] );

			if ( ! empty( $errors->errors ) ) {
				$aria_describedby_error = ' aria-describedby="login_error"';
			} else {
				$aria_describedby_error = '';
			}
		?>

		<form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'app-login.php', 'login_post' ) ); ?>" method="post">
			<p>
				<label for="user_login"><?php _e( 'Username or Email Address' ); ?><br />
				<input type="text" name="log" id="user_login"<?php echo $aria_describedby_error; ?> class="input" value="<?php echo esc_attr( $user_login ); ?>" size="20" /></label>
			</p>
			<p>
				<label for="user_pass"><?php _e( 'Password' ); ?><br />
				<input type="password" name="pwd" id="user_pass"<?php echo $aria_describedby_error; ?> class="input" value="" size="20" /></label>
			</p>
			<?php
			/**
			 * Fires following the 'Password' field in the login form.
			 *
			 * @since Previous 2.1.0
			 */
			do_action( 'login_form' );

			?>
			<p class="forgetmenot"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" <?php checked( $rememberme ); ?> /> <?php esc_html_e( 'Remember Me' ); ?></label></p>

			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Log In' ); ?>" />

				<?php if ( $interim_login ) { ?>
				<input type="hidden" name="interim-login" value="1" />

				<?php } else { ?>
				<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to); ?>" />

				<?php } ?>

				<?php if ( $customize_login ) : ?>
				<input type="hidden" name="customize-login" value="1" />

				<?php endif; ?>
				<input type="hidden" name="testcookie" value="1" />

			</p>

		</form>

		<?php if ( ! $interim_login ) {

		?>
		<p id="nav">
			<?php if ( ! isset( $_GET['checkemail'] ) || ! in_array( $_GET['checkemail'], [ 'confirm', 'newpass' ] ) ) :

				if ( get_option( 'users_can_register' ) ) :

					$registration_url = sprintf( '<a href="%s">%s</a>', esc_url( wp_registration_url() ), __( 'Register' ) );

					// This filter is documented in app-includes/general-template.php.
					echo apply_filters( 'register', $registration_url );

				endif;

				?>
				<a href="<?php echo esc_url( app_lostpassword_url() ); ?>"><?php _e( 'Lost your password?' ); ?></a>

			<?php endif; ?>
		</p>
		<?php } ?>

		<script type="text/javascript">
			function wp_attempt_focus() {

				setTimeout( function() {
					try {
					<?php if ( $user_login ) { ?>
						d = document.getElementById( 'user_pass' );
						d.value = '';

					<?php } else { ?>

						d = document.getElementById( 'user_login' );

						<?php if ( 'invalid_username' == $errors->get_error_code() ) { ?>

						if ( d.value != '' )
						d.value = '';
						<?php

						}
					} ?>
						d.focus();
						d.select();
					}
					catch(e) {}
				}, 200 );
			}

			<?php
			/**
			 * Filters whether to print the call to `wp_attempt_focus()` on the login screen.
			 *
			 * @since Previous 4.8.0
			 * @param bool $print Whether to print the function call. Default true.
			 */
			if ( apply_filters( 'enable_login_autofocus', true ) && ! $error ) { ?>
				wp_attempt_focus();
			<?php
			} ?>

			if ( typeof wpOnload=='function' )wpOnload();
			<?php
			if ( $interim_login ) { ?>
			( function() {
			try {
				var i, links = document.getElementsByTagName( 'a' );
				for ( i in links ) {
					if ( links[i].href )
						links[i].target = '_blank';
				}
			} catch(e) {}
			}() );
			<?php } ?>
		</script>

		<?php
		$user_log->login_footer();

		if ( $switched_locale ) {
			restore_previous_locale();
		}

		break;
		} // end action switch
