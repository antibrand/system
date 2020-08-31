<?php
/**
 * List manage screen class
 *
 * Bootstrap for list management pages such as
 * post types, users, plugins, themes, media, etc.
 *
 * @package App_Package
 * @subpackage Administration/Backend
 * @since 1.0.0
 */

namespace AppNamespace\Backend;

// Stop here if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * List manage screen class
 *
 * @since  1.0.0
 * @access public
 */
class List_Manage_Screen extends Admin_Screen {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return self
	 */
	protected function __construct() {

		parent :: __construct();

		// Edit capabilities.
		$this->die();
	}

	/**
	 * Edit capabilities
	 *
	 * Messages if the edit request cannot
	 * be fulfilled.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the die() message.
	 */
	public function die() {

		// wp_die( $message )
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
}
