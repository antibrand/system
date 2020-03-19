<?php
/**
 * Theme deactivation
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
class AB_Theme_Deactivate {

    /**
	 * Constructor magic method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		add_action( 'switch_theme', [ $this, 'deactivate' ] );

	}

    /**
	 * Function to be fired when theme is deactivated
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 *
	 * @link   https://codex.wordpress.org/Function_Reference/remove_theme_mods
	 */
    public function deactivate() {

        // update_option( 'fresh_site', 0 );

    }
}

// Run the class.
new AB_Theme_Deactivate;