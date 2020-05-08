<?php
/**
 * Writing settings administration panel.
 *
 * @package App_Package
 * @subpackage Administration
 */

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );
}

$title       = __( 'Writing Settings' );
$parent_file = 'options-general.php';

$help_overview = sprintf(
	'<h3>%1s</h3>',
	__( 'Overview' )
);

$help_overview .= sprintf(
	'<p>%1s</p>',
	__( 'You can submit content in several different ways; this screen holds the settings for all of them. The top section controls the editor within the dashboard, while the rest control external publishing methods.' )
);

$help_overview .= sprintf(
	'<p>%1s</p>',
	__( 'You must click the Save Changes button at the bottom of the screen for new settings to take effect.' )
);

$help_overview = apply_filters( 'help_options_writing_overview', $help_overview );

get_current_screen()->add_help_tab( [
	'id'      => 'overview',
	'title'   => __( 'Overview' ),
	'content' => $help_overview
] );

// This filter is documented in wp-admin/options.php.
if ( apply_filters( 'enable_post_by_email_configuration', true ) ) {

	$help_postemail = sprintf(
		'<h3>%1s</h3>',
		__( 'Post Via Email' )
	);

	$help_postemail .= sprintf(
		'<p>%1s</p>',
		__( 'Post via email settings allow you to send your installation an email with the content of your post. You must set up a secret email account with POP3 access to use this, and any mail received at this address will be posted, so it&#8217;s a good idea to keep this address very secret.' )
	);

	get_current_screen()->add_help_tab( [
		'id'      => 'options-postemail',
		'title'   => __( 'Post Via Email' ),
		'content' => $help_postemail
	] );
}

// This filter is documented in wp-admin/options-writing.php.
if ( apply_filters( 'enable_update_services_configuration', true ) ) {

	$help_services = sprintf(
		'<h3>%1s</h3>',
		__( 'Update Services' )
	);

	$help_services .= sprintf(
		'<p>%1s</p>',
		__( 'Will automatically alert various services of your new posts.' )
	);

	get_current_screen()->add_help_tab( [
		'id'      => 'options-services',
		'title'   => __( 'Update Services' ),
		'content' => $help_services
	] );
}

/**
 * Help sidebar content
 *
 * This system adds no content to the help sidebar
 * but there is a filter applied for adding content.
 *
 * @since 1.0.0
 */
$set_help_sidebar = apply_filters( 'set_help_sidebar_options_writing', '' );
get_current_screen()->set_help_sidebar( $set_help_sidebar );

include( ABSPATH . 'wp-admin/admin-header.php' );

?>
	<div class="wrap">

		<h1><?php echo esc_html( $title ); ?></h1>

		<form method="post" action="options.php">

			<?php settings_fields( 'writing' ); ?>

		<table class="form-table">
		<?php if ( get_site_option( 'initial_db_version' ) < 32453 ) : ?>
			<tr>
			<th scope="row"><?php _e( 'Formatting' ); ?></th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?php _e( 'Formatting' ); ?></span></legend>
					<label for="use_smilies">
					<input name="use_smilies" type="checkbox" id="use_smilies" value="1" <?php checked( '1', get_option( 'use_smilies' ) ); ?> />
					<?php _e( 'Convert emoticons like <code>:-)</code> and <code>:-P</code> to graphics on display' ); ?></label><br />
					<label for="use_balanceTags"><input name="use_balanceTags" type="checkbox" id="use_balanceTags" value="1" <?php checked( '1', get_option( 'use_balanceTags' ) ); ?> /> <?php _e( 'Should correct invalidly nested XHTML automatically' ); ?></label>
				</fieldset>
			</td>
			</tr>
		<?php endif; ?>
			<tr>
				<th scope="row"><label for="default_category"><?php _e( 'Default Post Category' ); ?></label></th>
				<td>
				<?php
				wp_dropdown_categories( [ 'hide_empty' => 0, 'name' => 'default_category', 'orderby' => 'name', 'selected' => get_option( 'default_category' ), 'hierarchical' => true ] );
				?>
				</td>
			</tr>
		<?php
		$post_formats = get_post_format_strings();
		unset( $post_formats['standard'] );
		?>
			<tr>
				<th scope="row"><label for="default_post_format"><?php _e( 'Default Post Format' ); ?></label></th>
				<td>
					<select name="default_post_format" id="default_post_format">
						<option value="0"><?php echo get_post_format_string( 'standard' ); ?></option>
				<?php foreach ( $post_formats as $format_slug => $format_name ): ?>
						<option<?php selected( get_option( 'default_post_format' ), $format_slug ); ?> value="<?php echo esc_attr( $format_slug ); ?>"><?php echo esc_html( $format_name ); ?></option>
				<?php endforeach; ?>
					</select>
				</td>
			</tr>

		<?php do_settings_fields( 'writing', 'default' ); ?>

		</table>

		<?php
		// This filter is documented in wp-admin/options.php.
		if ( apply_filters( 'enable_post_by_email_configuration', true ) ) {
		?>
		<h2 class="title"><?php _e( 'Post via email' ); ?></h2>
		<p><?php
		printf(
			// Translators: 1, 2, 3: examples of random email addresses.
			__( 'To post by email you must set up a secret email account with POP3 access. Any mail received at this address will be posted, so it&#8217;s a good idea to keep this address very secret. Here are three random strings you could use: %1$s, %2$s, %3$s.' ),
			sprintf( '<kbd>%s</kbd>', wp_generate_password( 8, false ) ),
			sprintf( '<kbd>%s</kbd>', wp_generate_password( 8, false ) ),
			sprintf( '<kbd>%s</kbd>', wp_generate_password( 8, false ) )
		);
		?></p>

		<table class="form-table">
			<tr>
				<th scope="row"><label for="mailserver_url"><?php _e( 'Mail Server' ); ?></label></th>
				<td>
					<input name="mailserver_url" type="text" id="mailserver_url" value="<?php form_option( 'mailserver_url' ); ?>" class="regular-text code" />
					<label for="mailserver_port"><?php _e( 'Port' ); ?></label>
					<input name="mailserver_port" type="text" id="mailserver_port" value="<?php form_option( 'mailserver_port' ); ?>" class="small-text" />
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="mailserver_login"><?php _e( 'Login Name' ); ?></label></th>
				<td>
					<input name="mailserver_login" type="text" id="mailserver_login" value="<?php form_option( 'mailserver_login' ); ?>" class="regular-text ltr" /></td>
				</tr>
				<tr>
				<th scope="row"><label for="mailserver_pass"><?php _e( 'Password' ); ?></label></th>
				<td>
					<input name="mailserver_pass" type="text" id="mailserver_pass" value="<?php form_option( 'mailserver_pass' ); ?>" class="regular-text ltr" />
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="default_email_category"><?php _e( 'Default Mail Category' ); ?></label></th>
				<td>
					<?php
					wp_dropdown_categories( [ 'hide_empty' => 0, 'name' => 'default_email_category', 'orderby' => 'name', 'selected' => get_option( 'default_email_category' ), 'hierarchical' => true ] );
					?>
				</td>
			</tr>
		<?php do_settings_fields( 'writing', 'post_via_email' ); ?>
		</table>
		<?php } ?>

		<?php do_settings_sections( 'writing' ); ?>

		<?php submit_button(); ?>
		</form>
	</div>

<?php
include( ABSPATH . 'wp-admin/admin-footer.php' ); ?>