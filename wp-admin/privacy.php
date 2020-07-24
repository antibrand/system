<?php
/**
 * Privacy policy page
 *
 * @package App_Package
 * @subpackage Administration
 */

// Administration page dependencies.
require_once( dirname( __FILE__ ) . '/admin.php' );

// Variable for the privacy policy guide URL parameter.
$is_privacy_guide = ( isset( $_GET['privacy-policy-guide'] ) && current_user_can( 'manage_privacy_options' ) );

// Conditional page title.
if ( $is_privacy_guide ) {

	// Page title & title tag.
	$title = __( 'Privacy Policy Guide' );

	// "Borrow" xfn.js for now so we don't have to create new files.
	wp_enqueue_script( 'xfn' );

} else {

	// Page title & title tag.
	$title = __( 'Account Privacy' );

	wp_enqueue_script( 'jquery-ui-tabs' );
}

// Parent file for menu highlighting.
$parent_file = 'options-general.php';

if ( ! current_user_can( 'manage_privacy_options' ) ) {
	wp_die( __( 'You are not allowed to manage privacy on this site.' ) );
}

if ( isset( $_POST['action'] ) ) {
	$action = $_POST['action'];
} else {
	$action = '';
}

if ( ! empty( $action ) ) {

	check_admin_referer( $action );

	if ( 'set-privacy-page' === $action ) {

		if ( isset( $_POST['page_for_privacy_policy'] ) ) {
			$privacy_policy_page_id = (int) $_POST['page_for_privacy_policy'];
		} else {
			$privacy_policy_page_id = 0;
		}

		update_option( 'wp_page_for_privacy_policy', $privacy_policy_page_id );

		$privacy_page_updated_message = __( 'Privacy Policy page updated successfully.' );

		if ( $privacy_policy_page_id ) {
			/*
			 * Don't always link to the menu customizer:
			 *
			 * - Unpublished pages can't be selected by default.
			 * - `WP_Customize_Nav_Menus::__construct()` checks the user's capabilities.
			 * - Themes might not "officially" support menus.
			 */
			if (
				'publish' === get_post_status( $privacy_policy_page_id )
				&& current_user_can( 'edit_theme_options' )
				&& current_theme_supports( 'menus' )
			) {
				$privacy_page_updated_message = sprintf(
					/* translators: %s: URL to live manager -> menus */
					__( 'Privacy Policy page updated successfully. Remember to <a href="%s">update menus</a>!' ),
					esc_url( add_query_arg( 'autofocus[panel]', 'nav_menus', admin_url( 'customize.php' ) ) )
				);
			}
		}

		add_settings_error(
			'page_for_privacy_policy',
			'page_for_privacy_policy',
			$privacy_page_updated_message,
			'updated'
		);

	} elseif ( 'create-privacy-page' === $action ) {

		if ( ! class_exists( 'WP_Privacy_Policy_Content' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/misc.php' );
		}

		$privacy_policy_page_content = WP_Privacy_Policy_Content::get_default_content();
		$privacy_policy_page_id      = wp_insert_post(
			[
				'post_title'   => __( 'Privacy Policy' ),
				'post_status'  => 'draft',
				'post_type'    => 'page',
				'post_content' => $privacy_policy_page_content,
			],
			true
		);

		if ( is_wp_error( $privacy_policy_page_id ) ) {

			add_settings_error(
				'page_for_privacy_policy',
				'page_for_privacy_policy',
				__( 'Unable to create a Privacy Policy page.' ),
				'error'
			);

		} else {
			update_option( 'wp_page_for_privacy_policy', $privacy_policy_page_id );

			wp_redirect( admin_url( 'post.php?post=' . $privacy_policy_page_id . '&action=edit' ) );
			exit;
		}
	}
}

// If a Privacy Policy page ID is available, make sure the page actually exists. If not, display an error.
$privacy_policy_page_exists = false;
$privacy_policy_page_id     = (int) get_option( 'wp_page_for_privacy_policy' );

if ( ! empty( $privacy_policy_page_id ) ) {

	$privacy_policy_page = get_post( $privacy_policy_page_id );

	if ( ! $privacy_policy_page instanceof WP_Post ) {

		add_settings_error(
			'page_for_privacy_policy',
			'page_for_privacy_policy',
			__( 'The currently selected Privacy Policy page does not exist. Please create or select a new page.' ),
			'error'
		);

	} else {

		if ( 'trash' === $privacy_policy_page->post_status ) {

			add_settings_error(
				'page_for_privacy_policy',
				'page_for_privacy_policy',
				sprintf(
					/* translators: URL to Pages Trash */
					__( 'The currently selected Privacy Policy page is in the trash. Please create or select a new Privacy Policy page or <a href="%s">restore the current page</a>.' ),
					'edit.php?post_status=trash&post_type=page'
				),
				'error'
			);

		} else {
			$privacy_policy_page_exists = true;
		}
	}
}

require_once( ABSPATH . 'wp-admin/admin-header.php' );

if ( $is_privacy_guide ) :
	include_once( ABSPATH . APP_VIEWS . '/backend/content/privacy-policy-guide.php' );
else :

/**
 * Set up the page tabs as an array for adding tabs
 * from a plugin.
 *
 * @since  1.0.0
 */
$page_tabs = [

    // Privacy Policy tab.
    sprintf(
        '<li><a class="nav-tab nav-tab-active"><span>%2s</span></a></li>',
        esc_html__( 'Privacy Policy' )
	),

	// Export Data tab.
    sprintf(
        '<li><a class="nav-tab" href="%1s"><span>%2s</span></a></li>',
        esc_url( add_query_arg( [ 'page' => 'export_personal_data' ], admin_url( 'users.php' ) ) ),
        esc_html__( 'Export Data' )
	),

	// Remove Data tab.
    sprintf(
        '<li><a class="nav-tab" href="%1s"><span>%2s</span></a></li>',
        esc_url( add_query_arg( [ 'page' => 'remove_personal_data' ], admin_url( 'users.php' ) ) ),
        esc_html__( 'Remove Data' )
	)

];

?>
<div class="wrap">

	<h1><?php echo $title; ?></h1>
	<p class="description"><?php _e( 'Manage privacy policies and user account data' ); ?></p>

	<hr class="wp-header-end" />

	<nav class="nav-tab-wrapper wp-clearfix">
		<ul>
			<?php echo implode( $page_tabs ); ?>
		</ul>
	</nav>
	<h2><?php _e( 'Privacy Policy Page' ); ?></h2>
	<p>
		<?php _e( 'As a website owner, you may opt to consent to national or international privacy laws. For example, you may want to create and display a privacy policy.' ); ?>
		<?php _e( 'If you already have a privacy policy page, please select it below. If not, please create one.' ); ?>
	</p>
	<p>
		<?php _e( 'The new page will include help and suggestions for a privacy policy.' ); ?>
		<?php _e( 'However, it is your responsibility to use those resources correctly, to provide the information that the privacy policy requires, and to keep that information current and accurate.' ); ?>
	</p>
	<p>
		<?php _e( 'After the privacy policy page is set, we suggest that you edit it.' ); ?>
		<?php _e( 'We would also suggest reviewing the privacy policy from time to time, especially after installing or updating any themes or plugins. There may be changes or new suggested information for you to consider adding to the policy.' ); ?>
	</p>
	<?php

	if ( $privacy_policy_page_exists ) {
		$edit_href = add_query_arg(
			[
				'post'   => $privacy_policy_page_id,
				'action' => 'edit',
			],
			admin_url( 'post.php' )
		);

		$view_href = get_permalink( $privacy_policy_page_id );

		?>
		<p class="tools-privacy-edit"><strong>
			<?php

			if ( 'publish' === get_post_status( $privacy_policy_page_id ) ) {
				/* translators: 1: URL to edit page, 2: URL to view page */
				printf( __( '<a href="%1$s">Edit</a> or <a href="%2$s">view</a> the privacy policy page content.' ), $edit_href, $view_href );
			} else {
				/* translators: 1: URL to edit page, 2: URL to preview page */
				printf( __( '<a href="%1$s">Edit</a> or <a href="%2$s">preview</a> the privacy policy page content.' ), $edit_href, $view_href );
			}

			?>
		</strong></p>
		<h3><?php _e( 'Sample Policy' ); ?></h3>
		<p>
			<?php

			/* translators: 1: Privacy Policy guide URL, 2: additional link attributes, 3: accessibility text */
			printf(
				__( 'For help putting together a privacy policy page <a href="%1$s" %2$s>view the sample%3$s</a> for demonstration content.' ),
				admin_url( 'privacy.php?privacy-policy-guide' ),
				'',
				''
			);

			?>
		</p>
		<?php
	}
	?>
	<hr>
	<table class="form-table tools-privacy-policy-page">
		<tr>
			<th scope="row">
				<?php
				if ( $privacy_policy_page_exists ) {
					_e( 'Change privacy policy page' );
				} else {
					_e( 'Select privacy policy page' );
				}
				?>
			</th>
			<td>
				<?php
				$has_pages = (bool) get_posts( [
					'post_type'      => 'page',
					'posts_per_page' => 1,
					'post_status'    => [
						'publish',
						'draft',
					],
				] );

				if ( $has_pages ) : ?>
					<form method="post" action="">
						<label for="page_for_privacy_policy">
							<?php _e( 'Select an existing page:' ); ?>
						</label>
						<input type="hidden" name="action" value="set-privacy-page" />
						<?php
						wp_dropdown_pages(
							[
								'name'              => 'page_for_privacy_policy',
								'show_option_none'  => __( '&mdash; Select &mdash;' ),
								'option_none_value' => '0',
								'selected'          => $privacy_policy_page_id,
								'post_status'       => [ 'draft', 'publish' ],
							]
						);

						wp_nonce_field( 'set-privacy-page' );

						submit_button( __( 'Use This Page' ), 'primary', 'submit', false, [ 'id' => 'set-page' ] );
						?>
					</form>
				<?php endif; ?>

				<form class="wp-create-privacy-page" method="post" action="">
					<input type="hidden" name="action" value="create-privacy-page" />
					<span>
						<?php
						if ( $has_pages ) {
							_e( 'Or:' );
						} else {
							_e( 'There are no pages.' );
						}
						?>
					</span>
					<?php
					wp_nonce_field( 'create-privacy-page' );

					submit_button( __( 'Create New Page' ), 'primary', 'submit', false, [ 'id' => 'create-page' ] );
					?>
				</form>
			</td>
		</tr>
	</table>
<?php
// End if policy guide.
endif; ?>

</div><!-- End .wrap -->
<?php include( ABSPATH . 'wp-admin/admin-footer.php' );