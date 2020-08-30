<?php
/**
 * Admin functiontionality and pages
 * @package    Plugin
 * @subpackage Admin
 *
 * @since      1.0.0
 */

namespace Plugin\Admin;

// Alias namespaces.
use \AppNamespace\Backend as Backend;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Admin functiontionality and pages
 *
 * @since  1.0.0
 * @access public
 */
class Admin {

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

			// Require the class files.
			$instance->dependencies();

		}

		// Return the instance.
		return $instance;

	}

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access private
	 * @return self
	 */
	private function __construct() {

		// Add dashboard widgets.
		add_action( 'app_dashboard_setup', [ $this, 'dashboard_widgets' ] );
	}

	/**
	 * Class dependency files
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function dependencies() {}

	/**
	 * Class dependency files
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function dashboard_widgets() {

		// Instance of the Dashboard class.
		$dashboard = new Backend\Dashboard;

		$dashboard->add_dashboard_widget( 'demo_widget_one', __( 'Demo Widget One' ), [ $this, 'demo_dashboard_widget_one' ] );
		$dashboard->add_dashboard_widget( 'demo_widget_two', __( 'Demo Widget Two' ), [ $this, 'demo_dashboard_widget_two' ] );
	}

	/**
	 * Demo dashboard widget
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the widget.
	 */
	public function demo_dashboard_widget_one() {

	?>
		<h3><?php _e( 'Demo Dashboard Widget #1' ); ?></h3>
		<p><?php _e( 'Demonstration widget added via plugin.' ); ?></p>
	<?php
	}

	/**
	 * Demo dashboard widget
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the widget.
	 */
	public function demo_dashboard_widget_two() {

	?>
		<h3><?php _e( 'Demo Dashboard Widget #2' ); ?></h3>
		<p><?php _e( 'Demonstration widget added via plugin.' ); ?></p>
	<?php
	}

}

/**
 * Put an instance of the class into a function
 *
 * @since  1.0.0
 * @access public
 * @return object Returns an instance of the class.
 */
function abp_admin() {

	return Admin::instance();

}

// Run an instance of the class.
abp_admin();
