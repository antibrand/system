<?php
/**
 * Class autoloader
 *
 * @package App_Package
 * @subpackage Administration
 */

namespace AppNamespace;

/**
 * A map of classes to files to load
 *
 * The index is the class name, including namespace,
 * the value is the path to the corresponding file.
 *
 * @since 1.0.0
 */
const APP_CLASSES = [

	// Classes used on the back end and/or front end.
	'APP_User_Toolbar'    => __DIR__ . '/classes/includes/class-user-toolbar.php',
	'AppNamespace\Includes\Walker'          => __DIR__ . '/classes/includes/class-walker.php',
	'AppNamespace\Includes\Walker_Nav_Menu' => __DIR__ . '/classes/includes/class-walker-nav-menu.php',
	'AppNamespace\Includes\Walker_Page'     => __DIR__ . '/classes/includes/class-walker-page.php',
	'AppNamespace\Includes\Walker_Category' => __DIR__ . '/classes/includes/class-walker-category.php',
	'AppNamespace\Includes\Walker_Category_Dropdown' => __DIR__ . '/classes/includes/class-walker-category-dropdown.php',
	'AppNamespace\Includes\Site_Icon'       => __DIR__ . '/classes/includes/class-site-icon.php',
	'AppNamespace\Includes\Import_Data'     => __DIR__ . '/classes/includes/class-import-data.php',
	'AppNamespace\Includes\Export_Data'     => __DIR__ . '/classes/includes/class-export-data.php',
	'AppNamespace\Includes\Import_Data_Content' => __DIR__ . '/classes/includes/class-import-data-content.php',
	'AppNamespace\Includes\Error_Messages'  => __DIR__ . '/classes/includes/class-error-messages.php',

	// Classes used in the back end only.
	'AppNamespace\Backend\Screen'               => __DIR__ . '/classes/backend/class-screen.php',
	'AppNamespace\Backend\Dashboard'            => __DIR__ . '/classes/backend/class-dashboard.php',
	'AppNamespace\Backend\Data_Page'            => __DIR__ . '/classes/backend/class-data-page.php',
	'AppNamespace\Backend\List_Table'           => __DIR__ . '/classes/backend/class-list-table.php',
	'AppNamespace\Backend\List_Table_Compat'    => __DIR__ . '/classes/backend/class-list-table-compat.php',
	'AppNamespace\Backend\Posts_List_Table'     => __DIR__ . '/classes/backend/class-posts-list-table.php',
	'AppNamespace\Backend\Terms_List_Table'     => __DIR__ . '/classes/backend/class-terms-list-table.php',
	'AppNamespace\Backend\Media_List_Table'     => __DIR__ . '/classes/backend/class-media-list-table.php',
	'AppNamespace\Backend\Comments_List_Table'  => __DIR__ . '/classes/backend/class-comments-list-table.php',
	'AppNamespace\Backend\Post_Comments_List_Table'  => __DIR__ . '/classes/backend/class-post-comments-list-table.php',
	'AppNamespace\Backend\Users_List_Table'     => __DIR__ . '/classes/backend/class-users-list-table.php',
	'AppNamespace\Backend\Plugins_List_Table'   => __DIR__ . '/classes/backend/class-plugins-list-table.php',
	'AppNamespace\Backend\Walker_Nav_Menu_Edit' => __DIR__ . '/classes/backend/class-walker-nav-menu-edit.php',
	'AppNamespace\Backend\Walker_Category_Checklist' => __DIR__ . '/classes/backend/class-walker-category-checklist.php',
	'AppNamespace\Backend\Walker_Nav_Menu_Checklist' => __DIR__ . '/classes/backend/class-walker-nav-menu-checklist.php',
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
		if ( isset( APP_CLASSES[ $classname ] ) ) {
			require APP_CLASSES[ $classname ];
		}
	}
);

/**
 * Alias namespaced classes
 *
 * Make plugins and themes that call classes which were previously
 * not namespaced aware of the new locations.
 *
 * @since  1.0.0
 *
 * @link https://www.php.net/manual/en/function.class-alias.php
 */
\class_alias( \APP_User_Toolbar :: class, \WP_Admin_Bar :: class );
\class_alias( Includes\Walker :: class, \Walker :: class );
\class_alias( Includes\Walker_Nav_Menu :: class, \Walker_Nav_Menu :: class );
\class_alias( Includes\Walker_Page :: class, \Walker_Page :: class );
\class_alias( Includes\Walker_Category :: class, \Walker_Category :: class );
\class_alias( Includes\Walker_Category_Dropdown :: class, \Walker_CategoryDropdown :: class );
\class_alias( Backend\Walker_Nav_Menu_Edit :: class, \Walker_Nav_Menu_Edit :: class );
\class_alias( Backend\Walker_Nav_Menu_Checklist :: class, \Walker_Nav_Menu_Checklist :: class );