<?php
/**
 * Dependencies API: App_Dependency class
 *
 * @package App_Package
 * @subpackage Dependencies
 * @since Previous 4.7.0
 */

/**
 * Class App_Dependency
 *
 * Helper class to register a handle and associated data.
 *
 * @access private
 * @since  Previous 2.6.0
 */
class App_Dependency {

	/**
	 * The handle name
	 *
	 * @since Previous 2.6.0
	 * @var   null
	 */
	public $handle;

	/**
	 * The handle source
	 *
	 * @since Previous 2.6.0
	 * @var   null
	 */
	public $src;

	/**
	 * An array of handle dependencies
	 *
	 * @since Previous 2.6.0
	 * @var   array
	 */
	public $deps = [];

	/**
	 * The handle version
	 *
	 * Used for cache-busting.
	 *
	 * @since Previous 2.6.0
	 * @var   bool|string
	 */
	public $ver = false;

	/**
	 * Additional arguments for the handle
	 *
	 * Custom property, such as $in_footer or $media.
	 *
	 * @since Previous 2.6.0
	 * @var null
	 */
	public $args = null;

	/**
	 * Extra data to supply to the handle.
	 *
	 * @since Previous 2.6.0
	 * @var   array
	 */
	public $extra = [];

	/**
	 * Setup dependencies
	 *
	 * @since  Previous 2.6.0
	 * @return self
	 */
	public function __construct() {

		@list( $this->handle, $this->src, $this->deps, $this->ver, $this->args ) = func_get_args();

		if ( ! is_array( $this->deps ) ) {
			$this->deps = [];
		}
	}

	/**
	 * Add handle data
	 *
	 * @since  Previous 2.6.0
	 * @param  string $name The data key to add.
	 * @param  mixed  $data The data value to add.
	 * @return bool False if not scalar, true otherwise.
	 */
	public function add_data( $name, $data ) {

		if ( ! is_scalar( $name ) ) {
			return false;
		}

		$this->extra[$name] = $data;

		return true;
	}

}
