<?php
/**
 * WhuPped
 *
 * @package WhuPped
 * @version 1.0.0
 *
 * Plugin Name:  WhuPped!
 * Description: Deprecated PHP classes, methods, and functions.
 */

namespace AppNamespace\Compatibility;

/**
 * Alias namespaces
 *
 * Make sure the namespaces here are the same base as that
 * used in your copy of this website management system.
 *
 * @since 1.0.0
 */
use \AppNamespace\Admin    as Admin;
use \AppNamespace\Includes as Includes;

/**
 * Alias classes
 *
 * @since 1.0.0
 */
class_alias( Admin\Screen              :: class, \WP_Screen              :: class );
class_alias( Admin\List_Table          :: class, \WP_List_Table          :: class );
class_alias( Admin\List_Table_Compat   :: class, \WP_List_Table_Compat   :: class );
class_alias( Admin\Posts_List_Table    :: class, \WP_Posts_List_Table    :: class );
class_alias( Admin\Terms_List_Table    :: class, \WP_Terms_List_Table    :: class );
class_alias( Admin\Media_List_Table    :: class, \WP_Media_List_Table    :: class );
class_alias( Admin\Comments_List_Table :: class, \WP_Comments_List_Table :: class );
class_alias( Admin\Users_List_Table    :: class, \WP_Users_List_Table    :: class );
class_alias( Admin\Plugins_List_Table  :: class, \WP_Plugins_List_Table  :: class );

class_alias( Includes\Error     :: class, \WP_Error           :: class );
class_alias( Includes\Site_Icon :: class, \WP_Site_Icon           :: class );