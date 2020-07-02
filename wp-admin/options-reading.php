<?php
/**
 * Reading settings administration panel.
 *
 * @package App_Package
 * @subpackage Administration
 */

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! current_user_can( 'manage_options' ) )
	wp_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );

$title       = __( 'Reading Settings' );
$parent_file = 'options-general.php';

add_action( 'admin_head', 'options_reading_add_js' );

$help_overview = sprintf(
	'<h3>%1s</h3>',
	__( 'Overview' )
);

$help_overview .= sprintf(
	'<p>%1s</p>',
	__( 'This screen contains the settings that affect the display of your content.' )
);

$help_overview .= sprintf(
	'<p>%1s</p>',
	__( 'You can choose what&#8217;s displayed on the homepage of your site. It can be posts in reverse chronological order (classic blog), or a fixed/static page. To set a static homepage, you first need to create two pages. One will become the homepage, and the other will be where your posts are displayed.' )
);

$help_overview .= sprintf(
	'<p>%1s</p>',
	__( 'You can also control the display of your content in RSS feeds, including the maximum number of posts to display and whether to show full text or a summary.' )
);

$help_overview .= sprintf(
	'<p>%1s</p>',
	__( 'You must click the Save Changes button at the bottom of the screen for new settings to take effect.' )
);

$help_overview = apply_filters( 'help_options_reading_overview', $help_overview );

get_current_screen()->add_help_tab( [
	'id'      => 'overview',
	'title'   => __( 'Overview' ),
	'content' => $help_overview
] );

$help_visibility = sprintf(
	'<h3>%1s</h3>',
	has_action( 'blog_privacy_selector' ) ? __( 'Site Visibility' ) : __( 'Search Engine Visibility' ),
);

$help_visibility .= sprintf(
	'<p>%1s</p>',
	__( 'You can choose whether or not your site will be crawled by robots and spiders. If you want those services to ignore your site, click the checkbox next to &#8220;Discourage search engines from indexing this site&#8221; and click the Save Changes button at the bottom of the screen. Note that your privacy is not complete; your site is still visible on the web.' )
);

$help_visibility .= sprintf(
	'<p>%1s</p>',
	__( 'When this setting is in effect, a reminder is shown in the Site Overview box of the Dashboard that says, &#8220;Search Engines Discouraged,&#8221; to remind you that your site is not being crawled.' )
);

$help_visibility = apply_filters( 'help_options_site_visibility', $help_visibility );

get_current_screen()->add_help_tab( [
	'id'      => 'site-visibility',
	'title'   => has_action( 'blog_privacy_selector' ) ? __( 'Site Visibility' ) : __( 'Search Engine Visibility' ),
	'content' => $help_visibility
] );

/**
 * Help sidebar content
 *
 * This system adds no content to the help sidebar
 * but there is a filter applied for adding content.
 *
 * @since 1.0.0
 */
$set_help_sidebar = apply_filters( 'set_help_sidebar_options_reading', '' );
get_current_screen()->set_help_sidebar( $set_help_sidebar );

include( ABSPATH . 'wp-admin/admin-header.php' );

?>
	<div class="wrap">

		<h1><?php echo esc_html( $title ); ?></h1>

		<form method="post" action="options.php">

		<?php settings_fields( 'reading' );

		if ( ! in_array( get_option( 'blog_charset' ), [ 'utf8', 'utf-8', 'UTF8', 'UTF-8' ] ) ) {
			add_settings_field( 'blog_charset', __( 'Encoding for pages and feeds' ), 'options_reading_blog_charset', 'reading', 'default', [ 'label_for' => 'blog_charset' ] );
		}

		if ( ! get_pages() ) { ?>
		<input name="show_on_front" type="hidden" value="posts" />

		<table class="form-table">
		<?php
			if ( 'posts' != get_option( 'show_on_front' ) ) {
				update_option( 'show_on_front', 'posts' );
			}

		} else {
			if ( 'page' == get_option( 'show_on_front' ) && ! get_option( 'page_on_front' ) && ! get_option( 'page_for_posts' ) ) {
				update_option( 'show_on_front', 'posts' );
			}
		?>
		<table class="form-table">

			<tr>
				<th scope="row"><?php _e( 'Your homepage displays' ); ?></th>

				<td id="front-static-pages">
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e( 'Your homepage displays' ); ?></span></legend>

						<p>
							<label>
								<input name="show_on_front" type="radio" value="posts" class="tog" <?php checked( 'posts', get_option( 'show_on_front' ) ); ?> />
								<?php _e( 'Your latest posts' ); ?>
							</label>
						</p>

						<p>
							<label>
								<input name="show_on_front" type="radio" value="page" class="tog" <?php checked( 'page', get_option( 'show_on_front' ) ); ?> />
								<?php printf( __( 'A <a href="%s">static page</a> (select below)' ), 'edit.php?post_type=page' ); ?>
							</label>
						</p>

						<ul>
							<li><label for="page_on_front"><?php printf( __( 'Homepage: %s' ), wp_dropdown_pages( array( 'name' => 'page_on_front', 'echo' => 0, 'show_option_none' => __( '&mdash; Select &mdash;' ), 'option_none_value' => '0', 'selected' => get_option( 'page_on_front' ) ) ) ); ?></label></li>
							<li><label for="page_for_posts"><?php printf( __( 'Posts page: %s' ), wp_dropdown_pages( array( 'name' => 'page_for_posts', 'echo' => 0, 'show_option_none' => __( '&mdash; Select &mdash;' ), 'option_none_value' => '0', 'selected' => get_option( 'page_for_posts' ) ) ) ); ?></label></li>
						</ul>

					<?php if ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) == get_option( 'page_on_front' ) ) : ?>
						<div id="front-page-warning" class="error inline">
							<p><?php _e( '<strong>Warning:</strong> these pages should not be the same!' ); ?></p>
						</div>
					<?php endif; ?>

					</fieldset>
				</td>
			</tr>
			<?php
			} ?>

			<tr>
				<th scope="row"><label for="posts_per_page"><?php _e( 'Blog pages show at most' ); ?></label></th>

				<td>
					<input name="posts_per_page" type="number" step="1" min="1" id="posts_per_page" value="<?php form_option( 'posts_per_page' ); ?>" class="small-text" /> <?php _e( 'posts' ); ?>
				</td>
			</tr>

			<tr>
				<th scope="row"><label for="posts_per_rss"><?php _e( 'Syndication feeds show the most recent' ); ?></label></th>

				<td><input name="posts_per_rss" type="number" step="1" min="1" id="posts_per_rss" value="<?php form_option( 'posts_per_rss' ); ?>" class="small-text" /> <?php _e( 'items' ); ?></td>
			</tr>

			<tr>
				<th scope="row"><?php _e( 'For each article in a feed, show' ); ?> </th>

				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e( 'For each article in a feed, show' ); ?> </span></legend>

						<p>
							<label><input name="rss_use_excerpt" type="radio" value="0" <?php checked( 0, get_option( 'rss_use_excerpt' ) ); ?>	/> <?php _e( 'Full text' ); ?></label>

							<br />

							<label><input name="rss_use_excerpt" type="radio" value="1" <?php checked( 1, get_option( 'rss_use_excerpt' ) ); ?> /> <?php _e( 'Summary' ); ?></label>
						</p>
					</fieldset>
				</td>
			</tr>

			<tr class="option-site-visibility">
				<th scope="row"><?php has_action( 'blog_privacy_selector' ) ? _e( 'Site Visibility' ) : _e( 'Search Engine Visibility' ); ?> </th>

				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php has_action( 'blog_privacy_selector' ) ? _e( 'Site Visibility' ) : _e( 'Search Engine Visibility' ); ?> </span></legend>

					<?php
					if ( has_action( 'blog_privacy_selector' ) ) : ?>
						<input id="blog-public" type="radio" name="blog_public" value="1" <?php checked( '1', get_option( 'blog_public' ) ); ?> />
						<label for="blog-public"><?php _e( 'Allow search engines to index this site' );?></label>

						<br/>

						<input id="blog-norobots" type="radio" name="blog_public" value="0" <?php checked( '0', get_option( 'blog_public' ) ); ?> />
						<label for="blog-norobots"><?php _e( 'Discourage search engines from indexing this site' ); ?></label>

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

					else : ?>
						<label for="blog_public"><input name="blog_public" type="checkbox" id="blog_public" value="0" <?php checked( '0', get_option( 'blog_public' ) ); ?> />

						<?php _e( 'Discourage search engines from indexing this site' ); ?></label>
						<p class="description"><?php _e( 'It is up to search engines to honor this request.' ); ?></p>
					<?php
					endif; ?>
					</fieldset>
				</td>
			</tr>

			<?php do_settings_fields( 'reading', 'default' ); ?>

		</table>

		<?php do_settings_sections( 'reading' ); ?>

		<?php submit_button(); ?>
		</form>
	</div>
<?php

include( ABSPATH . 'wp-admin/admin-footer.php' );