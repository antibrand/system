<?php
/**
 * Dashboard intro panel: Administrator
 *
 * @package App_Package
 * @subpackage Administration
 */

// Alias namespaces.
use \AppNamespace\Backend\Dashboard as Dashboard;

// App version.
$version = get_bloginfo( 'version' );

/**
 * Intro panel description
 *
 * Uses the white label tagline if available.
 */
if ( defined( 'APP_NAME' ) ) {
	$panel_heading = sprintf(
		'<h2>%1s %2s</h2>',
		__( 'Welcome to' ),
		APP_NAME
	);
} else {
	$panel_heading = sprintf(
		'<h2>%1s</h2>',
		__( 'Welcome' )
	);
}

if ( defined( 'APP_TAGLINE' ) ) {
	$panel_description = sprintf(
		'<p class="description">%1s</p>',
		APP_TAGLINE
	);
} else {
	$panel_description = sprintf(
		'<p class="description">%1s</p>',
		__( 'generic, white-label website management' )
	);
}

$screen = get_current_screen();

?>
<header>
	<h2><?php _e( 'Site Overview' ); ?></h2>
	<p class="description"><?php _e( 'This information is provided to you as a site administrator.' ); ?></p>
</header>

<?php Dashboard :: site_overview_tab(); ?>