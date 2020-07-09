<?php
/**
 * Build Administration Menu.
 *
 * @package App_Package
 * @subpackage Administration
 *
 * Constructs the admin menu.
 *
 * The elements in the array are :
 *     0: Menu item name
 *     1: Minimum level or capability required.
 *     2: The URL of the item's file
 *     3: Class
 *     4: ID
 *     5: Icon for top level menu
 *
 * @global array $menu
 */

/**
 * Get plugins path
 *
 * Used to check for active plugins with the `is_plugin_active` function.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// Get privacy notifications.
$change_notice = '';
if ( current_user_can( 'manage_privacy_options' ) && WP_Privacy_Policy_Content::text_change_check() ) {
	$change_notice = ' <span class="update-plugins 1"><span class="plugin-count">' . number_format_i18n( 1 ) . '</span></span>';
}

/**
 * Dashboard & admin index page
 *
 * Items vary if network activated (multisite).
 */
$menu[2] = [
	__( 'Dashboard' ),
	'read',
	'index.php',
	'',
	'menu-top menu-top-first menu-icon-dashboard',
	'menu-dashboard',
	'dashicons-dashboard'
];

$submenu['index.php'][0] = [
	__( 'Site Dashboard' ),
	'read',
	'index.php'
];

$submenu['index.php'][1] = [
	__( 'Site Home' ),
	'read',
	home_url( '/' )
];

if ( is_multisite() ) {

	$submenu['index.php'][5] = [
		__( 'Network' ),
		'read',
		'my-sites.php'
	];
}

if ( ! is_multisite() || current_user_can( 'update_core' ) ) {
	$update_data = wp_get_update_data();
}

if ( ! is_multisite() ) {

	if ( current_user_can( 'update_core' ) ) {
		$cap = 'update_core';
	} elseif ( current_user_can( 'update_plugins' ) ) {
		$cap = 'update_plugins';
	} elseif ( current_user_can( 'update_themes' ) ) {
		$cap = 'update_themes';
	} else {
		$cap = 'update_languages';
	}

	/*
	$submenu['index.php'][10] = array(
		sprintf(
			'%1s %2s',
			__( 'Updates' ),
			"<span class='update-plugins count-{$update_data['counts']['total']}'><span class='update-count'>" . number_format_i18n( $update_data['counts']['total'] ) . "</span></span>"
		),
		$cap,
		'update-core.php'
	);

	unset( $cap );
	*/
}

// Separator before content management items.
/*
$menu[4] = [
	'',
	'read',
	'separator1',
	'',
	'wp-menu-separator'
];
*/

$menu[5] = [
	__( 'Media' ),
	'upload_files',
	'upload.php',
	'',
	'menu-top menu-icon-media',
	'menu-media',
	'dashicons-admin-media'
];

$submenu['upload.php'][5] = [
	__( 'Library' ),
	'upload_files',
	'upload.php'
];
/* translators: add new file */
$submenu['upload.php'][10] = [
	_x( 'Add New', 'file' ),
	'upload_files',
	'media-new.php'
];

$submenu['upload.php'][25] = [
	__( 'Settings' ),
	'manage_options',
	'options-media.php'
];

$i = 15;
foreach ( get_taxonomies_for_attachments( 'objects' ) as $tax ) {

	if ( ! $tax->show_ui || ! $tax->show_in_menu ) {
		continue;
	}

	$submenu['upload.php'][$i++] = [
		esc_attr( $tax->labels->menu_name ),
		$tax->cap->manage_terms,
		'edit-tags.php?taxonomy=' . $tax->name . '&amp;post_type=attachment'
	];
}
unset( $tax, $i );

/**
 * Posts
 *
 * The default post type uses $menu[10]
 */

/**
 * Discussion
 *
 * Uses $menu[11] to place below Posts.
 */

// Avoid the comment count query for users who cannot edit_posts.
if ( current_user_can( 'edit_posts' ) ) {

	$awaiting_mod = wp_count_comments();
	$awaiting_mod = $awaiting_mod->moderated;

	$menu[11] = [
		sprintf( __( 'Discussion %s' ), '<span class="awaiting-mod count-' . absint( $awaiting_mod ) . '"><span class="pending-count">' . number_format_i18n( $awaiting_mod ) . '</span></span>' ),
		'edit_posts',
		'edit-comments.php',
		'',
		'menu-top menu-icon-comments',
		'menu-comments',
		'dashicons-admin-comments',
	];

	unset( $awaiting_mod );
}

$submenu[ 'edit-comments.php' ][0] = [
	__( 'All Comments' ),
	'edit_posts',
	'edit-comments.php'
];

$submenu['edit-comments.php'][25] = [
	__( 'Settings' ),
	'manage_options',
	'options-discussion.php'
];

/**
 * Pages
 *
 * The `pages` post type uses $menu[20]
 */

// The index of the last top-level menu in the object menu group
$_wp_last_object_menu = 25;

$types = (array) get_post_types(
	[
		'show_ui'      => true,
		'_builtin'     => false,
		'show_in_menu' => true
	]
);

$builtin = [ 'post', 'page' ];

foreach ( array_merge( $builtin, $types ) as $ptype ) {

	$ptype_obj = get_post_type_object( $ptype );

	// Check if it should be a submenu.
	if ( $ptype_obj->show_in_menu !== true ) {
		continue;
	}

	// If we're to use $_wp_last_object_menu, increment it first.
	if ( is_int( $ptype_obj->menu_position ) ) {
		$ptype_menu_position = $ptype_obj->menu_position;
	} else {
		$ptype_menu_position = ++$_wp_last_object_menu;
	}

	$ptype_for_id = sanitize_html_class( $ptype );
	$menu_icon    = 'dashicons-admin-post';

	if ( is_string( $ptype_obj->menu_icon ) ) {

		// Special handling for data:image/svg+xml and Dashicons.
		if ( 0 === strpos( $ptype_obj->menu_icon, 'data:image/svg+xml;base64,' ) || 0 === strpos( $ptype_obj->menu_icon, 'dashicons-' ) ) {
			$menu_icon = $ptype_obj->menu_icon;
		} else {
			$menu_icon = esc_url( $ptype_obj->menu_icon );
		}

	} elseif ( in_array( $ptype, $builtin ) ) {
		$menu_icon = 'dashicons-admin-' . $ptype;
	}

	$menu_class = 'menu-top menu-icon-' . $ptype_for_id;

	// 'post' special case
	if ( 'post' === $ptype ) {
		$menu_class    .= ' open-if-no-js';
		$ptype_file     = "edit.php";
		$post_new_file  = "post-new.php";
		$edit_tags_file = "edit-tags.php?taxonomy=%s";
	} else {
		$ptype_file     = "edit.php?post_type=$ptype";
		$post_new_file  = "post-new.php?post_type=$ptype";
		$edit_tags_file = "edit-tags.php?taxonomy=%s&amp;post_type=$ptype";
	}

	if ( in_array( $ptype, $builtin ) ) {
		$ptype_menu_id = 'menu-' . $ptype_for_id . 's';
	} else {
		$ptype_menu_id = 'menu-posts-' . $ptype_for_id;
	}
	/*
	 * If $ptype_menu_position is already populated or will be populated
	 * by a hard-coded value below, increment the position.
	 */
	$core_menu_positions = [ 59, 60, 65, 70, 75, 80, 85, 99 ];

	while ( isset( $menu[$ptype_menu_position] ) || in_array( $ptype_menu_position, $core_menu_positions ) ) {
		$ptype_menu_position++;
	}

	$menu[$ptype_menu_position] = [
		esc_attr( $ptype_obj->labels->menu_name ),
		$ptype_obj->cap->edit_posts,
		$ptype_file,
		'',
		$menu_class,
		$ptype_menu_id,
		$menu_icon
	];

	$submenu[ $ptype_file ][5] = [
		$ptype_obj->labels->all_items,
		$ptype_obj->cap->edit_posts,
		$ptype_file
	];

	$submenu[ $ptype_file ][10] = [
		$ptype_obj->labels->add_new,
		$ptype_obj->cap->create_posts,
		$post_new_file
	];

	$i = 15;
	foreach ( get_taxonomies( [], 'objects' ) as $tax ) {

		if ( ! $tax->show_ui || ! $tax->show_in_menu || ! in_array( $ptype, (array) $tax->object_type, true ) ) {
			continue;
		}

		$submenu[ $ptype_file ][$i++] = array(
			esc_attr( $tax->labels->menu_name ),
			$tax->cap->manage_terms,
			sprintf( $edit_tags_file, $tax->name )
		);
	}
}
unset( $ptype, $ptype_obj, $ptype_for_id, $ptype_menu_position, $menu_icon, $i, $tax, $post_new_file );

/*
$menu[54] = [
	'',
	'read',
	'separator2',
	'',
	'wp-menu-separator'
];
*/

/*
$menu[55] = [
	__( 'Content' ),
	'edit_others_posts',
	'options-content.php',
	'',
	'menu-top menu-icon-appearance',
	'menu-appearance',
	'dashicons-welcome-write-blog'
];

$submenu['options-content.php'][5] = [
	__( 'Content Options' ),
	'edit_others_posts',
	'options-content.php'
];

$submenu['options-content.php'][10] = [
	__( 'Content Types' ),
	'edit_others_posts',
	'content-types.php'
];

$submenu['options-content.php'][15] = [
	__( 'Authors' ),
	'edit_others_posts',
	'authors.php'
];

$submenu['options-content.php'][20] = [
	__( 'Taxonomies' ),
	'edit_others_posts',
	'content-taxes.php'
];
*/

if ( current_user_can( 'switch_themes' ) ) {
	$appearance_cap = 'switch_themes';
} else {
	$appearance_cap = 'edit_theme_options';
}

$menu[60] = [
	__( 'Framework' ),
	$appearance_cap,
	'themes.php',
	'',
	'menu-top menu-icon-appearance',
	'menu-appearance',
	'dashicons-layout'
];

$submenu['themes.php'][3] = [
	__( 'Manage Themes' ),
	$appearance_cap,
	'themes.php'
];

$customize_url = add_query_arg(
	'return',
	urlencode(
		remove_query_arg(
			wp_removable_query_args(),
			wp_unslash( $_SERVER['REQUEST_URI'] )
		)
	),
	'customize.php'
);

$submenu['themes.php'][5] = [
	__( 'Live Manager' ),
	'customize',
	esc_url( $customize_url ),
	'',
	'hide-if-no-customize'
];

if ( current_theme_supports( 'menus' ) || current_theme_supports( 'widgets' ) ) {

	$submenu['themes.php'][15] = [
		__( 'Menus' ),
		'edit_theme_options',
		'nav-menus.php'
	];
}

/**
 * Additional framework submenu hook
 *
 * Requires the permission to install themes.
 *
 * Used by the WordPress Themes plugin to add a page link if
 * the plugin is installed & activated.
 *
 * @since 1.0.0
 */
if ( is_plugin_active( 'wp-themes/index.php' ) && current_user_can( 'install_themes' ) ) {
	do_action( 'framework_submenu_item' );
}

unset( $customize_url );
unset( $appearance_cap );

// Add 'Editor' to the bottom of the Appearance menu.
if ( ! is_multisite() ) {
	add_action( 'admin_menu', '_add_themes_utility_last', 101 );
}
/**
 * Adds the (theme) 'Editor' link to the bottom of the Appearance menu.
 *
 * @access private
 * @since 3.0.0
 */
function _add_themes_utility_last() {

	// Must use API on the admin_menu hook, direct modification is only possible on/before the _admin_menu hook.
	add_submenu_page(
		'themes.php',
		_x( 'Theme Editor', 'theme editor' ),
		_x( 'Theme Editor', 'theme editor' ),
		'edit_themes',
		'theme-editor.php'
	);
}

$count = '';

if ( ! is_multisite() && current_user_can( 'update_plugins' ) ) {

	if ( ! isset( $update_data ) ) {
		$update_data = wp_get_update_data();
	}

	$count = "<span class='update-plugins count-{$update_data['counts']['plugins']}'><span class='plugin-count'>" . number_format_i18n($update_data['counts']['plugins']) . "</span></span>";
}

$menu[65] = [
	sprintf(
		'%1s %2s',
		__( 'Extend' ),
		$count
	),
	'activate_plugins',
	'plugins.php',
	'',
	'menu-top menu-icon-plugins',
	'menu-plugins',
	'dashicons-admin-plugins'
];

$submenu['plugins.php'][5] = [
	__( 'Plugins' ),
	'activate_plugins',
	'plugins.php'
];

$submenu['plugins.php'][10] = [
	__( 'Extensions' ),
	'manage_options',
	'extensions.php'
];

if ( ! is_multisite() ) {

	$submenu['plugins.php'][15] = [
		_x( 'Plugin Editor', 'plugin editor' ),
		'edit_plugins',
		'plugin-editor.php'
	];
}

unset( $update_data );

if ( current_user_can('list_users') ) {

	$menu[70] = [
		sprintf(
			__( 'Accounts %s' ),
			$change_notice
		),
		'list_users',
		'users.php',
		'',
		'menu-top menu-icon-users',
		'menu-users',
		'dashicons-admin-users'
	];
} else {

	$menu[70] = [
		__( 'Your Account' ),
		'read',
		'profile.php',
		'',
		'menu-top menu-icon-users',
		'menu-users',
		'dashicons-admin-users'
	];
}

if ( current_user_can( 'list_users' ) ) {

	// Back-compat for plugins adding submenus to profile.php.
	$_wp_real_parent_file['profile.php'] = 'users.php';

	$submenu['users.php'][5] = [
		__( 'All Accounts' ),
		'list_users',
		'users.php'
	];

	$submenu['users.php'][10] = [
		__( 'Your Account' ),
		'read',
		'profile.php'
	];

	if ( current_user_can( 'create_users' ) ) {
		$submenu['users.php'][15] = [
			_x( 'Add Account', 'user' ),
			'create_users',
			'user-new.php'
		];
	} elseif ( is_multisite() ) {
		$submenu['users.php'][15] = [
			_x( 'Add Account', 'user' ),
			'promote_users',
			'user-new.php'
		];
	}

} else {
	$_wp_real_parent_file['users.php'] = 'profile.php';

	$submenu['profile.php'][5] = [
		__( 'Your Account' ),
		'read',
		'profile.php'
	];

	if ( current_user_can( 'create_users' ) ) {
		$submenu['profile.php'][10] = [
			__( 'Add New User' ),
			'create_users',
			'user-new.php'
		];

	} elseif ( is_multisite() ) {
		$submenu['profile.php'][10] = [
			__( 'Add New User' ),
			'promote_users',
			'user-new.php'
		];
	}
}

$submenu['users.php'][20] = [
	sprintf( __( 'Privacy %s' ),
	$change_notice ),
	'manage_privacy_options',
	'privacy.php'
];

/*
 * Tools entries
 *
$menu[75] = [
	__( 'Tools' ),
	'edit_posts',
	'tools.php',
	'',
	'menu-top menu-icon-tools',
	'menu-tools',
	'dashicons-admin-tools'
];*/

// Separator before Admin.
/*
$menu[79] = [
	'',
	'read',
	'separator3',
	'',
	'wp-menu-separator'
];
*/

/**
 * Administrative tools & settings
 */
$menu[80] = [
	__( 'Admin Tools' ),
	'manage_options',
	'options-general.php',
	'',
	'menu-top menu-icon-settings',
	'menu-settings',
	'dashicons-admin-tools'
];

$submenu['options-general.php'][10] = [
	_x( 'Settings', 'settings screen' ),
	'manage_options',
	'options-general.php'
];

$submenu['options-general.php'][15] = [
	__( 'Writing' ),
	'manage_options',
	'options-writing.php'
];

$submenu['options-general.php'][20] = [
	__( 'Reading' ),
	'manage_options',
	'options-reading.php'
];

$submenu['options-general.php'][40] = [
	__( 'Permalinks' ),
	'manage_options',
	'options-permalink.php'
];

$submenu['options-general.php'][60] = [
	__( 'Database & System' ),
	'manage_options',
	'manage-data.php'
];

if ( is_multisite() && ! is_main_site() ) {
	$submenu['options-general.php'][75] = [
		__( 'Delete Site' ),
		'delete_site',
		'ms-delete-site.php'
	];
}

if ( ! is_multisite() && defined( 'WP_ALLOW_MULTISITE' ) && WP_ALLOW_MULTISITE ) {
	$submenu['options-general.php'][80] = [
		__( 'Network Setup' ),
		'setup_network',
		'network.php'
	];
}

// The index of the last top-level menu in the utility menu group.
$_wp_last_utility_menu = 80;

// The final separator.
$menu[99] = [
	'',
	'read',
	'separator-last',
	'',
	'wp-menu-separator'
];

/**
 * Back-compat for old top-levels
 *
 * @todo Remove this.
 */
$_wp_real_parent_file['post.php']       = 'edit.php';
$_wp_real_parent_file['post-new.php']   = 'edit.php';
$_wp_real_parent_file['edit-pages.php'] = 'edit.php?post_type=page';
$_wp_real_parent_file['page-new.php']   = 'edit.php?post_type=page';
$_wp_real_parent_file['wpmu-admin.php'] = 'tools.php';
$_wp_real_parent_file['ms-admin.php']   = 'tools.php';

/**
 * Ensure backward compatibility
 *
 * @todo Remove this.
 */
$compat = [
	'index'           => 'dashboard',
	'edit'            => 'posts',
	'post'            => 'posts',
	'upload'          => 'media',
	'edit-pages'      => 'pages',
	'page'            => 'pages',
	'edit-comments'   => 'comments',
	'options-general' => 'settings',
	'themes'          => 'appearance',
];

require_once( ABSPATH . 'wp-admin/includes/menu.php' );