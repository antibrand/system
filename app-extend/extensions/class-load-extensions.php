<?php
/**
 * Load system extensions
 *
 * Plugin name starts with an HTML bullet to
 * put it at the top of the list. Another
 * charabter ought to do the same.
 *
 * @package App_Package
 * @subpackage Includes\Extend
 * @since 1.0.0
 *
 * Plugin Name: Extensions Loader
 * Description: Loads extensions that have the core file in their own subdirectory of the extensions directory.
 * Plugin URI: https://antibrand.dev/extend
 * Author: antibrand
 * Author URI: https://antibrand.dev/
 * Tags: Essential, No Interface
 */

namespace AppNamespace\Includes\Extend;

/**
 * Load system extensions
 *
 * @since  1.0.0
 * @access public
 */
final class Load_Extensions {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Require extension files.
		$this->extensions();

	}

	/**
	 * Require extension files
	 *
	 * Follow the path to the file with the plugin header,
	 * typically a file named for the extension or
	 * an index file.
	 *
	 * It is recommended to check if the extension has been
	 * added using the `file_exists()` function.
	 *
	 * Example:
	 * ```
	 * if ( file_exists( APP_EXTENSIONS_PATH . '/example/example.php' ) ) {
	 * 		require_once( APP_EXTENSIONS_PATH . '/example/example.php' );
	 * }
	 * ```
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function extensions() {

		/**
		 * alias
		 *
		 * Deprecated PHP constants, classes, methods, and functions.
		 */
		if ( file_exists( APP_EXTENSIONS_PATH . '/alias/alias.php' ) ) {
			require_once( APP_EXTENSIONS_PATH . '/alias/alias.php' );
		}

	}
}

// Instantiate the class.
$load_extensions = new Load_Extensions;
