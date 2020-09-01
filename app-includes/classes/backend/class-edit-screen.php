<?php
/**
 * Edit screen class
 *
 * For post type edit pages.
 *
 * @package App_Package
 * @subpackage Classes/Backend
 * @since 1.0.0
 */

namespace AppNamespace\Backend;

// Stop here if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Edit screen class
 *
 * @since  1.0.0
 * @access public
 */
class Edit_Screen extends Admin_Screen {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return self
	 */
	protected function __construct() {

		parent :: __construct();

		// Edit screen actions.
		$this->action();
	}

	/**
	 * Edit screen actions
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function action() {

		// Edit actions.
	}
}
