<?php
/**
 * Upgrader API: Bulk_Plugin_Upgrader_Skin class
 *
 * @package App_Package
 * @subpackage Upgrader
 * @since 4.6.0
 */

/**
 * Bulk Plugin Upgrader Skin for Plugin Upgrades.
 *
 * @since 3.0.0
 *
 * @see Bulk_Upgrader_Skin
 */
class Bulk_Plugin_Upgrader_Skin extends Bulk_Upgrader_Skin {
	public $plugin_info = array(); // Plugin_Upgrader::bulk() will fill this in.

	public function add_strings() {
		parent::add_strings();
		$this->upgrader->strings['skin_before_update_header'] = __('Updating Plugin %1$s (%2$d/%3$d)');
	}

	/**
	 *
	 * @param string $title
	 */
	public function before($title = '') {
		parent::before($this->plugin_info['Title']);
	}

	/**
	 *
	 * @param string $title
	 */
	public function after($title = '') {
		parent::after($this->plugin_info['Title']);
		$this->decrement_update_count( 'plugin' );
	}

	/**
	 */
	public function bulk_footer() {
		parent::bulk_footer();
		$update_actions =  array(
			'plugins_page' => '<a href="' . self_admin_url( 'plugins.php' ) . '" target="_parent">' . __( 'Return to Manage Plugins' ) . '</a>',
			'updates_page' => '<a href="' . self_admin_url( 'update-core.php' ) . '" target="_parent">' . __( 'Return to Updates' ) . '</a>'
		);
		if ( ! current_user_can( 'activate_plugins' ) )
			unset( $update_actions['plugins_page'] );

		/**
		 * Filters the list of action links available following bulk plugin updates.
		 *
		 * @since 3.0.0
		 *
		 * @param array $update_actions Array of plugin action links.
		 * @param array $plugin_info    Array of information for the last-updated plugin.
		 */
		$update_actions = apply_filters( 'update_bulk_plugins_complete_actions', $update_actions, $this->plugin_info );

		if ( ! empty($update_actions) )
			$this->feedback(implode(' | ', (array)$update_actions));
	}
}
