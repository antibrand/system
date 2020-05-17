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
	'AppNamespace\Admin\Dashboard'              => __DIR__ . '/classes/class-dashboard.php',
	'AppNamespace\Admin\WP_Screen'              => __DIR__ . '/classes/class-wp-screen.php',
	'AppNamespace\Admin\WP_List_Table'          => __DIR__ . '/classes/class-wp-list-table.php',
	'AppNamespace\Admin\WP_List_Table_Compat'   => __DIR__ . '/classes/class-wp-list-table-compat.php',
	'AppNamespace\Admin\WP_Posts_List_Table'    => __DIR__ . '/classes/class-wp-posts-list-table.php',
	'AppNamespace\Admin\WP_Terms_List_Table'    => __DIR__ . '/classes/class-wp-terms-list-table.php',
	'AppNamespace\Admin\WP_Media_List_Table'    => __DIR__ . '/classes/class-wp-media-list-table.php',
	'AppNamespace\Admin\WP_Comments_List_Table' => __DIR__ . '/classes/class-wp-comments-list-table.php',
	'AppNamespace\Admin\WP_Users_List_Table'    => __DIR__ . '/classes/class-wp-users-list-table.php',
	'AppNamespace\Admin\WP_Plugins_List_Table'  => __DIR__ . '/classes/class-wp-plugins-list-table.php',


	'AppNamespace\Includes\WP_Site_Icon' => __DIR__ . '/classes/class-wp-site-icon.php',
];

spl_autoload_register(
	function ( string $classname ) {
		if ( isset( MAP[ $classname ] ) ) {
			require MAP[ $classname ];
		}
	}
);