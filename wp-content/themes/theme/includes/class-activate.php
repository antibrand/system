<?php
/**
 * Theme activation
 *
 * Do not namespace this file.
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 */

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme deactivation class
 *
 * @since  1.0.0
 * @access public
 */
class AB_Theme_Activate {

    /**
	 * Constructor magic method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		add_action( 'after_switch_theme', [ $this, 'activate' ] );

	}

    /**
	 * Function to be fired when theme is activated
	 *
	 * Default actions provided here are samples of how to set theme mods,
	 * add starter content, and redirect the user after activation.
	 *
	 * Remove or modify these as needed.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 *
	 * @link   https://developer.wordpress.org/reference/functions/set_theme_mod/
	 */
    public function activate() {

		// Start with a fresh site for starter content.
		add_option( 'fresh_site', 1 );
		update_option( 'fresh_site', 1 );

		// Delete the starter comment.
		if ( 0 != get_option( 'fresh_site' ) ) {
			wp_delete_comment( 1 );
		}


		/**
		 * Sample action: redirect to the Customizer on theme activation.
		 */
		global $pagenow;

		if ( 'themes.php' == $pagenow && is_admin() && isset( $_GET['activated'] ) ) {

			// URL returns to Dashboard on closing the Customizer.
			wp_redirect( admin_url( 'customize.php' ) . '?return=' . admin_url() );
		}

    }
}

// Run the class.
new AB_Theme_Activate;