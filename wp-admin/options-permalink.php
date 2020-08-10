<?php
/**
 * Permalink Settings Administration Screen.
 *
 * @package App_Package
 * @subpackage Administration
 */

use \AppNamespace\Backend as Backend;

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );
}

// Instance of the general settings class.
$class = Backend\Settings_Permalinks :: instance();

// Page identification.
$parent_file = $class->parent;
$title       = $class->title();
$description = $class->description();

$home_path           = get_home_path();
$iis7_permalinks     = iis7_supports_permalinks();
$permalink_structure = get_option( 'permalink_structure' );

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

	wp_redirect( admin_url( 'options-permalink.php?settings-updated=true' ) );

	exit;
}

flush_rewrite_rules();

// Get page header.
require( ABSPATH . 'wp-admin/admin-header.php' );

?>
<div class="wrap">

	<h1><?php echo esc_html( $title ); ?></h1>
	<?php echo $description; ?>

	<form name="form" action="options-permalink.php" method="post">

		<div class="app-tabs" data-tabbed="tabbed" data-tabevent="click" data-tabdeeplinking="true">

			<ul class='app-tabs-list app-tabs-horizontal hide-if-no-js'>
				<!-- Avoid whitespace by not closing the `<li>` tags. -->
				<li class="app-tab"><a href="#general"><?php _e( 'General' ); ?></a>
				<li class="app-tab"><a href="#taxonomy"><?php _e( 'Taxonomy' ); ?></a>
			</ul>

			<?php wp_nonce_field( 'update-permalink' ); ?>

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

			<div id="general" class="app-tab-content">

				<h2><?php _e( 'General Settings' ); ?></h2>

				<p><?php
					printf(
						__( 'You can create a custom URL structure for your permalinks and archives. Custom URL structures can improve the aesthetics, usability, and forward-compatibility of your links. A number of tags are available and here are some examples to get you started.' )
					);
				?></p>

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
							<label><input name="selection" id="custom_selection" type="radio" value="custom" <?php checked( ! in_array( $permalink_structure, $structures) ); ?> />
							<?php _e( 'Custom Structure' ); ?>
							</label>
						</th>
						<td>
							<code><?php echo get_option( 'home' ) . $blog_prefix; ?></code>
							<input name="permalink_structure" id="permalink_structure" type="text" value="<?php echo esc_attr( $permalink_structure ); ?>" class="regular-text code" />

							<div class="available-structure-tags hide-if-no-js">

								<div id="custom_selection_updated" aria-live="assertive" class="screen-reader-text"></div>
								<?php
								$available_tags = [
									'year'     => __( '%s (The year of the post, four digits, for example 2004.)' ),
									'monthnum' => __( '%s (Month of the year, for example 05.)' ),
									'day'      => __( '%s (Day of the month, for example 28.)' ),
									'hour'     => __( '%s (Hour of the day, for example 15.)' ),
									'minute'   => __( '%s (Minute of the hour, for example 43.)' ),
									'second'   => __( '%s (Second of the minute, for example 33.)' ),
									'post_id'  => __( '%s (The unique ID of the post, for example 423.)' ),
									'postname' => __( '%s (The sanitized post title (slug).)' ),
									'category' => __( '%s (Category slug. Nested sub-categories appear as nested directories in the URL.)' ),
									'author'   => __( '%s (A sanitized version of the author name.)' ),
								];

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
									<p><label for="tags-list"><?php _e( 'Available tags:' ); ?></label></p>

									<ul id="tags-list" role="list">
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

				<?php do_settings_sections( 'permalink' ); ?>
			</div>

			<div id="taxonomy" class="app-tab-content">

				<h2><?php _e( 'Taxonomy Settings' ); ?></h2>

				<p>
				<?php
					printf( __( 'If you like, you may enter custom structures for your category and tag URLs here. For example, using <code>topics</code> as your category base would make your category links like <code>%s/topics/general/</code>. If you leave these blank the defaults will be used.' ), get_option( 'home' ) . $blog_prefix . $prefix );
				?>
				</p>

				<p>
					<label for="category_base"><?php _e( 'Category base' ); ?></label>
					<br /><?php echo $blog_prefix; ?> <input name="category_base" id="category_base" type="text" value="<?php echo esc_attr( $category_base ); ?>" class="regular-text code" />
				</p>

				<p>
					<label for="tag_base"><?php _e( 'Tag base' ); ?></label>
					<br /><?php echo $blog_prefix; ?> <input name="tag_base" id="tag_base" type="text" value="<?php echo esc_attr( $tag_base ); ?>" class="regular-text code" />
				</p>

				<?php
				do_settings_fields( 'permalink', 'taxonomy' );
				do_settings_fields( 'permalink', 'optional' );
				?>

			</div>

		</div><!-- .app-tabs -->

		<?php submit_button( $class->submit ); ?>

	</form>

<?php if ( ! is_network() ) {

	if ( $iis7_permalinks ) :
		if ( isset( $_POST['submit'] ) && $permalink_structure && ! $using_index_permalinks && ! $writable ) :
			if ( file_exists( $home_path . 'web.config' ) ) : ?>
	<p>
	<?php
		printf(
			__( 'If your %1$s file was writable, we could do this automatically, but it isn&#8217;t so this is the url rewrite rule you should have in your %1$s file. Click in the field and press %2$s to select all. Then insert this rule inside of the %3$s element in %1$s file.' ),
			'<code>web.config</code>',
			'<kbd>CTRL + a</kbd>',
			'<code>/&lt;configuration&gt;/&lt;system.webServer&gt;/&lt;rewrite&gt;/&lt;rules&gt;</code>'
		);
	?>
	</p>

	<form action="options-permalink.php" method="post">

		<?php wp_nonce_field( 'update-permalink' ); ?>

		<p>
			<textarea rows="9" class="large-text readonly" name="rules" id="rules" readonly="readonly">
				<?php echo esc_textarea( $wp_rewrite->iis7_url_rewrite_rules() ); ?>
			</textarea>
		</p>
	</form>
	<p>
	<?php
		printf(
			__( 'If you temporarily make your %s file writable for us to generate rewrite rules automatically, do not forget to revert the permissions after rule has been saved.' ),
			'<code>web.config</code>'
		);
	?>
	</p>
		<?php else : ?>
	<p>
	<?php
		printf(
			__( 'If the root directory of your site was writable, we could do this automatically, but it isn&#8217;t so this is the url rewrite rule you should have in your %2$s file. Create a new file, called %1$s in the root directory of your site. Click in the field and press %2$s to select all. Then insert this code into the %2$s file.' ),
			'<code>web.config</code>',
			'<kbd>CTRL + a</kbd>'
		);
	?>
	</p>

	<form action="options-permalink.php" method="post">

		<?php wp_nonce_field( 'update-permalink' ); ?>

		<p>
			<textarea rows="18" class="large-text readonly" name="rules" id="rules" readonly="readonly">
				<?php echo esc_textarea( $wp_rewrite->iis7_url_rewrite_rules(true ) ); ?>
			</textarea>
		</p>

	</form>

	<p>
	<?php
		printf(
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
			__( 'If your %1$s file was writable, we could do this automatically, but it isn&#8217;t so these are the mod_rewrite rules you should have in your %1$s file. Click in the field and press %2$s to select all.' ),
			'<code>.htaccess</code>',
			'<kbd>CTRL + a</kbd>'
		);
	?>
	</p>

	<form action="options-permalink.php" method="post">

		<?php wp_nonce_field( 'update-permalink' ); ?>

		<p>
			<textarea rows="6" class="large-text readonly" name="rules" id="rules" readonly="readonly">
				<?php echo esc_textarea( $wp_rewrite->mod_rewrite_rules() ); ?>
			</textarea>
		</p>

	</form>

	<?php endif; ?>
<?php endif; ?>
<?php } // is_network. ?>

</div><!-- .wrap -->

<?php

// Get page footer.
require( ABSPATH . 'wp-admin/admin-footer.php' );
