<?php
/**
 * Header for default die handler
 *
 * @package App_Package
 * @subpackage Administration
 *
 * @see wp-includes/functions.php
 */

// HTML direction.
if ( is_rtl() ) {
	$direction = ' dir="rtl"';
} else {
	$direction = null;
}

// Body classes.
$body_classes = 'app-core-ui';
if ( is_rtl() ) {
	$body_classes .= 'rtl';
}

// Define a name of the website management system.
if ( defined( 'APP_NAME' ) && APP_NAME ) {
	$app_name = esc_html( APP_NAME );
} else {
	$app_name = esc_html__( 'system' );
}

if ( wp_installing() ) {
	$heading = esc_html( $app_name );
} else {
	$heading = esc_html( get_bloginfo( 'name' ) );
}

if ( defined( 'APP_TAGLINE' ) && APP_TAGLINE ) {
	$app_tagline = esc_html( APP_TAGLINE );
} else {
	$app_tagline = esc_html__( 'generic, white-label website management' );
}

if ( wp_installing() ) {
	$description = sprintf(
		'<p class="app-description">%1s</p>',
		esc_html( $app_tagline )
	);
} elseif ( ! empty( get_bloginfo( 'description' ) ) ) {
	$description = sprintf(
		'<p class="app-description">%1s</p>',
		esc_html( get_bloginfo( 'description' ) )
	);
} else {
	$description = null;
}

// Get the identity image or white label logo.
$app_get_logo = get_app_assets_url() . 'images/app-icon.png';

// Conditional logo markup.
if ( defined( 'APP_WEBSITE' ) && APP_WEBSITE ) {

	$app_icon = sprintf(
		'<a href="%1s"><img src="%2s" class="app-logo-image" alt="%3s" itemprop="logo" width="512" height="512"></a>',
		esc_url( APP_WEBSITE ),
		esc_attr( $app_get_logo ),
		esc_html( APP_NAME )
	);

} else {

	$app_icon = sprintf(
		'<img src="%1s" class="app-logo-image" alt="%2s" itemprop="logo" width="512" height="512">',
		esc_attr( $app_get_logo ),
		esc_html( APP_NAME )
	);

}

header( 'Content-Type: text/html; charset=utf-8' );

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
	<meta name="robots" content="noindex,nofollow" />

	<title><?php _e( 'System Error' ); ?></title>

	<link rel="icon" href="<?php echo esc_attr( $app_get_logo ); ?>" />

	<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>

	<?php
	// Enqueue jQuery for form steps (prev/next).
	wp_print_scripts( 'jquery' ); ?>

	<?php app_assets_css( 'utility', true ); ?>

</head>
<body class="<?php echo $body_classes; ?>">
	<header class="app-header">
		<div class="app-identity">
			<div class="app-logo">
				<?php echo $app_icon; ?>
			</div>
			<div class="app-title-description">
				<p class="app-title"><?php echo esc_html( $heading ); ?></p>
				<?php echo $description; ?>
			</div>
		</div>
	</header>
