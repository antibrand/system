<?php
/**
 * Signup form for site in a network.
 *
 * @package App_Package
 */

// Set up the application environment.
require( dirname( __FILE__ ) . '/app-load.php' );

add_action( 'wp_head', 'wp_no_robots' );

require( dirname( __FILE__ ) . '/app-site-header.php' );

nocache_headers();

if ( is_array( get_site_option( 'illegal_names' )) && isset( $_GET[ 'new' ] ) && in_array( $_GET[ 'new' ], get_site_option( 'illegal_names' ) ) ) {
	wp_redirect( network_home_url() );
	die();
}

/**
 * Prints signup_header via wp_head
 *
 * @since  Previous 3.0.0
 * @return void
 */
function do_signup_header() {

	/**
	 * Fires within the head section of the site sign-up screen
	 *
	 * @since Previous 3.0.0
	 */
	do_action( 'signup_header' );
}
add_action( 'wp_head', 'do_signup_header' );

if ( ! is_network() ) {
	wp_redirect( wp_registration_url() );
	die();
}

if ( ! is_main_site() ) {
	wp_redirect( network_site_url( 'app-signup.php' ) );
	die();
}

// Fix for page title.
$wp_query->is_404 = false;

/**
 * Fires before the Site Signup page is loaded
 *
 * @since Previous 4.4.0
 */
do_action( 'before_signup_header' );

/**
 * Prints styles for front-end network signup pages
 *
 * @since Previous 3.0.0
 */
function network_signup_styles() {

?>
	<style type="text/css">
	<?php echo apply_filters( 'network_signup_styles', '' ); ?>
	</style>
	<?php
}
add_action( 'wp_head', 'network_signup_styles' );
get_header( 'wp-signup' );

/**
 * Fires before the site sign-up form.
 *
 * @since Previous 3.0.0
 */
do_action( 'before_signup_form' );
?>
<div id="signup-content" class="widecolumn">

	<div class="mu_register wp-signup-container">
<?php
/**
 * Generates and displays the Signup and Create Site forms
 *
 * @since  Previous 3.0.0
 * @param  string $blogname The new site name.
 * @param  string $blog_title The new site title.
 * @param  WP_Error|string $errors A WP_Error object containing existing errors. Defaults to empty string.
 * @return mixed Returns the markup of the forms.
 */
function show_blog_form( $blogname = '', $blog_title = '', $errors = '' ) {

	if ( ! is_wp_error( $errors ) ) {
		$errors = new WP_Error();
	}

	$current_network = get_network();

	// Site name.
	echo '<p>';

	if ( ! is_subdomain_install() ) {
		echo '<label for="blogname">' . __( 'Site URL:' ) . '</label>';
	} else {
		echo '<label for="blogname">' . __( 'Site Domain:' ) . '</label>';
	}

	if ( ! is_subdomain_install() ) {
		echo '<br /><span class="prefix_address">' . $current_network->domain . $current_network->path . '</span>';
		echo '<input name="blogname" type="text" id="blogname" value="'. esc_attr( $blogname ) . '" maxlength="60" />';

	} else {
		echo '<br /><span class="suffix_address">.' . ( $site_domain = preg_replace( '|^www\.|', '', $current_network->domain ) ) . '</span>';
		echo '<input name="blogname" type="text" id="blogname" value="'.esc_attr( $blogname ) . '" maxlength="60" />';
	}

	echo '<span class="description form-description">' . __( 'Must be at least 4 characters, letters and numbers only, no spaces. It cannot be changed, so choose carefully!' ) . '</span>';

	echo '<p>';

	if ( ! is_user_logged_in() ) {

		if ( ! is_subdomain_install() ) {
			$site = $current_network->domain . $current_network->path . __( 'siteurl' );
		} else {
			$site = __( 'domain' ) . '.' . $site_domain . $current_network->path;
		}

		echo '<p>' . sprintf( __( 'Your address will be %s.' ), $site ) . '</p>';
	}

	if ( $errmsg = $errors->get_error_message( 'blogname' ) ) { ?>
		<p class="error"><?php echo $errmsg; ?></p>
	<?php } ?>

	<p>
		<label for="blog_title"><?php _e( 'Site Title:' ) ?></label>
		<?php echo '<input name="blog_title" type="text" id="blog_title" value="' . esc_attr( $blog_title ) . '" />'; ?>
	</p>

	<?php if ( $errmsg = $errors->get_error_message( 'blog_title' ) ) { ?>
		<p class="error"><?php echo $errmsg; ?></p>
	<?php }

	// Site Language.
	$languages = signup_get_available_languages();

	if ( ! empty( $languages ) ) :

	?>
		<p>
			<label for="site-language"><?php _e( 'Site Language:' ); ?></label>
			<?php
			// Network default.
			$lang = get_site_option( 'APP_LANG' );

			if ( isset( $_POST['APP_LANG'] ) ) {
				$lang = $_POST['APP_LANG'];
			}

			// Use US English if the default isn't available.
			if ( ! in_array( $lang, $languages ) ) {
				$lang = '';
			}

			wp_dropdown_languages( [
				'name'                        => 'APP_LANG',
				'id'                          => 'site-language',
				'selected'                    => $lang,
				'languages'                   => $languages,
				'show_available_translations' => false,
			] );
			?>
		</p>
	<?php endif; // End languages. ?>

	<div id="privacy">

        <p class="privacy-intro">

            <label for="blog_public_on"><?php _e( 'Privacy:' ); ?></label>
			<?php _e( 'Allow search engines to index this site.' ); ?>

			<br />

            <label class="checkbox" for="blog_public_on">
                <input type="radio" id="blog_public_on" name="blog_public" value="1" <?php if ( !isset( $_POST['blog_public'] ) || $_POST['blog_public'] == '1' ) { ?>checked="checked"<?php } ?> />
                <strong><?php _e( 'Yes' ); ?></strong>
			</label>

            <label class="checkbox" for="blog_public_off">
                <input type="radio" id="blog_public_off" name="blog_public" value="0" <?php if ( isset( $_POST['blog_public'] ) && $_POST['blog_public'] == '0' ) { ?>checked="checked"<?php } ?> />
                <strong><?php _e( 'No' ); ?></strong>
			</label>

        </p>
	</div>

	<?php

	/**
	 * Fires after the site sign-up form.
	 *
	 * @since Previous 3.0.0
	 * @param WP_Error $errors A WP_Error object possibly containing 'blogname' or 'blog_title' errors.
	 */
	do_action( 'signup_blogform', $errors );
}

/**
 * Validate the new site signup
 *
 * @since  Previous 3.0.0
 * @return array Contains the new site data and error messages.
 */
function validate_blog_form() {

	$user = '';

	if ( is_user_logged_in() ) {
		$user = wp_get_current_user();
	}

	return wpmu_validate_blog_signup( $_POST['blogname'], $_POST['blog_title'], $user );
}

/**
 * Display user registration form
 *
 * @since  Previous 3.0.0
 * @param  string $user_name The entered username.
 * @param  string $user_email The entered email address.
 * @param  WP_Error|string $errors A WP_Error object containing existing errors. Defaults to empty string.
 * @return mixed Returns the markup of the registration form.
 */
function show_user_form( $user_name = '', $user_email = '', $errors = '' ) {

	if ( ! is_wp_error( $errors ) ) {
		$errors = new WP_Error();
	}

	?>
	<p>
		<label for="user_name"><?php _e( 'Username:' ); ?></label>
		<input name="user_name" type="text" id="user_name" value="<?php echo esc_attr( $user_name ); ?>" autocapitalize="none" autocorrect="off" maxlength="60" />
		<span class="description form-description"><?php _e( 'Must be at least 4 characters, letters and numbers only.' ); ?></span>
	</p>

	<?php  if ( $errmsg = $errors->get_error_message( 'user_name' ) ) {
		echo '<p class="error">' . $errmsg . '</p>';
	} ?>

	<p>
		<label for="user_email"><?php _e( 'Email Address:' ); ?></label>
		<input name="user_email" type="email" id="user_email" value="<?php  echo esc_attr( $user_email ); ?>" maxlength="200" />
		<span class="description form-description"><?php _e( 'We send your registration email to this address. Double-check your email address before continuing.' ); ?></span>
	</p>

	<?php if ( $errmsg = $errors->get_error_message( 'user_email' ) ) { ?>
		<p class="error"><?php echo $errmsg; ?></p>
	<?php } ?>

	<?php if ( $errmsg = $errors->get_error_message( 'generic' ) ) {
		echo '<p class="error">' . $errmsg . '</p>';
	}

	/**
	 * Fires at the end of the user registration form on the site sign-up form.
	 *
	 * @since Previous 3.0.0
	 * @param WP_Error $errors A WP_Error object containing 'user_name' or 'user_email' errors.
	 */
	do_action( 'signup_extra_fields', $errors );
}

/**
 * Validate user signup name and email
 *
 * @since  Previous 3.0.0
 * @return array Contains username, email, and error messages.
 */
function validate_user_form() {
	return wpmu_validate_user_signup( $_POST['user_name'], $_POST['user_email']);
}

/**
 * Allow returning users to sign up for another site
 *
 * @since  Previous 3.0.0
 * @param  string $blogname The new site name
 * @param  string $blog_title The new site title.
 * @param  WP_Error|string $errors A WP_Error object containing existing errors. Defaults to empty string.
 * @return mixed Returns the markup of the signup form.
 */
function signup_another_blog( $blogname = '', $blog_title = '', $errors = '' ) {

	$current_user = wp_get_current_user();

	if ( ! is_wp_error( $errors) ) {
		$errors = new WP_Error();
	}

	$signup_defaults = [
		'blogname'   => $blogname,
		'blog_title' => $blog_title,
		'errors'     => $errors
	];

	/**
	 * Filters the default site sign-up variables
	 *
	 * @since Previous 3.0.0
	 * @param array $signup_defaults {
	 *     An array of default site sign-up variables.
	 *
	 *     @type string $blogname The site blogname.
	 *     @type string $blog_title The site title.
	 *     @type WP_Error $errors A WP_Error object possibly containing 'blogname' or 'blog_title' errors.
	 * }
	 */
	$filtered_results = apply_filters( 'signup_another_blog_init', $signup_defaults );
	$blogname         = $filtered_results['blogname'];
	$blog_title       = $filtered_results['blog_title'];
	$errors           = $filtered_results['errors'];

	echo '<h2>' . sprintf( __( 'Get another %s website' ), get_network()->site_name ) . '</h2>';

	if ( $errors->get_error_code() ) {
		echo '<p>' . __( 'There was a problem, please correct the form below and try again.' ) . '</p>';
	}
	?>
	<p><?php printf( __( 'Welcome back, %s. By filling out the form below, you can <strong>add another site to your account</strong>. There is no limit to the number of sites you can have, so create to your heart&#8217;s content, but write responsibly!' ), $current_user->display_name ) ?></p>

	<?php
	$blogs = get_blogs_of_user( $current_user->ID );

	if ( ! empty( $blogs) ) { ?>

			<p><?php _e( 'Sites you are already a member of:' ); ?></p>
			<ul>
				<?php foreach ( $blogs as $blog ) {
					$home_url = get_home_url( $blog->userblog_id );
					echo '<li><a href="' . esc_url( $home_url ) . '">' . $home_url . '</a></li>';
				} ?>
			</ul>
	<?php } ?>

	<p><?php _e( 'If you&#8217;re not going to use a great site domain, leave it for a new user. Now have at it!' ); ?></p>

	<form id="setupform" method="post" action="app-signup.php">

		<input type="hidden" name="stage" value="gimmeanotherblog" />
		<?php

		/**
		 * Hidden sign-up form fields output when creating another site or user
		 *
		 * @since Previous 3.0.0
		 * @param string $context A string describing the steps of the sign-up process. The value can be
		 *                        'create-another-site', 'validate-user', or 'validate-site'.
		 */
		do_action( 'signup_hidden_fields', 'create-another-site' );

		show_blog_form( $blogname, $blog_title, $errors ); ?>

		<p class="submit"><input type="submit" name="submit" class="submit" value="<?php esc_attr_e( 'Create Site' ); ?>" /></p>
	</form>
	<?php
}

/**
 * Validate a new site signup
 *
 * @since  Previous 3.0.0
 * @return null|bool True if site signup was validated, false if error.
 *                   The function halts all execution if the user is not logged in.
 */
function validate_another_blog_signup() {

	// Access global variables.
	global $blogname, $blog_title, $errors, $domain, $path;

	$current_user = wp_get_current_user();

	if ( ! is_user_logged_in() ) {
		die();
	}

	$result = validate_blog_form();

	// Extracted values set/overwrite globals.
	$domain     = $result['domain'];
	$path       = $result['path'];
	$blogname   = $result['blogname'];
	$blog_title = $result['blog_title'];
	$errors     = $result['errors'];

	if ( $errors->get_error_code() ) {
		signup_another_blog( $blogname, $blog_title, $errors );
		return false;
	}

	$public = (int) $_POST['blog_public'];

	$blog_meta_defaults = [
		'lang_id' => 1,
		'public'  => $public
	];

	// Handle the language setting for the new site.
	if ( ! empty( $_POST['APP_LANG'] ) ) {

		$languages = signup_get_available_languages();

		if ( in_array( $_POST['APP_LANG'], $languages ) ) {

			$language = wp_unslash( sanitize_text_field( $_POST['APP_LANG'] ) );

			if ( $language ) {
				$blog_meta_defaults['APP_LANG'] = $language;
			}
		}

	}

	/**
	 * Filters the new site meta variables
	 *
	 * Use the {@see 'add_signup_meta'} filter instead.
	 *
	 * @since Previous 3.0.0
	 * @deprecated 3.0.0 Use the {@see 'add_signup_meta'} filter instead.
	 * @param array $blog_meta_defaults An array of default blog meta variables.
	 */
	$meta_defaults = apply_filters( 'signup_create_blog_meta', $blog_meta_defaults );

	/**
	 * Filters the new default site meta variables
	 *
	 * @since Previous 3.0.0
	 * @param array $meta {
	 *     An array of default site meta variables.
	 *
	 *     @type int $lang_id     The language ID.
	 *     @type int $blog_public Whether search engines should be discouraged from indexing the site. 1 for true, 0 for false.
	 * }
	 */
	$meta    = apply_filters( 'add_signup_meta', $meta_defaults );
	$blog_id = wpmu_create_blog( $domain, $path, $blog_title, $current_user->ID, $meta, get_current_network_id() );

	if ( is_wp_error( $blog_id ) ) {
		return false;
	}

	confirm_another_blog_signup( $domain, $path, $blog_title, $current_user->user_login, $current_user->user_email, $meta, $blog_id );

	return true;
}

/**
 * Confirm a new site signup
 *
 * @since  Previous 3.0.0
 * @since  Previous 4.4.0 Added the `$blog_id` parameter.
 * @param  string $domain The domain URL.
 * @param  string $path The site root path.
 * @param  string $blog_title The site title.
 * @param  string $user_name The username.
 * @param  string $user_email The user's email address.
 * @param  array $meta Any additional meta from the {@see 'add_signup_meta'} filter in validate_blog_signup().
 * @param  int $blog_id The site ID.
 * @return mixed Returns the markup of the site signup form.
 */
function confirm_another_blog_signup( $domain, $path, $blog_title, $user_name, $user_email = '', $meta = [], $blog_id = 0 ) {

	if ( $blog_id ) {

		switch_to_blog( $blog_id );

		$home_url  = home_url( '/' );
		$login_url = wp_login_url();

		restore_current_blog();

	} else {
		$home_url  = 'http://' . $domain . $path;
		$login_url = 'http://' . $domain . $path . 'app-login.php';
	}

	$site = sprintf( '<a href="%1$s">%2$s</a>',
		esc_url( $home_url ),
		$blog_title
	);

	?>
	<h2><?php
		printf( __( 'The site %s is yours.' ), $site );
	?></h2>
	<p>
		<?php printf(
			__( '%1$s is your new site. <a href="%2$s">Log in</a> as &#8220;%3$s&#8221; using your existing password.' ),
			sprintf(
				'<a href="%s">%s</a>',
				esc_url( $home_url ),
				untrailingslashit( $domain . $path )
			),
			esc_url( $login_url ),
			$user_name
		); ?>
	</p>
	<?php

	/**
	 * Fires when the site or user sign-up process is complete.
	 *
	 * @since Previous 3.0.0
	 */
	do_action( 'signup_finished' );
}

/**
 * Setup the new user signup process
 *
 * @since  Previous 3.0.0
 * @param  string          $user_name  The username.
 * @param  string          $user_email The user's email.
 * @param  WP_Error|string $errors     A WP_Error object containing existing errors. Defaults to empty string.
 * @return mixed Returns the markup of the signup form.
 */
function signup_user( $user_name = '', $user_email = '', $errors = '' ) {

	global $active_signup;

	if ( ! is_wp_error( $errors ) ) {
		$errors = new WP_Error();
	}

	if ( isset( $_POST[ 'signup_for' ] ) ) {
		$signup_for = esc_html( $_POST[ 'signup_for' ] );
	} else {
		$signup_for = 'blog';
	}

	$signup_user_defaults = [
		'user_name'  => $user_name,
		'user_email' => $user_email,
		'errors'     => $errors,
	];

	/**
	 * Filters the default user variables used on the user sign-up form.
	 *
	 * @since Previous 3.0.0
	 * @param array $signup_user_defaults {
	 *     An array of default user variables.
	 *
	 *     @type string   $user_name  The user username.
	 *     @type string   $user_email The user email address.
	 *     @type WP_Error $errors     A WP_Error object with possible errors relevant to the sign-up user.
	 * }
	 */
	$filtered_results = apply_filters( 'signup_user_init', $signup_user_defaults );
	$user_name        = $filtered_results['user_name'];
	$user_email       = $filtered_results['user_email'];
	$errors           = $filtered_results['errors'];

	?>
	<h2><?php
		printf( __( 'Get your own %s account' ), get_network()->site_name );
	?></h2>

	<form id="setupform" method="post" action="app-signup.php" novalidate="novalidate">

		<input type="hidden" name="stage" value="validate-user-signup" />
		<?php

		// This action is documented in app-signup.php.
		do_action( 'signup_hidden_fields', 'validate-user' );

		show_user_form( $user_name, $user_email, $errors );

		?>
		<p>
		<?php if ( $active_signup == 'blog' ) {
		?>
			<input id="signupblog" type="hidden" name="signup_for" value="blog" />

		<?php
		} elseif ( $active_signup == 'user' ) {
		?>
			<input id="signupblog" type="hidden" name="signup_for" value="user" />

		<?php
		} else {
		?>
			<input id="signupblog" type="radio" name="signup_for" value="blog" <?php checked( $signup_for, 'blog' ); ?> />
			<label class="checkbox" for="signupblog"><?php _e( 'I would like a website and a user account' ); ?></label>
			<br />
			<input id="signupuser" type="radio" name="signup_for" value="user" <?php checked( $signup_for, 'user' ); ?> />
			<label class="checkbox" for="signupuser"><?php _e( 'I would like only a user account' ); ?></label>
		<?php } ?>
		</p>

		<p class="submit"><input type="submit" name="submit" class="submit" value="<?php esc_attr_e( 'Next' ); ?>" /></p>
	</form>
	<?php
}

/**
 * Validate the new user signup
 *
 * @since  Previous 3.0.0
 * @return bool True if new user signup was validated, false if error
 */
function validate_user_signup() {

	$result     = validate_user_form();
	$user_name  = $result['user_name'];
	$user_email = $result['user_email'];
	$errors     = $result['errors'];

	if ( $errors->get_error_code() ) {
		signup_user( $user_name, $user_email, $errors );
		return false;
	}

	if ( 'blog' == $_POST['signup_for'] ) {
		signup_blog( $user_name, $user_email );
		return false;
	}

	// This filter is documented in app-signup.php.
	wpmu_signup_user( $user_name, $user_email, apply_filters( 'add_signup_meta', [] ) );
	confirm_user_signup( $user_name, $user_email );

	return true;
}

/**
 * New user signup confirmation
 *
 * @since  Previous 3.0.0
 * @param  string $user_name The username
 * @param  string $user_email The user's email address
 * @return mixed Returns the markup of the message.
 */
function confirm_user_signup( $user_name, $user_email) {

?>
	<?php printf(
		'<h2>%1s %2s</h2>',
		$user_name,
		__( 'is your new username' )
	); ?>

	<p><?php _e( 'But, before you can start using your new username, <strong>you must activate it</strong>.' ); ?></p>

	<?php printf(
		'<p>%1s <span class="email-address">%2s</span> %3s</p>',
		__( 'Check your inbox at' ),
		$user_email,
		__( 'and click the link given.' ),
	); ?>

	<p><?php _e( 'If you do not activate your username within two days, you will have to sign up again.' ); ?></p>

<?php
	// This action is documented in app-signup.php.
	do_action( 'signup_finished' );
}

/**
 * Setup the new site signup
 *
 * @since Previous 3.0.0
 * @param string $user_name The username.
 * @param string $user_email The user's email address.
 * @param string $blogname The site name.
 * @param string $blog_title The site title.
 * @param WP_Error|string $errors A WP_Error object containing existing errors. Defaults to empty string.
 * @return mixed Returns the markup of the form.
 */
function signup_blog( $user_name = '', $user_email = '', $blogname = '', $blog_title = '', $errors = '' ) {

	if ( ! is_wp_error( $errors ) ) {
		$errors = new WP_Error();
	}

	$signup_blog_defaults = [
		'user_name'  => $user_name,
		'user_email' => $user_email,
		'blogname'   => $blogname,
		'blog_title' => $blog_title,
		'errors'     => $errors
	];

	/**
	 * Filters the default site creation variables for the site sign-up form.
	 *
	 * @since Previous 3.0.0
	 * @param array $signup_blog_defaults {
	 *     An array of default site creation variables.
	 *
	 *     @type string   $user_name  The user username.
	 *     @type string   $user_email The user email address.
	 *     @type string   $blogname   The blogname.
	 *     @type string   $blog_title The title of the site.
	 *     @type WP_Error $errors     A WP_Error object with possible errors relevant to new site creation variables.
	 * }
	 */
	$filtered_results = apply_filters( 'signup_blog_init', $signup_blog_defaults );

	$user_name  = $filtered_results['user_name'];
	$user_email = $filtered_results['user_email'];
	$blogname   = $filtered_results['blogname'];
	$blog_title = $filtered_results['blog_title'];
	$errors     = $filtered_results['errors'];

	if ( empty( $blogname ) ) {
		$blogname = $user_name;
	}
	?>
	<form id="setupform" method="post" action="app-signup.php">

		<input type="hidden" name="stage" value="validate-blog-signup" />
		<input type="hidden" name="user_name" value="<?php echo esc_attr( $user_name ); ?>" />
		<input type="hidden" name="user_email" value="<?php echo esc_attr( $user_email ); ?>" />
		<?php
		// This action is documented in app-signup.php.
		do_action( 'signup_hidden_fields', 'validate-site' );

		show_blog_form( $blogname, $blog_title, $errors ); ?>

		<p class="submit"><input type="submit" name="submit" class="submit" value="<?php esc_attr_e( 'Signup' ); ?>" /></p>
	</form>
	<?php
}

/**
 * Validate new site signup
 *
 * @since  Previous 3.0.0
 * @return bool True if the site signup was validated, false if error
 */
function validate_blog_signup() {

	// Re-validate user info.
	$user_result = wpmu_validate_user_signup( $_POST['user_name'], $_POST['user_email'] );

	$user_name   = $user_result['user_name'];
	$user_email  = $user_result['user_email'];
	$user_errors = $user_result['errors'];

	if ( $user_errors->get_error_code() ) {
		signup_user( $user_name, $user_email, $user_errors );
		return false;
	}

	$result     = wpmu_validate_blog_signup( $_POST['blogname'], $_POST['blog_title'] );
	$domain     = $result['domain'];
	$path       = $result['path'];
	$blogname   = $result['blogname'];
	$blog_title = $result['blog_title'];
	$errors     = $result['errors'];

	if ( $errors->get_error_code() ) {
		signup_blog( $user_name, $user_email, $blogname, $blog_title, $errors );
		return false;
	}

	$public      = (int) $_POST['blog_public'];
	$signup_meta = [
		'lang_id' => 1,
		'public'  => $public
	];

	// Handle the language setting for the new site.
	if ( ! empty( $_POST['APP_LANG'] ) ) {

		$languages = signup_get_available_languages();

		if ( in_array( $_POST['APP_LANG'], $languages ) ) {

			$language = wp_unslash( sanitize_text_field( $_POST['APP_LANG'] ) );

			if ( $language ) {
				$signup_meta['APP_LANG'] = $language;
			}
		}

	}

	// This filter is documented in app-signup.php.
	$meta = apply_filters( 'add_signup_meta', $signup_meta );

	wpmu_signup_blog( $domain, $path, $blog_title, $user_name, $user_email, $meta );
	confirm_blog_signup( $domain, $path, $blog_title, $user_name, $user_email, $meta );

	return true;
}

/**
 * New site signup confirmation
 *
 * @since  Previous 3.0.0
 * @param  string $domain The domain URL
 * @param  string $path The site root path
 * @param  string $blog_title The new site title
 * @param  string $user_name The user's username
 * @param  string $user_email The user's email address
 * @param  array $meta Any additional meta from the {@see 'add_signup_meta'} filter in validate_blog_signup()
 * @return mixed Returns the markup of the message.
 */
function confirm_blog_signup( $domain, $path, $blog_title, $user_name = '', $user_email = '', $meta = [] ) {

?>
	<?php printf(
		'<h2>%1s <a href="%2s">%3s</a> %4s</h2>',
		__( 'Your new website,' ),
		esc_url( "{$domain}{$path}" ),
		esc_html( "{$blog_title}" ),
		__( ', is almost ready.' )
	); ?>

	<p><?php _e( 'But, before you can start using your site, <strong>you must activate it</strong>.' ); ?></p>

	<?php printf(
		'<p>%1s <span class="email-address">%2s</span> %3s</p>',
		__( 'Check your inbox at' ),
		esc_html( $user_email ),
		__( 'and click the link given.' )
	); ?>

	<p><?php _e( 'If you do not activate your site within two days, you will have to sign up again.' ); ?></p>

	<h2><?php _e( 'Still waiting for your email?' ); ?></h2>

	<p><?php _e( 'If you haven&#8217;t received your email yet, there are a number of things you can do:' ); ?></p>

	<ul id="noemail-tips">
		<li><?php _e( 'Wait a little longer. Sometimes delivery of email can be delayed by processes outside of our control.' ); ?></li>
		<li><?php _e( 'Check the junk or spam folder of your email client. Sometime emails wind up there by mistake.' ); ?></li>
		<li><?php
			printf( __( 'Have you entered your email correctly? You have entered %s, if it&#8217;s incorrect, you will not receive your email.' ), esc_html( $user_email ) );
		?></li>
	</ul>

	<?php
	// This action is documented in app-signup.php.
	do_action( 'signup_finished' );
}

/**
 * Retrieves languages available during the site/user signup process
 *
 * @see get_available_languages()
 *
 * @since  Previous 4.4.0
 * @return array List of available languages.
 */
function signup_get_available_languages() {

	/**
	 * Filters the list of available languages for front-end site signups.
	 *
	 * Passing an empty array to this hook will disable output of the setting on the
	 * signup form, and the default language will be used when creating the site.
	 *
	 * Languages not already installed will be stripped.
	 *
	 * @since Previous 4.4.0
	 * @param array $available_languages Available languages.
	 */
	$languages = (array) apply_filters( 'signup_get_available_languages', get_available_languages() );

	/*
	 * Strip any non-installed languages and return
	 *
	 * Re-call get_available_languages() here in case a language pack was installed
	 * in a callback hooked to the 'signup_get_available_languages' filter before this point.
	 */
	return array_intersect_assoc( $languages, get_available_languages() );
}

// Main.
$active_signup = get_site_option( 'registration', 'none' );

/**
 * Filters the type of site sign-up
 *
 * @since Previous 3.0.0
 * @param string $active_signup String that returns registration type. The value can be
 *                              'all', 'none', 'blog', or 'user'.
 */
$active_signup = apply_filters( 'wpmu_active_signup', $active_signup );

if ( current_user_can( 'manage_network' ) ) {

	echo '<div class="mu_alert">';
	_e( 'Greetings Network Administrator!' );
	echo ' ';

	switch ( $active_signup ) {

		case 'none' :
			_e( 'The network currently disallows registrations.' );
			break;

		case 'blog' :
			_e( 'The network currently allows site registrations.' );
			break;

		case 'user' :
			_e( 'The network currently allows user registrations.' );
			break;

		default :
			_e( 'The network currently allows both site and user registrations.' );
			break;
	}

	echo ' ';

	printf( __( 'To change or disable registration go to your <a href="%s">Options page</a>.' ), esc_url( network_admin_url( 'settings.php' ) ) );
	echo '</div>';
}

if ( isset( $_GET['new'] ) ) {
	$newblogname = strtolower( preg_replace( '/^-|-$|[^-a-zA-Z0-9]/', '', $_GET['new'] ) );
} else {
	$newblogname = null;
}

$current_user = wp_get_current_user();

if ( $active_signup == 'none' ) {
	_e( 'Registration has been disabled.' );

} elseif ( $active_signup == 'blog' && !is_user_logged_in() ) {

	$login_url = wp_login_url( network_site_url( 'app-signup.php' ) );

	printf( __( 'You must first <a href="%s">log in</a>, and then you can create a new site.' ), $login_url );

} else {

	if ( isset( $_POST['stage'] ) ) {
		$stage = $_POST['stage'];
	} else {
		$stage = 'default';
	}

	switch ( $stage ) {

		case 'validate-user-signup' :

			if ( $active_signup == 'all' || $_POST[ 'signup_for' ] == 'blog' && $active_signup == 'blog' || $_POST[ 'signup_for' ] == 'user' && $active_signup == 'user' ) {
				validate_user_signup();
			} else {
				_e( 'User registration has been disabled.' );
			}

			break;

		case 'validate-blog-signup' :

			if ( $active_signup == 'all' || $active_signup == 'blog' ) {
				validate_blog_signup();
			} else {
				_e( 'Site registration has been disabled.' );
			}

			break;
		case 'gimmeanotherblog' :

			validate_another_blog_signup();
			break;

		case 'default' :
		default :

			if ( isset( $_POST[ 'user_email' ] ) ) {
				$user_email = $_POST[ 'user_email' ];
			} else {
				$user_email = '';
			}

			/**
			 * Fires when the site sign-up form is sent.
			 *
			 * @since Previous 3.0.0
			 */
			do_action( 'preprocess_signup_form' );

			if ( is_user_logged_in() && ( $active_signup == 'all' || $active_signup == 'blog' ) ) {
				signup_another_blog( $newblogname );

			} elseif ( ! is_user_logged_in() && ( $active_signup == 'all' || $active_signup == 'user' ) ) {
				signup_user( $newblogname, $user_email );

			} elseif ( ! is_user_logged_in() && ( $active_signup == 'blog' ) ) {
				_e( 'Sorry, new registrations are not allowed at this time.' );

			} else {
				_e( 'You are logged in already. No need to register again!' );
			}

			if ( $newblogname ) {

				$newblog = get_blogaddress_by_name( $newblogname );

				if ( $active_signup == 'blog' || $active_signup == 'all' ) {
					printf( '<p><em>' . __( 'The site you were looking for, %s, does not exist, but you can create it now!' ) . '</em></p>',
						'<strong>' . $newblog . '</strong>'
					);
				} else {
					printf( '<p><em>' . __( 'The site you were looking for, %s, does not exist.' ) . '</em></p>',
						'<strong>' . $newblog . '</strong>'
					);
				}
			}

			break;
	}
}
?>
	</div>
</div>
<?php
/**
 * Fires after the sign-up forms, before wp_footer.
 *
 * @since Previous 3.0.0
 */
do_action( 'after_signup_form' );

get_footer( 'wp-signup' );
