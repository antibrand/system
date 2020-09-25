<?php
/**
 * Content settings screen class
 *
 * @package App_Package
 * @subpackage Classes/Backend
 */

namespace AppNamespace\Backend;

// Stop here if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Content settings screen class
 *
 * @since  1.0.0
 * @access public
 */
class Settings_Content extends Settings_Screen {

	/**
	 * Page title
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $title = 'Content Options';

	/**
	 * Page description
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $description = 'Post authoring and content display settings.';

	/**
	 * Form fields
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string The name of the registered fields to be executed.
	 */
	public $fields = 'content';

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
	 * Print page scripts to head
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the script markup.
	 */
	function print_scripts() {

		/**
		 * Print unminified script if in development mode
		 * or in debug mode or compression is off.
		 */
		if ( ( defined( 'APP_DEV_MODE' ) && APP_DEV_MODE )
			|| ( defined( 'APP_DEBUG' ) && APP_DEBUG )
			|| ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG )
			|| ( defined( 'COMPRESS_SCRIPTS' ) && ! COMPRESS_SCRIPTS ) ) :
	?>
	<script>
		jQuery( document ).ready(function($) {
			var section    = $( '#front-static-pages' ),
				staticPage = section.find( 'input:radio[value="page"]' ),
				selects    = section.find( 'select' ),
				check_disabled = function() {
					selects.prop( 'disabled', ! staticPage.prop( 'checked' ) );
				};
			check_disabled();
			section.find( 'input:radio' ).change( check_disabled );
		});
	</script>
	<?php
		// If not in dev or debug mode.
		else :
	?>
	<script>jQuery(document).ready(function(e){function n(){d.prop('disabled',!a.prop('checked'))}var i=e('#front-static-pages'),a=i.find('input:radio[value="page"]'),d=i.find('select');n(),i.find('input:radio').change(n)});</script>
	<?php
		// End if dev or debug mode.
		endif;
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
			'id'         => 'publish',
			'capability' => 'manage_options',
			'tab'        => __( 'Publish' ),
			'icon'       => '',
			'heading'    => __( 'Content Creation Settings' ),
			'content'    => '',
			'callback'   => [ $this, 'publish' ]
		] );

		if ( apply_filters( 'enable_post_by_email_configuration', true ) ) :
			$screen->add_content_tab( [
				'id'         => 'display',
				'capability' => 'manage_options',
				'tab'        => __( 'Display' ),
				'icon'       => '',
				'heading'    => __( 'Content Display Settings' ),
				'content'    => '',
				'callback'   => [ $this, 'display' ]
			] );
		endif;
	}

	/**
	 * Publish tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function publish() {

		?>
		<div class="tab-section-wrap tab-section-wrap__publish">

			<?php if ( get_site_option( 'initial_db_version' ) < 32453 ) : ?>
				<fieldset form="<?php echo $this->fields . '-settings'; ?>">

					<legend class="screen-reader-text"><?php _e( 'Formatting' ); ?></legend>

					<h3><?php _e( 'Formatting' ); ?></h3>

					<p>
						<label for="use_smilies">
							<input name="use_smilies" type="checkbox" id="use_smilies" value="1" <?php checked( '1', get_option( 'use_smilies' ) ); ?> />
							<?php _e( 'Convert emoticons like <code>:-)</code> and <code>:-P</code> to graphics on display' ); ?>
						</label>
					</p>

					<p>
						<label for="use_balanceTags">
							<input name="use_balanceTags" type="checkbox" id="use_balanceTags" value="1" <?php checked( '1', get_option( 'use_balanceTags' ) ); ?> /> <?php _e( 'Should correct invalidly nested XHTML automatically' ); ?>
						</label>
					</p>

				</fieldset>
			<?php endif; ?>
				<tr>
					<th scope="row"><label for="default_category"><?php _e( 'Default Post Category' ); ?></label></th>
					<td>
					<?php
					wp_dropdown_categories( [ 'hide_empty' => 0, 'name' => 'default_category', 'orderby' => 'name', 'selected' => get_option( 'default_category' ), 'hierarchical' => true ] );
					?>
					</td>
				</tr>
			<?php
			$post_formats = get_post_format_strings();
			unset( $post_formats['standard'] );
			?>
				<tr>
					<th scope="row"><label for="default_post_format"><?php _e( 'Default Post Format' ); ?></label></th>
					<td>
						<select name="default_post_format" id="default_post_format">
							<option value="0"><?php echo get_post_format_string( 'standard' ); ?></option>
					<?php foreach ( $post_formats as $format_slug => $format_name ): ?>
							<option<?php selected( get_option( 'default_post_format' ), $format_slug ); ?> value="<?php echo esc_attr( $format_slug ); ?>"><?php echo esc_html( $format_name ); ?></option>
					<?php endforeach; ?>
						</select>
					</td>
				</tr>

			<?php // do_settings_fields( 'writing', 'default' ); ?>

			<?php if ( apply_filters( 'enable_post_by_email_configuration', true ) ) : ?>

			<h2><?php _e( 'Remote Content Creation' ); ?></h2>

			<p><?php
			printf(
				// Translators: 1, 2, 3: examples of random email addresses.
				__( 'To post by email you must set up a secret email account with POP3 access. Any mail received at this address will be posted, so it&#8217;s a good idea to keep this address very secret. Here are three random strings you could use: %1$s, %2$s, %3$s.' ),
				sprintf( '<kbd>%s</kbd>', wp_generate_password( 8, false ) ),
				sprintf( '<kbd>%s</kbd>', wp_generate_password( 8, false ) ),
				sprintf( '<kbd>%s</kbd>', wp_generate_password( 8, false ) )
			);
			?></p>

			<table class="form-table">
				<tr>
					<th scope="row"><label for="mailserver_url"><?php _e( 'Mail Server' ); ?></label></th>
					<td>
						<input name="mailserver_url" type="text" id="mailserver_url" value="<?php form_option( 'mailserver_url' ); ?>" class="regular-text code" />
						<label for="mailserver_port"><?php _e( 'Port' ); ?></label>
						<input name="mailserver_port" type="text" id="mailserver_port" value="<?php form_option( 'mailserver_port' ); ?>" class="small-text" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="mailserver_login"><?php _e( 'Login Name' ); ?></label></th>
					<td>
						<input name="mailserver_login" type="text" id="mailserver_login" value="<?php form_option( 'mailserver_login' ); ?>" class="regular-text ltr" /></td>
					</tr>
					<tr>
					<th scope="row"><label for="mailserver_pass"><?php _e( 'Password' ); ?></label></th>
					<td>
						<input name="mailserver_pass" type="text" id="mailserver_pass" value="<?php form_option( 'mailserver_pass' ); ?>" class="regular-text ltr" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="default_email_category"><?php _e( 'Default Mail Category' ); ?></label></th>
					<td>
						<?php
						wp_dropdown_categories( [ 'hide_empty' => 0, 'name' => 'default_email_category', 'orderby' => 'name', 'selected' => get_option( 'default_email_category' ), 'hierarchical' => true ] );
						?>
					</td>
				</tr>
			<?php // do_settings_fields( 'writing', 'post_via_email' ); ?>
			</table>

			<?php endif; // enable_post_by_email_configuration  ?>

		</div>
		<?php
	}
	/**
	 * Display tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function display() {

		?>
		<div class="tab-section-wrap tab-section-wrap__display">

			<?php
			if ( ! in_array( get_option( 'blog_charset' ), [ 'utf8', 'utf-8', 'UTF8', 'UTF-8' ] ) ) { ?>
				<p>
					<label for="site_charset"><?php _e( 'Encoding for pages and feeds' ); ?></label>
					<br /><input name="site_charset" type="text" id="site_charset" value="<?php echo esc_attr( get_option( 'blog_charset' ) ); ?>" class="regular-text" />
				</p>

				<p class="description"><?php _e( 'The character encoding of your site (UTF-8 is recommended)' ); ?></p>
			<?php }

			if ( ! get_pages() ) {  ?>
			<input name="show_on_front" type="hidden" value="posts" />

			<?php
				if ( 'posts' != get_option( 'show_on_front' ) ) {
					update_option( 'show_on_front', 'posts' );
				}

			} else {
				if ( 'page' == get_option( 'show_on_front' ) && ! get_option( 'page_on_front' ) && ! get_option( 'page_for_posts' ) ) {
					update_option( 'show_on_front', 'posts' );
				}
			?>
			<fieldset id="front-static-pages" form="<?php echo $this->fields . '-settings'; ?>">

				<legend class="screen-reader-text"><?php _e( 'Front Page' ); ?></legend>

				<h3><?php _e( 'Front Page' ); ?></h3>

				<ul class="form-field-list">
					<li>
						<label>
							<input name="show_on_front" type="radio" value="posts" class="tog" <?php checked( 'posts', get_option( 'show_on_front' ) ); ?> />
							<?php _e( 'Your latest posts' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input name="show_on_front" type="radio" value="page" class="tog" <?php checked( 'page', get_option( 'show_on_front' ) ); ?> />
							<?php printf( __( 'A <a href="%s">static page</a> (select below)' ), 'edit.php?post_type=page' ); ?>
						</label>
					</li>
				</ul>

				<ul class="form-field-list">
					<li>
						<label for="page_on_front"><?php printf( __( 'Homepage: %s' ), wp_dropdown_pages( array( 'name' => 'page_on_front', 'echo' => 0, 'show_option_none' => __( '&mdash; Select &mdash;' ), 'option_none_value' => '0', 'selected' => get_option( 'page_on_front' ) ) ) ); ?></label>
					</li>
					<li>
						<label for="page_for_posts"><?php printf( __( 'Posts page: %s' ), wp_dropdown_pages( array( 'name' => 'page_for_posts', 'echo' => 0, 'show_option_none' => __( '&mdash; Select &mdash;' ), 'option_none_value' => '0', 'selected' => get_option( 'page_for_posts' ) ) ) ); ?></label>
					</li>
				</ul>

			<?php if ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) == get_option( 'page_on_front' ) ) :

			?>
				<div id="front-page-warning" class="error inline">
					<p><?php _e( '<strong>Warning:</strong> these pages should not be the same!' ); ?></p>
				</div>
			<?php endif; ?>

			</fieldset>

			<fieldset form="<?php echo $this->fields . '-settings'; ?>">

				<legend class="screen-reader-text"><?php _e( 'Pagination' ); ?></legend>

				<h3><?php _e( 'Pagination' ); ?></h3>

				<?php
				} ?>

				<p>
					<label for="posts_per_page"><?php _e( 'Blog pages show at most' ); ?></label>
					<br /><input name="posts_per_page" type="number" step="1" min="1" id="posts_per_page" value="<?php form_option( 'posts_per_page' ); ?>" class="small-text" /> <?php _e( 'posts' ); ?>
				</p>

				<p>
					<label for="posts_per_rss"><?php _e( 'Syndication feeds show the most recent' ); ?></label>
					<br /><input name="posts_per_rss" type="number" step="1" min="1" id="posts_per_rss" value="<?php form_option( 'posts_per_rss' ); ?>" class="small-text" /> <?php _e( 'items' ); ?>
				</p>

			</fieldset>

			<fieldset form="<?php echo $this->fields . '-settings'; ?>">

				<legend class="screen-reader-text"><?php _e( 'RSS Feed' ); ?></legend>

				<h3><?php _e( 'RSS Feed' ); ?></h3>

				<p class="description"><?php _e( 'Articles in the RSS feeds can display the full content of each article or an automatic summary.' ); ?></p>

				<ul class="form-field-list">
					<li>
						<label>
							<input name="rss_use_excerpt" type="radio" value="0" <?php checked( 0, get_option( 'rss_use_excerpt' ) ); ?>	/>
							<?php _e( 'Full text' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input name="rss_use_excerpt" type="radio" value="1" <?php checked( 1, get_option( 'rss_use_excerpt' ) ); ?> />
							<?php _e( 'Summary' ); ?>
						</label>
					</li>
				</ul>

			</fieldset>

			<fieldset form="<?php echo $this->fields . '-settings'; ?>">

				<legend class="screen-reader-text"><?php has_action( 'blog_privacy_selector' ) ? _e( 'Site Visibility' ) : _e( 'Search Engine Visibility' ); ?></legend>

				<h3><?php has_action( 'blog_privacy_selector' ) ? _e( 'Site Visibility' ) : _e( 'Search Engine Visibility' ); ?></h3>

			<?php

			if ( has_action( 'blog_privacy_selector' ) ) :

			?>
				<p>
					<label for="blog-public">
						<input id="blog-public" type="radio" name="blog_public" value="1" <?php checked( '1', get_option( 'blog_public' ) ); ?> />
						<?php _e( 'Allow search engines to index this site' );?>
					</label>
				</p>

				<p>
					<label for="blog-norobots">
						<input id="blog-norobots" type="radio" name="blog_public" value="0" <?php checked( '0', get_option( 'blog_public' ) ); ?> />
						<?php _e( 'Discourage search engines from indexing this site' ); ?>
					</label>
				</p>

				<p class="description"><?php _e( 'Note: Neither of these options blocks access to your site &mdash; it is up to search engines to honor your request.' ); ?></p>
				<?php
				/**
				 * Enable the legacy 'Site Visibility' privacy options.
				 *
				 * By default the privacy options form displays a single checkbox to 'discourage' search
				 * engines from indexing the site. Hooking to this action serves a dual purpose:
				 * 1. Disable the single checkbox in favor of a multiple-choice list of radio buttons.
				 * 2. Open the door to adding additional radio button choices to the list.
				 *
				 * Hooking to this action also converts the 'Search Engine Visibility' heading to the more
				 * open-ended 'Site Visibility' heading.
				 *
				 * @since Previous 2.1.0
				 */
				do_action( 'blog_privacy_selector' );

			else :

			?>
				<p>
					<label for="blog_public">
						<input name="blog_public" type="checkbox" id="blog_public" value="0" <?php checked( '0', get_option( 'blog_public' ) ); ?> />
						<?php _e( 'Discourage search engines from indexing this site' ); ?>
					</label>
				</p>

				<p class="description"><?php _e( 'It is up to search engines to honor this request.' ); ?></p>
			<?php

			endif;

			?>
			</fieldset>
		</div>
		<?php
	}

	/**
	 * Render settings form
	 *
	 * Compiles the markup (fields, labels, descriptions, etc.)
	 * from registered tabs and prints them inside a form element.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function render_form() {

		echo "<form id='$this->fields-settings' method='post' action='$this->action'>";

		wp_nonce_field( 'update-permalink' );

		// settings_fields( $this->fields );
		// settings_fields( 'writing' );
		settings_fields( 'content' );

		do_action( 'settings_screen_add_fields_before' );
		do_action( 'render_screen_tabs' );
		do_action( 'settings_screen_add_fields_after' );

		echo get_submit_button( esc_html__( $this->submit ) );

		echo '</form>';
	}

	/**
	 * Help content
	 *
	 * Add content to the help section of the page.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help() {

		$screen = get_current_screen();

		$screen->add_help_tab( [
			'id'       => $screen->id . '-overview',
			'title'    => __( 'Overview' ),
			'content'  => null,
			'callback' => [ $this, 'help_overview' ]
		] );

		if ( apply_filters( 'enable_post_by_email_configuration', true ) ) {

			$screen->add_help_tab( [
				'id'       => $screen->id . '-postemail',
				'title'    => __( 'Post Via Email' ),
				'content'  => null,
				'callback' => [ $this, 'help_post_email' ]
			] );
		}

		if ( apply_filters( 'enable_update_services_configuration', true ) ) {

			$screen->add_help_tab( [
				'id'       => $screen->id . '-services',
				'title'    => __( 'Update Services' ),
				'content'  => null,
				'callback' => [ $this, 'help_services' ]
			] );
		}
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
			__( 'You can submit content in several different ways; this screen holds the settings for all of them. The top section controls the editor within the dashboard, while the rest control external publishing methods.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'You must click the Save Changes button at the bottom of the screen for new settings to take effect.' )
		);

		$help = apply_filters( 'help_settings_content_overview', $help );

		echo $help;
	}

	/**
	 * Email post help content
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help_post_email() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Post Via Email' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'Post via email settings allow you to send your installation an email with the content of your post. You must set up a secret email account with POP3 access to use this, and any mail received at this address will be posted, so it&#8217;s a good idea to keep this address very secret.' )
		);

		$help = apply_filters( 'help_settings_content_post_email', $help );

		echo $help;
	}

	/**
	 * Services help content
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help_services() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Update Services' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'Will automatically alert various services of your new posts.' )
		);

		$help = apply_filters( 'help_settings_content_services', $help );

		echo $help;
	}
}
