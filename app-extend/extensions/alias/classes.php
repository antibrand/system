<?php
/**
 * Alias classes
 *
 * @package Alias
 * @subpackage App_Package
 */

/**
 * Alias namespaces
 *
 * Make sure the namespaces here are the same base as that
 * used in your copy of this website management system.
 *
 * @since 1.0.0
 *
 * @link https://www.php.net/manual/en/function.class-alias.php
 */
use \AppNamespace\Backend  as Backend;
use \AppNamespace\Includes as Includes;

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

// Classes used on the back end and/or front end.
\class_alias( Includes\Site_Icon :: class, \WP_Site_Icon :: class );
\class_alias( Includes\User_Toolbar :: class, \WP_Admin_Bar :: class );
//\class_alias( Includes\Installer :: class, \WP_Upgrader :: class );
\class_alias( Includes\Walker :: class, \Walker :: class );
\class_alias( Includes\Walker_Nav_Menu :: class, \Walker_Nav_Menu :: class );
\class_alias( Includes\Walker_Page :: class, \Walker_Page :: class );
\class_alias( Includes\Walker_Category :: class, \Walker_Category :: class );
\class_alias( Includes\Walker_Category_Dropdown :: class, \Walker_CategoryDropdown :: class );
\class_alias( Backend\Walker_Nav_Menu_Edit :: class, \Walker_Nav_Menu_Edit :: class );
\class_alias( Backend\Walker_Nav_Menu_Checklist :: class, \Walker_Nav_Menu_Checklist :: class );

// Classes used in the back end only.
\class_alias( Backend\Screen :: class, \WP_Screen :: class );
\class_alias( Backend\List_Table :: class, \WP_List_Table :: class );
\class_alias( Backend\List_Table_Compat :: class, \WP_List_Table_Compat :: class );
\class_alias( Backend\Posts_List_Table :: class, \WP_Posts_List_Table :: class );
\class_alias( Backend\Terms_List_Table :: class, \WP_Terms_List_Table :: class );
\class_alias( Backend\Media_List_Table :: class, \WP_Media_List_Table :: class );
\class_alias( Backend\Comments_List_Table :: class, \WP_Comments_List_Table :: class );
\class_alias( Backend\Users_List_Table :: class, \WP_Users_List_Table :: class );
\class_alias( Backend\Plugins_List_Table :: class, \WP_Plugins_List_Table :: class );
