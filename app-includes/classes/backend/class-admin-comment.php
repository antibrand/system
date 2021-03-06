<?php
/**
 * Sample administration screen class
 *
 * Use this to add a new administration page.
 * Remove what is unnecessary, add what is needed.
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
 * Sample screen class
 *
 * @since  1.0.0
 * @access public
 */
class Admin_Comment extends Edit_Screen {

	/**
	 * Page parent file
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string The parent file of the administration screen.
	 */
	public $parent = 'edit-comments.php';

	/**
	 * Submenu file
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $submenu = 'edit-comments.php';

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

		// Form action.
		$action = $this->action();

		switch( $action ) {
			case 'editcomment' :
				$title = __( 'Edit Comment' );
				break;

			case 'delete'  :
			case 'approve' :
			case 'trash'   :
			case 'spam'    :

				$title = __( 'Moderate Comment' );
				break;
		}

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
	 * Comment form actions
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the name of the action.
	 */
	public function action() {

		global $action;

		wp_reset_vars( [ 'action' ] );

		if ( isset( $_POST['deletecomment'] ) ) {
			$action = 'deletecomment';
		}

		if ( 'cdc' == $action ) {
			$action = 'delete';
		} elseif ( 'mac' == $action ) {
			$action = 'approve';
		}

		if ( isset( $_GET['dt'] ) ) {

			if ( 'spam' == $_GET['dt'] ) {
				$action = 'spam';
			} elseif ( 'trash' == $_GET['dt'] ) {
				$action = 'trash';
			}
		}

		return $action;
	}

	/**
	 * Enqueue scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_scripts() {

		// Form action.
		$action = $this->action();

		switch( $action ) {
			case 'editcomment' :

				// Enqueue comment edit script.
				wp_enqueue_script( 'comment' );
			break;
		}
	}

	/**
	 * Primary content callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public function primary_tab_callback() {

		// Form action.
		$action = $this->action();

		// Conditional callback variable.
		switch( $action ) {
			case 'editcomment' :
				$callback = $this->comment_edit();
			break;

			case 'delete'  :
			case 'approve' :
			case 'trash'   :
			case 'spam'    :
				$callback = $this->comment_status();
			break;

			case 'deletecomment'    :
			case 'trashcomment'     :
			case 'untrashcomment'   :
			case 'spamcomment'      :
			case 'unspamcomment'    :
			case 'approvecomment'   :
			case 'unapprovecomment' :
				$callback = $this->comment_quick_edit();

			die;

			case 'editedcomment' :
				$callback = $this->comment_edited();

			exit();

			default :
				$callback = $this->comment_unknown();

			break;
		}
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
			'id'         => $screen->id . '-management',
			'capability' => 'manage_options',
			'tab'        => 'Hello',
			'icon'       => '',
			'heading'    => '',
			'content'    => null,
			'callback'   => [ $this, 'action_content' ]
		] );
	}

	/**
	 * Introduction
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function action_content() {
		echo '<span style="color: red;">Hello.</span>';
	}

	/**
	 * Remove help content
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help() {

		// Form action.
		$action = $this->action();

		switch( $action ) {
			case 'editcomment' :
				$this->edit_help();
			break;
		}

		return null;
	}

	/**
	 * Edit comment help content
	 *
	 * This is displayed only when editing a comment.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function edit_help() {

		get_current_screen()->add_help_tab( [
			'id'      => 'overview',
			'title'   => __( 'Overview' ),
			'content' =>
				sprintf(
					'<p>%1s</p><p>%2s</p>',
					__( 'You can edit the information left in a comment if needed. This is often useful when you notice that a commenter has made a typographical error.' ),
					__( 'You can also moderate the comment from this screen using the Status box, where you can also change the timestamp of the comment.' )
				)
		] );
	}
}
