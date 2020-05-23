<?php
/**
 * Template part for displaying page content in page.php
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
	<header class="entry-header">
		<?php
		if ( is_front_page() ) {

			// Front page uses h1 in the page header so h2 here.
			the_title( '<h2 class="entry-title">', '</h2>' );
		} else {
			the_title( '<h1 class="entry-title">', '</h1>' );
		}

		the_subtitle( '<p class="entry-subtitle">', '</p>' );
		?>
	</header>

	<?php AB_Theme\Tags\post_thumbnail(); ?>

	<div class="entry-content" itemprop="articleBody">
		<?php
		the_content();

		wp_link_pages( [
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'antibrand' ),
			'after'  => '</div>',
		] );
		?>
	</div>

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						__( 'Edit <span class="screen-reader-text">%s</span>', 'antibrand' ),
						[
							'span' => [
								'class' => [],
							],
						]
					),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer>
	<?php endif; ?>
</article>
