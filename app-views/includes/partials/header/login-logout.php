<?php
/**
 * Header for login and logout pages
 *
 * @package App_Package
 * @subpackage Administration
 */

// HTML direction.
if ( is_rtl() ) {
	$direction = ' dir="rtl"';
} else {
	$direction = null;
}

// Body classes.
$body_classes = 'app-core-ui';
if ( is_rtl() ) {
	$body_classes .= 'rtl';
}

// Define a name of the website management system.
if ( APP_NAME ) {
	$app_name = APP_NAME;
} else {
	$app_name = __( 'system' );
}

if ( APP_TAGLINE ) {
	$app_tagline = APP_TAGLINE;
} else {
	$app_tagline = __( 'generic, white-label website management' );
}

// Get the identity image or white label logo.
$app_get_logo = dirname( dirname( dirname( $_SERVER['PHP_SELF'] ) ) ) . '/app-assets/images/app-icon.png';

// Link for the logo image.
$app_link = APP_WEBSITE;

// Conditional logo markup.
if ( APP_WEBSITE ) {

	$app_logo = sprintf(
		'<a href="%1s"><img src="%2s" class="app-logo-image" alt="%3s" itemprop="logo" width="512" height="512"></a>',
		APP_WEBSITE,
		$app_get_logo,
		APP_NAME
	);

} else {

	$app_logo = sprintf(
		'<img src="%1s" class="app-logo-image" alt="%2s" itemprop="logo" width="512" height="512">',
		$app_get_logo,
		APP_NAME
	);

}

header( 'Content-Type: text/html; charset=utf-8' );

?>
<!doctype html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
	<meta name="robots" content="noindex,follow" />

	<title><?php echo esc_html( $login_title ); ?></title>

	<link rel="icon" href="<?php echo esc_attr( $app_get_logo ); ?>" />

	<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>

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
	 * @param array  $classes An array of body classes.
	 * @param string $action  The action that brought the visitor to the login page.
	 */
	$classes = apply_filters( 'login_body_class', $classes, $action );

?>
</head>
<body class="login <?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<header class="app-header">
		<div class="app-identity">
			<div class="app-logo">
				<?php echo $app_logo; ?>
			</div>
			<div class="app-title-description">
				<h1 class="app-title"><?php echo $app_name; ?></h1>
				<p class="app-description"><?php echo $app_tagline; ?></p>
			</div>
		</div>
	</header>
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
	?>