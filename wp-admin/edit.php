<?php
/**
 * Edit Posts Administration Screen.
 *
 * @package App_Package
 * @subpackage Administration
 */

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! $typenow ) {
	wp_die( __( 'Invalid post type.' ) );
}

if ( ! in_array( $typenow, get_post_types( array( 'show_ui' => true ) ) ) ) {
	wp_die( __( 'Sorry, you are not allowed to edit posts in this post type.' ) );
}

if ( 'attachment' === $typenow ) {
	if ( wp_redirect( admin_url( 'upload.php' ) ) ) {
		exit;
	}
}

/**
 * @global string       $post_type
 * @global WP_Post_Type $post_type_object
 */
global $post_type, $post_type_object;

$post_type = $typenow;
$post_type_object = get_post_type_object( $post_type );

if ( ! $post_type_object ) {
	wp_die( __( 'Invalid post type.' ) );
}

if ( ! current_user_can( $post_type_object->cap->edit_posts ) ) {
	wp_die(
		'<h1>' . __( 'You need a higher level of permission.' ) . '</h1>' .
		'<p>' . __( 'Sorry, you are not allowed to edit posts in this post type.' ) . '</p>',
		403
	);
}

$wp_list_table = _get_list_table( 'AppNamespace\Backend\Posts_List_Table' );
$pagenum       = $wp_list_table->get_pagenum();

// Back-compat for viewing comments of an entry
foreach ( array( 'p', 'attachment_id', 'page_id' ) as $_redirect ) {

	if ( ! empty( $_REQUEST[ $_redirect ] ) ) {
		wp_redirect( admin_url( 'edit-comments.php?p=' . absint( $_REQUEST[ $_redirect ] ) ) );

		exit;
	}
}
unset( $_redirect );

if ( 'post' != $post_type ) {

	$parent_file   = "edit.php?post_type=$post_type";
	$submenu_file  = "edit.php?post_type=$post_type";
	$post_new_file = "post-new.php?post_type=$post_type";

} else {
	$parent_file   = 'edit.php';
	$submenu_file  = 'edit.php';
	$post_new_file = 'post-new.php';
}

$doaction = $wp_list_table->current_action();

if ( $doaction ) {

	check_admin_referer('bulk-posts');

	$sendback = remove_query_arg( [ 'trashed', 'untrashed', 'deleted', 'locked', 'ids' ], wp_get_referer() );

	if ( ! $sendback ) {
		$sendback = admin_url( $parent_file );
	}

	$sendback = add_query_arg( 'paged', $pagenum, $sendback );

	if ( strpos( $sendback, 'post.php' ) !== false ) {
		$sendback = admin_url( $post_new_file );
	}

	if ( 'delete_all' == $doaction ) {

		// Prepare for deletion of all posts with a specified post status (i.e. Empty trash).
		$post_status = preg_replace( '/[^a-z0-9_-]+/i', '', $_REQUEST['post_status'] );

		// Validate the post status exists.
		if ( get_post_status_object( $post_status ) ) {
			$post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type=%s AND post_status = %s", $post_type, $post_status ) );
		}

		$doaction = 'delete';

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

	switch ( $doaction ) {

		case 'trash':
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

		case 'untrash':

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

		case 'delete':

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

		case 'edit':

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

		default:

			// This action is documented in wp-admin/edit-comments.php.
			$sendback = apply_filters( 'handle_bulk_actions-' . get_current_screen()->id, $sendback, $doaction, $post_ids );
			break;
	}

	$sendback = remove_query_arg( [ 'action', 'action2', 'tags_input', 'post_author', 'comment_status', '_status', 'post', 'bulk_edit', 'post_view' ], $sendback );

	wp_redirect( $sendback );

	exit();

} elseif ( ! empty( $_REQUEST['_wp_http_referer'] ) ) {

	 wp_redirect( remove_query_arg( [ '_wp_http_referer', '_wpnonce' ], wp_unslash( $_SERVER['REQUEST_URI'] ) ) );

	 exit;
}

$wp_list_table->prepare_items();

wp_enqueue_script( 'inline-edit-post' );
wp_enqueue_script( 'heartbeat' );

$title = $post_type_object->labels->name;

if ( 'post' == $post_type ) {

	$help_overview = sprintf(
		'<h3>%1s</h3>',
		__( 'Overview' )
	);

	$help_overview .= sprintf(
		'<p>%1s</p>',
		__( 'This screen provides access to all of your posts. You can customize the display of this screen to suit your workflow.' )
	);

	$help_overview = apply_filters( 'help_overview_posts', $help_overview );

	get_current_screen()->add_help_tab( [
	'id'      => 'overview',
	'title'	  => __( 'Overview' ),
	'content' => $help_overview
	] );

	$help_screen_content = sprintf(
		'<h3>%1s</h3>',
		__( 'Screen Content' )
	);

	$help_screen_content .= sprintf(
		'<p>%1s</p>',
		__( 'You can customize the display of this screen&#8217;s contents in a number of ways:' )
	);

	$help_screen_content .= '<ul>';

	$help_screen_content .= sprintf(
		'<li>%1s</li>',
		__( 'You can hide/display columns based on your needs and decide how many posts to list per screen using the Screen Options tab.' )
	);

	$help_screen_content .= sprintf(
		'<li>%1s</li>',
		__( 'You can filter the list of posts by post status using the text links above the posts list to only show posts with that status. The default view is to show all posts.' )
	);

	$help_screen_content .= sprintf(
		'<li>%1s</li>',
		__( 'You can view posts in a simple title list or with an excerpt using the Screen Options tab.' )
	);

	$help_screen_content .= sprintf(
		'<li>%1s</li>',
		__( 'You can refine the list to show only posts in a specific category or from a specific month by using the dropdown menus above the posts list. Click the Filter button after making your selection. You also can refine the list by clicking on the post author, category or tag in the posts list.' )
	);

	$help_screen_content .= '</ul>';

	$help_screen_content = apply_filters( 'help_screen_content_posts', $help_screen_content );

	get_current_screen()->add_help_tab( [
	'id'      => 'screen-content',
	'title'   => __( 'Screen Content' ),
	'content' => $help_screen_content
	] );

	$help_action_links = sprintf(
		'<h3>%1s</h3>',
		__( 'Available Actions' )
	);

	$help_action_links .= sprintf(
		'<p>%1s</p>',
		__( 'Hovering over a row in the posts list will display action links that allow you to manage your post. You can perform the following actions:' )
	);

	$help_action_links .= '<ul>';

	$help_action_links .= sprintf(
		'<li>%1s</li>',
		__( '<strong>Edit</strong> takes you to the editing screen for that post. You can also reach that screen by clicking on the post title.' )
	);

	$help_action_links .= sprintf(
		'<li>%1s</li>',
		__( '<strong>Quick Edit</strong> provides inline access to the metadata of your post, allowing you to update post details without leaving this screen.' )
	);

	$help_action_links .= sprintf(
		'<li>%1s</li>',
		__( '<strong>Trash</strong> removes your post from this list and places it in the trash, from which you can permanently delete it.' )
	);

	$help_action_links .= sprintf(
		'<li>%1s</li>',
		__( '<strong>Preview</strong> will show you what your draft post will look like if you publish it. View will take you to your live site to view the post. Which link is available depends on your post&#8217;s status.' )
	);

	$help_action_links .= '</ul>';

	$help_action_links = apply_filters( 'help_action_links_posts', $help_action_links );

	get_current_screen()->add_help_tab( [
	'id'      => 'action-links',
	'title'   => __( 'Available Actions' ),
	'content' => $help_action_links
	] );

	$help_bulk_actions = sprintf(
		'<h3>%1s</h3>',
		__( 'Bulk Actions' )
	);

	$help_bulk_actions .= sprintf(
		'<p>%1s</p>',
		__( 'You can also edit or move multiple posts to the trash at once. Select the posts you want to act on using the checkboxes, then select the action you want to take from the Bulk Actions menu and click Apply.' )
	);

	$help_bulk_actions .= sprintf(
		'<p>%1s</p>',
		__( 'When using Bulk Edit, you can change the metadata (categories, author, etc.) for all selected posts at once. To remove a post from the grouping, just click the x next to its name in the Bulk Edit area that appears.' )
	);

	$help_bulk_actions = apply_filters( 'help_bulk_actions_posts', $help_bulk_actions );

	get_current_screen()->add_help_tab( [
	'id'      => 'bulk-actions',
	'title'   => __( 'Bulk Actions' ),
	'content' => $help_bulk_actions
	] );

	/**
	 * Help sidebar content
	 *
	 * This system adds no content to the help sidebar
	 * but there is a filter applied for adding content.
	 *
	 * @since 1.0.0
	 */
	$set_help_sidebar = apply_filters( 'set_help_sidebar_posts', '' );
	get_current_screen()->set_help_sidebar( $set_help_sidebar );

} elseif ( 'page' == $post_type ) {

	$help_overview = sprintf(
		'<h3>%1s</h3>',
		__( 'Overview' )
	);

	$help_overview .= sprintf(
		'<p>%1s</p>',
		__( 'Pages are similar to posts in that they have a title, body text, and associated metadata, but they are different in that they are not part of the chronological blog stream, kind of like permanent posts. Pages are not categorized or tagged, but can have a hierarchy. You can nest pages under other pages by making one the &#8220;Parent&#8221; of the other, creating a group of pages.' )
	);

	$help_overview = apply_filters( 'help_overview_pages', $help_overview );

	get_current_screen()->add_help_tab( [
	'id'      => 'overview',
	'title'   => __( 'Overview' ),
	'content' => $help_overview
	] );

	$help_managing_pages = sprintf(
		'<h3>%1s</h3>',
		__( 'Managing Pages' )
	);

	$help_managing_pages .= sprintf(
		'<p>%1s</p>',
		__( 'Managing pages is very similar to managing posts, and the screens can be customized in the same way.' )
	);

	$help_managing_pages .= sprintf(
		'<p>%1s</p>',
		__( 'You can also perform the same types of actions, including narrowing the list by using the filters, acting on a page using the action links that appear when you hover over a row, or using the Bulk Actions menu to edit the metadata for multiple pages at once.' )
	);

	$help_managing_pages = apply_filters( 'help_managing_pages', $help_managing_pages );

	get_current_screen()->add_help_tab( [
	'id'      => 'managing-pages',
	'title'   => __( 'Managing Pages' ),
	'content' => $help_managing_pages
	] );

	/**
	 * Help sidebar content
	 *
	 * This system adds no content to the help sidebar
	 * but there is a filter applied for adding content.
	 *
	 * @since 1.0.0
	 */
	$set_help_sidebar = apply_filters( 'set_help_sidebar_pages', '' );
	get_current_screen()->set_help_sidebar( $set_help_sidebar );

}

get_current_screen()->set_screen_reader_content( [
	'heading_views'      => $post_type_object->labels->filter_items_list,
	'heading_pagination' => $post_type_object->labels->items_list_navigation,
	'heading_list'       => $post_type_object->labels->items_list,
] );

add_screen_option( 'per_page', [ 'default' => 20, 'option' => 'edit_' . $post_type . '_per_page' ] );

$bulk_counts = [
	'updated'   => isset( $_REQUEST['updated'] )   ? absint( $_REQUEST['updated'] )   : 0,
	'locked'    => isset( $_REQUEST['locked'] )    ? absint( $_REQUEST['locked'] )    : 0,
	'deleted'   => isset( $_REQUEST['deleted'] )   ? absint( $_REQUEST['deleted'] )   : 0,
	'trashed'   => isset( $_REQUEST['trashed'] )   ? absint( $_REQUEST['trashed'] )   : 0,
	'untrashed' => isset( $_REQUEST['untrashed'] ) ? absint( $_REQUEST['untrashed'] ) : 0,
];

$bulk_messages = [];
$bulk_messages['post'] = [
	'updated'   => _n( '%s post updated.', '%s posts updated.', $bulk_counts['updated'] ),
	'locked'    => ( 1 == $bulk_counts['locked'] ) ? __( '1 post not updated, somebody is editing it.' ) :
	                   _n( '%s post not updated, somebody is editing it.', '%s posts not updated, somebody is editing them.', $bulk_counts['locked'] ),
	'deleted'   => _n( '%s post permanently deleted.', '%s posts permanently deleted.', $bulk_counts['deleted'] ),
	'trashed'   => _n( '%s post moved to the Trash.', '%s posts moved to the Trash.', $bulk_counts['trashed'] ),
	'untrashed' => _n( '%s post restored from the Trash.', '%s posts restored from the Trash.', $bulk_counts['untrashed'] ),
];
$bulk_messages['page'] = [
	'updated'   => _n( '%s page updated.', '%s pages updated.', $bulk_counts['updated'] ),
	'locked'    => ( 1 == $bulk_counts['locked'] ) ? __( '1 page not updated, somebody is editing it.' ) :
	                   _n( '%s page not updated, somebody is editing it.', '%s pages not updated, somebody is editing them.', $bulk_counts['locked'] ),
	'deleted'   => _n( '%s page permanently deleted.', '%s pages permanently deleted.', $bulk_counts['deleted'] ),
	'trashed'   => _n( '%s page moved to the Trash.', '%s pages moved to the Trash.', $bulk_counts['trashed'] ),
	'untrashed' => _n( '%s page restored from the Trash.', '%s pages restored from the Trash.', $bulk_counts['untrashed'] ),
];

/**
 * Filters the bulk action updated messages.
 *
 * By default, custom post types use the messages for the 'post' post type.
 *
 * @since Previous 3.7.0
 * @param array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
 *                             keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
 * @param array $bulk_counts   Array of item counts for each message, used to build internationalized strings.
 */
$bulk_messages = apply_filters( 'bulk_post_updated_messages', $bulk_messages, $bulk_counts );
$bulk_counts   = array_filter( $bulk_counts );

require_once( ABSPATH . 'wp-admin/admin-header.php' );

?>
	<div class="wrap">
		<h1><?php echo esc_html( $post_type_object->labels->name ); ?></h1>

		<?php
		if ( isset( $_REQUEST['s'] ) && strlen( $_REQUEST['s'] ) ) {
			// Translators: %s: search keywords.
			printf( ' <p class="subtitle">' . __( 'Search results for &#8220;%s&#8221;' ) . '</p>', get_search_query() );
		}

		// If we have a bulk message to issue:
		$messages = [];

		foreach ( $bulk_counts as $message => $count ) {

			if ( isset( $bulk_messages[ $post_type ][ $message ] ) ) {
				$messages[] = sprintf( $bulk_messages[ $post_type ][ $message ], number_format_i18n( $count ) );
			} elseif ( isset( $bulk_messages['post'][ $message ] ) ) {
				$messages[] = sprintf( $bulk_messages['post'][ $message ], number_format_i18n( $count ) );
			}

			if ( $message == 'trashed' && isset( $_REQUEST['ids'] ) ) {

				$ids = preg_replace( '/[^0-9,]/', '', $_REQUEST['ids'] );
				$messages[] = '<a href="' . esc_url( wp_nonce_url( "edit.php?post_type=$post_type&doaction=undo&action=untrash&ids=$ids", "bulk-posts" ) ) . '">' . __( 'Undo' ) . '</a>';
			}
		}

		if ( $messages ) {
			echo '<div id="message" class="updated notice is-dismissible"><p>' . join( ' ', $messages ) . '</p></div>';
		}

		unset( $messages );

		$_SERVER['REQUEST_URI'] = remove_query_arg( [ 'locked', 'skipped', 'updated', 'deleted', 'trashed', 'untrashed' ], $_SERVER['REQUEST_URI'] );
		?>

		<div class="list-table-top">

			<?php $wp_list_table->views(); ?>

			<form method="get" id="search-<?php echo $post_type; ?>-list" class="search-form">
				<?php $wp_list_table->search_box( $post_type_object->labels->search_items, 'post' ); ?>
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

			<?php $wp_list_table->display(); ?>

		</form>

		<?php
		if ( $wp_list_table->has_items() ) {
			$wp_list_table->inline_edit();
		}
		?>

		<div id="ajax-response"></div>

	</div><!-- .wrap -->

<?php
include( ABSPATH . 'wp-admin/admin-footer.php' );