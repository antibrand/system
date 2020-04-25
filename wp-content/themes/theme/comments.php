<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
} ?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$ab_theme_comment_count = get_comments_number();
			if ( '1' === $ab_theme_comment_count ) {
				printf(
					esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'antibrand' ),
					'<span>' . get_the_title() . '</span>'
				);
			} else {
				printf(
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $ab_theme_comment_count, 'comments title', 'antibrand' ) ),
					number_format_i18n( $ab_theme_comment_count ),
					'<span>' . get_the_title() . '</span>'
				);
			}
			?>
		</h2>

		<?php the_comments_navigation(); ?>

		<ol class="comment-list">
			<?php
			wp_list_comments( [
				'style'      => 'ol',
				'short_ping' => true,
			] );
			?>
		</ol>

		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'antibrand' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().

	comment_form();
	?>

</div>