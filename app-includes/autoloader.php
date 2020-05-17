<?php
/**
 * Class autoloader
 *
 * @package App_Package
 * @subpackage Administration
 */

namespace AppNamespace;

/*
 * A map of classes to files to be used when determining which file to load
 * when a class needs to be loaded.
 *
 * The index is the fully qualified class name which includes the namespace,
 * the value is the path to the relevant file.
 */
const MAP = [
	'AppNamespace\Admin\Dashboard'       => __DIR__ . '/classes/class-dashboard.php',
	'AppNamespace\Admin\WP_Screen'       => __DIR__ . '/classes/class-wp-screen.php',
	'AppNamespace\Includes\WP_Site_Icon' => __DIR__ . '/classes/class-wp-site-icon.php',

	// Admin directory.
	'WP_List_Table'        => ABSPATH . 'wp-admin/includes/class-wp-list-table.php',
	'WP_List_Table_Compat' => ABSPATH . 'wp-admin/includes/class-wp-list-table-compat.php'
];

spl_autoload_register(
	function ( string $classname ) {
		if ( isset( MAP[ $classname ] ) ) {
			require MAP[ $classname ];
		}
	}
);