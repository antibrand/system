<?php
/**
 * Upgrade API: WP_Upgrader class
 *
 * Requires skin classes and WP_Upgrader subclasses for backward compatibility.
 *
 * @package App_Package
 * @subpackage Upgrader
 * @since Previous 2.8.0
 */

/** Plugin_Upgrader_Skin class */
require_once( APP_INC_PATH . '/classes/backend/class-plugin-upgrader-skin.php' );

/** Bulk_Plugin_Upgrader_Skin class */
require_once( APP_INC_PATH . '/classes/backend/class-bulk-plugin-upgrader-skin.php' );

/** Plugin_Installer_Skin class */
require_once( APP_INC_PATH . '/classes/backend/class-plugin-installer-skin.php' );

/** Language_Pack_Upgrader_Skin class */
require_once( ABSPATH . 'wp-admin/includes/class-language-pack-upgrader-skin.php' );

/** WP_Ajax_Upgrader_Skin class */
require_once( ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php' );

/** Language_Pack_Upgrader class */
require_once( ABSPATH . 'wp-admin/includes/class-language-pack-upgrader.php' );

/** Core_Upgrader class */
require_once( APP_INC_PATH . '/classes/backend/class-core-upgrader.php' );

/** WP_Automatic_Updater class */
require_once( ABSPATH . 'wp-admin/includes/class-wp-automatic-updater.php' );
