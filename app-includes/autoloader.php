<?php
/**
 * Class autoloader
 *
 * @package App_Package
 * @subpackage Administration
 */

// App namespace.
namespace AppNamespace;

/*
 * A map of classes to files to load
 *
 * The index is the class name, including namespace,
 * the value is the path to the corresponding file.
 */
const MAP = [

	// Classes used in the back end only.
	'AppNamespace\Admin\Dashboard'           => __DIR__ . '/classes/class-dashboard.php',
	'AppNamespace\Admin\Screen'              => __DIR__ . '/classes/class-screen.php',
	'AppNamespace\Admin\List_Table'          => __DIR__ . '/classes/class-list-table.php',
	'AppNamespace\Admin\List_Table_Compat'   => __DIR__ . '/classes/class-list-table-compat.php',
	'AppNamespace\Admin\Posts_List_Table'    => __DIR__ . '/classes/class-posts-list-table.php',
	'AppNamespace\Admin\Terms_List_Table'    => __DIR__ . '/classes/class-terms-list-table.php',
	'AppNamespace\Admin\Media_List_Table'    => __DIR__ . '/classes/class-media-list-table.php',
	'AppNamespace\Admin\Comments_List_Table' => __DIR__ . '/classes/class-comments-list-table.php',
	'AppNamespace\Admin\Users_List_Table'    => __DIR__ . '/classes/class-users-list-table.php',
	'AppNamespace\Admin\Plugins_List_Table'  => __DIR__ . '/classes/class-plugins-list-table.php',

	// Classes used on the back end and front end.
	'AppNamespace\Includes\Site_Icon'        => __DIR__ . '/classes/class-site-icon.php',
];

// Run the autoloader.
spl_autoload_register(
	function ( string $classname ) {
		if ( isset( MAP[ $classname ] ) ) {
			require MAP[ $classname ];
		}
	}
);