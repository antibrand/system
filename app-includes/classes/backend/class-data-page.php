<?php
/**
 * Manage data page
 *
 * @package App_Package
 * @subpackage Administration
 * @since 1.0.0
 */

namespace AppNamespace\Backend;

/**
 * Manage data page class
 *
 * Displays tabbed content for managing website and network data.
 *
 * @since  1.0.0
 * @access public
 */
class Data_Page {

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

			// Instantiate the tabbed content.
			$instance->tabs();
		}

		// Return the instance.
		return $instance;
	}

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {}

	/**
	 * Tabbed content
	 *
	 * Add content to the tabbed section of the dashboard page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tabbed content.
	 */
	public function tabs() {

		// Stop if not on the relevant screen.
		$screen = get_current_screen();
		if ( 'manage-data' != $screen->id ) {
			return;
		}

		if ( is_multisite() ) {
			$start_heading = __( 'Network Data' );
		} else {
			$start_heading = __( 'Website Data' );
		}

		$start_heading = apply_filters( 'tabs_data_start_heading', $start_heading );

		// Start. Editor capability for post imporation.
		$screen->add_content_tab( [
			'id'         => 'data-start',
			'capability' => 'edit_posts',
			'tab'        => __( 'Data' ),
			'icon'       => '',
			'heading'    => $start_heading,
			'content'    => '',
			'callback'   => [ $this, 'start_tab' ]
		] );

		// Import data. Import capability.
		$screen->add_content_tab( [
			'id'         => 'data-import',
			'capability' => 'import',
			'tab'        => __( 'Import' ),
			'icon'       => '',
			'heading'    => __( 'Import Data' ),
			'content'    => '',
			'callback'   => [ $this, 'import_tab' ]
		] );

		// User accounts data. Erase others personal data capability.
		$screen->add_content_tab( [
			'id'         => 'data-accounts',
			'capability' => 'erase_others_personal_data',
			'tab'        => __( 'Accounts' ),
			'icon'       => '',
			'heading'    => __( 'Accounts Data' ),
			'content'    => '',
			'callback'   => [ $this, 'accounts_tab' ]
		] );
	}

	/**
	 * Start tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function start_tab() {

	?>
	<div class="tab-section-wrap tab-section-wrap__data-start">

		<p><?php _e( 'This section is in development.' ); ?></p>
	</div>
	<?php

	}

	/**
	 * Import tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function import_tab() {

	?>
	<div class="tab-section-wrap tab-section-wrap__data-import">

		<p><?php _e( 'This section is in development.' ); ?></p>
	</div>
	<?php

	}

	/**
	 * Accounts tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function accounts_tab() {

	?>
	<div class="tab-section-wrap tab-section-wrap__data-accounts">

		<p><?php _e( 'This section is in development.' ); ?></p>
	</div>
	<?php

	}
}