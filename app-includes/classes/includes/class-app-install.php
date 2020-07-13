<?php
/**
 * App install class
 *
 * For installing the website management system.
 *
 * @package App_Package
 * @subpackage Includes\Config_Install
 * @since 1.0.0
 */

namespace AppNamespace\Includes;

/**
 * App install class
 *
 * @since 1.0.0
 */
final class App_Install {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {}

	/**
	 * Display installer setup form.
	 *
	 * @since  Previous 2.8.0
	 * @access public
	 * @global wpdb $wpdb Database abstraction object.
	 * @param  string|null $error
	 */
	public function display_setup_form( $error = null ) {

		global $wpdb;

		$sql        = $wpdb->prepare( "SHOW TABLES LIKE %s", $wpdb->esc_like( $wpdb->users ) );
		$user_table = ( $wpdb->get_var( $sql ) != null );

		// Ensure that Blogs appear in search engines by default.
		$blog_public = 1;

		if ( isset( $_POST['weblog_title'] ) ) {
			$blog_public = isset( $_POST['blog_public'] );
		}

		$weblog_title = isset( $_POST['weblog_title'] ) ? trim( wp_unslash( $_POST['weblog_title'] ) ) : '';
		$user_name    = isset( $_POST['user_name'] ) ? trim( wp_unslash( $_POST['user_name'] ) ) : '';
		$admin_email  = isset( $_POST['admin_email']  ) ? trim( wp_unslash( $_POST['admin_email'] ) ) : '';

		if ( ! is_null( $error ) ) {

			ob_start();

		?>
		<div class="setup-install-wrap setup-install-introduction">

			<h1><?php _ex( 'Welcome', 'Hello' ); ?></h1>
			<p class="message"><?php echo $error; ?></p>

		<?php } ?>
		<div class="setup-install-wrap setup-install-introduction">
		<form id="setup" method="post" action="install.php?step=2" novalidate="novalidate">
			<table class="form-table">
				<tr>
					<th scope="row"><label for="weblog_title"><?php _e( 'Site Title' ); ?></label></th>
					<td><input name="weblog_title" type="text" id="weblog_title" size="25" value="<?php echo esc_attr( $weblog_title ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="user_login"><?php _e( 'Username' ); ?></label></th>
					<td>
					<?php
					if ( $user_table ) {
						_e( 'User(s) already exists.' );
						echo '<input name="user_name" type="hidden" value="admin" />';
					} else {
						?><input name="user_name" type="text" id="user_login" size="25" value="<?php echo esc_attr( sanitize_user( $user_name, true ) ); ?>" />
						<p><?php _e( 'Usernames can have only alphanumeric characters, spaces, underscores, hyphens, periods, and the @ symbol.' ); ?></p>
					<?php
					} ?>
					</td>
				</tr>
				<?php if ( ! $user_table ) : ?>
				<tr class="form-field form-required user-pass1-wrap">
					<th scope="row">
						<label for="pass1">
							<?php _e( 'Password' ); ?>
						</label>
					</th>
					<td>
						<div class="">
							<?php $initial_password = isset( $_POST['admin_password'] ) ? stripslashes( $_POST['admin_password'] ) : wp_generate_password( 18 ); ?>
							<input type="password" name="admin_password" id="pass1" class="regular-text" autocomplete="off" data-reveal="1" data-pw="<?php echo esc_attr( $initial_password ); ?>" aria-describedby="pass-strength-result" />
							<button type="button" class="button wp-hide-pw hide-if-no-js" data-start-masked="<?php echo (int) isset( $_POST['admin_password'] ); ?>" data-toggle="0" aria-label="<?php esc_attr_e( 'Hide password' ); ?>">
								<span class="dashicons dashicons-hidden"></span>
								<span class="text"><?php _e( 'Hide' ); ?></span>
							</button>
							<div id="pass-strength-result" aria-live="polite"></div>
						</div>
						<p><span class="description important hide-if-no-js">
						<strong><?php _e( 'Important:' ); ?></strong>
						<?php /* translators: The non-breaking space prevents 1Password from thinking the text "log in" should trigger a password save prompt. */ ?>
						<?php _e( 'You will need this password to log&nbsp;in. Please store it in a secure location.' ); ?></span></p>
					</td>
				</tr>
				<tr class="form-field form-required user-pass2-wrap hide-if-js">
					<th scope="row">
						<label for="pass2"><?php _e( 'Repeat Password' ); ?>
							<span class="description"><?php _e( '(required)' ); ?></span>
						</label>
					</th>
					<td>
						<input name="admin_password2" type="password" id="pass2" autocomplete="off" />
					</td>
				</tr>
				<tr class="password-weak">
					<th scope="row"><?php _e( 'Confirm Password' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="pw_weak" class="pw-checkbox" />
							<?php _e( 'Confirm use of weak password' ); ?>
						</label>
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<th scope="row"><label for="admin_email"><?php _e( 'Your Email' ); ?></label></th>
					<td><input name="admin_email" type="email" id="admin_email" size="25" value="<?php echo esc_attr( $admin_email ); ?>" />
					<p><?php _e( 'Double-check your email address before continuing.' ); ?></p></td>
				</tr>
				<tr>
					<th scope="row"><?php has_action( 'blog_privacy_selector' ) ? _e( 'Site Visibility' ) : _e( 'Search Engine Visibility' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php has_action( 'blog_privacy_selector' ) ? _e( 'Site Visibility' ) : _e( 'Search Engine Visibility' ); ?> </span></legend>
							<?php
							if ( has_action( 'blog_privacy_selector' ) ) { ?>
								<p>
									<input id="blog-public" type="radio" name="blog_public" value="1" <?php checked( 1, $blog_public ); ?> />
									<label for="blog-public"><?php _e( 'Allow search engines to index this site' );?></label><br/>
								</p>
								<p>
									<input id="blog-norobots" type="radio" name="blog_public" value="0" <?php checked( 0, $blog_public ); ?> />
									<label for="blog-norobots"><?php _e( 'Discourage search engines from indexing this site' ); ?></label>
								</p>
								<p class="description"><?php _e( 'Note: Neither of these options blocks access to your site &mdash; it is up to search engines to honor your request.' ); ?></p>
								<?php
								/** This action is documented in wp-admin/options-reading.php */
								do_action( 'blog_privacy_selector' );
							} else { ?>
								<p>
									<label for="blog_public"><input name="blog_public" type="checkbox" id="blog_public" value="0" <?php checked( 0, $blog_public ); ?> />
									<?php _e( 'Discourage search engines from indexing this site' ); ?></label>
								</p>
								<p class="description"><?php _e( 'It is up to search engines to honor this request.' ); ?></p>
							<?php } ?>
						</fieldset>
					</td>
				</tr>
			</table>
			<p class="step"><?php submit_button( __( 'Install' ), 'large', 'Submit', false, array( 'id' => 'submit' ) ); ?></p>
			<input type="hidden" name="language" value="<?php echo isset( $_REQUEST['language'] ) ? esc_attr( $_REQUEST['language'] ) : ''; ?>" />
		</form>
	</div>
	<?php
	ob_get_clean();
	}
}
