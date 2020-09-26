<?php
/**
 * List manage users class
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
 * List manage users class
 *
 * @since  1.0.0
 * @access public
 */
class List_Manage_Users extends List_Manage_Screen {

	/**
	 * Page parent file
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string The parent file of the administration screen.
	 */
	public $parent = 'users.php';

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
	 * Edit capabilities
	 *
	 * Messages if the edit request cannot
	 * be fulfilled.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the die() message.
	 */
	public function die() {

		if ( ! current_user_can( 'list_users' ) ) {
			wp_die(
				'<p><strong>' . __( 'You need a higher level of permission.' ) . '</strong></p>' .
				'<p>' . __( 'Sorry, you are not allowed to list users.' ) . '</p>',
				403
			);
		}
	}

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the translated title.
	 */
	public function title() {

		$title = __( 'User Accounts' );

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
	 * Screen options
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function screen_options() {

		add_screen_option( 'per_page' );
	}

	/**
	 * Help content
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help() {

		// Help overview.
		get_current_screen()->add_help_tab( [
			'id'       => 'overview',
			'title'    => __( 'Overview' ),
			'content'  => null,
			'callback' => [ $this, 'help_overview' ]
		] );

		get_current_screen()->add_help_tab( [
			'id'       => 'screen-content',
			'title'    => __( 'Screen Content' ),
			'content'  => null,
			'callback' => [ $this, 'help_screen_content' ]
		] );

		get_current_screen()->add_help_tab( [
			'id'       => 'action-links',
			'title'    => __( 'Available Actions' ),
			'content'  => null,
			'callback' => [ $this, 'help_action_links' ]
		] );
	}

	/**
	 * Help overview tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the markup of the tab content.
	 */
	public function help_overview() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Overview' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'This screen lists all the existing users for your site. Each user has one of five defined roles as set by the site admin: Site Administrator, Editor, Author, Contributor, or Subscriber. Users with roles other than Administrator will see fewer options in the dashboard navigation when they are logged in, based on their role.' )
		);


		$help .= sprintf(
			'<p>%1s</p>',
			__( 'To add a new user for your site, click the Add New button at the top of the screen or Add New in the Users menu section.' )
		);

		echo apply_filters( 'help_users_overview', $help );
	}

	/**
	 * Help screen content tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the markup of the tab content.
	 */
	public function help_screen_content() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Screen Content' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'You can customize the display of this screen in a number of ways:' )
		);

		$help .= '<ul>';

		$help .= sprintf(
			'<li>%1s</li>',
			__( 'You can hide/display columns based on your needs and decide how many users to list per screen using the Screen Options tab.' )
		);

		$help .= sprintf(
			'<li>%1s</li>',
			__( 'You can filter the list of users by User Role using the text links above the users list to show All, Administrator, Editor, Author, Contributor, or Subscriber. The default view is to show all users. Unused User Roles are not listed.' )
		);

		$help .= sprintf(
			'<li>%1s</li>',
			__( 'You can view all posts made by a user by clicking on the number under the Posts column.' )
		);

		$help .= '</ul>';

		echo apply_filters( 'help_users_screen_content', $help );
	}

	/**
	 * Help screen content tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the markup of the tab content.
	 */
	public function help_action_links() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Available Actions' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'Hovering over a row in the users list will display action links that allow you to manage users. You can perform the following actions:' )
		);

		$help .= '<ul>';

		$help .= sprintf(
			'<li>%1s</li>',
			__( '<strong>Edit</strong> takes you to the editable profile screen for that user. You can also reach that screen by clicking on the username.' )
		);

		if ( is_network() ) {
			$help .= sprintf(
				'<li>%1s</li>',
				__( '<strong>Remove</strong> allows you to remove a user from your site. It does not delete their content. You can also remove multiple users at once by using Bulk Actions.' )
			);
		} else {
			$help .= sprintf(
				'<li>%1s</li>',
				__( '<strong>Delete</strong> brings you to the Delete Users screen for confirmation, where you can permanently remove a user from your site and delete their content. You can also delete multiple users at once by using Bulk Actions.' )
			);
		}

		$help .= '</ul>';

		echo apply_filters( 'help_users_action_links', $help );
	}
}
