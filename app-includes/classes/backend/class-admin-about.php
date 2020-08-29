<?php
/**
 * About screen class
 *
 * Informational page about the website management system.
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
 * About screen class
 *
 * @since  1.0.0
 * @access public
 */
class Admin_About extends Admin_Screen {

	/**
	 * Page parent file
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string The parent file of the settings screen.
	 */
	public $parent = 'index.php';

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
	 * @var string
	 */
	public $description = '';

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
	}

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the translated title.
	 */
	public function title() {

		$name    = get_app_info( 'name' );
		$version = get_app_info( 'version' );

		$title = sprintf(
			'%1s %2s',
			esc_html__( $name ),
			$version
		);

		return apply_filters( 'about_page_title', $title );
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

		// Introduction.
		$description = sprintf(
			'<p class="description">%1s</p>',
			__( 'Learn more about the content management system.' )
		);
		$screen->add_content_tab( [
			'id'         => $screen->id . '-intro',
			'capability' => 'manage_options',
			'tab'        => __( 'Intro' ),
			'heading'    => __( 'Introduction' ),
			'content'    => $description,
			'callback'   => [ $this, 'introduction' ]
		] );
		unset( $description );

		// Features.
		$description = sprintf(
			'<p class="description">%1s</p>',
			__( 'Features of the content management system.' )
		);
		$screen->add_content_tab( [
			'id'         => $screen->id . '-features',
			'capability' => 'manage_options',
			'tab'        => __( 'Features' ),
			'heading'    => __( 'Features' ),
			'content'    => $description,
			'callback'   => [ $this, 'features' ]
		] );
		unset( $description );

		// Manage.
		$description = sprintf(
			'<p class="description">%1s</p>',
			__( '' )
		);
		$screen->add_content_tab( [
			'id'         => $screen->id . '-manage',
			'capability' => 'manage_options',
			'tab'        => __( 'Manage' ),
			'heading'    => __( 'Manage' ),
			'content'    => $description,
			'callback'   => [ $this, 'manage' ]
		] );
		unset( $description );

		// Extend.
		$description = sprintf(
			'<p class="description">%1s</p>',
			__( 'Extending the functionality of the content management system.' )
		);
		$screen->add_content_tab( [
			'id'         => $screen->id . '-extend',
			'capability' => 'manage_options',
			'tab'        => __( 'Extend' ),
			'heading'    => __( 'Extend' ),
			'content'    => $description,
			'callback'   => [ $this, 'extend' ]
		] );
		unset( $description );
	}

	/**
	 * Introduction
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function introduction() {

		?>
		<p><?php _e( 'Put section content here.' ); ?></p>
		<?php
	}

	/**
	 * Features
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function features() {

		?>
		<p><?php _e( 'Put section content here.' ); ?></p>
		<?php
	}

	/**
	 * Manage
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function manage() {

		?>
		<p><?php _e( 'Put section content here.' ); ?></p>
		<?php
	}

	/**
	 * Extend
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function extend() {

		?>
		<p><?php _e( 'Put section content here.' ); ?></p>
		<?php
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

		// No help needed for this page.
		return null;
	}
}
