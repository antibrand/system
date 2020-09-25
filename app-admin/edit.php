<?php
/**
 * Edit post types pages
 *
 * @package App_Package
 * @subpackage Administration
 */

// Alias namespaces.
use \AppNamespace\Backend as Backend;

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

// Load the administration environment.
require_once( APP_INC_PATH . '/backend/app-admin.php' );

// Instance of the list class.
$list_manage = new Backend\Posts_List_Table;

// Instance of the page class.
$page = Backend\List_Manage_Edit :: instance();

// Page identification.
if ( 'post' != $post_type ) {
	$parent_file   = "edit.php?post_type=$post_type";
	$submenu_file  = "edit.php?post_type=$post_type";
	$post_new_file = "post-new.php?post_type=$post_type";

} else {
	$parent_file   = 'edit.php';
	$submenu_file  = 'edit.php';
	$post_new_file = 'post-new.php';
}

$screen      = $page->screen();
$title       = $page->title();
$description = $page->description();

// Stop if not allowed.
$page->die();

// Get the current page number.
$pagenum = $list_manage->get_pagenum();

// Current action from the bulk actions dropdown.
$action = $list_manage->current_action();

if ( $action ) {

	check_admin_referer( 'bulk-posts' );

	$sendback = remove_query_arg( [ 'trashed', 'untrashed', 'deleted', 'locked', 'ids' ], wp_get_referer() );

	if ( ! $sendback ) {
		$sendback = admin_url( $parent_file );
	}

	$sendback = add_query_arg( 'paged', $pagenum, $sendback );

	if ( strpos( $sendback, 'post.php' ) !== false ) {
		$sendback = admin_url( $post_new_file );
	}

	if ( 'delete_all' == $action ) {

		// Prepare for deletion of all posts with a specified post status (i.e. Empty trash).
		$post_status = preg_replace( '/[^a-z0-9_-]+/i', '', $_REQUEST['post_status'] );

		// Validate the post status exists.
		if ( get_post_status_object( $post_status ) ) {
			$post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type=%s AND post_status = %s", $post_type, $post_status ) );
		}

		$action = 'delete';

	} elseif ( isset( $_REQUEST['media'] ) ) {
		$post_ids = $_REQUEST['media'];
	} elseif ( isset( $_REQUEST['ids'] ) ) {
		$post_ids = explode( ',', $_REQUEST['ids'] );
	} elseif ( ! empty( $_REQUEST['post'] ) ) {
		$post_ids = array_map( 'intval', $_REQUEST['post'] );
	}

	if ( ! isset( $post_ids ) ) {
		wp_redirect( $sendback );

		exit;
	}

	switch ( $action ) {

		case 'trash' :
			$trashed = $locked = 0;

			foreach ( (array) $post_ids as $post_id ) {

				if ( ! current_user_can( 'delete_post', $post_id ) )
					wp_die( __( 'Sorry, you are not allowed to move this item to the Trash.' ) );

				if ( wp_check_post_lock( $post_id ) ) {
					$locked++;
					continue;
				}

				if ( ! wp_trash_post( $post_id ) ) {
					wp_die( __( 'Error in moving to Trash.' ) );
				}

				$trashed++;
			}

			$sendback = add_query_arg( [ 'trashed' => $trashed, 'ids' => join( ',', $post_ids ), 'locked' => $locked ], $sendback );

			break;

		case 'untrash' :

			$untrashed = 0;

			foreach ( (array) $post_ids as $post_id ) {

				if ( ! current_user_can( 'delete_post', $post_id ) ) {
					wp_die( __( 'Sorry, you are not allowed to restore this item from the Trash.' ) );
				}

				if ( ! wp_untrash_post( $post_id ) ) {
					wp_die( __( 'Error in restoring from Trash.' ) );
				}

				$untrashed++;
			}

			$sendback = add_query_arg( 'untrashed', $untrashed, $sendback );

			break;

		case 'delete' :

			$deleted = 0;

			foreach ( (array) $post_ids as $post_id ) {

				$post_del = get_post( $post_id );

				if ( ! current_user_can( 'delete_post', $post_id ) ) {
					wp_die( __( 'Sorry, you are not allowed to delete this item.' ) );
				}

				if ( $post_del->post_type == 'attachment' ) {

					if ( ! wp_delete_attachment( $post_id ) ) {
						wp_die( __( 'Error in deleting.' ) );
					}

				} else {

					if ( ! wp_delete_post( $post_id ) ) {
						wp_die( __( 'Error in deleting.' ) );
					}
				}

				$deleted++;
			}

			$sendback = add_query_arg( 'deleted', $deleted, $sendback );

			break;

		case 'edit' :

			if ( isset( $_REQUEST['bulk_edit'] ) ) {

				$done = bulk_edit_posts( $_REQUEST );

				if ( is_array( $done ) ) {

					$done['updated'] = count( $done['updated'] );
					$done['skipped'] = count( $done['skipped'] );
					$done['locked']  = count( $done['locked'] );
					$sendback = add_query_arg( $done, $sendback );
				}
			}

			break;

		default :

			// This action is documented in APP_ADMIN_DIRR/edit-comments.php.
			$sendback = apply_filters( 'handle_bulk_actions-' . get_current_screen()->id, $sendback, $action, $post_ids );
			break;
	}

	$sendback = remove_query_arg( [ 'action', 'action2', 'tags_input', 'post_author', 'comment_status', '_status', 'post', 'bulk_edit', 'post_view' ], $sendback );

	wp_redirect( $sendback );

	exit();

} elseif ( ! empty( $_REQUEST['_wp_http_referer'] ) ) {

	 wp_redirect( remove_query_arg( [ '_wp_http_referer', '_wpnonce' ], wp_unslash( $_SERVER['REQUEST_URI'] ) ) );

	 exit;
}

$list_manage->prepare_items();

// Page title.
$title = sprintf(
	'%1s %2s',
	__( 'Manage' ),
	$post_type_object->labels->name
);

get_current_screen()->set_screen_reader_content( [
	'heading_views'      => $post_type_object->labels->filter_items_list,
	'heading_pagination' => $post_type_object->labels->items_list_navigation,
	'heading_list'       => $post_type_object->labels->items_list,
] );

add_screen_option( 'per_page', [ 'default' => 20, 'option' => 'edit_' . $post_type . '_per_page' ] );

// Get the admin page header.
include( APP_VIEWS_PATH . '/backend/header/admin-header.php' );

?>
	<div class="wrap">

		<h1><?php echo $title; ?></h1>

		<?php
		if ( isset( $_REQUEST['s'] ) && strlen( $_REQUEST['s'] ) ) {
			// Translators: %s: search keywords.
			printf( ' <p class="subtitle">' . __( 'Search results for &#8220;%s&#8221;' ) . '</p>', get_search_query() );
		}

		// Action messages
		$page->messages();

		$_SERVER['REQUEST_URI'] = remove_query_arg( [ 'locked', 'skipped', 'updated', 'deleted', 'trashed', 'untrashed' ], $_SERVER['REQUEST_URI'] );
		?>

		<div class="list-table-top">

			<?php $list_manage->views(); ?>

			<form method="get" id="search-<?php echo $post_type; ?>-list" class="search-form">
				<?php $list_manage->search_box( $post_type_object->labels->search_items, 'post' ); ?>
			</form>

		</div>

		<form id="posts-filter" class="list-table-form" method="get">

			<input type="hidden" name="post_status" class="post_status_page" value="<?php echo ! empty( $_REQUEST['post_status'] ) ? esc_attr( $_REQUEST['post_status'] ) : 'all'; ?>" />
			<input type="hidden" name="post_type" class="post_type_page" value="<?php echo $post_type; ?>" />

			<?php if ( ! empty( $_REQUEST['author'] ) ) { ?>
			<input type="hidden" name="author" value="<?php echo esc_attr( $_REQUEST['author'] ); ?>" />
			<?php } ?>

			<?php if ( ! empty( $_REQUEST['show_sticky'] ) ) { ?>
			<input type="hidden" name="show_sticky" value="1" />
			<?php } ?>

			<?php $list_manage->display(); ?>

		</form>

		<?php
		if ( $list_manage->has_items() ) {
			$list_manage->inline_edit();
		}
		?>

		<div id="ajax-response"></div>
	</div><!-- .wrap -->
<?php

// Get the admin page footer.
include( APP_VIEWS_PATH . '/backend/footer/admin-footer.php' );
