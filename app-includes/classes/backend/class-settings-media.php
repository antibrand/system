<?php
/**
 * Media settings screen class
 *
 * @package App_Package
 * @subpackage Administration/Backend
 */

namespace AppNamespace\Backend;

// Stop here if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Settings screen class
 *
 * @since  1.0.0
 * @access private
 */
class Settings_Media extends Settings_Screen {

	/**
	 * Page parent file
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string The parent file of the settings screen.
	 */
	public $parent = 'upload.php';

	/**
	 * Page title
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $title = 'Media Settings';

	/**
	 * Page description
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $description = 'Manage images, audio & video, documents, and more.';

	/**
	 * Form action
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string This will most likely be options.php.
	 */
	protected $action = 'options.php';

	/**
	 * Form fields
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string The name of the registered fields to be executed.
	 *             Defaults are 'general', 'writing', 'reading', permalinks'.
	 */
	protected $fields = 'media';

	/**
	 * Instance of the class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object Returns the instance.
	 */
	public static function instance() {

		// Return the instance.
		return new self;
	}

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return self
	 */
	protected function __construct() {

		parent :: __construct();
	}

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the translated title.
	 */
	public function title() {

		$title = esc_html__( $this->title );

		return $title;
	}

	/**
	 * Page description
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the description markup.
	 */
	public function description() {

		$description = sprintf(
			'<p class="description">%1s</p>',
			esc_html__( $this->description )
		);

		return $description;
	}

	/**
	 * Enqueue scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function scripts() {

		// Script for tabbed content.
		wp_enqueue_script( 'app-tabs' );
	}

	/**
	 * Tabbed content
	 *
	 * Add content to the tabbed section of the page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function tabs() {

		$screen = get_current_screen();

		$screen->add_content_tab( [
			'id'         => 'images',
			'capability' => 'manage_options',
			'tab'        => __( 'Images' ),
			'icon'       => '',
			'heading'    => __( 'Manage Image Sizes' ),
			'content'    => '',
			'callback'   => [ $this, 'images' ]
		] );

		$screen->add_content_tab( [
			'id'         => 'galleries',
			'capability' => 'manage_options',
			'tab'        => __( 'Galleries' ),
			'icon'       => '',
			'heading'    => __( 'Manage Image Galleries' ),
			'content'    => '',
			'callback'   => [ $this, 'galleries' ]
		] );

		$screen->add_content_tab( [
			'id'         => 'library',
			'capability' => 'manage_options',
			'tab'        => __( 'Library' ),
			'icon'       => '',
			'heading'    => __( 'Manage Media Library' ),
			'content'    => '',
			'callback'   => [ $this, 'library' ]
		] );
	}

	/**
	 * Images tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function images() {

		?>
		<div class="tab-section-wrap tab-section-wrap__images">

			<p><?php _e( 'The sizes listed below determine the maximum dimensions in pixels to use when adding an image to the Media Library. The active theme may override the size settings as require for its layout.' ); ?></p>

			<fieldset form="<?php echo $this->fields . '-settings'; ?>" class="form-tab-fieldset">

				<legend class="screen-reader-text"><span><?php _e( 'Thumbnail Size Settings' ); ?></span></legend>

				<h3><?php _e( 'Thumbnail Size' ); ?></h3>

				<ul class="form-field-list">
					<li>
						<label for="thumbnail_size_w"><?php _e( 'Width' ); ?></label>
						<input name="thumbnail_size_w" type="number" step="1" min="0" id="thumbnail_size_w" value="<?php form_option( 'thumbnail_size_w' ); ?>" class="small-text" />
					</li>
					<li>
						<label for="thumbnail_size_h"><?php _e( 'Height' ); ?></label>
						<input name="thumbnail_size_h" type="number" step="1" min="0" id="thumbnail_size_h" value="<?php form_option( 'thumbnail_size_h' ); ?>" class="small-text" />
					</li>
				</ul>

				<p>
					<label for="thumbnail_crop">
						<input name="thumbnail_crop" type="checkbox" id="thumbnail_crop" value="1" <?php checked( '1', get_option( 'thumbnail_crop' ) ); ?>/>
						<?php _e( 'Crop to exact dimensions' ); ?>
					</label>
				</p>
			</fieldset>

			<fieldset form="<?php echo $this->fields . '-settings'; ?>" class="form-tab-fieldset">

				<legend class="screen-reader-text"><span><?php _e( 'Medium Size Settings' ); ?></span></legend>

				<h3><?php _e( 'Medium Size' ); ?></h3>

				<ul class="form-field-list">
					<li>
						<label for="medium_size_w"><?php _e( 'Max Width' ); ?></label>
						<input name="medium_size_w" type="number" step="1" min="0" id="medium_size_w" value="<?php form_option( 'medium_size_w' ); ?>" class="small-text" />
					</li>
					<li>
						<label for="medium_size_h"><?php _e( 'Max Height' ); ?></label>
						<input name="medium_size_h" type="number" step="1" min="0" id="medium_size_h" value="<?php form_option( 'medium_size_h' ); ?>" class="small-text" />
					</li>
				</ul>

				<p>
					<label for="medium_crop">
						<input name="medium_crop" type="checkbox" id="medium_crop" value="1" <?php checked( '1', get_option( 'medium_crop' ) ); ?>/>
						<?php _e( 'Crop to exact dimensions' ); ?>
					</label>
				</p>

			</fieldset>

			<fieldset form="<?php echo $this->fields . '-settings'; ?>" class="form-tab-fieldset">

				<legend class="screen-reader-text"><span><?php _e( 'Large Size Settings' ); ?></span></legend>

				<h3><?php _e( 'Large Size' ); ?></h3>

				<ul class="form-field-list">
					<li>
						<label for="large_size_w"><?php _e( 'Max Width' ); ?></label>
						<input name="large_size_w" type="number" step="1" min="0" id="large_size_w" value="<?php form_option( 'large_size_w' ); ?>" class="small-text" />
					</li>
					<li>
						<label for="large_size_h"><?php _e( 'Max Height' ); ?></label>
						<input name="large_size_h" type="number" step="1" min="0" id="large_size_h" value="<?php form_option( 'large_size_h' ); ?>" class="small-text" />
					</li>
				</ul>

				<p>
					<label for="large_crop">
						<input name="large_crop" type="checkbox" id="large_crop" value="1" <?php checked( '1', get_option( 'large_crop' ) ); ?>/>
						<?php _e( 'Crop to exact dimensions' ); ?>
					</label>
				</p>

			</fieldset>
		</div>
		<?php
	}

	/**
	 * Galleries tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function galleries() {

		echo sprintf(
			'<p>%1s</p>',
			__( 'This section is under development.' )
		);
	}

	/**
	 * Uploads tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function library() {

		?>
		<div class="tab-section-wrap tab-section-wrap__library">

		<?php if ( isset( $GLOBALS['wp_settings']['media']['embeds'] ) ) : ?>

			<h3><?php _e( 'Embeds' ) ?></h3>

			<?php do_settings_fields( 'media', 'embeds' ); ?>

		<?php endif; ?>

		<?php if ( ! is_network() ) : ?>

			<h3><?php _e( 'File Uploads' ); ?></h3>

			<?php
			/**
			 * If upload_url_path is not the default (empty) and upload_path
			 * is not the default ( 'app-views/uploads' or empty).
			 */
			if ( get_option( 'upload_url_path' ) || ( get_option( 'upload_path' ) != 'app-views/uploads' && get_option( 'upload_path' ) ) ) :

			?>
			<p>
				<label for="upload_path"><?php _e( 'Store uploads in this folder' ); ?></label>
				<br /><input name="upload_path" type="text" id="upload_path" value="<?php echo esc_attr( get_option( 'upload_path' ) ); ?>" class="regular-text code" />
			</p>
			<p class="description"><?php
				printf( __( 'Default is %s' ), '<code>app-views/uploads</code>' );
			?></p>

			<p>
				<label for="upload_url_path"><?php _e( 'Full URL path to files' ); ?></label>
				<br /><input name="upload_url_path" type="text" id="upload_url_path" value="<?php echo esc_attr( get_option( 'upload_url_path' ) ); ?>" class="regular-text code" />
			</p>
			<p class="description"><?php _e( 'Configuring this is optional. By default, it should be blank.' ); ?></p>

			<?php else : ?>

			<p>
				<label for="uploads_use_yearmonth_folders">
					<input name="uploads_use_yearmonth_folders" type="checkbox" id="uploads_use_yearmonth_folders" value="1"<?php checked( '1', get_option( 'uploads_use_yearmonth_folders' ) ); ?> />
				<?php _e( 'Organize my uploads into month- and year-based folders' ); ?>
				</label>
			</p>

			<?php endif; // get_option( 'upload_url_path' ) ?>

			<?php do_settings_fields( 'media', 'uploads' ); ?>

			<?php if ( current_user_can( 'manage_options' ) ) : ?>

			<h3><?php _e( 'Deleting Files' ); ?></h3>

			<p>
				<label for="media_allow_trash">
					<input name="media_allow_trash" type="checkbox" id="media_allow_trash" value="1"<?php checked( '1', get_option( 'media_allow_trash' ) ); ?> />
					<?php _e( 'Allow trash for media library items' ); ?>
				</label>
			</p>

		</div>
		<?php endif;
	endif;

	}

	/**
	 * Help content
	 *
	 * Add content to the help section of the page.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function help() {

		$screen = get_current_screen();

		$screen->add_help_tab( [
			'id'       => $screen->id . '-overview',
			'title'    => __( 'Overview' ),
			'content'  => null,
			'callback' => [ $this, 'help_overview' ],
		] );
	}

	/**
	 * Overview help content
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help_overview() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Overview' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'You can set maximum sizes for images inserted into your written content; you can also insert an image as Full Size.' )
		);

		if ( ! is_network() && ( get_option( 'upload_url_path' ) || ( get_option( 'upload_path' ) != 'app-views/uploads' && get_option( 'upload_path' ) ) ) ) {

			$help .= sprintf(
				'<p>%1s</p>',
				__( 'Uploading Files allows you to choose the folder and path for storing your uploaded files.' )
			);
		}

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'You must click the Save Changes button at the bottom of the screen for new settings to take effect.' )
		);

		$help = apply_filters( 'help_settings_media_overview', $help );

		echo $help;
	}
}
