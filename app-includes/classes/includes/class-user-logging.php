<?php
/**
 * User logging class
 *
 * For logging registered users into websites and
 * for logging them out.
 *
 * @package App_Package
 * @subpackage Includes\Users
 * @since 1.0.0
 */

namespace AppNamespace\Includes;

/**
 * User logging class
 *
 * @since 1.0.0
 */
class User_Logging {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {}

	/**
	 * Output the login page header.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $title Optional. Login Page title to display in the `<title>` element.
	 *                       Default 'Log In'.
	 * @param  string $message Optional. Message to display in header. Default empty.
	 * @param  WP_Error $wp_error Optional. The error to pass. Default is a WP_Error instance.
	 */
	public function login_header( $login_title = '', $message = '', $wp_error = null ) {

		global $error, $interim_login, $action;

		if ( ! is_wp_error( $wp_error ) ) {
			$wp_error = new \WP_Error();
		}

		$site_title  = get_bloginfo( 'name', 'display' );
		$login_title = __( 'User login for' );

		// Switch the title direction for RTL languages.
		$login_title = sprintf(
			__( '%1$s %2$s' ),
			$login_title,
			$site_title
		);

		$login_title = apply_filters( 'login_title_tag', $login_title );

		// Get the identity image or white label logo.
		$app_get_logo = app_assets_url( 'images/app-icon.png' );

		// Conditional logo markup.
		if ( defined( 'APP_WEBSITE' ) && APP_WEBSITE ) {

			$app_icon = sprintf(
				'<a href="%1s"><img src="%2s" class="app-logo-image" alt="%3s" itemprop="logo" width="512" height="512"></a>',
				esc_url( APP_WEBSITE ),
				esc_attr( $app_get_logo ),
				esc_html( APP_NAME )
			);

		} else {

			$app_icon = sprintf(
				'<img src="%1s" class="app-logo-image" alt="%2s" itemprop="logo" width="512" height="512">',
				esc_attr( $app_get_logo ),
				esc_html( APP_NAME )
			);
		}

		?>
		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
			<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
			<meta name="robots" content="noindex,follow" />

			<title><?php echo esc_html( $login_title ); ?></title>

			<link rel="icon" href="<?php echo esc_attr( $app_get_logo ); ?>" />

			<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>

			<?php
			// Enqueue jQuery for form steps (prev/next).
			wp_print_scripts( 'jquery' ); ?>

			<?php wp_enqueue_style( 'login' );

			// Remove all stored post data on logging out.
			if ( 'loggedout' == $wp_error->get_error_code() ) {
				add_action( 'login_head', [ $this, 'logout_remove_data' ] );
			}

			/**
			 * Enqueue scripts and styles for the login page.
			 *
			 * @since Previous 3.1.0
			 */
			do_action( 'login_enqueue_scripts' );

			/**
			 * Fires in the login page header after scripts are enqueued.
			 *
			 * @since Previous 2.1.0
			 */
			do_action( 'login_head' );

			if ( is_multisite() ) {

				$login_header_url   = network_home_url();
				$login_header_title = __( 'User Login' );

			} else {

				$login_header_url   = site_url();
				$login_header_title = __( 'User Login' );
			}

			/**
			 * Filters link URL of the header logo above login form.
			 *
			 * @since Previous 2.1.0
			 * @param string $login_header_url Login header logo URL.
			 */
			$login_header_url = apply_filters( 'login_headerurl', $login_header_url );

			/**
			 * Filters the title attribute of the header logo above login form.
			 *
			 * @since Previous 2.1.0
			 * @param string $login_header_title Login header logo title attribute.
			 */
			$login_header_title = apply_filters( 'login_headertitle', $login_header_title );

			/*
			* To match the URL/title set above, Multisite sites have the blog name,
			* while single sites get the header title.
			*/
			if ( is_multisite() ) {
				$login_header_text = __( 'User Login' );
			} else {
				$login_header_text = $login_header_title;
			}

			$classes = array( 'login-action-' . $action, 'app-core-ui' );

			if ( is_rtl() ) {
				$classes[] = 'rtl';
			}

			if ( $interim_login ) {

				$classes[] = 'interim-login';
				?>
				<style type="text/css">html{background-color: transparent;}</style>
				<?php

				if ( 'success' ===  $interim_login ) {
					$classes[] = 'interim-login-success';
				}
			}
			$classes[] =' locale-' . sanitize_html_class( strtolower( str_replace( '_', '-', get_locale() ) ) );

			/**
			 * Filters the login page body classes.
			 *
			 * @since Previous 3.5.0
			 * @param array  $classes An array of body classes.
			 * @param string $action  The action that brought the visitor to the login page.
			 */
			$classes = apply_filters( 'login_body_class', $classes, $action );

			?>
		</head>
		<body class="login <?php echo esc_attr( implode( ' ', $classes ) ); ?>">

		<?php
		/**
		 * Fires in the login page header after the body tag is opened.
		 *
		 * @since Previous 4.6.0
		 */
		do_action( 'login_header' );

		?>

		<header class="login-header">
			<div class="login-identity">
				<div class="site-logo">
					<?php echo $app_icon; ?>
				</div>
				<div class="site-title-description">
					<p class="site-title">
						<a href="<?php echo esc_url( $login_header_url ); ?>" title="<?php echo esc_attr( $login_header_title ); ?>" tabindex="-1"><?php echo get_bloginfo( 'name' ); ?></a>
					</p>
					<p class="site-description"><?php echo get_bloginfo( 'description' ); ?></p>
				</div>
			</div>
		</header>

		<div class="login-wrap">

			<main class="login-content">

				<h1><?php echo $login_header_text; ?></h1>

		<?php

		unset( $login_header_url, $login_header_title );

		/**
		 * Filters the message to display above the login form.
		 *
		 * @since Previous 2.1.0
		 *
		 * @param string $message Login message text.
		 */
		$message = apply_filters( 'login_message', $message );

		if ( ! empty( $message ) ) {
			echo $message . "\n";
		}

		// In case a plugin uses $error rather than the $wp_errors object
		if ( ! empty( $error ) ) {

			$wp_error->add( 'error', $error );
			unset( $error );
		}

		if ( $wp_error->get_error_code() ) {

			$errors   = '';
			$messages = '';

			foreach ( $wp_error->get_error_codes() as $code ) {

				$severity = $wp_error->get_error_data( $code );

				foreach ( $wp_error->get_error_messages( $code ) as $error_message ) {

					if ( 'message' == $severity ) {
						$messages .= '	' . $error_message . "<br />\n";
					} else {
						$errors .= '	' . $error_message . "<br />\n";
					}
				}
			}

			if ( ! empty( $errors ) ) {
				/**
				 * Filters the error messages displayed above the login form.
				 *
				 * @since Previous 2.1.0
				 *
				 * @param string $errors Login error message.
				 */
				echo '<div id="login_error">' . apply_filters( 'login_errors', $errors ) . "</div>\n";
			}

			if ( ! empty( $messages ) ) {
				/**
				 * Filters instructional messages displayed above the login form.
				 *
				 * @since Previous 2.5.0
				 *
				 * @param string $messages Login messages.
				 */
				echo '<p class="message">' . apply_filters( 'login_messages', $messages ) . "</p>\n";
			}
		}
	}

	/**
	 * Outputs the footer for the login page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $input_id Which input to auto-focus
	 */
	public function login_footer( $input_id = '' ) {

		global $interim_login;

		$footer_message = sprintf(
			'<p>%1s %2s</p>',
			__( 'User login for' ),
			get_bloginfo( 'name' )
		);

		$footer_message = apply_filters( 'login_footer_message', $footer_message );

		// Don't allow interim logins to navigate away from the page.
		if ( ! $interim_login ): ?>

		<p id="backtoblog">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php
				printf( _x( 'Go to %s', 'site' ), get_bloginfo( 'title', 'display' ) );
			?>
			</a>
		</p>

		<?php the_privacy_policy_link( '<div class="privacy-policy-page-link">', '</div>' ); ?>

		<?php endif; ?>

			</main>
		</div>

		<?php if ( ! empty( $input_id ) ) :

		?>
		<script type="text/javascript">
			try{ document.getElementById( '<?php echo $input_id; ?>' ).focus(); }catch(e) {}
			if ( typeof wpOnload=='function' )wpOnload();
		</script>
		<?php endif; ?>

		<?php
		/**
		 * Fires in the login page footer.
		 *
		 * @since Previous 3.1.0
		 */
		do_action( 'login_footer' );

		?>
			<footer id="colophon" class="login-footer">
				<div class="footer-content">
					<?php echo $footer_message; ?>
				</div>
			</footer>
		</body>
		</html>
		<?php
	}

	/**
	 * Handles sending password retrieval email to user.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return bool|WP_Error True: when finish. WP_Error on error
	 */
	public function retrieve_password() {

		$errors = new \WP_Error();
		$user_data = '';

		if ( empty( $_POST['user_login'] ) || ! is_string( $_POST['user_login'] ) ) {

			$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Enter a username or email address.' ) );

		} elseif ( strpos( $_POST['user_login'], '@' ) ) {

			$user_data = get_user_by( 'email', trim( wp_unslash( $_POST['user_login'] ) ) );

			if ( empty( $user_data ) ) {
				$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: There is no user registered with that email address.' ) );
			}

		} else {

			$login     = trim( $_POST['user_login'] );
			$user_data = get_user_by( 'login', $login );
		}

		/**
		 * Fires before errors are returned from a password reset request.
		 *
		 * @since Previous 2.1.0
		 * @since Previous 4.4.0 Added the `$errors` parameter.
		 *
		 * @param WP_Error $errors A WP_Error object containing any errors generated
		 *                         by using invalid credentials.
		 */
		do_action( 'lostpassword_post', $errors );

		if ( $errors->get_error_code() ) {
			return $errors;
		}

		if ( ! $user_data ) {

			$errors->add( 'invalidcombo', __( '<strong>ERROR</strong>: Invalid username or email.' ) );

			return $errors;
		}

		// Redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
		$key        = get_password_reset_key( $user_data );

		if ( is_wp_error( $key ) ) {
			return $key;
		}

		if ( is_multisite() ) {
			$site_name = get_network()->site_name;

		} else {
			/*
			* The blogname option is escaped with esc_html on the way into the database
			* in sanitize_option we want to reverse this for the plain text arena of emails.
			*/
			$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}

		$message  = __( 'Someone has requested a password reset for the following account:' ) . "\r\n\r\n";
		$message .= sprintf( __( 'Site Name: %s' ), $site_name ) . "\r\n\r\n";
		$message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
		$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
		$message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
		$message .= '<' . network_site_url( "app-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";

		/* translators: Password reset email subject. %s: Site name */
		$title = sprintf( __( '[%s] Password Reset' ), $site_name );

		/**
		 * Filters the subject of the password reset email.
		 *
		 * @since Previous 2.8.0
		 * @since Previous 4.4.0 Added the `$user_login` and `$user_data` parameters.
		 *
		 * @param string  $title      Default email title.
		 * @param string  $user_login The username for the user.
		 * @param WP_User $user_data  WP_User object.
		 */
		$title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

		/**
		 * Filters the message body of the password reset mail.
		 *
		 * If the filtered message is empty, the password reset email will not be sent.
		 *
		 * @since Previous 2.8.0
		 * @since Previous 4.1.0 Added `$user_login` and `$user_data` parameters.
		 *
		 * @param string  $message    Default mail message.
		 * @param string  $key        The activation key.
		 * @param string  $user_login The username for the user.
		 * @param WP_User $user_data  WP_User object.
		 */
		$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

		if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {

			$die = sprintf(
				'<p>%1s</p>',
				__( 'The password email could not be sent.' )
			);

			$die .= sprintf(
				'<p>%1s</p>',
				__( 'A possible reason is that your host may have disabled the mail() function.' )
			);

			wp_die(
				$die,
				__( 'Email Error' ),
				[
					'back_link' => true
				]
			);
		}

		return true;
	}

	/**
	 * Logout redirection
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function logout_redirect() {

		check_admin_referer( 'log-out' );

		$user = wp_get_current_user();

		wp_logout();

		if ( ! empty( $_REQUEST['redirect_to'] ) ) {
			$redirect_to = $requested_redirect_to = $_REQUEST['redirect_to'];

		} else {

			$redirect_to = 'app-login.php?loggedout=true';
			$requested_redirect_to = '';
		}

		if ( $switched_locale ) {
			restore_previous_locale();
		}

		/**
		 * Filters the log out redirect URL.
		 *
		 * @since 1.0.0
		 * @param string  $redirect_to           The redirect destination URL.
		 * @param string  $requested_redirect_to The requested redirect destination URL passed as a parameter.
		 * @param WP_User $user                  The WP_User object for the user that's logging out.
		 */
		$redirect_to = apply_filters( 'logout_redirect', $redirect_to, $requested_redirect_to, $user );

		wp_safe_redirect( $redirect_to );

	}

	/**
	 * Retrieve password
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function password_assistance() {

		if ( ! empty( $_GET['wp_lang'] ) ) {
			$lang = sanitize_text_field( $_GET['wp_lang'] );
		} else {
			$lang = '';
		}

		$errors    = '';
		$http_post = ( 'POST' == $_SERVER['REQUEST_METHOD'] );
		$switched_locale = switch_to_locale( $lang );

		if ( $http_post ) {

			$errors = $this->retrieve_password();

			if ( ! is_wp_error( $errors ) ) {

				$redirect_to = ! empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : 'app-login.php?checkemail=confirm';

				wp_safe_redirect( $redirect_to );

				exit();
			}
		}

		if ( isset( $_GET['error'] ) ) {

			if ( 'invalidkey' == $_GET['error'] ) {
				$errors->add( 'invalidkey', __( 'Your password reset link appears to be invalid. Please request a new link below.' ) );

			} elseif ( 'expiredkey' == $_GET['error'] ) {
				$errors->add( 'expiredkey', __( 'Your password reset link has expired. Please request a new link below.' ) );
			}
		}

		$lostpassword_redirect = ! empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
		/**
		 * Filters the URL redirected to after submitting the lostpassword/retrievepassword form.
		 *
		 * @since Previous 3.0.0
		 *
		 * @param string $lostpassword_redirect The redirect destination URL.
		 */
		$redirect_to = apply_filters( 'lostpassword_redirect', $lostpassword_redirect );

		/**
		 * Fires before the lost password form.
		 *
		 * @since Previous 1.5.1
		 */
		do_action( 'lost_password' );

		$this->login_header( __( 'Lost Password' ), '<p class="message">' . __( 'Please enter your username or email address. You will receive a link to create a new password via email.' ) . '</p>', $errors );

		$user_login = '';

		if ( isset( $_POST['user_login'] ) && is_string( $_POST['user_login'] ) ) {
			$user_login = wp_unslash( $_POST['user_login'] );
		}

		?>

		<form name="lostpasswordform" id="lostpasswordform" action="<?php echo esc_url( network_site_url( 'app-login.php?action=lostpassword', 'login_post' ) ); ?>" method="post">
			<p>
				<label for="user_login" ><?php _e( 'Username or Email Address' ); ?><br />
				<input type="text" name="user_login" id="user_login" class="input" value="<?php echo esc_attr( $user_login ); ?>" size="20" /></label>
			</p>
			<?php
			/**
			 * Fires inside the lostpassword form tags, before the hidden fields.
			 *
			 * @since Previous 2.1.0
			 */
			do_action( 'lostpassword_form' );

			?>
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />

			<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Get New Password' ); ?>" /></p>

		</form>

		<p id="nav">
			<a href="<?php echo esc_url( wp_login_url() ); ?>"><?php _e( 'Log in' ) ?></a>

			<?php
			if ( get_option( 'users_can_register' ) ) :
				$registration_url = sprintf( '<a href="%s">%s</a>', esc_url( wp_registration_url() ), __( 'Register' ) );

				/** This filter is documented in wp-includes/general-template.php */
				echo apply_filters( 'register', $registration_url );
			endif;
			?>
		</p>

		<?php

		$this->login_footer( 'user_login' );

		if ( $switched_locale ) {
			restore_previous_locale();
		}

	}

	/**
	 * Retrieve password
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function reset_password() {

		list( $rp_path ) = explode( '?', wp_unslash( $_SERVER['REQUEST_URI'] ) );

		$rp_cookie = 'wp-resetpass-' . COOKIEHASH;

		if ( isset( $_GET['key'] ) ) {

			$value = sprintf( '%s:%s', wp_unslash( $_GET['login'] ), wp_unslash( $_GET['key'] ) );
			setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
			wp_safe_redirect( remove_query_arg( [ 'key', 'login' ] ) );

			exit;
		}

		if ( isset( $_COOKIE[ $rp_cookie ] ) && 0 < strpos( $_COOKIE[ $rp_cookie ], ':' ) ) {

			list( $rp_login, $rp_key ) = explode( ':', wp_unslash( $_COOKIE[ $rp_cookie ] ), 2 );
			$user = check_password_reset_key( $rp_key, $rp_login );

			if ( isset( $_POST['pass1'] ) && ! hash_equals( $rp_key, $_POST['rp_key'] ) ) {
				$user = false;
			}

		} else {
			$user = false;
		}

		if ( ! $user || is_wp_error( $user ) ) {

			setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );

			if ( $user && $user->get_error_code() === 'expired_key' ) {
				wp_redirect( site_url( 'app-login.php?action=lostpassword&error=expiredkey' ) );
			} else {
				wp_redirect( site_url( 'app-login.php?action=lostpassword&error=invalidkey' ) );
			}

			exit;
		}

		$errors = new WP_Error();

		if ( isset( $_POST['pass1'] ) && $_POST['pass1'] != $_POST['pass2'] ) {
			$errors->add( 'password_reset_mismatch', __( 'The passwords do not match.' ) );
		}

		/**
		 * Fires before the password reset procedure is validated.
		 *
		 * @since Previous 3.5.0
		 *
		 * @param object           $errors WP Error object.
		 * @param WP_User|WP_Error $user   WP_User object if the login and reset key match. WP_Error object otherwise.
		 */
		do_action( 'validate_password_reset', $errors, $user );

		if ( ( ! $errors->get_error_code() ) && isset( $_POST['pass1'] ) && ! empty( $_POST['pass1'] ) ) {

			reset_password( $user, $_POST['pass1'] );
			setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );

			$this->login_header( __( 'Password Reset' ), '<p class="message reset-pass">' . __( 'Your password has been reset.' ) . ' <a href="' . esc_url( wp_login_url() ) . '">' . __( 'Log in' ) . '</a></p>' );

			$this->login_footer();

			exit;
		}

		wp_enqueue_script( 'utils' );
		wp_enqueue_script( 'user-profile' );

		$this->login_header( __( 'Reset Password' ), '<p class="message reset-pass">' . __( 'Enter your new password below.' ) . '</p>', $errors );

		?>
		<form name="resetpassform" id="resetpassform" action="<?php echo esc_url( network_site_url( 'app-login.php?action=resetpass', 'login_post' ) ); ?>" method="post" autocomplete="off">

			<input type="hidden" id="user_login" value="<?php echo esc_attr( $rp_login ); ?>" autocomplete="off" />

			<div class="user-pass1-wrap">

				<p>
					<label for="pass1"><?php _e( 'New password' ) ?></label>
				</p>

				<div class="wp-pwd">
					<div class="password-input-wrapper">
						<input type="password" data-reveal="1" data-pw="<?php echo esc_attr( wp_generate_password( 16 ) ); ?>" name="pass1" id="pass1" class="input password-input" size="24" value="" autocomplete="off" aria-describedby="pass-strength-result" />
						<span class="button button-secondary wp-hide-pw hide-if-no-js">
							<span class="dashicons dashicons-hidden"></span>
						</span>
					</div>
					<div id="pass-strength-result" class="hide-if-no-js" aria-live="polite"><?php _e( 'Strength indicator' ); ?></div>
				</div>
				<div class="password-weak">
					<label>
						<input type="checkbox" name="pw_weak" class="pw-checkbox" />
						<?php _e( 'Confirm use of weak password' ); ?>
					</label>
				</div>
			</div>

			<p class="user-pass2-wrap">
				<label for="pass2"><?php _e( 'Confirm new password' ) ?></label><br />
				<input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" />
			</p>

			<p class="description indicator-hint"><?php echo wp_get_password_hint(); ?></p>

			<?php
			/**
			 * Fires following the 'Strength indicator' meter in the user password reset form.
			 *
			 * @since Previous 3.9.0
			 * @param WP_User $user User object of the user whose password is being reset.
			 */
			do_action( 'resetpass_form', $user );

			?>
			<input type="hidden" name="rp_key" value="<?php echo esc_attr( $rp_key ); ?>" />

			<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Reset Password' ); ?>" /></p>

		</form>

		<p id="nav">
			<a href="<?php echo esc_url( wp_login_url() ); ?>"><?php _e( 'Log in' ); ?></a>

			<?php
			if ( get_option( 'users_can_register' ) ) :
				$registration_url = sprintf( '<a href="%s">%s</a>', esc_url( wp_registration_url() ), __( 'Register' ) );

				/** This filter is documented in wp-includes/general-template.php */
				echo apply_filters( 'register', $registration_url );
			endif;
			?>
		</p>

		<?php
		$this->login_footer( 'user_pass' );

		if ( $switched_locale ) {
			restore_previous_locale();
		}

	}

	/**
	 * Logout remove stored post data
	 *
	 * This is not added by add_action( 'login_head' ) lin WordPress,
	 * but here it is removable by plugins.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Returns JavaScript markup.
	 */
	public function logout_remove_data() {

		$script = '<script>if("sessionStorage" in window){try{for(var key in sessionStorage){if(key.indexOf("wp-autosave-")!=-1){sessionStorage.removeItem(key)}}}catch(e){}};</script>';

		echo $script;
	}
}
