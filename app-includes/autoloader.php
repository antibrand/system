<?php
/**
 * Class autoloader
 *
 * @package App_Package
 * @subpackage Administration
 */

namespace AppNamespace;

/*
 * A map of classes to files to load
 *
 * The index is the class name, including namespace,
 * the value is the path to the corresponding file.
 */
const MAP = [

	// Classes used in the back end only.
	'AppNamespace\Backend\Dashboard'           => __DIR__ . '/classes/backend/class-dashboard.php',
	'AppNamespace\Backend\Screen'              => __DIR__ . '/classes/backend/class-screen.php',
	'AppNamespace\Backend\List_Table'          => __DIR__ . '/classes/backend/class-list-table.php',
	'AppNamespace\Backend\List_Table_Compat'   => __DIR__ . '/classes/backend/class-list-table-compat.php',
	'AppNamespace\Backend\Posts_List_Table'    => __DIR__ . '/classes/backend/class-posts-list-table.php',
	'AppNamespace\Backend\Terms_List_Table'    => __DIR__ . '/classes/backend/class-terms-list-table.php',
	'AppNamespace\Backend\Media_List_Table'    => __DIR__ . '/classes/backend/class-media-list-table.php',
	'AppNamespace\Backend\Comments_List_Table' => __DIR__ . '/classes/backend/class-comments-list-table.php',
	'AppNamespace\Backend\Users_List_Table'    => __DIR__ . '/classes/backend/class-users-list-table.php',
	'AppNamespace\Backend\Plugins_List_Table'  => __DIR__ . '/classes/backend/class-plugins-list-table.php',

	// Classes used on the back end and front end.
	'AppNamespace\Includes\Site_Icon'      => __DIR__ . '/classes/includes/class-site-icon.php',
	'AppNamespace\Includes\Error_Messages' => __DIR__ . '/classes/includes/class-error-messages.php',
];

/**
 * Register classes to be loaded
 *
 * @link https://www.php.net/manual/en/function.spl-autoload-register.php
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
spl_autoload_register(
	function ( string $classname ) {
		if ( isset( MAP[ $classname ] ) ) {
			require MAP[ $classname ];
		}
	}
);