<?php
/**
 * Administration screen class
 *
 * Bootstrap for administration pages.
 *
 * @package App_Package
 * @subpackage Administration/Backend
 * @since 1.0.0
 *
 * For screen requiring form subbmission extend
 * the `Settings_Screen` class.
 *
 * @see APP_INC_PATH/classes/backend/class-settings-screen.php
 */

namespace AppNamespace\Backend;

// Stop here if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Administration screen class
 *
 * @since  1.0.0
 * @access public
 */
class Admin_Screen {

	/**
	 * Page parent file
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string The parent file of the administration screen.
	 *                For instance, if the page is registered as a submenu
	 *                item of index.php then that is the parent.
	 */
	public $parent = '';

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $title = '';

	/**
	 * Page description
	 *
	 * @since 1.0.0
	 * @access public
	 * @var    string
	 */
	public $description = '';

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return self
	 */
	protected function __construct() {

		// Enqueue page scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'parent_enqueue_scripts' ] );

		// Print page scripts to head.
		add_action( 'admin_head', [ $this, 'parent_print_scripts' ] );

		// Add screen options.
		add_action( 'admin_head', [ $this, 'screen_options' ] );

		// Render tabbed content.
		add_action( 'render_screen_tabs', [ $this, 'render_tabs' ] );

		// Set the tabbed content.
		$this->tabs();

		// Set the help content.
		$this->help();

		// Set the help sidebar
		$this->set_help_sidebar();
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
	 * This is for scripts that shall not be
	 * overridden by class extension. Specific
	 * screens should use enqueue_scripts() to
	 * enqueue scripts for its screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function parent_enqueue_scripts() {

		// Script for tabbed content.
		wp_enqueue_script( 'app-tabs' );
	}

	/**
	 * Print scripts
	 *
	 * This is for scripts that shall not be
	 * overridden by class extension. Specific
	 * screens should use print_scripts() to
	 * print scripts for its screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function parent_print_scripts() {

		// Print scripts
	}

	/**
	 * Screen options
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function screen_options() {

		// add_screen_option();
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
			'id'         => $screen->id . '-page',
			'capability' => 'manage_options',
			'tab'        => '',
			'icon'       => '',
			'heading'    => '',
			'content'    => '',
			'callback'   => null
		] );
	}

	/**
	 * Render tabbed content
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function render_tabs() {
		echo get_current_screen()->render_content_tabs();
	}

	/**
	 * Help content
	 *
	 * Add content to the help section of the page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help() {

		$screen = get_current_screen();

		$screen->add_help_tab( [
			'id'       => $screen->id . '-overview',
			'title'    => __( 'Overview' ),
			'content'  => '',
			'callback' => null
		] );
	}

	/**
	 * Set help sidebar
	 *
	 * Use the help_sidebar() method when extending the class
	 * to render the sidebar markup.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function set_help_sidebar() {

		$screen = get_current_screen();

		// Add a help sidebar.
		$screen->set_help_sidebar(
			$this->help_sidebar()
		);
	}

	/**
	 * Help sidebar
	 *
	 * Render the sidebar markup.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void Applies a filter for the markup of the help sidebar content.
	 */
	public function help_sidebar() {
		return apply_filters( 'help_admin_page_sidebar', '' );
	}
}
