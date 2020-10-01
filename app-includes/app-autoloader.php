<?php
/**
 * Class autoloader
 *
 * @package App_Package
 * @subpackage Includes
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
	'AppNamespace\Includes\App_Constants'  => __DIR__ . '/classes/includes/class-app-environment.php',
	'AppNamespace\Includes\App_Install'    => __DIR__ . '/classes/includes/class-app-install.php',
	'AppNamespace\Includes\PasswordHash'   => __DIR__ . '/classes/includes/class-phpass.php',
	'AppNamespace\Includes\User_Logging'   => __DIR__ . '/classes/includes/class-user-logging.php',
	'AppNamespace\Includes\User_Toolbar'   => __DIR__ . '/classes/includes/class-user-toolbar.php',
	'AppNamespace\Includes\Installer'      => __DIR__ . '/classes/includes/class-installer.php',
	'AppNamespace\Includes\Error_Messages' => __DIR__ . '/classes/includes/class-error-messages.php',
	'WP_Filesystem_Base' => __DIR__ . '/classes/backend/class-filesystem-base.php',
	'ftp' => __DIR__ . '/classes/backend/class-ftp.php',

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
	'AppNamespace\Includes\Browser_Icon' => __DIR__ . '/classes/includes/class-site-icon.php',

	// Backend functionality.
	'PclZip' => __DIR__ . '/classes/backend/class-pclzip.php',

	// Upgraders.
	'Core_Upgrader'             => __DIR__ . '/classes/backend/class-core-upgrader.php',
	'WP_Automatic_Updater'      => __DIR__ . '/classes/backend/class-automatic-updater.php',
	'WP_Upgrader_Skin'          => __DIR__ . '/classes/backend/class-wp-upgrader-skin.php',
	'WP_Ajax_Upgrader_Skin'     => __DIR__ . '/classes/backend/class-ajax-upgrader-skin.php',
	'Bulk_Upgrader_Skin'        => __DIR__ . '/classes/backend/class-bulk-upgrader-skin.php',
	'Automatic_Upgrader_Skin'   => __DIR__ . '/classes/backend/class-automatic-upgrader-skin.php',
	'Plugin_Upgrader'           => __DIR__ . '/classes/backend/class-plugin-upgrader.php',
	'Plugin_Installer_Skin'     => __DIR__ . '/classes/backend/class-plugin-installer-skin.php',
	'Plugin_Upgrader_Skin'      => __DIR__ . '/classes/backend/class-plugin-upgrader-skin.php',
	'Bulk_Plugin_Upgrader_Skin' => __DIR__ . '/classes/backend/class-bulk-plugin-upgrader-skin.php',
	'Theme_Upgrader'            => __DIR__ . '/classes/backend/class-theme-upgrader.php',
	'Theme_Installer_Skin'      => __DIR__ . '/classes/backend/class-theme-installer-skin.php',
	'Theme_Upgrader_Skin'       => __DIR__ . '/classes/backend/class-theme-upgrader-skin.php',
	'Bulk_Theme_Upgrader_Skin'  => __DIR__ . '/classes/backend/class-bulk-theme-upgrader-skin.php',
	'File_Upload_Upgrader'      => __DIR__ . '/classes/backend/class-file-upload-upgrader.php',
	'Language_Pack_Upgrader'    => __DIR__ . '/classes/backend/class-language-pack-upgrader.php',
	'Language_Pack_Upgrader_Skin' => __DIR__ . '/classes/backend/class-language-pack-upgrader-skin.php',

	/**
	 * Administration screen
	 *
	 * These load classes for the various administration pages.
	 *
	 * @since 1.0.0
	 */
	'AppNamespace\Backend\Screen' => __DIR__ . '/classes/backend/class-screen.php',

	// Dashboard screens.
	'AppNamespace\Backend\Dashboard' => __DIR__ . '/classes/backend/class-admin-dashboard.php',

	// General and/or multi-purpose screens.
	'AppNamespace\Backend\Admin_Screen' => __DIR__ . '/classes/backend/class-admin-screen.php',
	'AppNamespace\Backend\Admin_About'  => __DIR__ . '/classes/backend/class-admin-about.php',

	// List management screens.
	'AppNamespace\Backend\List_Manage_Screen'     => __DIR__ . '/classes/backend/class-list-manage-screen.php',
	'AppNamespace\Backend\List_Manage_Edit'       => __DIR__ . '/classes/backend/class-list-manage-edit.php',
	'AppNamespace\Backend\List_Manage_Discussion' => __DIR__ . '/classes/backend/class-list-manage-discussion.php',
	'AppNamespace\Backend\List_Manage_Users'      => __DIR__ . '/classes/backend/class-list-manage-users.php',

	// Edit screens.
	'AppNamespace\Backend\Edit_Screen'       => __DIR__ . '/classes/backend/class-edit-screen.php',
	'AppNamespace\Backend\Admin_Comment'     => __DIR__ . '/classes/backend/class-admin-comment.php',
	'AppNamespace\Backend\Admin_Account_New' => __DIR__ . '/classes/backend/class-admin-account-new.php',

	// Settings screens.
	'AppNamespace\Backend\Settings_Screen'     => __DIR__ . '/classes/backend/class-settings-screen.php',
	'AppNamespace\Backend\Settings_General'    => __DIR__ . '/classes/backend/class-settings-general.php',
	'AppNamespace\Backend\Settings_Permalinks' => __DIR__ . '/classes/backend/class-settings-permalinks.php',
	'AppNamespace\Backend\Settings_Media'      => __DIR__ . '/classes/backend/class-settings-media.php',
	'AppNamespace\Backend\Settings_Content'    => __DIR__ . '/classes/backend/class-settings-content.php',
	'AppNamespace\Backend\Settings_Discussion' => __DIR__ . '/classes/backend/class-settings-discussion.php',
	'AppNamespace\Backend\Data_Page'           => __DIR__ . '/classes/backend/class-data-page.php',
	'Custom_Background'   => __DIR__ . '/classes/backend/class-custom-background.php',
	'Custom_Image_Header' => __DIR__ . '/classes/backend/class-custom-header.php',

	// UI features.
	'WP_Internal_Pointers' => __DIR__ . '/classes/backend/class-internal-pointers.php',

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
