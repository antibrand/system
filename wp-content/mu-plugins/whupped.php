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

/**
 * Alias namespaces
 *
 * Make sure the namespaces here are the same base as that
 * used in your copy of this website management system.
 *
 * @since 1.0.0
 */
use \AppNamespace\Admin as Admin;
use \AppNamespace\Includes as Includes;

/**
 * Class aliases
 *
 * @since 1.0.0
 */
\class_alias( Admin\Screen :: class, \WP_Screen :: class );
\class_alias( Admin\List_Table :: class, \WP_List_Table :: class );
\class_alias( Admin\Posts_List_Table :: class, \WP_Posts_List_Table :: class );