<?php
/**
 * Discussion settings screen class
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
 * Discussion settings screen class
 *
 * @since  1.0.0
 * @access private
 */
class Settings_Discussion extends Settings_Screen {

	/**
	 * Page parent file
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string The parent file of the settings screen.
	 */
	public $parent = 'edit-comments.php';

	/**
	 * Page title
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $title = 'Discussion Settings';

	/**
	 * Page description
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $description = 'Manage comment options, discussion modderation, notifications, profiles.';

	/**
	 * Form fields
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string The name of the registered fields to be executed.
	 */
	protected $fields = 'discussion';

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

		// Print page scripts to head.
		add_action( 'admin_head', [ $this, 'child_print_scripts' ] );
	}

	/**
	 * Print page scripts to head
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the script markup.
	 */
	function child_print_scripts() {
		?>
			<script>
			(function($){
				var parent = $( '#show_avatars' ),
					children = $( '.avatar-settings' );
				parent.change(function(){
					children.toggleClass( 'hide-if-js', ! this.checked );
				});
			})(jQuery);
			</script>
		<?php
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
			'id'         => 'comments',
			'capability' => 'manage_options',
			'tab'        => __( 'Comments' ),
			'icon'       => '',
			'heading'    => __( 'Comment Settings' ),
			'content'    => '',
			'callback'   => [ $this, 'comments' ]
		] );

		$screen->add_content_tab( [
			'id'         => 'moderation',
			'capability' => 'manage_options',
			'tab'        => __( 'Moderation' ),
			'icon'       => '',
			'heading'    => __( 'Moderation Settings' ),
			'content'    => '',
			'callback'   => [ $this, 'moderation' ]
		] );

		$screen->add_content_tab( [
			'id'         => 'notifications',
			'capability' => 'manage_options',
			'tab'        => __( 'Notifications' ),
			'icon'       => '',
			'heading'    => __( 'Notification Settings' ),
			'content'    => '',
			'callback'   => [ $this, 'notifications' ]
		] );

		$screen->add_content_tab( [
			'id'         => 'profiles',
			'capability' => 'manage_options',
			'tab'        => __( 'Profiles' ),
			'icon'       => '',
			'heading'    => __( 'User Profile Settings' ),
			'content'    => '',
			'callback'   => [ $this, 'profiles' ]
		] );
	}

	/**
	 * Comments tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function comments() {

		?>
		<div class="tab-section-wrap">

			<fieldset>

				<legend class="screen-reader-text"><?php _e( 'Default Comment Settings' ); ?></legend>

				<h3><?php _e( 'Default Comment Settings' ); ?></h3>

				<p>
					<label for="default_comment_status">
						<input name="default_comment_status" type="checkbox" id="default_comment_status" value="open" <?php checked( 'open', get_option( 'default_comment_status' )); ?> />
						<?php _e( 'Allow people to post comments on new articles' ); ?>
					</label>
				</p>

				<p class="description"><?php echo '( ' . __( 'These settings may be overridden for individual articles.' ) . ' )'; ?></p>

			</fieldset>

			<fieldset>

				<legend class="screen-reader-text"><?php _e( 'Other Comment Settings' ); ?></legend>

				<h3><?php _e( 'Other Comment Settings' ); ?></h3>

				<p>
					<label for="require_name_email">
						<input type="checkbox" name="require_name_email" id="require_name_email" value="1" <?php checked( '1', get_option( 'require_name_email' )); ?> /> <?php _e( 'Comment author must fill out name and email' ); ?>
					</label>
				</p>

				<p>
					<label for="comment_registration">
						<input name="comment_registration" type="checkbox" id="comment_registration" value="1" <?php checked( '1', get_option( 'comment_registration' )); ?> />
						<?php _e( 'Users must be registered and logged in to comment' ); ?>
						<?php if ( !get_option( 'users_can_register' ) && is_network() ) echo ' ' . __( '(Signup has been disabled. Only members of this site can comment.)' ); ?>
					</label>
				</p>

				<p>
					<label for="close_comments_for_old_posts">
						<input name="close_comments_for_old_posts" type="checkbox" id="close_comments_for_old_posts" value="1" <?php checked( '1', get_option( 'close_comments_for_old_posts' )); ?> />
						<?php printf(
							__( 'Automatically close comments on articles older than %s days' ),
							'</label> <label for="close_comments_days_old"><input name="close_comments_days_old" type="number" min="0" step="1" id="close_comments_days_old" value="' . esc_attr( get_option( 'close_comments_days_old' ) ) . '" class="small-text" />'
						); ?>
					</label>
				</p>

				<p>
					<label for="show_comments_cookies_opt_in">
						<input name="show_comments_cookies_opt_in" type="checkbox" id="show_comments_cookies_opt_in" value="1" <?php checked( '1', get_option( 'show_comments_cookies_opt_in' ) ); ?> />
						<?php _e( 'Show comments cookies opt-in checkbox.' ); ?>
					</label>
				</p>

				<p>
					<label for="thread_comments">
						<input name="thread_comments" type="checkbox" id="thread_comments" value="1" <?php checked( '1', get_option( 'thread_comments' )); ?> />
						<?php
						/**
						 * Filters the maximum depth of threaded/nested comments.
						 *
						 * @since 2.7.0.
						 *
						 * @param int $max_depth The maximum depth of threaded comments. Default 10.
						 */
						$maxdeep = (int) apply_filters( 'thread_comments_depth_max', 10 );

						$thread_comments_depth = '</label> <label for="thread_comments_depth"><select name="thread_comments_depth" id="thread_comments_depth">';
						for ( $i = 2; $i <= $maxdeep; $i++ ) {
							$thread_comments_depth .= "<option value='" . esc_attr( $i ) . "'";
							if ( get_option( 'thread_comments_depth' ) == $i ) $thread_comments_depth .= " selected='selected'";
							$thread_comments_depth .= ">$i</option>";
						}
						$thread_comments_depth .= '</select>';

						printf( __( 'Enable threaded (nested) comments %s levels deep' ), $thread_comments_depth );

						?>
					</label>
				</p>

				<p>
					<label for="page_comments">
						<input name="page_comments" type="checkbox" id="page_comments" value="1" <?php checked( '1', get_option( 'page_comments' ) ); ?> />
						<?php
						$default_comments_page = '</label> <label for="default_comments_page"><select name="default_comments_page" id="default_comments_page"><option value="newest"';
						if ( 'newest' == get_option( 'default_comments_page' ) ) $default_comments_page .= ' selected="selected"';
						$default_comments_page .= '>' . __( 'last' ) . '</option><option value="oldest"';
						if ( 'oldest' == get_option( 'default_comments_page' ) ) $default_comments_page .= ' selected="selected"';
						$default_comments_page .= '>' . __( 'first' ) . '</option></select>';
						printf(
							// Translators: 1: Form field control for number of top level comments per page, 2: Form field control for the 'first' or 'last' page */
							__( 'Break comments into pages with %1$s top level comments per page and the %2$s page displayed by default' ),
							'</label> <label for="comments_per_page"><input name="comments_per_page" type="number" step="1" min="0" id="comments_per_page" value="' . esc_attr( get_option( 'comments_per_page' ) ) . '" class="small-text" />',
							$default_comments_page
						);
						?>
					</label>
				</p>

				<p>
					<label for="comment_order">
					<?php
						$comment_order = '<select name="comment_order" id="comment_order"><option value="asc"';
						if ( 'asc' == get_option( 'comment_order' ) ) $comment_order .= ' selected="selected"';
						$comment_order .= '>' . __( 'older' ) . '</option><option value="desc"';
						if ( 'desc' == get_option( 'comment_order' ) ) $comment_order .= ' selected="selected"';
						$comment_order .= '>' . __( 'newer' ) . '</option></select>';

						printf( __( 'Comments should be displayed with the %s comments at the top of each page' ), $comment_order );
					?>
					</label>
				</p>

			</fieldset>
		</div>
		<?php
	}

	/**
	 * Moderation tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function moderation() {

		?>
		<div class="tab-section-wrap">

			<fieldset>

				<legend class="screen-reader-text"><?php _e( 'Before a comment appears' ); ?></legend>

				<h3><?php _e( 'Before a comment appears' ); ?></h3>

				<p>
					<label for="comment_moderation">
						<input name="comment_moderation" type="checkbox" id="comment_moderation" value="1" <?php checked( '1', get_option( 'comment_moderation' )); ?> />
						<?php _e( 'Comment must be manually approved' ); ?>
					</label>
				</p>

				<p>
					<label for="comment_whitelist">
						<input type="checkbox" name="comment_whitelist" id="comment_whitelist" value="1" <?php checked( '1', get_option( 'comment_whitelist' )); ?> /> <?php _e( 'Comment author must have a previously approved comment' ); ?>
					</label>
				</p>

			</fieldset>

			<fieldset>

				<legend class="screen-reader-text"><?php _e( 'Comment Moderation' ); ?></legend>

				<h3><?php _e( 'Comment Moderation' ); ?></h3>

				<p>
					<label for="comment_max_links">
						<?php printf(__( 'Hold a comment in the queue if it contains %s or more links. (A common characteristic of comment spam is a large number of hyperlinks.)' ), '<input name="comment_max_links" type="number" step="1" min="0" id="comment_max_links" value="' . esc_attr( get_option( 'comment_max_links' ) ) . '" class="small-text" />' ); ?>
					</label>
				</p>

				<p>
					<label for="moderation_keys">
						<?php _e( 'When a comment contains any of these words in its content, name, URL, email, or IP address, it will be held in the <a href="edit-comments.php?comment_status=moderated">moderation queue</a>. One word or IP address per line. It will match inside words, so &#8220;central&#8221; will match &#8220;decentralize&#8221;.' ); ?>
					</label>
				</p>
				<p>
					<textarea name="moderation_keys" rows="10" cols="50" id="moderation_keys" class="large-text code"><?php echo esc_textarea( get_option( 'moderation_keys' ) ); ?></textarea>
				</p>

			</fieldset>

			<fieldset>

				<legend class="screen-reader-text"><?php _e( 'Comment Blacklist' ); ?></legend>

				<h3><?php _e( 'Comment Blacklist' ); ?></h3>

				<p>
					<label for="blacklist_keys">
						<?php _e( 'When a comment contains any of these words in its content, name, URL, email, or IP address, it will be put in the trash. One word or IP address per line. It will match inside words, so &#8220;fruit&#8221; will match &#8220;grapefruit&#8221;.' ); ?>
					</label>
				</p>
				<p>
					<textarea name="blacklist_keys" rows="10" cols="50" id="blacklist_keys" class="large-text code"><?php echo esc_textarea( get_option( 'blacklist_keys' ) ); ?></textarea>
				</p>

			</fieldset>
		</div>
		<?php
	}

	/**
	 * Notifications tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function notifications() {

		?>
		<div class="tab-section-wrap">

			<fieldset>

				<legend class="screen-reader-text"></legend>

				<h3><?php _e( 'Email Notification' ); ?></h3>

				<p>
					<label for="comments_notify">
						<input name="comments_notify" type="checkbox" id="comments_notify" value="1" <?php checked( '1', get_option( 'comments_notify' )); ?> />
						<?php _e( 'Anyone posts a comment' ); ?>
					</label>
				</p>

				<p>
					<label for="moderation_notify">
						<input name="moderation_notify" type="checkbox" id="moderation_notify" value="1" <?php checked( '1', get_option( 'moderation_notify' )); ?> />
						<?php _e( 'A comment is held for moderation' ); ?>
					</label>
				</p>

			</fieldset>

		</div>
		<?php
	}

	/**
	 * Profiles tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function profiles() {

		global $user_email;

		?>
		<div class="tab-section-wrap">

			<p><?php _e( 'An avatar is an image that follows you from weblog to weblog appearing beside your name when you comment on avatar enabled sites.' ); ?></p>

			<p><?php _e( 'Here you can enable the display of avatars for people who comment on your site.' ); ?></p>

			<?php $show_avatars = get_option( 'show_avatars' ); ?>

			<fieldset>

				<h3><?php _e( 'Avatar Display' ); ?></h3>

				<legend class="screen-reader-text"><?php _e( 'Avatar Display' ); ?></legend>

				<p>
					<label for="show_avatars">
						<input type="checkbox" id="show_avatars" name="show_avatars" value="1" <?php checked( $show_avatars, 1 ); ?> />
						<?php _e( 'Show Avatars' ); ?>
					</label>
				</p>

			</fieldset>

			<fieldset class="defaultavatarpicker avatar-settings<?php if ( ! $show_avatars ) echo ' hide-if-js'; ?>">

				<legend class="screen-reader-text"><?php _e( 'Default Avatar' ); ?></legend>

				<h3><?php _e( 'Default Avatar' ); ?></h3>

				<p><?php _e( 'For users without a custom avatar of their own a generic logo can be displayed.' ); ?></p>

				<ul class="form-field-list">

				<?php
				$avatar_defaults = [
					'mystery' => __( 'Mystery Person' ),
					'generic'   => __( 'Generic' )
				];

				/**
				 * Filters the default avatars.
				 *
				 * Avatars are stored in key/value pairs, where the key is option value,
				 * and the name is the displayed avatar name.
				 *
				 * @since Previous 2.6.0
				 * @param array $avatar_defaults Array of default avatars.
				 */
				$avatar_defaults = apply_filters( 'avatar_defaults', $avatar_defaults );
				$default         = get_option( 'avatar_default', 'mystery' );
				$avatar_list     = '';

				// Force avatars on to display these choices
				add_filter( 'pre_option_show_avatars', '__return_true', 100 );

				foreach ( $avatar_defaults as $default_key => $default_name ) {

					$selected     = ( $default == $default_key) ? 'checked="checked" ' : '';
					$avatar_list .= '<li>';
					$avatar_list .= "\n\t<label><input type='radio' name='avatar_default' id='avatar_{$default_key}' value='" . esc_attr( $default_key) . "' {$selected}/> ";
					$avatar_list .= get_avatar( $user_email, 32, $default_key, '', array( 'force_default' => true ) );
					$avatar_list .= ' ' . $default_name . '</label>';
					$avatar_list .= '<br />';
				}

				remove_filter( 'pre_option_show_avatars', '__return_true', 100 );

				/**
				 * Filters the HTML output of the default avatar list.
				 *
				 * @since Previous 2.6.0
				 * @param string $avatar_list HTML markup of the avatar list.
				 */
				echo apply_filters( 'default_avatar_select', $avatar_list );
				?>
				</ul>

			</fieldset>
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
			'content'  => null,
			'callback' => [ $this, 'help_overview' ]
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
			__( 'This screen provides many options for controlling the management and display of comments and links to your posts/pages. So many, in fact, they won&#8217;t all fit here! :) Use the documentation links to get information on what each discussion setting does.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'You must click the Save Changes button at the bottom of the screen for new settings to take effect.' )
		);

		$help = apply_filters( 'help_settings_discussion_overview', $help );

		echo $help;
	}
}
