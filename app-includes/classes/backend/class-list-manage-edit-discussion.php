<?php
/**
 * List manage screen class
 *
 * Bootstrap for list management pages such as
 * post types, users, plugins, themes, media, etc.
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
 * List manage screen class
 *
 * @since  1.0.0
 * @access public
 */
class List_Manage_Edit_Discussion extends List_Manage_Screen {

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

		// Edit capabilities.
		$this->die();
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

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die(
				'<p><strong>' . __( 'You need a higher level of permission.' ) . '</strong></p>' .
				'<p>' . __( 'Sorry, you are not allowed to edit comments.' ) . '</p>',
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
	 * Enqueue page-specific scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'admin-comments' );

		if ( 'true' == get_user_option( 'comment_shortcuts' ) ) {
			wp_enqueue_script( 'jquery-table-hotkeys' );
		}
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

		// Discussion moderation.
		get_current_screen()->add_help_tab( [
			'id'      => 'discussion-moderation',
			'title'   => __( 'Moderation' ),
			'content'  => null,
			'callback' => [ $this, 'help_moderation' ]
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
			__( 'Discussion Overview' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'You can manage comments made on your site similar to the way you manage posts and other content. This screen is customizable in the same ways as other management screens, and you can act on comments using the on-hover action links or the Bulk Actions.' )
		);

		echo apply_filters( 'help_overview_discussion', $help );
	}

	/**
	 * Help discussion moderation tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the markup of the tab content.
	 */
	public function help_moderation() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Discussion Moderation' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'A red bar on the left means the comment is waiting for you to moderate it.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'In the <strong>Author</strong> column, in addition to the author&#8217;s name, email address, and blog URL, the commenter&#8217;s IP address is shown. Clicking on this link will show you all the comments made from this IP address.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'In the <strong>Comment</strong> column, hovering over any comment gives you options to approve, reply (and approve), quick edit, edit, spam mark, or trash that comment.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'In the <strong>In Response To</strong> column, there are three elements. The text is the name of the post that inspired the comment, and links to the post editor for that entry. The View Post link leads to that post on your live site. The small bubble with the number in it shows the number of approved comments that post has received. If there are pending comments, a red notification circle with the number of pending comments is displayed. Clicking the notification circle will filter the comments screen to show only pending comments on that post.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'In the <strong>Submitted On</strong> column, the date and time the comment was left on your site appears. Clicking on the date/time link will take you to that comment on your live site.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'Many people take advantage of keyboard shortcuts to moderate their comments more quickly. Use the link to the side to learn more.' )
		);

		echo apply_filters( 'help_discussion_moderation', $help );
	}
}
