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

	// The screen's parent file.
	public $parent = '';

	// Page title.
	public $title = '';

	// Page description.
	public $description = '';

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return self
	 */
	protected function __construct() {

		add_action( 'admin_enqueue_scripts', [ $this, 'scripts' ] );

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
		return $this->title;
	}

	/**
	 * Page description
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the description markup.
	 */
	public function description() {
		return $this->description;
	}

	/**
	 * Enqueue scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function scripts() {

		// Script for tabbed content.
		wp_enqueue_script( 'app-tabs' );
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
			'heading'    => '',
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

		echo '<form method="post" action="options.php">';

		do_action( 'settings_screen_add_fields_before' );
		do_action( 'render_tabs_settings_screen' );
		do_action( 'settings_screen_add_fields_after' );

		echo sprintf(
			'<p>%1s</p>',
			get_submit_button( __( 'Save Settings' ) )
		);

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
			'callback' => null,
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
		return apply_filters( 'settings_page_help_sidebar', '' );
	}
}
