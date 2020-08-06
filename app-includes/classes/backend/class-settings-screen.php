<?php
/**
 * Settings screen class
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
class Settings_Screen {

	/**
	 * Page parent file
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string The parent file of the settings screen.
	 *             For instance, if the page is registered as a submenu
	 *             item of options-general.php then that is the parent.
	 */
	public $parent = 'options-general.php';

	/**
	 * Page title
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $title = '';

	/**
	 * Page description
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $description = '';

	/**
	 * Form action
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string This will most likely be options.php.
	 */
	public $action = 'options.php';

	/**
	 * Form fields
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string The name of the registered fields to be executed.
	 *             Defaults are 'general', 'writing', 'reading', permalinks'.
	 */
	public $fields = '';

	/**
	 * Submit button
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string The thext of the form submit button.
	 */
	public $submit = 'Save Settings';

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

		// Allow hashtags for content tabs.
		add_filter( 'app_tabs_hashtags','__return_true' );

		// Render tabbed content.
		add_action( 'render_tabs_settings_screen', [ $this, 'render_tabs' ] );

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
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function parent_print_scripts() {

		// Print scripts
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
	 * Render settings form
	 *
	 * Compiles the markup (fields, labels, descriptions, etc.)
	 * from registered tabs and prints them inside a form element.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function render_form() {

		echo "<form id='$this->fields-settings' method='post' action='$this->action'>";

		settings_fields( $this->fields );

		do_action( "settings_screen_{$this->fields}_tabs_before" );
		do_action( 'render_tabs_settings_screen' );
		do_action( "settings_screen-{$this->fields}-tabs_after" );

		echo get_submit_button( esc_html__( $this->submit ) );

		echo '</form>';
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
	 * @since 1.0.0
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
	 * @since 1.0.0
	 * @access public
	 * @return void Applies a filter for the markup of the help sidebar content.
	 */
	public function help_sidebar() {
		return apply_filters( 'help_settings_page_sidebar', '' );
	}
}
