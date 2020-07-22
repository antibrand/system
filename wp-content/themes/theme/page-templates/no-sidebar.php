<?php
/**
 * No Sidebar template.
 *
 * Template Name: No Sidebar
 * Template Post Type: post, page
 * Description: Does not load the primary sidebar.
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" itemscope itemprop="mainContentOfPage">

		<?php while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

		endwhile; // End of the loop.
		?>

		</main>
	</div>

<?php
get_footer();
