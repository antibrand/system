<?php
/**
 * The template for displaying search results pages
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" itemscope itemprop="mainContentOfPage">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php printf( esc_html__( 'Search Results for: %s', 'antibrand' ), '<span>' . get_search_query() . '</span>' );
					?>
				</h1>
			</header>

			<?php while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'search' );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main>
	</section>

<?php
get_sidebar();
get_footer();
