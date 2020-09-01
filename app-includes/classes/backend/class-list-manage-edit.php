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
class List_Manage_Edit extends List_Manage_Screen {

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $title = 'Edit';

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

		global $typenow;

		if ( ! $typenow ) {
			wp_die( sprintf(
				'<p>%1s</p>',
				__( 'Invalid post type.' )
			) );
		}

		if ( ! in_array( $typenow, get_post_types( array( 'show_ui' => true ) ) ) ) {
			wp_die( sprintf(
				'<p>%1s</p>',
				__( 'Sorry, you are not allowed to edit posts in this post type.' )
			) );
		}

		if ( 'attachment' === $typenow ) {
			if ( wp_redirect( admin_url( 'upload.php' ) ) ) {
				exit;
			}
		}

		global $post_type, $post_type_object;

		$post_type = $typenow;
		$post_type_object = get_post_type_object( $post_type );

		if ( ! $post_type_object ) {
			wp_die( sprintf(
				'<p>%1s</p>',
				__( 'Invalid post type.' )
			) );
		}

		if ( ! current_user_can( $post_type_object->cap->edit_posts ) ) {
			wp_die(
				'<p>' . __( 'You need a higher level of permission.' ) . '</p>' .
				'<p>' . __( 'Sorry, you are not allowed to edit posts in this post type.' ) . '</p>',
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
	 * @return string Returns the message.description markup.
	 */
	public function description() {

		$description = sprintf(
			'<p class="description">%1s</p>',
			esc_html__( $this->description )
		);

		return $description;
	}

	/**
	 * Action messages
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the message.
	 */
	public function messages() {

		global $post_type;

		$bulk_counts = [
			'updated'   => isset( $_REQUEST['updated'] )   ? absint( $_REQUEST['updated'] )   : 0,
			'locked'    => isset( $_REQUEST['locked'] )    ? absint( $_REQUEST['locked'] )    : 0,
			'deleted'   => isset( $_REQUEST['deleted'] )   ? absint( $_REQUEST['deleted'] )   : 0,
			'trashed'   => isset( $_REQUEST['trashed'] )   ? absint( $_REQUEST['trashed'] )   : 0,
			'untrashed' => isset( $_REQUEST['untrashed'] ) ? absint( $_REQUEST['untrashed'] ) : 0,
		];

		$bulk_messages = [];
		$bulk_messages['post'] = [
			'updated'   => _n( '%s post updated.', '%s posts updated.', $bulk_counts['updated'] ),
			'locked'    => ( 1 == $bulk_counts['locked'] ) ? __( '1 post not updated, somebody is editing it.' ) :
							   _n( '%s post not updated, somebody is editing it.', '%s posts not updated, somebody is editing them.', $bulk_counts['locked'] ),
			'deleted'   => _n( '%s post permanently deleted.', '%s posts permanently deleted.', $bulk_counts['deleted'] ),
			'trashed'   => _n( '%s post moved to the Trash.', '%s posts moved to the Trash.', $bulk_counts['trashed'] ),
			'untrashed' => _n( '%s post restored from the Trash.', '%s posts restored from the Trash.', $bulk_counts['untrashed'] ),
		];
		$bulk_messages['page'] = [
			'updated'   => _n( '%s page updated.', '%s pages updated.', $bulk_counts['updated'] ),
			'locked'    => ( 1 == $bulk_counts['locked'] ) ? __( '1 page not updated, somebody is editing it.' ) :
							   _n( '%s page not updated, somebody is editing it.', '%s pages not updated, somebody is editing them.', $bulk_counts['locked'] ),
			'deleted'   => _n( '%s page permanently deleted.', '%s pages permanently deleted.', $bulk_counts['deleted'] ),
			'trashed'   => _n( '%s page moved to the Trash.', '%s pages moved to the Trash.', $bulk_counts['trashed'] ),
			'untrashed' => _n( '%s page restored from the Trash.', '%s pages restored from the Trash.', $bulk_counts['untrashed'] ),
		];

		/**
		 * Filters the bulk action updated messages.
		 *
		 * By default, custom post types use the messages for the 'post' post type.
		 *
		 * @since Previous 3.7.0
		 * @param array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
		 *                             keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
		 * @param array $bulk_counts   Array of item counts for each message, used to build internationalized strings.
		 */
		$bulk_messages = apply_filters( 'bulk_post_updated_messages', $bulk_messages, $bulk_counts );
		$bulk_counts   = array_filter( $bulk_counts );

		// If we have a bulk message to issue:
		$messages = [];

		foreach ( $bulk_counts as $message => $count ) {

			if ( isset( $bulk_messages[ $post_type ][ $message ] ) ) {
				$messages[] = sprintf( $bulk_messages[ $post_type ][ $message ], number_format_i18n( $count ) );
			} elseif ( isset( $bulk_messages['post'][ $message ] ) ) {
				$messages[] = sprintf( $bulk_messages['post'][ $message ], number_format_i18n( $count ) );
			}

			if ( $message == 'trashed' && isset( $_REQUEST['ids'] ) ) {

				$ids = preg_replace( '/[^0-9,]/', '', $_REQUEST['ids'] );
				$messages[] = '<a href="' . esc_url( wp_nonce_url( "edit.php?post_type=$post_type&doaction=undo&action=untrash&ids=$ids", "bulk-posts" ) ) . '">' . __( 'Undo' ) . '</a>';
			}
		}

		if ( is_array( $messages ) && $messages ) {
			echo '<div id="message" class="updated notice is-dismissible"><p>' . join( ' ', $messages ) . '</p></div>';
		}

		unset( $messages );
	}

	/**
	 * Remove help content
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help() {

		global $typenow;

		// Add help tabs.
		if ( 'post' == $typenow ) {
			$this->posts_help();
		} elseif ( 'page' == $typenow ) {
			$this->pages_help();
		}

		return null;
	}

	/**
	 * Edit posts help content
	 *
	 * This is displayed only when editing posts.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function posts_help() {

		get_current_screen()->add_help_tab( [
			'id'      => 'overview',
			'title'	   => __( 'Overview' ),
			'content'  => '',
			'callback' => [ $this, 'posts_overview' ]
		] );

		get_current_screen()->add_help_tab( [
			'id'       => 'screen-content',
			'title'    => __( 'Screen Content' ),
			'content'  => '',
			'callback' => [ $this, 'posts_screen_content' ]
		] );

		get_current_screen()->add_help_tab( [
			'id'       => 'action-links',
			'title'    => __( 'Available Actions' ),
			'content'  => '',
			'callback' => [ $this, 'posts_action_links' ]
		] );

		get_current_screen()->add_help_tab( [
			'id'       => 'bulk-actions',
			'title'    => __( 'Bulk Actions' ),
			'content'  => '',
			'callback' => [ $this, 'posts_bulk_actions' ]
		] );
	}

	/**
	 * Edit pages help content
	 *
	 * This is displayed only when editing pages.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function pages_help() {

		get_current_screen()->add_help_tab( [
			'id'       => 'overview',
			'title'    => __( 'Overview' ),
			'content'  => '',
			'callback' => [ $this, 'pages_overview' ]
		] );

		get_current_screen()->add_help_tab( [
			'id'       => 'managing-pages',
			'title'    => __( 'Managing Pages' ),
			'content'  => '',
			'callback' => [ $this, 'managing_pages' ]
		] );
	}

	/**
	 * Posts overview tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab.
	 */
	public function posts_overview() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Overview' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'This screen provides access to all of your posts. You can customize the display of this screen to suit your workflow.' )
		);

		echo apply_filters( 'help_overview_posts', $help );
	}

	/**
	 * Posts screen content tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab.
	 */
	public function posts_screen_content() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Screen Content' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'You can customize the display of this screen&#8217;s contents in a number of ways:' )
		);

		$help .= '<ul>';

		$help .= sprintf(
			'<li>%1s</li>',
			__( 'You can hide/display columns based on your needs and decide how many posts to list per screen using the Screen Options tab.' )
		);

		$help .= sprintf(
			'<li>%1s</li>',
			__( 'You can filter the list of posts by post status using the text links above the posts list to only show posts with that status. The default view is to show all posts.' )
		);

		$help .= sprintf(
			'<li>%1s</li>',
			__( 'You can view posts in a simple title list or with an excerpt using the Screen Options tab.' )
		);

		$help .= sprintf(
			'<li>%1s</li>',
			__( 'You can refine the list to show only posts in a specific category or from a specific month by using the dropdown menus above the posts list. Click the Filter button after making your selection. You also can refine the list by clicking on the post author, category or tag in the posts list.' )
		);

		$help .= '</ul>';

		echo apply_filters( 'help_screen_content_posts', $help );
	}

	/**
	 * Posts action links tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab.
	 */
	public function posts_action_links() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Available Actions' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'Hovering over a row in the posts list will display action links that allow you to manage your post. You can perform the following actions:' )
		);

		$help .= '<ul>';

		$help .= sprintf(
			'<li>%1s</li>',
			__( '<strong>Edit</strong> takes you to the editing screen for that post. You can also reach that screen by clicking on the post title.' )
		);

		$help .= sprintf(
			'<li>%1s</li>',
			__( '<strong>Quick Edit</strong> provides inline access to the metadata of your post, allowing you to update post details without leaving this screen.' )
		);

		$help .= sprintf(
			'<li>%1s</li>',
			__( '<strong>Trash</strong> removes your post from this list and places it in the trash, from which you can permanently delete it.' )
		);

		$help .= sprintf(
			'<li>%1s</li>',
			__( '<strong>Preview</strong> will show you what your draft post will look like if you publish it. View will take you to your live site to view the post. Which link is available depends on your post&#8217;s status.' )
		);

		$help .= '</ul>';

		echo apply_filters( 'help_action_links_posts', $help );
	}

	/**
	 * Posts bulk actions tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab.
	 */
	public function posts_bulk_actions() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Bulk Actions' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'You can also edit or move multiple posts to the trash at once. Select the posts you want to act on using the checkboxes, then select the action you want to take from the Bulk Actions menu and click Apply.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'When using Bulk Edit, you can change the metadata (categories, author, etc.) for all selected posts at once. To remove a post from the grouping, just click the x next to its name in the Bulk Edit area that appears.' )
		);

		echo apply_filters( 'help_bulk_actions_posts', $help );
	}

	/**
	 * Pages overview tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function pages_overview() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Overview' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'Pages are similar to posts in that they have a title, body text, and associated metadata, but they are different in that they are not part of the chronological blog stream, kind of like permanent posts. Pages are not categorized or tagged, but can have a hierarchy. You can nest pages under other pages by making one the &#8220;Parent&#8221; of the other, creating a group of pages.' )
		);

		echo apply_filters( 'help_overview_pages', $help );
	}

	/**
	 * Pages overview tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function managing_pages() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Managing Pages' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'Managing pages is very similar to managing posts, and the screens can be customized in the same way.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'You can also perform the same types of actions, including narrowing the list by using the filters, acting on a page using the action links that appear when you hover over a row, or using the Bulk Actions menu to edit the metadata for multiple pages at once.' )
		);

		echo apply_filters( 'help_managing_pages', $help );
	}
}
