<?php
/**
 * Sample administration screen class
 *
 * Use this to add a new administration page.
 * Remove what is unnecessary, add what is needed.
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
 * Sample screen class
 *
 * @since  1.0.0
 * @access public
 */
class Admin_Sample extends Admin_Screen {

	/**
	 * Page parent file
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string The parent file of the administration screen.
	 */
	public $parent = '';

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

		// Sample tab.
		$description = sprintf(
			'<p class="description">%1s</p>',
			__( 'This is a sample tab description.' )
		);
		$screen->add_content_tab( [
			'id'         => $screen->id . '-sample',
			'capability' => 'manage_options',
			'tab'        => '',
			'icon'       => '',
			'heading'    => '',
			'content'    => '',
			'callback'   => [ $this, 'sample' ]
		] );
		unset( $description );
	}

	/**
	 * Sample tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function sample() {

		?>
		<p><?php _e( 'Put section content here.' ); ?></p>
		<?php
	}
}
