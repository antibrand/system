<?php
/**
 * Header for config and install pages
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
$app_get_logo = dirname( dirname( dirname( $_SERVER['PHP_SELF'] ) ) ) . '/app-assets/images/app-icon.png';

// Link for the logo image.
$app_link = APP_WEBSITE;

// Conditional logo markup.
if ( APP_WEBSITE ) {

	$app_logo = sprintf(
		'<a href="%1s"><img src="%2s" class="app-logo-image" alt="%3s" itemprop="logo" width="512" height="512"></a>',
		APP_WEBSITE,
		$app_get_logo,
		APP_NAME
	);

} else {

	$app_logo = sprintf(
		'<img src="%1s" class="app-logo-image" alt="%2s" itemprop="logo" width="512" height="512">',
		$app_get_logo,
		APP_NAME
	);

}

// Identity colors
if ( defined( 'APP_COLOR' ) ) {
	$app_color = APP_COLOR;
} else {
	$app_color = 'inherit';
}

if ( defined( 'APP_DARK_COLOR' ) ) {
	$app_dark_color = APP_DARK_COLOR;
} else {
	$app_dark_color = 'white';
}

if ( defined( 'APP_BG_COLOR' ) ) {
	$app_bg_color = APP_BG_COLOR;
} else {
	$app_bg_color = 'white';
}

if ( defined( 'APP_DARK_BG_COLOR' ) ) {
	$app_dark_bg_color = APP_DARK_BG_COLOR;
} else {
	$app_dark_bg_color = '#252525';
}

if ( defined( 'APP_PRIMARY_COLOR' ) ) {
	$app_primary_color = APP_PRIMARY_COLOR;
} else {
	$app_primary_color = '#ffee00';
}

if ( defined( 'APP_SECONDARY_COLOR' ) ) {
	$app_secondary_color = APP_SECONDARY_COLOR;
} else {
	$app_secondary_color = '#3ad4fb';
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

	<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>

	<?php app_assets_css( 'install', true ); ?>
	<!-- style>
		body {
			background-color: <?php // echo $app_bg_color; ?>;
			color: <?php // echo $app_color; ?>
		}
		.dark-mode {
			background-color: <?php // echo $app_dark_bg_color; ?>;
			color: <?php // echo $app_dark_color; ?>
		}
		.setup-install-wrap .button {
			background-color: <?php // echo $app_primary_color; ?>;
			border-color: <?php // echo $app_primary_color; ?>
		}
		.setup-install-wrap .button:hover,
		.setup-install-wrap .button:focus {
			background-color: <?php // echo $app_secondary_color; ?>;
			border-color: <?php // echo $app_secondary_color; ?>
		}
		.dark-mode code {
			color: #222222
		}
	</style> -->
</head>
<body class="<?php echo $body_classes; ?>">
	<header class="app-header">
		<div class="app-identity">
			<div class="app-logo">
				<?php echo $app_logo; ?>
			</div>
			<div class="app-title-description">
				<h1 class="app-title"><?php echo APP_NAME; ?></h1>
				<p class="app-description"><?php echo APP_TAGLINE; ?></p>
			</div>
		</div>
	</header>