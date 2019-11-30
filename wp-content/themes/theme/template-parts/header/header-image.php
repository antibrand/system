<?php
/**
 * Header image markup
 *
 * Renders the image tag, semantic markup, and wrapper.
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 */

?>
<div class="site-header-image" role="presentation">
	<figure>
		<?php
		if ( has_header_image() ) {
			$attributes = [
				'alt'  => ''
			];
			the_header_image_tag( $attributes );
		} else {
			echo sprintf(
				'<img src="%1s" alt="" width="2048" height="878" />',
				get_theme_file_uri( '/assets/images/default-header.jpg' )
			);
		} ?>
	</figure>
</div>