<?php
/**
 * Import Administration Screen
 *
 * @package App_Package
 * @subpackage Administration
 */

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

// Load the administration environment.
require_once( APP_INC_PATH . '/backend/app-admin.php' );

// Instance of the dashboard class.
\AppNamespace\Backend\Data_Page :: instance();

require_once( APP_INC_PATH . '/backend/export.php' );

$parent_file = 'options-general.php';

$title = apply_filters(
	'manage_data_page_title',
	__( 'Database Tools' )
);

$description = apply_filters(
	'manage_data_page_description',
	__( 'Manage the database, import and export content.' )
);

// Get the admin page header.
include( APP_VIEWS_PATH . '/backend/header/admin-header.php' );

/**
 * Set up export
 *
 * Originally from export.php
 */

// If the 'download' URL parameter is set, an export file is baked and returned.
if ( isset( $_GET['download'] ) ) {

	$args = [];

	if ( ! isset( $_GET['content'] ) || 'all' == $_GET['content'] ) {
		$args['content'] = 'all';

	} elseif ( 'posts' == $_GET['content'] ) {

		$args['content'] = 'post';

		if ( $_GET['cat'] ) {
			$args['category'] = (int) $_GET['cat'];
		}

		if ( $_GET['post_author'] ) {
			$args['author'] = (int) $_GET['post_author'];
		}

		if ( $_GET['post_start_date'] || $_GET['post_end_date'] ) {
			$args['start_date'] = $_GET['post_start_date'];
			$args['end_date']   = $_GET['post_end_date'];
		}

		if ( $_GET['post_status'] ) {
			$args['status'] = $_GET['post_status'];
		}

	} elseif ( 'pages' == $_GET['content'] ) {

		$args['content'] = 'page';

		if ( $_GET['page_author'] ) {
			$args['author'] = (int) $_GET['page_author'];
		}

		if ( $_GET['page_start_date'] || $_GET['page_end_date'] ) {
			$args['start_date'] = $_GET['page_start_date'];
			$args['end_date']   = $_GET['page_end_date'];
		}

		if ( $_GET['page_status'] ) {
			$args['status'] = $_GET['page_status'];
		}

	} elseif ( 'attachment' == $_GET['content'] ) {

		$args['content'] = 'attachment';

		if ( $_GET['attachment_start_date'] || $_GET['attachment_end_date'] ) {
			$args['start_date'] = $_GET['attachment_start_date'];
			$args['end_date'] = $_GET['attachment_end_date'];
		}

	} else {
		$args['content'] = $_GET['content'];
	}

	/**
	 * Filters the export args.
	 *
	 * @since Previous 3.5.0
	 * @param array $args The arguments to send to the exporter.
	 */
	$args = apply_filters( 'export_args', $args );

	export_wp( $args );

	die();
}

?>
<script type="text/javascript">
	jQuery(document).ready( function($) {

		var form    = $( '#export-filters' ),
			filters = form.find( '.export-filters' );
		filters.hide();
		form.find( 'input:radio' ).change( function() {

			filters.slideUp( 250 );
			switch ( $(this).val() ) {
				case 'attachment':
					$( '#attachment-filters' ).slideDown( 250 );
					break;
				case 'posts':
					$( '#post-filters' ).slideDown( 250 );
					break;
				case 'pages':
					$( '#page-filters' ).slideDown( 250 );
					break;
			}
		});
	});
</script>

<div class="wrap">

	<h1><?php echo esc_html( $title ); ?></h1>
	<p class="description"><?php echo $description; ?></p>

	<div id="data-page-tabs">
		<?php

		// Tabbed content.
		echo get_current_screen()->render_content_tabs();

		?>
	</div>
</div>

<?php
wp_print_request_filesystem_credentials_modal();
wp_print_admin_notice_templates();

// Get the admin page footer.
include( APP_VIEWS_PATH . '/backend/footer/admin-footer.php' );
