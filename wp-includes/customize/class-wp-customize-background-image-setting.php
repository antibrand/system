<?php
/**
 * Live manager API: WP_Customize_Background_Image_Setting class
 *
 * @package App_Package
 * @subpackage Live_Manager
 * @since 4.4.0
 */

/**
 * Live manager background image setting class
 *
 * @since 3.4.0
 *
 * @see WP_Customize_Setting
 */
final class WP_Customize_Background_Image_Setting extends WP_Customize_Setting {
	public $id = 'background_image_thumb';

	/**
	 * @since 3.4.0
	 *
	 * @param $value
	 */
	public function update( $value ) {
		remove_theme_mod( 'background_image_thumb' );
	}
}
