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

	// Utility.
	'AppNamespace\Includes\App_Install'    => __DIR__ . '/classes/includes/class-app-install.php',
	'AppNamespace\Includes\PasswordHash'   => __DIR__ . '/classes/includes/class-phpass.php',
	'AppNamespace\Includes\User_Logging'   => __DIR__ . '/classes/includes/class-user-logging.php',
	'AppNamespace\Includes\User_Toolbar'   => __DIR__ . '/classes/includes/class-user-toolbar.php',
	'AppNamespace\Includes\Installer'      => __DIR__ . '/classes/includes/class-installer.php',
	'AppNamespace\Includes\Error_Messages' => __DIR__ . '/classes/includes/class-error-messages.php',

	// Walkers.
	'AppNamespace\Includes\Walker'          => __DIR__ . '/classes/includes/class-walker.php',
	'AppNamespace\Includes\Walker_Nav_Menu' => __DIR__ . '/classes/includes/class-walker-nav-menu.php',
	'AppNamespace\Includes\Walker_Page'     => __DIR__ . '/classes/includes/class-walker-page.php',
	'AppNamespace\Includes\Walker_Category' => __DIR__ . '/classes/includes/class-walker-category.php',
	'AppNamespace\Includes\Walker_Category_Dropdown' => __DIR__ . '/classes/includes/class-walker-category-dropdown.php',
	'AppNamespace\Backend\Walker_Nav_Menu_Edit'      => __DIR__ . '/classes/backend/class-walker-nav-menu-edit.php',
	'AppNamespace\Backend\Walker_Category_Checklist' => __DIR__ . '/classes/backend/class-walker-category-checklist.php',
	'AppNamespace\Backend\Walker_Nav_Menu_Checklist' => __DIR__ . '/classes/backend/class-walker-nav-menu-checklist.php',

	// Data.
	'AppNamespace\Includes\Import_Data'         => __DIR__ . '/classes/includes/class-import-data.php',
	'AppNamespace\Includes\Export_Data'         => __DIR__ . '/classes/includes/class-export-data.php',
	'AppNamespace\Includes\Import_Data_Content' => __DIR__ . '/classes/includes/class-import-data-content.php',

	// Presentation.
	'AppNamespace\Includes\Site_Icon' => __DIR__ . '/classes/includes/class-site-icon.php',

	// Screens.
	'AppNamespace\Backend\Screen'              => __DIR__ . '/classes/backend/class-screen.php',
	'AppNamespace\Backend\Dashboard'           => __DIR__ . '/classes/backend/class-dashboard.php',
	'AppNamespace\Backend\Settings_Screen'     => __DIR__ . '/classes/backend/class-settings-screen.php',
	'AppNamespace\Backend\Settings_General'    => __DIR__ . '/classes/backend/class-settings-general.php',
	'AppNamespace\Backend\Settings_Permalinks' => __DIR__ . '/classes/backend/class-settings-permalinks.php',
	'AppNamespace\Backend\Settings_Media'      => __DIR__ . '/classes/backend/class-settings-media.php',
	'AppNamespace\Backend\Settings_Content'    => __DIR__ . '/classes/backend/class-settings-content.php',
	'AppNamespace\Backend\Settings_Discussion' => __DIR__ . '/classes/backend/class-settings-discussion.php',
	'AppNamespace\Backend\Data_Page'           => __DIR__ . '/classes/backend/class-data-page.php',

	// List tables.
	'AppNamespace\Backend\List_Table'          => __DIR__ . '/classes/backend/class-list-table.php',
	'AppNamespace\Backend\List_Table_Compat'   => __DIR__ . '/classes/backend/class-list-table-compat.php',
	'AppNamespace\Backend\Posts_List_Table'    => __DIR__ . '/classes/backend/class-posts-list-table.php',
	'AppNamespace\Backend\Terms_List_Table'    => __DIR__ . '/classes/backend/class-terms-list-table.php',
	'AppNamespace\Backend\Media_List_Table'    => __DIR__ . '/classes/backend/class-media-list-table.php',
	'AppNamespace\Backend\Comments_List_Table' => __DIR__ . '/classes/backend/class-comments-list-table.php',
	'AppNamespace\Backend\Post_Comments_List_Table' => __DIR__ . '/classes/backend/class-post-comments-list-table.php',
	'AppNamespace\Backend\Users_List_Table'         => __DIR__ . '/classes/backend/class-users-list-table.php',
	'AppNamespace\Backend\Plugins_List_Table'       => __DIR__ . '/classes/backend/class-plugins-list-table.php',
	'AppNamespace\Backend\Extensions_List_Table'    => __DIR__ . '/classes/backend/class-extensions-list-table.php',

	// Network.
	'AppNamespace\Network\Network'      => __DIR__ . '/classes/network/class-network.php',
	'AppNamespace\Network\Network_Site' => __DIR__ . '/classes/network/class-network-site.php',
	'AppNamespace\Network\Sites_List_Table'  => __DIR__ . '/classes/network/class-network-sites-list-table.php',
	'AppNamespace\Network\Themes_List_Table' => __DIR__ . '/classes/network/class-network-themes-list-table.php',
	'AppNamespace\Network\Users_List_Table'  => __DIR__ . '/classes/network/class-network-users-list-table.php',

	// Live manage.
	'Live_Manager' => __DIR__ . '/classes/live-manage/class-live-manager.php',

	// Extend.
	'AppNamespace\Includes\Extend' => __DIR__ . '/classes/includes/class-load-extensions.php'

// End APP_CLASSES array.
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
