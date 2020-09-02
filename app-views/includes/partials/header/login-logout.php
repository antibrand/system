<?php
/**
 * Header for login and logout pages
 *
 * @package App_Package
 * @subpackage Administration
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
if ( APP_NAME ) {
	$app_name = APP_NAME;
} else {
	$app_name = __( 'system' );
}

if ( APP_TAGLINE ) {
	$app_tagline = APP_TAGLINE;
} else {
	$app_tagline = __( 'generic, white-label website management' );
}

// Get the identity image or white label logo.
$app_get_logo = dirname( dirname( dirname( $_SERVER['PHP_SELF'] ) ) ) . '/' . APP_ASSETS_DIR . '/images/app-icon.png';

// Link for the logo image.
$app_link = APP_WEBSITE;

// Conditional logo markup.
if ( APP_WEBSITE ) {

	$app_icon = sprintf(
		'<a href="%1s"><img src="%2s" class="app-icon-image" alt="%3s" itemprop="logo" width="512" height="512"></a>',
		APP_WEBSITE,
		$app_get_logo,
		APP_NAME
	);

} else {

	$app_icon = sprintf(
		'<img src="%1s" class="app-icon-image" alt="%2s" itemprop="logo" width="512" height="512">',
		$app_get_logo,
		APP_NAME
	);

}

header( 'Content-Type: text/html; charset=utf-8' );

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />

	<link rel="icon" href="<?php echo $app_get_logo; ?>" />

	<title><?php _e( 'Configuration File Setup' ); ?></title>

	<link rel="preload" href="<?php echo site_url( APP_ASSETS_DIR . '/fonts/sans/variable/sans.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
	<link rel="preload" href="<?php echo site_url( APP_ASSETS_DIR . '/fonts/sans/variable/sans-italic.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
	<link rel="preload" href="<?php echo site_url( APP_ASSETS_DIR . '/fonts/serif/variable/serif.woff' ); ?>" as="font" type="font/woff" crossorigin>
	<link rel="preload" href="<?php echo site_url( APP_ASSETS_DIR . '/fonts/serif/variable/serif.woff' ); ?>" as="font" type="font/woff" crossorigin>
	<link rel="preload" href="<?php echo site_url( APP_ASSETS_DIR . '/fonts/mono/variable/mono.woff2' ); ?>" as="font" type="font/woff2" crossorigin>

	<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>

	<?php
	// Enqueue jQuery for form steps (prev/next).
	wp_print_scripts( 'jquery' ); ?>

</head>
<body class="<?php echo $body_classes; ?>">
	<header class="app-header">
		<div class="app-identity">
			<div class="app-icon">
				<?php echo $app_icon; ?>
			</div>
			<div class="app-title-description">
				<h1 class="app-title"><?php echo $app_name; ?></h1>
				<p class="app-description"><?php echo $app_tagline; ?></p>
			</div>
		</div>
	</header>