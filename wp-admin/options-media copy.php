<?php
/**
 * Media settings
 *
 * @package App_Package
 * @subpackage Administration
 */

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );
}

$title       = __( 'Media Settings' );
$parent_file = 'upload.php';

$help_overview = sprintf(
	'<h3>%1s</h3>',
	__( 'Overview' )
);

$help_overview .= sprintf(
	'<p>%1s</p>',
	__( 'You can set maximum sizes for images inserted into your written content; you can also insert an image as Full Size.' )
);

if ( ! is_network() && ( get_option( 'upload_url_path' ) || ( get_option( 'upload_path' ) != 'wp-content/uploads' && get_option( 'upload_path' ) ) ) ) {

	$help_overview .= sprintf(
		'<p>%1s</p>',
		__( 'Uploading Files allows you to choose the folder and path for storing your uploaded files.' )
	);
}

$help_overview .= sprintf(
	'<p>%1s</p>',
	__( 'You must click the Save Changes button at the bottom of the screen for new settings to take effect.' )
);

$help_overview = apply_filters( 'help_options_media_overview', $help_overview );

get_current_screen()->add_help_tab( [
	'id'      => 'overview',
	'title'   => __( 'Overview' ),
	'content' => $help_overview,
] );

/**
 * Help sidebar content
 *
 * This system adds no content to the help sidebar
 * but there is a filter applied for adding content.
 *
 * @since 1.0.0
 */
$set_help_sidebar = apply_filters( 'set_help_sidebar_options_media', '' );
get_current_screen()->set_help_sidebar( $set_help_sidebar );

include( ABSPATH . 'wp-admin/admin-header.php' );

?>
	<div class="wrap">

		<h1><?php echo esc_html( $title ); ?></h1>

		<form action="options.php" method="post">

			<?php settings_fields( 'media' ); ?>

			<h2 class="title"><?php _e( 'Image sizes' ) ?></h2>

			<p><?php _e( 'The sizes listed below determine the maximum dimensions in pixels to use when adding an image to the Media Library. The active theme may override the size settings as require for its layout.' ); ?></p>

			<table class="form-table">
				<tr>
					<th scope="row"><?php _e( 'Thumbnail size' ) ?></th>

					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'Thumbnail size' ); ?></span></legend>

							<label for="thumbnail_size_w"><?php _e( 'Width' ); ?></label>
							<input name="thumbnail_size_w" type="number" step="1" min="0" id="thumbnail_size_w" value="<?php form_option( 'thumbnail_size_w' ); ?>" class="small-text" />

							<br />

							<label for="thumbnail_size_h"><?php _e( 'Height' ); ?></label>
							<input name="thumbnail_size_h" type="number" step="1" min="0" id="thumbnail_size_h" value="<?php form_option( 'thumbnail_size_h' ); ?>" class="small-text" />
						</fieldset>

						<input name="thumbnail_crop" type="checkbox" id="thumbnail_crop" value="1" <?php checked( '1', get_option( 'thumbnail_crop' ) ); ?>/>

						<label for="thumbnail_crop"><?php _e( 'Crop to exact dimensions' ); ?></label>
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e( 'Medium size' ) ?></th>

					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'Medium size' ); ?></span></legend>

							<label for="medium_size_w"><?php _e( 'Max Width' ); ?></label>
							<input name="medium_size_w" type="number" step="1" min="0" id="medium_size_w" value="<?php form_option( 'medium_size_w' ); ?>" class="small-text" />

							<br />

							<label for="medium_size_h"><?php _e( 'Max Height' ); ?></label>
							<input name="medium_size_h" type="number" step="1" min="0" id="medium_size_h" value="<?php form_option( 'medium_size_h' ); ?>" class="small-text" />

						</fieldset>

						<input name="medium_crop" type="checkbox" id="medium_crop" value="1" <?php checked( '1', get_option( 'medium_crop' ) ); ?>/>

						<label for="medium_crop"><?php _e( 'Crop to exact dimensions' ); ?></label>
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e( 'Large size' ) ?></th>

					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'Large size' ); ?></span></legend>

							<label for="large_size_w"><?php _e( 'Max Width' ); ?></label>
							<input name="large_size_w" type="number" step="1" min="0" id="large_size_w" value="<?php form_option( 'large_size_w' ); ?>" class="small-text" />

							<br />

							<label for="large_size_h"><?php _e( 'Max Height' ); ?></label>
							<input name="large_size_h" type="number" step="1" min="0" id="large_size_h" value="<?php form_option( 'large_size_h' ); ?>" class="small-text" />

						</fieldset>

						<input name="large_crop" type="checkbox" id="large_crop" value="1" <?php checked( '1', get_option( 'large_crop' ) ); ?>/>

						<label for="large_crop"><?php _e( 'Crop to exact dimensions' ); ?></label>
					</td>
				</tr>

				<?php do_settings_fields( 'media', 'default' ); ?>

			</table>

			<?php
			/**
			 * @global array $wp_settings
			 */
			if ( isset( $GLOBALS['wp_settings']['media']['embeds'] ) ) :

			?>
			<h2 class="title"><?php _e( 'Embeds' ) ?></h2>

			<table class="form-table">
				<?php do_settings_fields( 'media', 'embeds' ); ?>
			</table>
			<?php endif; ?>

			<?php if ( ! is_network() ) :

			?>
			<h2 class="title"><?php _e( 'Uploading Files' ); ?></h2>

			<table class="form-table">
				<?php

				// If upload_url_path is not the default (empty), and upload_path is not the default ( 'wp-content/uploads' or empty)
				if ( get_option( 'upload_url_path' ) || ( get_option( 'upload_path' ) != 'wp-content/uploads' && get_option( 'upload_path' ) ) ) :
				?>
				<tr>
					<th scope="row"><label for="upload_path"><?php _e( 'Store uploads in this folder' ); ?></label></th>

					<td><input name="upload_path" type="text" id="upload_path" value="<?php echo esc_attr(get_option( 'upload_path' )); ?>" class="regular-text code" />

					<p class="description"><?php
						printf( __( 'Default is %s' ), '<code>wp-content/uploads</code>' );
					?></p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="upload_url_path"><?php _e( 'Full URL path to files' ); ?></label></th>

					<td><input name="upload_url_path" type="text" id="upload_url_path" value="<?php echo esc_attr( get_option( 'upload_url_path' )); ?>" class="regular-text code" />

					<p class="description"><?php _e( 'Configuring this is optional. By default, it should be blank.' ); ?></p>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="td-full">
						<?php else : ?>
						<tr>
						<td class="td-full">
						<?php endif; ?>

						<label for="uploads_use_yearmonth_folders">
						<input name="uploads_use_yearmonth_folders" type="checkbox" id="uploads_use_yearmonth_folders" value="1"<?php checked( '1', get_option( 'uploads_use_yearmonth_folders' )); ?> />

						<?php _e( 'Organize my uploads into month- and year-based folders' ); ?>

						</label>
					</td>
				</tr>

				<?php do_settings_fields( 'media', 'uploads' ); ?>

			</table>

			<?php if ( current_user_can( 'manage_options' ) ) : ?>

			<h2 class="title"><?php _e( 'Deleting Files' ); ?></h2>

			<table class="form-table">

				<tr>
					<td colspan="2" class="td-full">

						<label for="media_allow_trash">
						<input name="media_allow_trash" type="checkbox" id="media_allow_trash" value="1"<?php checked( '1', get_option( 'media_allow_trash' ) ); ?> />

						<?php _e( 'Allow trash for media library items' ); ?>

						</label>
					</td>
				</tr>

				<?php do_settings_fields( 'media', 'delete' ); ?>

			</table>
			<?php endif; ?>

		<?php endif; ?>

		<?php do_settings_sections( 'media' ); ?>

		<?php submit_button(); ?>

		</form>
	</div>

<?php include( ABSPATH . 'wp-admin/admin-footer.php' ); ?>