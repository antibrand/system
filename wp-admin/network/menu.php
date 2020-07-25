<?php
/**
 * Build network administration menu
 *
 * @package App_Package
 * @subpackage Network
 * @since Previous 3.1.0
 */

$menu[2] = [
	__( 'Dashboard' ),
	'manage_network',
	'index.php',
	'',
	'menu-top menu-top-first menu-icon-dashboard',
	'menu-dashboard',
	'dashicons-dashboard'
];

$submenu['index.php'][0] = [
	__( 'Dashboard' ),
	'read',
	'index.php'
];

$submenu['index.php'][1] = [
	__( 'Site Home' ),
	'read',
	home_url( '/' )
];

$menu[4] = [
	'',
	'read',
	'separator1',
	'',
	'wp-menu-separator'
];

$menu[5] = [
	__( 'Network' ),
	'manage_sites',
	'sites.php',
	'',
	'menu-top menu-icon-site',
	'menu-site',
	'dashicons-admin-multisite'
];

$submenu['sites.php'][5] = [
	__( 'Network Sites' ),
	'manage_sites',
	'sites.php'
];

$submenu['sites.php'][10] = [
	_x( 'Add Site', 'site' ),
	'create_sites', 'site-new.php'
];

$menu[10] = [
	__( 'Accounts' ),
	'manage_network_users',
	'users.php',
	'',
	'menu-top menu-icon-users',
	'menu-users',
	'dashicons-admin-users'
];

$submenu['users.php'][5] = [
	__( 'All Accounts' ),
	'manage_network_users',
	'users.php'
];

$submenu['users.php'][10] = [
	_x( 'Add Account', 'user' ),
	'create_users',
	'user-new.php'
];

$menu[15] = [
	__( 'Themes' ),
	'manage_network_themes',
	'themes.php',
	'',
	'menu-top menu-icon-appearance',
	'menu-appearance',
	'dashicons-admin-appearance'
];

$submenu['themes.php'][5] = [
	__( 'Installed Themes' ),
	'manage_network_themes',
	'themes.php'
];

$submenu['themes.php'][15] = [
	_x( 'Editor', 'theme editor' ),
	'edit_themes',
	'theme-editor.php'
];

$menu[20] = [
	__( 'Plugins' ),
	'manage_network_plugins',
	'plugins.php',
	'',
	'menu-top menu-icon-plugins',
	'menu-plugins',
	'dashicons-admin-plugins'
];

$submenu['plugins.php'][5] = [
	__( 'Installed Plugins' ),
	'manage_network_plugins',
	'plugins.php'
];

$submenu['plugins.php'][15] = [
	_x( 'Editor', 'plugin editor' ),
	'edit_plugins',
	'plugin-editor.php'
];

$menu[25] = [
	__( 'Network Tools' ),
	'manage_network_options',
	'settings.php',
	'',
	'menu-top menu-icon-settings',
	'menu-settings',
	'dashicons-admin-settings'
];

if ( defined( 'APP_NETWORK' ) && defined( 'APP_ALLOW_NETWORK' ) && APP_ALLOW_NETWORK ) {

	$submenu['settings.php'][5] = [
		__( 'Network Settings' ),
		'manage_network_options',
		'settings.php'
	];

	$submenu['settings.php'][10] = [
		__( 'Network Setup' ),
		'setup_network',
		'setup.php'
	];

	$submenu['settings.php'][15] = [
		__( 'Upgrade Network' ),
		'upgrade_network',
		'upgrade.php'
	];
}

unset( $update_data );

$menu[99] = [
	'',
	'exist',
	'separator-last',
	'',
	'wp-menu-separator'
];

require_once( ABSPATH . APP_INC . '/backend/menu.php' );
