<?php
/**
 * Erase personal data page
 *
 * @package App_Package
 * @subpackage Administration
 */

// Stop if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Not allowed.' );
}

/**
 * Require both caps in order to make it explicitly clear that delegating
 * erasure from network admins to single-site admins will give them the
 * ability to affect global users, rather than being limited to the site
 * that they administer.
 */
if ( ! current_user_can( 'erase_others_personal_data' ) || ! current_user_can( 'delete_users' ) ) {
	wp_die( __( 'You are not allowed to erase data on this site.' ) );
}

app_personal_data_handle_actions();
app_personal_data_cleanup_requests();

// "Borrow" xfn.js for now so we don't have to create new files.
wp_enqueue_script( 'xfn' );

$requests_table = new WP_Privacy_Data_Removal_Requests_Table( [
	'plural'   => 'privacy_requests',
	'singular' => 'privacy_request',
] );

$requests_table->process_bulk_action();
$requests_table->prepare_items();

// Page title.
$title = __( 'Remove Personal Data' );

// Parent file for menu highlighting.
$parent_file = 'users.php';

/**
 * Set up the page tabs as an array for adding tabs
 * from a plugin.
 *
 * @since  1.0.0
 */
$page_tabs = [

    // Privacy Policy tab.
    sprintf(
        '<li><a class="nav-tab" href="%1s"><span>%2s</span></a></li>',
        esc_url( admin_url( 'privacy.php' ) ),
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
		'<li><a class="nav-tab nav-tab-active"><span>%1s</span></a></li>',
        esc_html__( 'Remove Data' )
	)
];

?>
<div class="wrap">

	<header>
		<h1><?php echo $title; ?></h1>
		<p class="description"><?php _e( '' ); ?></p>
	</header>

	<nav class="nav-tab-wrapper app-clearfix">
		<ul>
			<?php echo implode( $page_tabs ); ?>
		</ul>
	</nav>

	<h2><?php esc_html_e( 'Data Removal Form' ); ?></h2>

	<p><?php esc_html_e( 'Use the following form to add a data erasure request. An email will be sent to the user at this email address asking them to verify the request.' ); ?></p>

	<?php settings_errors(); ?>

	<form method="post" class="wp-privacy-request-form">
		<div class="wp-privacy-request-form-field">
			<label for="username_or_email_for_privacy_request"><?php esc_html_e( 'Username or email address' ); ?></label>
			<input type="text" required class="regular-text" id="username_or_email_for_privacy_request" name="username_or_email_for_privacy_request" />
			<?php submit_button( __( 'Send Request' ), 'secondary', 'submit', false ); ?>
		</div>
		<?php wp_nonce_field( 'personal-data-request' ); ?>
		<input type="hidden" name="action" value="add_remove_personal_data_request" />
		<input type="hidden" name="type_of_action" value="remove_personal_data" />
	</form>

	<h2><?php esc_html_e( 'Requests for Data Removal' ); ?></h2>

	<div class="list-table-top">
		<?php $requests_table->views(); ?>
	</div>

	<form class="search-form app-clearfix">
		<?php $requests_table->search_box( __( 'Search Requests' ), 'requests' ); ?>
		<input type="hidden" name="page" value="remove_personal_data" />
		<input type="hidden" name="filter-status" value="<?php echo isset( $_REQUEST['filter-status'] ) ? esc_attr( sanitize_text_field( $_REQUEST['filter-status'] ) ) : ''; ?>" />
		<input type="hidden" name="orderby" value="<?php echo isset( $_REQUEST['orderby'] ) ? esc_attr( sanitize_text_field( $_REQUEST['orderby'] ) ) : ''; ?>" />
		<input type="hidden" name="order" value="<?php echo isset( $_REQUEST['order'] ) ? esc_attr( sanitize_text_field( $_REQUEST['order'] ) ) : ''; ?>" />
	</form>

	<form method="post">
		<?php
		$requests_table->display();
		$requests_table->embed_scripts();
		?>
	</form>
</div><!-- End .wrap -->