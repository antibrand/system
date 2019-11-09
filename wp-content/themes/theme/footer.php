<?php
/**
 * The template for displaying the footer
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 */

// Get the site name.
$site_name = esc_attr( get_bloginfo( 'name' ) );

// Copyright HTML.
$copyright = sprintf(
	'<p class="copyright-text" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">&copy; <span class="screen-reader-text">%1s</span><span itemprop="copyrightYear">%2s</span> <span itemprop="copyrightHolder">%3s.</span> %4s.</p>',
	esc_html__( 'Copyright ', 'unbranded' ),
	get_the_time( 'Y' ),
	$site_name,
	esc_html__( 'All rights reserved', 'unbranded' )
); ?>

	</div>

	<footer id="colophon" class="site-footer">
		<div class="footer-content global-wrapper footer-wrapper">
				<?php echo $copyright; ?>
		</div>
	</footer>
</div>

<?php AB_Theme\Tags\after_page(); ?>
<?php wp_footer(); ?>

</body>
</html>