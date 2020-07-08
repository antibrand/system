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
	 * @access private
	 * @return self
	 */
	public function __construct() {}

	/**
	 * Output the login page header.
	 *
	 * @param string $title Optional. Login Page title to display in the `<title>` element.
	 *                      Default 'Log In'.
	 * @param string $message Optional. Message to display in header. Default empty.
	 * @param WP_Error $wp_error Optional. The error to pass. Default is a WP_Error instance.
	 */
	function login_header( $title = 'Log In', $message = '', $wp_error = null ) {

		global $error, $interim_login, $action;

		// Don't index any of these forms.
		add_action( 'login_head', 'wp_no_robots' );

		if ( ! is_wp_error( $wp_error ) ) {
			$wp_error = new \WP_Error();
		}

		$login_title = get_bloginfo( 'name', 'display' );

		// Switch the title direction for RTL languages.
		if ( is_rtl() ) {

			$login_title = sprintf(
				__( '%1$s &lsaquo; %2$s' ),
				$login_title,
				$title
			);

		} else {

			$login_title = sprintf(
				__( '%1$s &rsaquo; %2$s' ),
				$title,
				$login_title
			);
		}

		$login_title = apply_filters( 'login_title', $login_title, $title );

		?>
		<!doctype html>
		<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
			<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
			<title><?php echo $login_title; ?></title>

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
			$login_header_title = get_network()->site_name;

		} else {

			$login_header_url   = site_url();
			$login_header_title = get_bloginfo( 'name' );
		}

		/**
		 * Filters link URL of the header logo above login form.
		 *
		 * @since Previous 2.1.0
		 *
		 * @param string $login_header_url Login header logo URL.
		 */
		$login_header_url = apply_filters( 'login_headerurl', $login_header_url );

		/**
		 * Filters the title attribute of the header logo above login form.
		 *
		 * @since Previous 2.1.0
		 *
		 * @param string $login_header_title Login header logo title attribute.
		 */
		$login_header_title = apply_filters( 'login_headertitle', $login_header_title );

		/*
		* To match the URL/title set above, Multisite sites have the blog name,
		* while single sites get the header title.
		*/
		if ( is_multisite() ) {
			$login_header_text = get_bloginfo( 'name', 'display' );
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
		 *
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
		<div id="login">
			<h1><a href="<?php echo esc_url( $login_header_url ); ?>" title="<?php echo esc_attr( $login_header_title ); ?>" tabindex="-1"><?php echo $login_header_text; ?></a></h1>
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
	 * @param string $input_id Which input to auto-focus
	 */
	public function login_footer( $input_id = '' ) {

		global $interim_login;

		// Don't allow interim logins to navigate away from the page.
		if ( ! $interim_login ): ?>

		<p id="backtoblog">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php
				printf( _x( '&larr; Back to %s', 'site' ), get_bloginfo( 'title', 'display' ) );
			?>
			</a>
		</p>

		<?php the_privacy_policy_link( '<div class="privacy-policy-page-link">', '</div>' ); ?>

		<?php endif; ?>

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
		</body>
		</html>
		<?php
	}

	/**
	 * Handles sending password retrieval email to user.
	 *
	 * @return bool|WP_Error True: when finish. WP_Error on error
	 */
	public function retrieve_password() {

		$errors = new \WP_Error();

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
			wp_die( __( 'The email could not be sent.' ) . "<br />\n" . __( 'Possible reason: your host may have disabled the mail() function.' ) );
		}

		return true;
	}

	/**
	 * Logout remove stored post data
	 *
	 * This is not added by add_action( 'login_head' ) lin WordPress,
	 * but here it is removable by plugins.
	 */
	public function logout_remove_data() {

		$script = '<script>if("sessionStorage" in window){try{for(var key in sessionStorage){if(key.indexOf("wp-autosave-")!=-1){sessionStorage.removeItem(key)}}}catch(e){}};</script>';

		echo $script;
	}
}
