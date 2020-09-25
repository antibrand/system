<?php
/**
 * Settings screen class
 *
 * Bootstrap for settings & forms pages.
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
 * Settings screen class
 *
 * @since  1.0.0
 * @access public
 */
class Settings_Screen extends Admin_Screen {

	/**
	 * Page parent file
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string The parent file of the settings screen.
	 *                For instance, if the page is registered as a submenu
	 *                item of options-general.php then that is the parent.
	 */
	public $parent = 'options-general.php';

	/**
	 * Form action
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string This will most likely be options.php.
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
	 * @since  1.0.0
	 * @access public
	 * @var    string The thext of the form submit button.
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

		// Run the parent constructor method.
		parent :: __construct();

		// Allow hashtags for content tabs.
		add_filter( 'app_tabs_hashtags', '__return_true' );
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
		do_action( 'render_screen_tabs' );
		do_action( "settings_screen-{$this->fields}-tabs_after" );

		echo get_submit_button( esc_html__( $this->submit ) );

		echo '</form>';
	}
}
