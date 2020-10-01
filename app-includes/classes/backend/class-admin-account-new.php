<?php
/**
 * New user account screen class
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
 * New user account screen class
 *
 * @since  1.0.0
 * @access public
 */
class Admin_Account_New extends Admin_Screen {

	/**
	 * Page parent file
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string The parent file of the administration screen.
	 */
	public $parent = 'users.php';

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $title = 'Add New User';

	/**
	 * Page description
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $description = 'Create a new user account for this website.';

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

		if ( current_user_can( 'create_users' ) ) {
			$title = __( 'Add New User' );
		} elseif ( current_user_can( 'promote_users' ) ) {
			$title = __( 'Add Existing User' );
		} else {
			$title = $this->title;
		}

		$title = esc_html__( $title );

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
	 * Page capabilities
	 *
	 * Messages if the page request cannot
	 * be fulfilled.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the die() message.
	 */
	public function die() {

		if ( is_network() ) {
			if ( ! current_user_can( 'create_users' ) && ! current_user_can( 'promote_users' ) ) {
				wp_die(
					'<p><strong>' . __( 'You need a higher level of permission.' ) . '</strong></p>' .
					'<p>' . __( 'Sorry, you are not allowed to add users to this network.' ) . '</p>',
					403
				);
			}
		} elseif ( ! current_user_can( 'create_users' ) ) {
			wp_die(
				'<p><strong>' . __( 'You need a higher level of permission.' ) . '</strong></p>' .
				'<p>' . __( 'Sorry, you are not allowed to create users.' ) . '</p>',
				403
			);
		}
	}

	/**
	 * Enqueue page-specific scripts
	 *
	 * This is for scripts that are
	 * spefific to a screen class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'wp-ajax-response' );
		wp_enqueue_script( 'user-profile' );
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

	/**
	 * Help content
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help() {

		$screen = get_current_screen();

		if ( is_network() ) {
			$help = 'help_network_overview';
		} else {
			$help = 'help_overview';
		}

		$screen->add_help_tab( [
			'id'       => $screen->id . '-overview',
			'title'    => __( 'Overview' ),
			'content'  => null,
			'callback' => [ $this, $help ]
		] );

		$screen->add_help_tab( [
			'id'       => 'user-roles',
			'title'    => __( 'User Roles' ),
			'content'  => null,
			'callback' => [ $this, 'help_user_roles' ]
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
			__( 'To add a new user account, fill in the form on this screen and click the Add New User button at the bottom.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'New users are automatically assigned a password, which they can change after logging in. You can view or edit the assigned password by clicking the Show Password button. The username cannot be changed once the user has been added.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'By default, new users will receive an email letting them know they&#8217;ve been added as a user for your site. This email will also contain a password reset link. Uncheck the box if you don&#8217;t want to send the new user a welcome email.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'Remember to click the Add New User button at the bottom of this screen when you are finished.' )
		);

		echo apply_filters( 'help_account_new_overview', $help );
	}

	/**
	 * Network overview help content
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help_network_overview() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Overview' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'To add a new user to account, fill in the form on this screen and click the Add New User button at the bottom.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'Because this is a network installation, you may add accounts that already exist on the Network by specifying a username or email, and defining a role. For more options, such as specifying a password, you have to be a Network Administrator and use the hover link under an existing user&#8217;s name to Edit the user profile under Network Admin > All Users.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'New users will receive an email letting them know they&#8217;ve been added as a user for your site. This email will also contain their password. Check the box if you don&#8217;t want the user to receive a welcome email.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'Remember to click the Add New User button at the bottom of this screen when you are finished.' )
		);

		echo apply_filters( 'help_account_new_network_overview', $help );
	}

	/**
	 * User roles help content
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help_user_roles() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'User Roles' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'Here is a basic overview of the different user roles and the permissions associated with each one:' )
		);

		$help .= '<ul>';

		$help .= sprintf(
			'<li>%1s</li>',
			__( 'Subscribers can read comments/comment/receive newsletters, etc. but cannot create regular site content.' )
		);

		$help .= sprintf(
			'<li>%1s</li>',
			__( 'Contributors can write and manage their posts but not publish posts or upload media files.' )
		);

		$help .= sprintf(
			'<li>%1s</li>',
			__( 'Authors can publish and manage their own posts, and are able to upload files.' )
		);

		$help .= sprintf(
			'<li>%1s</li>',
			__( 'Editors can publish posts, manage posts as well as manage other people&#8217;s posts, etc.' )
		);

		$help .= sprintf(
			'<li>%1s</li>',
			__( 'Administrators have access to all the administration features.' )
		);

		$help .= '</ul>';

		echo apply_filters( 'help_account_new_user_roles', $help );
	}
}
