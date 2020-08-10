<?php
/**
 * Permalink settings screen class
 *
 * This class is currently being used only to use identifier
 * functions and to add contextual help tabs.
 *
 * The tabbed content is added in the page template using
 * the `app-tabs` wrapper and data attributes.
 *
 * @todo Remove the help tabs added here if they cannot be used.
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
 * Permalink settings screen class
 *
 * @since  1.0.0
 * @access public
 */
class Settings_Permalinks extends Settings_Screen {

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
	 * Form action
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $action = 'options-permalink.php';

	/**
	 * Form fields
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string The name of the registered fields to be executed.
	 */
	public $fields = 'permalink';

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

		$this->title = __( 'URL Permalink Settings' );

		return apply_filters( 'settings_permalinks_page_title', $this->title );
	}

	/**
	 * Page description
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the description markup.
	 */
	public function description() {

		$this->description = sprintf(
			'<p class="description">%1s</p>',
			__( 'Create a custom URL structure for your permalinks and archives.' )
		);

		return apply_filters( 'settings_permalinks_page_description', $this->description );
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
			'id'         => 'general',
			'id_before'  => null,
			'capability' => 'manage_options',
			'tab'        => __( 'General' ),
			'icon'       => '',
			'heading'    => __( 'General Settings' ),
			'content'    => __( '' ),
			'callback'   => [ $this, 'general_settings' ]
		] );

		$screen->add_content_tab( [
			'id'         => 'taxonomy',
			'id_before'  => null,
			'capability' => 'manage_options',
			'tab'        => __( 'Taxonomy' ),
			'icon'       => '',
			'heading'    => __( 'Taxonomy Settings' ),
			'content'    => __( '' ),
			'callback'   => [ $this, 'taxonomy_settings' ]
		] );
	}

	/**
	 * General Settings tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function general_settings() {

		?>
		<div class="tab-section-wrap">
		</div>
		<?php
	}

	/**
	 * Taxonomy Settings tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function taxonomy_settings() {

		?>
		<div class="tab-section-wrap">
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

		$screen->add_help_tab( [
			'id'       => $screen->id . '-settings',
			'title'    => __( 'General' ),
			'content'  => '',
			'callback' => [ $this, 'help_settings' ]
		] );

		$screen->add_help_tab( [
			'id'      => $screen->id . '-custom-structures',
			'title'   => __( 'Taxonomy' ),
			'content' => '',
			'callback' => [ $this, 'help_taxonomy' ]
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
			__( 'Permalinks are the permanent URLs to your individual pages and blog posts, as well as your category and tag archives. A permalink is the web address used to link to your content. The URL to each post should be permanent, and never change &#8212; hence the name permalink.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'This screen allows you to choose your permalink structure. You can choose from common settings or create custom URL structures.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'You must click the ' . $this->submit . ' button at the bottom of the screen for new settings to take effect.' )
		);

		$help = apply_filters( 'help_settings_permalinks_overview', $help );

		echo $help;
	}

	/**
	 * Permalink Settings help content
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help_settings() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'General Settings' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'Permalinks can contain useful information, such as the post date, title, or other elements. You can choose from any of the suggested permalink formats, or you can craft your own if you select Custom Structure.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'If you pick an option other than Plain, your general URL path with structure tags (terms surrounded by <code>%</code>) will also appear in the custom structure field and your path can be further modified there.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'When you assign multiple categories or tags to a post, only one can show up in the permalink: the lowest numbered category. This applies if your custom structure includes <code>%category%</code> or <code>%tag%</code>.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'You must click the Save Changes button at the bottom of the screen for new settings to take effect.' )
		);

		$help = apply_filters( 'help_options_permalinks_settings', $help );

		echo $help;
	}

	/**
	 * Taxonomy Settings help content
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help_taxonomy() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Taxonomy Settings' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'The Optional fields let you customize the &#8220;category&#8221; and &#8220;tag&#8221; base names that will appear in archive URLs. For example, the page listing all posts in the &#8220;General&#8221; category could be <code>/topics/general</code> instead of <code>/category/general</code>.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'You must click the Save Changes button at the bottom of the screen for new settings to take effect.' )
		);

		$help = apply_filters( 'help_options_permalinks_structures', $help );

		echo $help;
	}
}
