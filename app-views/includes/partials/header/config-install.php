<?php
/**
 * Header for config and install pages
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
if ( defined( 'APP_NAME' ) && APP_NAME ) {
	$app_name = APP_NAME;
} else {
	$app_name = __( 'system' );
}

if ( defined( 'APP_TAGLINE' ) && APP_TAGLINE ) {
	$app_tagline = APP_TAGLINE;
} else {
	$app_tagline = __( 'generic, white-label website management' );
}

// Get the identity image or white label logo.
$app_get_logo = dirname( dirname( dirname( $_SERVER['REQUEST_URI'] ) ) ) . '/app-assets/images/app-icon.png';

// Conditional logo markup.
if ( defined( 'APP_WEBSITE' ) && APP_WEBSITE ) {

	$app_logo = sprintf(
		'<a href="%1s"><img src="%2s" class="app-logo-image" alt="%3s" itemprop="logo" width="512" height="512"></a>',
		esc_url( APP_WEBSITE ),
		esc_attr( $app_get_logo ),
		esc_html( APP_NAME )
	);

} else {

	$app_logo = sprintf(
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

	<title><?php _e( 'Configuration File Setup' ); ?></title>

	<link rel="icon" href="<?php echo esc_attr( $app_get_logo ); ?>" />

	<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>

	<style>
	/**
	 * Hide form steps if JavaScript is running
	 *
	 * These will be revealed by jQuery navigation or
	 * if JavaScript is disabled.
	 */
	.js .form-step {
		display: none;
	}
	</style>

	<?php
	// Enqueue jQuery for form steps (prev/next).
	wp_print_scripts( 'jquery' ); ?>

	<?php app_assets_css( 'install', true ); ?>

</head>
<body class="<?php echo $body_classes; ?>">
	<header class="app-header">
		<div class="app-identity">
			<div class="app-logo">
				<?php echo $app_logo; ?>
			</div>
			<div class="app-title-description">
				<h1 class="app-title"><?php echo $app_name; ?></h1>
				<p class="app-description"><?php echo $app_tagline; ?></p>
			</div>
		</div>
	</header>
