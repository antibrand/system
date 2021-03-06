<?php
/**
 * Administration screen class
 *
 * Bootstrap for administration pages.
 *
 * This is a parent class for several screen type classes
 * which in turn are parent to specific screen classes.
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
	 * Submenu file
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $submenu = '';

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

		// Get the current screen.
		$this->screen();

		// Page capabilities.
		$this->die();

		// Enqueue page parent scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'parent_enqueue_scripts' ] );

		// Print page parent scripts to head.
		add_action( 'admin_head', [ $this, 'parent_print_scripts' ] );

		// Enqueue page-specific scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// Print page-specific scripts to head.
		add_action( 'admin_head', [ $this, 'print_scripts' ] );

		// Enqueue page parent styles.
		add_action( 'admin_enqueue_scripts', [ $this, 'parent_enqueue_styles' ] );

		// Print page parent styles to head.
		add_action( 'admin_head', [ $this, 'parent_print_styles' ] );

		// Enqueue page-specific styles.
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );

		// Print page-specific styles to head.
		add_action( 'admin_head', [ $this, 'print_styles' ] );

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
	 * Current screen
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the translated title.
	 */
	public function screen() {

		// Get the current scrren.
		return get_current_screen()->id;
	}

	/**
	 * Page capabilities
	 *
	 * Messages if the page request cannot
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

	/**
	 * Enqueue page parent scripts
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
	 * Print page parent scripts
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

		// <script></script>
		// file_get_contents();
	}

	/**
	 * Enqueue page-specific scripts
	 *
	 * This is for scripts that are
	 * spefific to a screen class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_scripts() {

		// wp_enqueue_script();
	}

	/**
	 * Print page-specific scripts
	 *
	 * This is for scripts that are
	 * spefific to a screen class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function print_scripts() {

		// <script></script>
		// file_get_contents();
	}

	/**
	 * Enqueue page parent styles
	 *
	 * This is for styles that shall not be
	 * overridden by class extension. Specific
	 * screens should use enqueue_styles() to
	 * enqueue styles for its screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function parent_enqueue_styles() {

		// wp_enqueue_style();
	}

	/**
	 * Print page parent styles
	 *
	 * This is for styles that shall not be
	 * overridden by class extension. Specific
	 * screens should use print_styles() to
	 * print styles for its screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function parent_print_styles() {

		// <style></style>
		// file_get_contents();
	}

	/**
	 * Enqueue page-specific styles
	 *
	 * This is for styles that are
	 * spefific to a screen class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_styles() {

		// wp_enqueue_style();
	}

	/**
	 * Print page-specific styles
	 *
	 * This is for styles that are
	 * spefific to a screen class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function print_styles() {

		// <style></style>
		// file_get_contents();
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
