<?php
/**
 * Header for setup-config and install pages
 *
 * @package    WMS
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

// Get the identity image or white label logo.
$app_logo = dirname( dirname( $_SERVER['PHP_SELF'] ) ) . '/app-assets/images/app-logo.jpg';

// Link for the logo image.
$app_link = null;

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<title><?php _e( 'Configuration File Setup' ); ?></title>
	<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>
	<?php wp_admin_css( 'install', true ); ?>
</head>
<body class="<?php echo $body_classes; ?>">
	<header class="app-header">
		<div class="app-identity global-wrapper">
			<div class="app-logo">
				<a href="<?php echo $app_link; ?>" class="app-logo-link" rel="home" itemprop="url"><img src="<?php echo $app_logo; ?>" class="app-logo-image" alt="<?php _e( 'App identity image' ); ?>" itemprop="logo" width="512" height="512"></a>
			</div>
			<div class="app-title-description">
				<h1 class="app-title"><?php echo $app_title; ?></h1>
				<p class="app-description"><?php _e( 'Installation & Configuration Process' ); ?></p>
			</div>
		</div>
	</header>