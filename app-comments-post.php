<?php
/**
 * Handles Comment Post and prevents duplicate comment posting.
 *
 * @package App_Package
 */

if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {

	$protocol = $_SERVER['SERVER_PROTOCOL'];

	if ( ! in_array( $protocol, [ 'HTTP/1.1', 'HTTP/2', 'HTTP/2.0' ] ) ) {
		$protocol = 'HTTP/1.0';
	}

	header( 'Allow: POST' );
	header( "$protocol 405 Method Not Allowed" );
	header( 'Content-Type: text/plain' );

	exit;
}

// Set up the application environment.
require( dirname(__FILE__) . '/app-load.php' );

nocache_headers();

$comment = wp_handle_comment_submission( wp_unslash( $_POST ) );

if ( is_wp_error( $comment ) ) {

	$data = intval( $comment->get_error_data() );

	if ( ! empty( $data ) ) {
		wp_die( '<p>' . $comment->get_error_message() . '</p>', __( 'Comment Submission Failure' ), [ 'response' => $data, 'back_link' => true ] );
	} else {
		exit;
	}
}

$user = wp_get_current_user();
$cookies_consent = ( isset( $_POST['wp-comment-cookies-consent'] ) );

/**
 * Perform other actions when comment cookies are set.
 *
 * @since Previous 3.4.0
 * @since Previous 4.9.6 The `$cookies_consent` parameter was added.
 *
 * @param WP_Comment $comment Comment object.
 * @param WP_User $user Comment author's user object. The user may not exist.
 * @param boolean $cookies_consent Comment author's consent to store cookies.
 */
do_action( 'set_comment_cookies', $comment, $user, $cookies_consent );

if ( empty( $_POST['redirect_to'] ) ) {
	$location = get_comment_link( $comment );
} else {
	$location = $_POST['redirect_to'] . '#comment-' . $comment->comment_ID;
}

/**
 * Filters the location URI to send the commenter after posting.
 *
 * @since Previous 2.0.5
 *
 * @param string $location The 'redirect_to' URI sent via $_POST.
 * @param WP_Comment $comment Comment object.
 */
$location = apply_filters( 'comment_post_redirect', $location, $comment );

wp_safe_redirect( $location );

exit;