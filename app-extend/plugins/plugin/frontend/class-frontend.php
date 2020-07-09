<?php
/**
 * The frontend functionality of the plugin
 *
 * @package    Plugin
 * @subpackage Frontend
 *
 * @since      1.0.0
 */

namespace Plugin\Frontend;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The frontend functionality of the plugin
 *
 * @since  1.0.0
 * @access public
 */
class Frontend {

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

			// Frontend dependencies
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
	 * Frontend dependencies
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function dependencies() {}

}

/**
 * Put an instance of the class into a function
 *
 * @since  1.0.0
 * @access public
 * @return object Returns an instance of the class.
 */
function abp_frontend() {

	return Frontend::instance();

}

// Run an instance of the class.
abp_frontend();
