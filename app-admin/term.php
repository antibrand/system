<?php
/**
 * Edit Term Administration Screen.
 *
 * @package App_Package
 * @subpackage Administration
 * @since 4.5.0
 */

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

// Load the administration environment.
require_once( APP_INC_PATH . '/backend/app-admin.php' );

if ( empty( $_REQUEST['tag_ID'] ) ) {
	$sendback = admin_url( 'edit-tags.php' );
	if ( ! empty( $taxnow ) ) {
		$sendback = add_query_arg( array( 'taxonomy' => $taxnow ), $sendback );
	}
	wp_redirect( esc_url( $sendback ) );
	exit;
}

$tag_ID = absint( $_REQUEST['tag_ID'] );
$tag    = get_term( $tag_ID, $taxnow, OBJECT, 'edit' );

if ( ! $tag instanceof WP_Term ) {
	wp_die( __( 'You attempted to edit an item that doesn&#8217;t exist. Perhaps it was deleted?' ) );
}

$tax      = get_taxonomy( $tag->taxonomy );
$taxonomy = $tax->name;
$title    = $tax->labels->edit_item;

if ( ! in_array( $taxonomy, get_taxonomies( array( 'show_ui' => true ) ) ) ||
     ! current_user_can( 'edit_term', $tag->term_id )
) {
	wp_die(
		'<h1>' . __( 'You need a higher level of permission.' ) . '</h1>' .
		'<p>' . __( 'Sorry, you are not allowed to edit this item.' ) . '</p>',
		403
	);
}

$post_type = get_current_screen()->post_type;

// Default to the first object_type associated with the taxonomy if no post type was passed.
if ( empty( $post_type ) ) {
	$post_type = reset( $tax->object_type );
}

if ( 'post' != $post_type ) {
	$parent_file  = ( 'attachment' == $post_type ) ? 'upload.php' : "edit.php?post_type=$post_type";
	$submenu_file = "edit-tags.php?taxonomy=$taxonomy&amp;post_type=$post_type";
} else {
	$parent_file  = 'edit.php';
	$submenu_file = "edit-tags.php?taxonomy=$taxonomy";
}

get_current_screen()->set_screen_reader_content( array(
	'heading_pagination' => $tax->labels->items_list_navigation,
	'heading_list'       => $tax->labels->items_list,
) );
wp_enqueue_script( 'admin-tags' );

// Get the admin page header.
include( APP_VIEWS_PATH . '/backend/header/admin-header.php' );

include( APP_ADMIN_PATH . '/edit-tag-form.php' );

// Get the admin page footer.
include( APP_VIEWS_PATH . '/backend/footer/admin-footer.php' );
