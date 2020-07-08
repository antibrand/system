<?php
/**
 * Successful installation message
 *
 * @package App_Package
 * @subpackage Administration
 */

?>
<div class="setup-install-wrap setup-install-installation-success">

	<h1><?php _e( 'Installation Successful' ); ?></h1>

	<p><?php _e( 'The website management system has been installed and you can now log in.' ); ?></p>

	<table class="form-table install-success">
		<tr>
			<th><?php _e( 'Username' ); ?></th>
			<td><?php echo esc_html( sanitize_user( $user_name, true ) ); ?></td>
		</tr>
		<tr>
			<th><?php _e( 'Password' ); ?></th>
			<td><?php
			if ( ! empty( $result['password'] ) && empty( $admin_password_check ) ): ?>
				<code><?php echo esc_html( $result['password'] ) ?></code><br />
			<?php endif ?>
				<p><?php echo $result['password_message'] ?></p>
			</td>
		</tr>
	</table>
	<p class="step"><a href="<?php echo esc_url( wp_login_url() ); ?>" class="button button-large"><?php _e( 'Log In' ); ?></a></p>
</div>
