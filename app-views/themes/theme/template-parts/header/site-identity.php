<?php
/**
 * Site identity markup
 *
 * Renders the site title, descriiption, and logo.
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 */

/**
 * Conditional site logo markup
 *
 * Uses a link to the home page if not on the home page.
 */
if ( is_front_page() && has_custom_logo() ) {
	$id        = get_theme_mod( 'custom_logo' );
	$src       = wp_get_attachment_image_src( $id , 'full' );
	$site_logo = sprintf(
		'<img src="%1s" class="custom-logo" alt="%2s" itemprop="logo" width="512" height="512">',
		$src[0],
		get_bloginfo( 'name' )
	);
} elseif ( has_custom_logo() ) {
	$site_logo = get_custom_logo();
} else {
	$site_logo = sprintf(
		'<a href="%1s" class="custom-logo-link" rel="home" itemprop="url"><img src="%2s" class="custom-logo" alt="%3s" itemprop="logo" width="512" height="512"></a>',
		esc_url( home_url( '/' ) ),
		get_theme_file_uri( '/assets/images/identity-image.png' ),
		get_bloginfo( 'name' )
	);
}

/**
 * Conditional site name markup
 *
 * Uses an h1 element on the home page with no link.
 * Uses a p element on all other pages with a link to
 * the home page.
 */
if ( is_front_page() ) {
	$site_title = sprintf(
		'<h1 class="site-title">%1s</h1>',
		get_bloginfo( 'name' )
	);
} else {
	$site_title = sprintf(
		'<p class="site-title"><a href="%1s" rel="home">%2s</a></p>',
		esc_url( home_url( '/' ) ),
		get_bloginfo( 'name' )
	);
}
// Apply a filter for customization.
$site_title = apply_filters( 'ab_site_title', $site_title );

/**
 * Conditional site description markup
 *
 * Prints nothing if the description/tagline is field empty.
 * Prints a p element if the customizer is open, wether the
 * description/tagline is field empty or not because the Site
 * Identity section can edit the description.
 */
$get_description = get_bloginfo( 'description', 'display' );
if ( ! empty( $get_description ) || is_customize_preview() ) {
	$site_description = sprintf(
		'<p class="site-description">%1s</p>',
		$get_description
	);
} else {
	$site_description = null;
}
// Apply a filter for customization.
$site_description = apply_filters( 'ab_site_title', $site_description );

?>
<div class="site-identity">
	<div class="site-logo">
		<?php echo $site_logo; ?>
	</div>
	<div class="site-title-description">
		<?php echo $site_title; ?>
		<?php echo $site_description; ?>
	</div>
</div>