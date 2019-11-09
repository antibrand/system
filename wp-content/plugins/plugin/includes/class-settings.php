<?php
/**
 * The core settings class for the plugin
 *
 * @package    Plugin
 * @subpackage Includes
 *
 * @since      1.0.0
 */

namespace Plugin\Includes;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core settings class for the plugin
 *
 * @since  1.0.0
 * @access public
 */
class Settings {

	/**
	 * Instance of the class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object Returns the instance.
	 */
	public static function instance() {

		// Varialbe for the instance to be used outside the class.
		static $instance = null;

		if ( is_null( $instance ) ) {

			// Set variable for new instance.
			$instance = new self;

			// Require the class files.
			$instance->dependencies();

		}

		// Return the instance.
		return $instance;

	}

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {}

	/**
	 * Class dependency files
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function dependencies() {}

}

/**
 * Put an instance of the class into a function
 *
 * @since  1.0.0
 * @access public
 * @return object Returns an instance of the class.
 */
function abp_settings() {

	return Settings::instance();

}

// Run an instance of the class.
abp_settings();
