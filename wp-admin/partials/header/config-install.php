<?php
/**
 * Header for setup-config and install pages
 *
 * @package    App_Package
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
$app_logo = APP_LOGO;

// Link for the logo image.
$app_link = APP_WEBSITE;

// Conditional logo markup.
if ( $app_logo && defined( 'APP_WEBSITE' ) ) {
	$app_logo = sprintf(
		'<a href="%1s"><img src="%2s" class="app-logo-image" alt="%3s" itemprop="logo" width="512" height="512"></a>',
		APP_WEBSITE,
		$app_logo,
		APP_NAME
	);
} elseif ( $app_logo ) {
	$app_logo = sprintf(
		'<img src="%1s" class="app-logo-image" alt="%2s" itemprop="logo" width="512" height="512">',
		$app_logo,
		APP_NAME
	);
} else {
	$app_logo = '';
}

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
				<?php echo $app_logo; ?>
			</div>
			<div class="app-title-description">
				<h1 class="app-title"><?php echo APP_NAME; ?></h1>
				<p class="app-description"><?php echo APP_TAGLINE; ?></p>
			</div>
		</div>
	</header>