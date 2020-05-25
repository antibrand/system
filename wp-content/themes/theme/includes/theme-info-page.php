<?php
/**
 * Theme info page
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 */

namespace AB_Theme\Includes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get theme data as variables.
$get_theme        = wp_get_theme();
$get_theme_name   = $get_theme->get( 'Name' );
$get_template     = $get_theme->get( 'Template' );
$get_parent       = wp_get_theme( get_template() );
$parent_name      = $get_parent->get( 'Name' );
$get_theme_uri    = $get_theme->get( 'ThemeURI' );
$get_author       = $get_theme->get( 'Author' );
$get_author_uri   = $get_theme->get( 'AuthorURI' );
$get_theme_desc   = $get_theme->get( 'Description' );
$get_theme_vers   = $get_theme->get( 'Version' );
$get_theme_domain = $get_theme->get( 'TextDomain' );
$get_theme_tags   = $get_theme->get( 'Tags' );
$get_theme_cover  = $get_theme->get_theme_cover();
$get_theme_icon   = $get_theme->get_theme_icon();

// Text if data is not provided by the theme.
$not_provided = __( 'Not provided in the stylesheet header', 'antibrand' );

// Theme description.
if ( $get_theme_desc ) {
	$description = $get_theme_desc;
} else {
	$description = $not_provided;
}

// Theme link.
if ( $get_theme_uri ) {
	$theme_uri = '<a href="' . $get_theme_uri . '" target="_blank">' . $get_theme_uri . '</a>';
} else {
	$theme_uri = $not_provided;
}

// Theme author.
if ( $get_author ) {
	$author = $get_author;
} else {
	$author = $not_provided;
}

// Theme author link.
if ( $get_author_uri ) {
	$author_uri = '<a href="' . $get_author_uri . '" target="_blank">' . $get_author_uri . '</a>';
} else {
	$author_uri = $not_provided;
}

// Theme version.
if ( $get_theme_vers ) {
	$version = $get_theme_vers;
} else {
	$version = $not_provided;
}

// Theme text domain;
if ( $get_theme_domain ) {
	$domain = $get_theme_domain;
} else {
	$domain = $not_provided;
}

// Theme tags.
if ( $get_theme_tags ) {
	$tags = $get_theme_tags;
} else {
	$tags = $not_provided;
}

// Begin page output.
?>

<div class="wrap theme-info-page">

	<h1><?php _e( 'Active Theme Information', 'antibrand' ); ?></h1>
	<p class="description"><?php echo apply_filters( 'ab_theme_page_description', $get_theme_desc ); ?></p>

	<hr />
	<main>
		<h2><?php echo apply_filters( 'ab_theme_page_details_title', esc_html__( 'Theme Details', 'antibrand' ) ); ?></h2>

		<img class="avatar alignright" src="<?php echo esc_url( $get_theme_icon ); ?>" alt="<?php echo $theme_name; ?>" width="80" height="80" />

		<ul class="content-list">
			<li><strong><?php esc_html_e( 'Theme Name: ', 'antibrand' ); ?></strong><?php echo $get_theme_name; ?></li>
			<?php if ( $get_template ) : ?>
				<li><strong><?php _e( 'Template: ', 'antibrand' ); ?></strong><?php echo $parent_name; ?></li>
			<?php endif; ?>
			<li><strong><?php esc_html_e( 'Theme URI: ', 'antibrand' ); ?></strong><?php echo $theme_uri; ?></li>
			<li><strong><?php esc_html_e( 'Author: ', 'antibrand' ); ?></strong><?php echo $author; ?></li>
			<li><strong><?php esc_html_e( 'Author URI: ', 'antibrand' ); ?></strong><?php echo $author_uri; ?></li>
			<li><strong><?php esc_html_e( 'Description: ', 'antibrand' ); ?></strong><?php echo $description; ?></li>
			<li><strong><?php esc_html_e( 'Version: ', 'antibrand' ); ?></strong><?php echo $version; ?></li>
			<li><strong><?php esc_html_e( 'Text Domain: ', 'antibrand' ); ?></strong><?php echo $domain; ?></li>
			<li><strong><?php esc_html_e( 'Tags: ', 'antibrand' ); ?></strong><?php echo implode( ', ', $tags ); ?></li>
		</ul>
		<?php if ( $get_theme_cover ) : ?>
			<a href="<?php echo $get_theme_uri; ?>" target="_blank" rel="nofollow"><img src="<?php echo esc_url( $get_theme_cover ); ?>" /></a>
		<?php endif; ?>
	</main>
</div><!-- .wrap -->