<?php
/**
 * Upgrader API: Bulk_Plugin_Upgrader_Skin class
 *
 * @package App_Package
 * @subpackage Upgrader
 * @since Previous 4.6.0
 */

/**
 * Bulk Theme Upgrader Skin for Theme Upgrades.
 *
 * @see Bulk_Upgrader_Skin
 *
 * @since Previous 3.0.0
 */
class Bulk_Theme_Upgrader_Skin extends Bulk_Upgrader_Skin {

	/**
	 * Prepare theme info
	 *
	 * Theme_Upgrader::bulk() will fill this in.
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @var    array
	 */
	public $theme_info = [];

	/**
	 * Add strings
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @return void
	 */
	public function add_strings() {

		parent::add_strings();

		$this->upgrader->strings['skin_before_update_header'] = __( 'Updating Theme %1$s (%2$d/%3$d)' );
	}

	/**
	 * Before
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @param  string $title
	 */
	public function before( $title = '' ) {
		parent::before( $this->theme_info->display( 'Name' ) );
	}

	/**
	 * After
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @param  string $title
	 */
	public function after( $title = '' ) {
		parent::after( $this->theme_info->display( 'Name' ) );
		$this->decrement_update_count( 'theme' );
	}

	/**
	 * Bulk footer
	 * 
	 * @since  Previous 3.0.0
	 * @access public
	 * @return mixed[]
	 */
	public function bulk_footer() {

		parent::bulk_footer();

		$update_actions = [
			'themes_page' => '<a href="' . self_admin_url( 'themes.php' ) . '" target="_parent">' . __( 'Return to Themes page' ) . '</a>',
			'updates_page' => '<a href="' . self_admin_url( 'update-core.php' ) . '" target="_parent">' . __( 'Return to Updates page' ) . '</a>'
		];

		if ( ! current_user_can( 'switch_themes' ) && ! current_user_can( 'edit_theme_options' ) ) {
			unset( $update_actions['themes_page'] );
		}

		/**
		 * Filters the list of action links available following bulk theme updates.
		 *
		 * @since Previous 3.0.0
		 * @param array $update_actions Array of theme action links.
		 * @param array $theme_info     Array of information for the last-updated theme.
		 */
		$update_actions = apply_filters( 'update_bulk_theme_complete_actions', $update_actions, $this->theme_info );

		if ( ! empty( $update_actions ) ) {
			$this->feedback( implode( ' | ', ( array ) $update_actions ) );
		}
	}
}
