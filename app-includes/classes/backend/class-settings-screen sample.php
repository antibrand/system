<?php
/**
 * Sample settings screen class
 *
 * Use this to add a new settings page.
 * Remove what is unnecessary, add what is needed.
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
 * Sample settings screen class
 *
 * @since  1.0.0
 * @access public
 */
class Settings_Sample extends Settings_Screen {

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
	public $title = 'Sample Settings';

	/**
	 * Page description
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $description = 'Use this to add a new settings page. Remove what is unnecessary, add what is needed.';

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
	 */
	public $fields = 'sample';

	/**
	 * Submit button
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string The thext of the form submit button.
	 */
	public $submit = 'Save Settings';

	/**
	 * Instance of the class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object Returns the instance.
	 */
	public static function instance() {

		// Return the instance.
		return new self;
	}

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

		// Enqueue page scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'child_enqueue_scripts' ] );

		// Print page scripts to head.
		add_action( 'admin_head', [ $this, 'child_print_scripts' ] );
	}

	/**
	 * Enqueue scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function child_enqueue_scripts() {

		// Enqueue scripts.
	}

	/**
	 * Print scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function child_print_scripts() {

		// Print scripts.
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
			'tab'        => __( 'Sample' ),
			'icon'       => '',
			'heading'    => __( 'Sample Tab' ),
			'content'    => __( 'This content is not from a callback method.' ),
			'callback'   => [ $this, 'sample' ]
		] );
	}

	/**
	 * Sample tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function sample() {

		?>
		<div class="tab-section-wrap">
			<p><?php _e( 'This content is from a callback method.' ); ?></p>
		</div>
		<?php
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
			'callback' => [ $this, 'help_overview' ]
		] );
	}

	/**
	 * Overview help content
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help_overview() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Overview' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'This is sample help content.' )
		);

		$help = apply_filters( 'help_settings_sample_overview', $help );

		echo $help;
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
		return apply_filters( 'help_settings_sample_sidebar', '' );
	}
}
