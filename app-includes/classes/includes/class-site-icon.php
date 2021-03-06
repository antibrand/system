<?php
/**
 * Browser_Icon class
 *
 * @package App_Package
 * @subpackage Includes
 * @since Previous 4.3.0
 */

namespace AppNamespace\Includes;

/**
 * Core class used to implement site icon functionality.
 *
 * @since Previous 4.3.0
 */
class Browser_Icon {

	/**
	 * The minimum size of the site icon.
	 *
	 * @since  WP 4.3.0
	 * @var    int
	 * @access public
	 */
	public $min_size  = 512;

	/**
	 * The size to which to crop the image so that we can display it in the UI nicely.
	 *
	 * @since  WP 4.3.0
	 * @var    int
	 * @access public
	 */
	public $page_crop = 512;

	/**
	 * List of site icon sizes.
	 *
	 * @since  WP 4.3.0
	 * @var    array
	 * @access public
	 */
	public $site_icon_sizes = [
		/*
		 * Square, medium sized tiles for IE11+.
		 */
		270,

		/*
		 * App icon for Android/Chrome.
		 *
		 * @link https://developer.chrome.com/multidevice/android/installtohomescreen
		 */
		192,

		/*
		 * App icons up to iPhone 6 Plus.
		 */
		180,

		// Our regular Favicon.
		32,
	];

	/**
	 * Registers actions and filters.
	 *
	 * @since  WP 4.3.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		add_action( 'delete_attachment', [ $this, 'delete_attachment_data' ] );
		add_filter( 'get_post_metadata', [ $this, 'get_post_metadata' ], 10, 4 );
	}

	/**
	 * Creates an attachment 'object'.
	 *
	 * @since  WP 4.3.0
	 * @access public
	 * @param  string $cropped Cropped image URL.
	 * @param  int $parent_attachment_id Attachment ID of parent image.
	 * @return array Attachment object.
	 */
	public function create_attachment_object( $cropped, $parent_attachment_id ) {

		$parent     = get_post( $parent_attachment_id );
		$parent_url = wp_get_attachment_url( $parent->ID );
		$url        = str_replace( basename( $parent_url ), basename( $cropped ), $parent_url );

		$size       = @getimagesize( $cropped );
		$image_type = ( $size ) ? $size['mime'] : 'image/jpeg';

		$object = [
			'ID'             => $parent_attachment_id,
			'post_title'     => basename( $cropped ),
			'post_content'   => $url,
			'post_mime_type' => $image_type,
			'guid'           => $url,
			'context'        => 'site-icon'
		];

		return $object;
	}

	/**
	 * Inserts an attachment.
	 *
	 * @since Previous 4.3.0
	 * @access public
	 * @param array $object Attachment object.
	 * @param string $file File path of the attached image.
	 * @return int Attachment ID
	 */
	public function insert_attachment( $object, $file ) {

		$attachment_id = wp_insert_attachment( $object, $file );
		$metadata      = wp_generate_attachment_metadata( $attachment_id, $file );

		/**
		 * Filters the site icon attachment metadata.
		 *
		 * @see wp_generate_attachment_metadata()
		 *
		 * @since Previous 4.3.0
		 * @param array $metadata Attachment metadata.
		 */
		$metadata = apply_filters( 'site_icon_attachment_metadata', $metadata );
		wp_update_attachment_metadata( $attachment_id, $metadata );

		return $attachment_id;
	}

	/**
	 * Adds additional sizes to be made when creating the browser_icon images.
	 *
	 * @since Previous 4.3.0
	 * @access public
	 * @param array $sizes List of additional sizes.
	 * @return array Additional image sizes.
	 */
	public function additional_sizes( $sizes = [] ) {

		$only_crop_sizes = [];

		/**
		 * Filters the different dimensions that a site icon is saved in.
		 *
		 * @since Previous 4.3.0
		 * @param array $site_icon_sizes Sizes available for the browser icon.
		 */
		$this->site_icon_sizes = apply_filters( 'site_icon_image_sizes', $this->site_icon_sizes );

		// Use a natural sort of numbers.
		natsort( $this->site_icon_sizes );
		$this->site_icon_sizes = array_reverse( $this->site_icon_sizes );

		// Ensure that we only resize the image into.
		foreach ( $sizes as $name => $size_array ) {

			if ( isset( $size_array['crop'] ) ) {
				$only_crop_sizes[ $name ] = $size_array;
			}
		}

		foreach ( $this->site_icon_sizes as $size ) {

			if ( $size < $this->min_size ) {
				$only_crop_sizes[ 'browser_icon-' . $size ] = [
					'width ' => $size,
					'height' => $size,
					'crop'   => true,
				];
			}
		}

		return $only_crop_sizes;
	}

	/**
	 * Adds browser icon sizes to the array of image sizes on demand.
	 *
	 * @since  WP 4.3.0
	 * @access public
	 * @param  array $sizes List of image sizes.
	 * @return array List of intermediate image sizes.
	 */
	public function intermediate_image_sizes( $sizes = [] ) {

		// This filter is documented in this file.
		$this->site_icon_sizes = apply_filters( 'site_icon_image_sizes', $this->site_icon_sizes );

		foreach ( $this->site_icon_sizes as $size ) {
			$sizes[] = 'browser_icon-' . $size;
		}

		return $sizes;
	}

	/**
	 * Deletes the browser icon when the image file is deleted.
	 *
	 * @since  WP 4.3.0
	 * @access public
	 * @param  int $post_id Attachment ID.
	 */
	public function delete_attachment_data( $post_id ) {

		$site_icon_id = get_option( 'browser_icon' );

		if ( $site_icon_id && $post_id == $site_icon_id ) {
			delete_option( 'browser_icon' );
		}
	}

	/**
	 * Adds custom image sizes when meta data for an image is requested, that happens to be used as browser icon.
	 *
	 * @since  WP 4.3.0
	 * @access public
	 * @param  null|array|string $value The value get_metadata() should return a single metadata value, or an
	 *                                  array of values.
	 * @param  int $post_id Post ID.
	 * @param  string $meta_key Meta key.
	 * @param  string|array $single Meta value, or an array of values.
	 * @return array|null|string The attachment metadata value, array of values, or null.
	 */
	public function get_post_metadata( $value, $post_id, $meta_key, $single ) {

		if ( $single && '_wp_attachment_backup_sizes' === $meta_key ) {

			$site_icon_id = get_option( 'browser_icon' );

			if ( $post_id == $site_icon_id ) {
				add_filter( 'intermediate_image_sizes', [ $this, 'intermediate_image_sizes' ] );
			}
		}

		return $value;
	}
}