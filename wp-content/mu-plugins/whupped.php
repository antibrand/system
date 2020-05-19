<?php
/**
 * WhuPped
 *
 * Creates aliases for PHP classes, methods, and functions
 * that previously had brand prefixes.
 *
 * @package WhuPped
 * @version 1.0.0
 *
 * Plugin Name:  WhuPped!
 * Description: Deprecated PHP classes, methods, and functions.
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
 * Alias classes
 *
 * @since 1.0.0
 */

// Classes used on the back end and/or front end.
\class_alias( Includes\Site_Icon :: class, \WP_Site_Icon :: class );

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