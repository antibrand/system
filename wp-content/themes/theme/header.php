<?php
/**
 * Begin HTML markup
 *
 * Renders the `<head>` section, gets site
 * identity and navigation.
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 *
 * @todo Add hooks for nav above or below header.
 */

// Conditional canonical link.
if ( is_home() && ! is_front_page() ) {
    $canonical = get_permalink( get_option( 'page_for_posts' ) );
} elseif ( is_archive() ) {
    $canonical = get_permalink( get_option( 'page_for_posts' ) );
} else {
    $canonical = get_permalink();
}

?>
<!doctype html>
<?php
/**
 * Before HTML methods
 *
 * Made available for plugins. For instance, Advanced Custom Fields
 * requires the `acf_head()` function to run before the `<html>` element
 * for frontend forms.
 *
 * Check for the `before_html()` function which runs the `before_html`
 * hook. If it is not found the do the action here. This is a failsafe
 * in case the theme is used in a system, such as WordPress, which does
 * not have the `before_html()` function.
 *
 * @since 1.0.0
 */
if ( function_exists( 'before_html' ) ) {
	before_html();
} else {
	do_action( 'before_html' );
}

// Begin HTML output.
?>
<!doctype html>
<?php do_action( 'before_html' ); ?>
<html <?php language_attributes(); ?> class="no-js">
<head id="<?php echo get_bloginfo( 'wpurl' ); ?>" data-template-set="<?php echo get_template(); ?>">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<!--[if IE ]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link href="<?php echo $canonical; ?>" rel="canonical" />
	<?php if ( is_search() ) { echo '<meta name="robots" content="noindex,nofollow" />'; } ?>

	<?php do_action( 'before_wp_head' ); ?>
	<?php wp_head(); ?>
	<?php do_action( 'after_wp_head' ); ?>
</head>

<body <?php body_class( 'frontend' ); ?>>
<?php AB_Theme\Tags\before_page(); ?>
<div id="page" class="site" itemscope="itemscope" itemtype="<?php AB_Theme\Tags\site_schema(); ?>">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'antibrand' ); ?></a>

	<?php get_template_part( 'template-parts/navigation/navigation', 'main' ); ?>

	<header id="masthead" class="site-header" role="banner" itemscope="itemscope" itemtype="http://schema.org/Organization">
		<?php get_template_part( 'template-parts/header/site-identity' ); ?>
		<?php get_template_part( 'template-parts/header/header-image' ); ?>
	</header>

	<div id="content" class="site-content">
