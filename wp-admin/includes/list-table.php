<?php
/**
 * Helper functions for displaying a list of items in an ajaxified HTML table.
 *
 * @package App_Package
 * @subpackage List_Table
 * @since Previous 3.1.0
 */

/**
 * Alias namespaces
 *
 * Make sure the namespaces here are the same base as that
 * used in your copy of this website management system.
 *
 * @since 1.0.0
 */
use \AppNamespace\Backend as Backend;

/**
 * Fetch an instance of a WP_List_Table class.
 *
 * @access private
 * @since Previous 3.1.0
 * @global string $hook_suffix
 * @param string $class The type of the list table, which is the class name.
 * @param array $args Optional. Arguments to pass to the class. Accepts 'screen'.
 * @return object|bool Object on success, false if the class does not exist.
 */
function _get_list_table( $class, $args = [] ) {

	$core_classes = [

		//Site Admin
		'AppNamespace\Backend\Posts_List_Table'          => 'posts',
		'AppNamespace\Backend\Media_List_Table'          => 'media',
		'AppNamespace\Backend\Terms_List_Table'          => 'terms',
		'AppNamespace\Backend\Users_List_Table'          => 'users',
		'AppNamespace\Backend\Comments_List_Table'       => 'comments',
		'AppNamespace\Backend\Post_Comments_List_Table'  => [ 'comments', 'post-comments' ],
		'AppNamespace\Backend\Links_List_Table'          => 'links',
		'AppNamespace\Backend\Plugins_List_Table'        => 'plugins',
		'AppNamespace\Backend\Extensions_List_Table'     => 'extensions',
		'AppNamespace\Backend\Plugin_Install_List_Table' => 'plugin-install',
		'AppNamespace\Backend\Themes_List_Table'         => 'themes',
		'AppNamespace\Backend\Theme_Install_List_Table'  => [ 'themes', 'theme-install' ],

		// Network Admin
		'AppNamespace\Backend\MS_Sites_List_Table'  => 'ms-sites',
		'AppNamespace\Backend\MS_Users_List_Table'  => 'ms-users',
		'AppNamespace\Backend\MS_Themes_List_Table' => 'ms-themes',
	];

	if ( isset( $core_classes[ $class ] ) ) {

		if ( isset( $args['screen'] ) ) {
			$args['screen'] = convert_to_screen( $args['screen'] );
		} elseif ( isset( $GLOBALS['hook_suffix'] ) ) {
			$args['screen'] = get_current_screen();
		} else {
			$args['screen'] = null;
		}

		return new $class( $args );
	}

	return false;
}

/**
 * Register column headers for a particular screen
 *
 * @see get_column_headers(), print_column_headers(), get_hidden_columns()
 *
 * @since  WP 2.7.0
 * @param  string $screen The handle for the screen to add help to. This is usually the hook name returned by the add_*_page() functions.
 * @param  array $columns An array of columns with column IDs as the keys and translated column names as the values.
 * @return object/List_Table_Compat
 */
function register_column_headers( $screen, $columns ) {
	new Backend\List_Table_Compat( $screen, $columns );
}

/**
 * Prints column headers for a particular screen.
 *
 * @since  WP 2.7.0
 * @param  string|Screen $screen  The screen hook name or screen object.
 * @param  bool $with_id Whether to set the id attribute or not.
 * @return string Returns the column headers.
 */
function print_column_headers( $screen, $with_id = true ) {

	$wp_list_table = new Backend\List_Table_Compat( $screen );

	$wp_list_table->print_column_headers( $with_id );
}