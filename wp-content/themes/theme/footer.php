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

// Footer message.
$message = sprintf(
	'<p class="footer-message" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">%1s %2s</p>',
	get_the_time( 'Y' ),
	$site_name
); ?>

	</div>

	<footer id="colophon" class="site-footer">
		<div class="footer-content global-wrapper footer-wrapper">
			<?php echo apply_filters( 'ab_theme_footer_message', $message ); ?>
		</div>
	</footer>
</div>

<?php AB_Theme\Tags\after_page(); ?>
<?php wp_footer(); ?>

</body>
</html>