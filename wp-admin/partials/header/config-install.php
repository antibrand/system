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

header( 'Content-Type: text/html; charset=utf-8' );
?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<title><?php _e( 'Configuration File Setup' ); ?></title>
	<?php wp_admin_css( 'install', true ); ?>
</head>
<body class="<?php echo $body_classes; ?>">
