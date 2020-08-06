<?php
/**
 * Content settings screen class
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

		/**
		 * @todo Fix this or remove permalinks, keep on separate page.
		 *
		$screen->add_content_tab( [
			'id'         => 'permalinks',
			'capability' => 'manage_options',
			'tab'        => __( 'Permalinks' ),
			'icon'       => '',
			'heading'    => __( 'Content Permalink Settings' ),
			'content'    => '',
			'callback'   => [ $this, 'permalinks' ]
		] );
		*/
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
	 * Permalinks tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function permalinks() {

		global $wp_rewrite;

		$wp_rewrite = new \WP_Rewrite();
		$home_path           = get_home_path();
		$iis7_permalinks     = iis7_supports_permalinks();
		$permalink_structure = get_option( 'permalink_structure' );
		$is_nginx = '';
		$prefix = $blog_prefix = '';

		if ( ! got_url_rewrite() ) {
			$prefix = '/index.php';
		}

		/**
		 * In a subdirectory configuration of network, the `/blog` prefix is used by
		 * default on the main site to avoid collisions with other sites created on that
		 * network. If the `permalink_structure` option has been changed to remove this
		 * base prefix, core can no longer account for the possible collision.
		 */
		if ( is_network() && ! is_subdomain_install() && is_main_site() && 0 === strpos( $permalink_structure, '/blog/' ) ) {
			$blog_prefix = '/blog';
		}

		$category_base   = get_option( 'category_base' );
		$tag_base        = get_option( 'tag_base' );
		$update_required = false;

		if ( $iis7_permalinks ) {

			if ( ( ! file_exists( $home_path . 'web.config' ) && win_is_writable( $home_path) ) || win_is_writable( $home_path . 'web.config' ) ) {
				$writable = true;
			} else {
				$writable = false;
			}

		} elseif ( $is_nginx ) {
			$writable = false;

		} else {

			if ( ( ! file_exists( $home_path . '.htaccess' ) && is_writable( $home_path ) ) || is_writable( $home_path . '.htaccess' ) ) {
				$writable = true;

			} else {
				$writable        = false;
				$existing_rules  = array_filter( extract_from_markers( $home_path . '.htaccess', 'system' ) );
				$new_rules       = array_filter( explode( "\n", $wp_rewrite->mod_rewrite_rules() ) );
				$update_required = ( $new_rules !== $existing_rules );
			}
		}

		$using_index_permalinks = $wp_rewrite->using_index_permalinks();

		if ( isset( $_POST['permalink_structure'] ) || isset( $_POST['category_base'] ) ) {

			check_admin_referer( 'update-permalink' );

			if ( isset( $_POST['permalink_structure'] ) ) {

				if ( isset( $_POST['selection'] ) && 'custom' != $_POST['selection'] ) {
					$permalink_structure = $_POST['selection'];
				} else {
					$permalink_structure = $_POST['permalink_structure'];
				}

				if ( ! empty( $permalink_structure ) ) {

					$permalink_structure = preg_replace( '#/+#', '/', '/' . str_replace( '#', '', $permalink_structure ) );

					if ( $prefix && $blog_prefix ) {
						$permalink_structure = $prefix . preg_replace( '#^/?index\.php#', '', $permalink_structure );
					} else {
						$permalink_structure = $blog_prefix . $permalink_structure;
					}
				}

				$permalink_structure = sanitize_option( 'permalink_structure', $permalink_structure );
				$wp_rewrite->set_permalink_structure( $permalink_structure );
			}

			if ( isset( $_POST['category_base'] ) ) {

				$category_base = $_POST['category_base'];

				if ( ! empty( $category_base ) ) {
					$category_base = $blog_prefix . preg_replace( '#/+#', '/', '/' . str_replace( '#', '', $category_base ) );
				}

				$wp_rewrite->set_category_base( $category_base );
			}

			if ( isset( $_POST['tag_base'] ) ) {

				$tag_base = $_POST['tag_base'];

				if ( ! empty( $tag_base ) ) {
					$tag_base = $blog_prefix . preg_replace( '#/+#', '/', '/' . str_replace( '#', '', $tag_base ) );
				}

				$wp_rewrite->set_tag_base( $tag_base );
			}

			$message = __( 'Permalink structure updated.' );

			if ( $iis7_permalinks ) {

				if ( $permalink_structure && ! $using_index_permalinks && ! $writable ) {
					$message = __( 'You should update your web.config now.' );
				} elseif ( $permalink_structure && ! $using_index_permalinks && $writable ) {
					$message = __( 'Permalink structure updated. Remove write access on web.config file now!' );
				}

			} elseif ( ! $is_nginx && $permalink_structure && ! $using_index_permalinks && ! $writable && $update_required ) {
				$message = __( 'You should update your .htaccess now.' );
			}

			if ( ! get_settings_errors() ) {
				add_settings_error( 'general', 'settings_updated', $message, 'updated' );
			}

			set_transient( 'settings_errors', get_settings_errors(), 30 );

			wp_redirect( admin_url( 'options-content.php?settings-updated=true' ) );

			exit;
		}

		flush_rewrite_rules();


		?>
		<div class="tab-section-wrap tab-section-wrap__permalinks">

			<?php wp_nonce_field( 'update-permalink' ) ?>

			<p><?php
				printf(
					// Translators: %s: Codex URL.
					__( 'You can create a custom URL structure for your permalinks and archives. Custom URL structures can improve the aesthetics, usability, and forward-compatibility of your links. A number of tags are available and here are some examples to get you started.' )
				);
			?></p>

			<?php
			if ( is_network() && ! is_subdomain_install() && is_main_site() && 0 === strpos( $permalink_structure, '/blog/' ) ) {

			$permalink_structure = preg_replace( '|^/?blog|', '', $permalink_structure );
			$category_base       = preg_replace( '|^/?blog|', '', $category_base );
			$tag_base            = preg_replace( '|^/?blog|', '', $tag_base );
			}

			$structures = [
			0 => '',
			1 => $prefix . '/%year%/%monthnum%/%day%/%postname%/',
			2 => $prefix . '/%year%/%monthnum%/%postname%/',
			3 => $prefix . '/' . _x( 'archives', 'sample permalink base' ) . '/%post_id%',
			4 => $prefix . '/%postname%/',
			]; ?>

			<h2 class="title"><?php _e( 'Common Settings' ); ?></h2>

			<table class="form-table permalink-structure">
			<tr>
				<th><label><input name="selection" type="radio" value="" <?php checked( '', $permalink_structure ); ?> /> <?php _e( 'Plain' ); ?></label></th>
				<td><code><?php echo get_option( 'home' ); ?>/?p=123</code></td>
			</tr>
			<tr>
				<th><label><input name="selection" type="radio" value="<?php echo esc_attr( $structures[1] ); ?>" <?php checked( $structures[1], $permalink_structure ); ?> /> <?php _e( 'Day and name' ); ?></label></th>
				<td><code><?php echo get_option( 'home' ) . $blog_prefix . $prefix . '/' . date( 'Y' ) . '/' . date( 'm' ) . '/' . date( 'd' ) . '/' . _x( 'sample-post', 'sample permalink structure' ) . '/'; ?></code></td>
			</tr>
			<tr>
				<th><label><input name="selection" type="radio" value="<?php echo esc_attr( $structures[2] ); ?>" <?php checked( $structures[2], $permalink_structure ); ?> /> <?php _e( 'Month and name' ); ?></label></th>
				<td><code><?php echo get_option( 'home' ) . $blog_prefix . $prefix . '/' . date( 'Y' ) . '/' . date( 'm' ) . '/' . _x( 'sample-post', 'sample permalink structure' ) . '/'; ?></code></td>
			</tr>
			<tr>
				<th><label><input name="selection" type="radio" value="<?php echo esc_attr( $structures[3] ); ?>" <?php checked( $structures[3], $permalink_structure ); ?> /> <?php _e( 'Numeric' ); ?></label></th>
				<td><code><?php echo get_option( 'home' ) . $blog_prefix . $prefix . '/' . _x( 'archives', 'sample permalink base' ) . '/123'; ?></code></td>
			</tr>
			<tr>
				<th><label><input name="selection" type="radio" value="<?php echo esc_attr( $structures[4] ); ?>" <?php checked( $structures[4], $permalink_structure ); ?> /> <?php _e( 'Post name' ); ?></label></th>
				<td><code><?php echo get_option( 'home' ) . $blog_prefix . $prefix . '/' . _x( 'sample-post', 'sample permalink structure' ) . '/'; ?></code></td>
			</tr>
			<tr>
				<th>
					<label><input name="selection" id="custom_selection" type="radio" value="custom" <?php checked( !in_array( $permalink_structure, $structures) ); ?> />
					<?php _e( 'Custom Structure' ); ?>
					</label>
				</th>
				<td>
					<code><?php echo get_option( 'home' ) . $blog_prefix; ?></code>
					<input name="permalink_structure" id="permalink_structure" type="text" value="<?php echo esc_attr( $permalink_structure ); ?>" class="regular-text code" />

					<div class="available-structure-tags hide-if-no-js">

						<div id="custom_selection_updated" aria-live="assertive" class="screen-reader-text"></div>
						<?php
						$available_tags = array(
							// Translators: %s: permalink structure tag.
							'year'     => __( '%s (The year of the post, four digits, for example 2004.)' ),
							// Translators: %s: permalink structure tag.
							'monthnum' => __( '%s (Month of the year, for example 05.)' ),
							// Translators: %s: permalink structure tag.
							'day'      => __( '%s (Day of the month, for example 28.)' ),
							// Translators: %s: permalink structure tag.
							'hour'     => __( '%s (Hour of the day, for example 15.)' ),
							// Translators: %s: permalink structure tag.
							'minute'   => __( '%s (Minute of the hour, for example 43.)' ),
							// Translators: %s: permalink structure tag.
							'second'   => __( '%s (Second of the minute, for example 33.)' ),
							// Translators: %s: permalink structure tag.
							'post_id'  => __( '%s (The unique ID of the post, for example 423.)' ),
							// Translators: %s: permalink structure tag.
							'postname' => __( '%s (The sanitized post title (slug).)' ),
							// Translators: %s: permalink structure tag.
							'category' => __( '%s (Category slug. Nested sub-categories appear as nested directories in the URL.)' ),
							// Translators: %s: permalink structure tag.
							'author'   => __( '%s (A sanitized version of the author name.)' ),
						);

						/**
						 * Filters the list of available permalink structure tags on the Permalinks settings page.
						 *
						 * @since Previous 4.8.0
						 * @param array $available_tags A key => value pair of available permalink structure tags.
						 */
						$available_tags = apply_filters( 'available_permalink_structure_tags', $available_tags );

						// Translators: %s: permalink structure tag.
						$structure_tag_added = __( '%s added to permalink structure' );

						// Translators: %s: permalink structure tag.
						$structure_tag_already_used = __( '%s (already used in permalink structure )' );

						if ( ! empty( $available_tags ) ) :

						?>
							<p><?php _e( 'Available tags:' ); ?></p>
							<ul role="list">
								<?php
								foreach ( $available_tags as $tag => $explanation ) {
									?>
									<li>
										<button type="button"
												class="button button-secondary"
												aria-label="<?php echo esc_attr( sprintf( $explanation, $tag ) ); ?>"
												data-added="<?php echo esc_attr( sprintf( $structure_tag_added, $tag ) ); ?>"
												data-used="<?php echo esc_attr( sprintf( $structure_tag_already_used, $tag ) ); ?>">
											<?php echo '%' . $tag . '%'; ?>
										</button>
									</li>
									<?php
								} ?>
							</ul>
						<?php endif; ?>
					</div>
				</td>
			</tr>
			</table>

			<h2 class="title"><?php _e( 'Optional' ); ?></h2>

			<p>
			<?php
			// Translators: %s: placeholder that must come at the start of the URL */
			printf( __( 'If you like, you may enter custom structures for your category and tag URLs here. For example, using <code>topics</code> as your category base would make your category links like <code>%s/topics/uncategorized/</code>. If you leave these blank the defaults will be used.' ), get_option( 'home' ) . $blog_prefix . $prefix );
			?>
			</p>

			<table class="form-table">
			<tr>
				<th><label for="category_base"><?php // Translators: prefix for category permalinks */ _e( 'Category base' ); ?></label></th>
				<td><?php echo $blog_prefix; ?> <input name="category_base" id="category_base" type="text" value="<?php echo esc_attr( $category_base ); ?>" class="regular-text code" /></td>
			</tr>
			<tr>
				<th><label for="tag_base"><?php _e( 'Tag base' ); ?></label></th>
				<td><?php echo $blog_prefix; ?> <input name="tag_base" id="tag_base" type="text" value="<?php echo esc_attr( $tag_base ); ?>" class="regular-text code" /></td>
			</tr>
			<?php // do_settings_fields( 'permalink', 'optional' ); ?>
			</table>

			<?php // do_settings_sections( 'permalink' ); ?>

			<?php if ( ! is_network() ) {

				if ( $iis7_permalinks ) :
					if ( isset( $_POST['submit'] ) && $permalink_structure && ! $using_index_permalinks && ! $writable ) :
						if ( file_exists( $home_path . 'web.config' ) ) : ?>
				<p>
				<?php
					printf(
						// Translators: 1: web.config, 2: Codex URL, 3: CTRL + a, 4: element code */
						__( 'If your %1$s file was writable, we could do this automatically, but it isn&#8217;t so this is the url rewrite rule you should have in your %1$s file. Click in the field and press %2$s to select all. Then insert this rule inside of the %3$s element in %1$s file.' ),
						'<code>web.config</code>',
						'<kbd>CTRL + a</kbd>',
						'<code>/&lt;configuration&gt;/&lt;system.webServer&gt;/&lt;rewrite&gt;/&lt;rules&gt;</code>'
					);
				?>
				</p>

				<?php wp_nonce_field( 'update-permalink' ) ?>
					<p><textarea rows="9" class="large-text readonly" name="rules" id="rules" readonly="readonly"><?php echo esc_textarea( $wp_rewrite->iis7_url_rewrite_rules() ); ?></textarea></p>
				<p>
				<?php
					printf(
						// Translators: %s: web.config.
						__( 'If you temporarily make your %s file writable for us to generate rewrite rules automatically, do not forget to revert the permissions after rule has been saved.' ),
						'<code>web.config</code>'
					);
				?>
				</p>
					<?php else : ?>
				<p>
				<?php
					printf(
						// Translators: 1: Codex URL, 2: web.config, 3: CTRL + a */
						__( 'If the root directory of your site was writable, we could do this automatically, but it isn&#8217;t so this is the url rewrite rule you should have in your %2$s file. Create a new file, called %1$s in the root directory of your site. Click in the field and press %2$s to select all. Then insert this code into the %2$s file.' ),
						'<code>web.config</code>',
						'<kbd>CTRL + a</kbd>'
					);
				?>
				</p>

				<?php wp_nonce_field( 'update-permalink' ) ?>

				<p><textarea rows="18" class="large-text readonly" name="rules" id="rules" readonly="readonly"><?php echo esc_textarea( $wp_rewrite->iis7_url_rewrite_rules(true ) ); ?></textarea></p>

				<p>
				<?php
					printf(
						// Translators: %s: web.config.
						__( 'If you temporarily make your site&#8217;s root directory writable for us to generate the %s file automatically, do not forget to revert the permissions after the file has been created.' ),
						'<code>web.config</code>'
					);
				?>
				</p>
					<?php endif; ?>
				<?php endif; ?>
				<?php else:
				if ( $permalink_structure && ! $using_index_permalinks && ! $writable && $update_required ) : ?>
				<p>
				<?php
					printf(
						// Translators: 1: .htaccess, 2: Codex URL, 3: CTRL + a */
						__( 'If your %1$s file was writable, we could do this automatically, but it isn&#8217;t so these are the mod_rewrite rules you should have in your %1$s file. Click in the field and press %2$s to select all.' ),
						'<code>.htaccess</code>',
						'<kbd>CTRL + a</kbd>'
					);
				?>
				</p>

				<?php wp_nonce_field( 'update-permalink' ) ?>

				<p>
					<textarea rows="6" class="large-text readonly" name="rules" id="rules" readonly="readonly"><?php echo esc_textarea( $wp_rewrite->mod_rewrite_rules() ); ?></textarea>
				</p>

				<?php endif; ?>
				<?php endif; ?>
				<?php } // network. ?>
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
		do_action( 'render_tabs_settings_screen' );
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
