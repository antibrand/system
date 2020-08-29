<?php
/**
 * Add Site Administration Screen
 *
 * @package App_Package
 * @subpackage Network
 * @since Previous 3.1.0
 */

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

/** Translation Installation API */
require_once( APP_INC_PATH . '/backend/translation-install.php' );

if ( ! current_user_can( 'create_sites' ) ) {
	wp_die( __( 'Sorry, you are not allowed to add sites to this network.' ) );
}

get_current_screen()->add_help_tab( [
	'id'      => 'overview',
	'title'   => __( 'Overview' ),
	'content' =>
		'<p>' . __( 'This screen is for Network Admins to add new sites to the network. This is not affected by the registration settings.' ) . '</p>' .
		'<p>' . __( 'If the admin email for the new site does not exist in the database, a new user will also be created.' ) . '</p>'
] );

get_current_screen()->set_help_sidebar( '' );

if ( isset($_REQUEST['action']) && 'add-site' == $_REQUEST['action'] ) {
	check_admin_referer( 'add-blog', '_wpnonce_add-blog' );

	if ( ! is_array( $_POST['blog'] ) )
		wp_die( __( 'Can&#8217;t create an empty site.' ) );

	$blog = $_POST['blog'];
	$domain = '';
	if ( preg_match( '|^([a-zA-Z0-9-])+$|', $blog['domain'] ) )
		$domain = strtolower( $blog['domain'] );

	// If not a subdomain installation, make sure the domain isn't a reserved word
	if ( ! is_subdomain_install() ) {
		$subdirectory_reserved_names = get_subdirectory_reserved_names();

		if ( in_array( $domain, $subdirectory_reserved_names ) ) {
			wp_die(
				/* translators: %s: reserved names list */
				sprintf( __( 'The following words are reserved for use by functions and cannot be used as blog names: %s' ),
					'<code>' . implode( '</code>, <code>', $subdirectory_reserved_names ) . '</code>'
				)
			);
		}
	}

	$title = $blog['title'];

	$meta = array(
		'public' => 1
	);

	// Handle translation installation for the new site.
	if ( isset( $_POST['APP_LANG'] ) ) {
		if ( '' === $_POST['APP_LANG'] ) {
			$meta['APP_LANG'] = ''; // en_US
		} elseif ( in_array( $_POST['APP_LANG'], get_available_languages() ) ) {
			$meta['APP_LANG'] = $_POST['APP_LANG'];
		} elseif ( current_user_can( 'install_languages' ) && wp_can_install_language_pack() ) {
			$language = wp_download_language_pack( wp_unslash( $_POST['APP_LANG'] ) );
			if ( $language ) {
				$meta['APP_LANG'] = $language;
			}
		}
	}

	if ( empty( $domain ) )
		wp_die( __( 'Missing or invalid site address.' ) );

	if ( isset( $blog['email'] ) && '' === trim( $blog['email'] ) ) {
		wp_die( __( 'Missing email address.' ) );
	}

	$email = sanitize_email( $blog['email'] );
	if ( ! is_email( $email ) ) {
		wp_die( __( 'Invalid email address.' ) );
	}

	if ( is_subdomain_install() ) {
		$newdomain = $domain . '.' . preg_replace( '|^www\.|', '', get_network()->domain );
		$path      = get_network()->path;
	} else {
		$newdomain = get_network()->domain;
		$path      = get_network()->path . $domain . '/';
	}

	$password = 'N/A';
	$user_id = email_exists($email);
	if ( !$user_id ) { // Create a new user with a random password
		/**
		 * Fires immediately before a new user is created via the network site-new.php page.
		 *
		 * @since Previous 4.5.0
		 *
		 * @param string $email Email of the non-existent user.
		 */
		do_action( 'pre_network_site_new_created_user', $email );

		$user_id = username_exists( $domain );
		if ( $user_id ) {
			wp_die( __( 'The domain or path entered conflicts with an existing username.' ) );
		}
		$password = wp_generate_password( 12, false );
		$user_id = wpmu_create_user( $domain, $password, $email );
		if ( false === $user_id ) {
			wp_die( __( 'There was an error creating the user.' ) );
		}

		/**
		  * Fires after a new user has been created via the network site-new.php page.
		  *
		  * @since Previous 4.4.0
		  *
		  * @param int $user_id ID of the newly created user.
		  */
		do_action( 'network_site_new_created_user', $user_id );
	}

	$wpdb->hide_errors();
	$id = wpmu_create_blog( $newdomain, $path, $title, $user_id, $meta, get_current_network_id() );
	$wpdb->show_errors();
	if ( ! is_wp_error( $id ) ) {
		if ( ! is_super_admin( $user_id ) && !get_user_option( 'primary_blog', $user_id ) ) {
			update_user_option( $user_id, 'primary_blog', $id, true );
		}

		wp_mail(
			get_site_option( 'admin_email' ),
			sprintf(
				/* translators: %s: network name */
				__( '[%s] New Site Created' ),
				get_network()->site_name
			),
			sprintf(
				/* translators: 1: user login, 2: site url, 3: site name/title */
				__( 'New site created by %1$s

Address: %2$s
Name: %3$s' ),
				$current_user->user_login,
				get_site_url( $id ),
				wp_unslash( $title )
			),
			sprintf(
				'From: "%1$s" <%2$s>',
				_x( 'Site Admin', 'email "From" field' ),
				get_site_option( 'admin_email' )
			)
		);
		wpmu_welcome_notification( $id, $user_id, $password, $title, array( 'public' => 1 ) );
		wp_redirect( add_query_arg( array( 'update' => 'added', 'id' => $id ), 'site-new.php' ) );
		exit;
	} else {
		wp_die( $id->get_error_message() );
	}
}

if ( isset($_GET['update']) ) {
	$messages = array();
	if ( 'added' == $_GET['update'] )
		$messages[] = sprintf(
			/* translators: 1: dashboard url, 2: network admin edit url */
			__( 'Site added. <a href="%1$s">Visit Dashboard</a> or <a href="%2$s">Edit Site</a>' ),
			esc_url( get_admin_url( absint( $_GET['id'] ) ) ),
			network_admin_url( 'site-info.php?id=' . absint( $_GET['id'] ) )
		);
}

$title = __( 'Add New Site' );
$parent_file = 'sites.php';

wp_enqueue_script( 'user-suggest' );

// Get the admin page header.
include( APP_VIEWS_PATH . '/backend/header/admin-header.php' );

?>

<div class="wrap">

	<h1 id="add-new-site"><?php _e( 'Add New Site' ); ?></h1>

	<?php
	if ( ! empty( $messages ) ) {
		foreach ( $messages as $msg )
			echo '<div id="message" class="updated notice is-dismissible"><p>' . $msg . '</p></div>';
	} ?>

	<form method="post" action="<?php echo network_admin_url( 'site-new.php?action=add-site' ); ?>" novalidate="novalidate">

		<?php wp_nonce_field( 'add-blog', '_wpnonce_add-blog' ) ?>

		<p>
			<label for="site-address"><?php _e( 'Site Address (URL)' ) ?></label>
			<br />
			<?php if ( is_subdomain_install() ) { ?>
				<input name="blog[domain]" type="text" class="regular-text" id="site-address" aria-describedby="site-address-desc" autocapitalize="none" autocorrect="off"/><br />.<?php echo preg_replace( '|^www\.|', '', get_network()->domain ); ?>
			<?php } else {
				echo get_network()->domain . get_network()->path ?><br /><input name="blog[domain]" type="text" class="regular-text" id="site-address" aria-describedby="site-address-desc"  autocapitalize="none" autocorrect="off" />
			<?php } ?>
			<br /><span class="description" id="site-address-desc"><?php _e( 'Only lowercase letters (a-z), numbers, and hyphens are allowed.' ); ?></span>
		</p>

		<p>
			<label for="site-title"><?php _e( 'Site Title' ) ?></label>
			<br /><input name="blog[title]" type="text" class="regular-text" id="site-title" />
		</p>

		<?php
		$languages    = get_available_languages();
		$translations = wp_get_available_translations();
		if ( ! empty( $languages ) || ! empty( $translations ) ) :

		?>
		<p>
			<label for="site-language"><?php _e( 'Site Language' ); ?></label>
			<?php
			// Network default.
			$lang = get_site_option( 'APP_LANG' );

			// Use English if the default isn't available.
			if ( ! in_array( $lang, $languages ) ) {
				$lang = '';
			}

			echo '<br />';

			wp_dropdown_languages(
				[
					'name'                        => 'APP_LANG',
					'id'                          => 'site-language',
					'selected'                    => $lang,
					'languages'                   => $languages,
					'translations'                => $translations,
					'show_available_translations' => current_user_can( 'install_languages' ) && wp_can_install_language_pack(),
				]
			);
			?>
		</p>
		<?php endif; // Languages. ?>

		<p>
			<label for="admin-email"><?php _e( 'Admin Email' ) ?></label>
			<br /><input name="blog[email]" type="email" class="regular-text wp-suggest-user" id="admin-email" data-autocomplete-type="search" data-autocomplete-field="user_email" />
		</p>

		<p>
			<?php _e( 'A new user will be created if the above email address is not in the database.' ) ?>
			<br /><?php _e( 'The username and a link to set the password will be mailed to this email address.' ) ?>
		</p>
		<?php
		/**
		 * Fires at the end of the new site form in network admin.
		 *
		 * @since Previous 4.5.0
		 */
		do_action( 'network_site_new_form' ); ?>

		<p><?php submit_button( __( 'Add Site' ), 'primary', 'add-site' ); ?></p>

	</form>
</div>
<?php

require( APP_ADMIN_PATH . '/admin-footer.php' );
