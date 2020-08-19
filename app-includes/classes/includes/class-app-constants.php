<?php
/**
 * App constamts class
 *
 * Methods to define constants.
 *
 * @package App_Package
 * @subpackage Includes
 * @since 1.0.0
 */

namespace AppNamespace\Includes;

// Get the system environment constants from the system root directory.
if ( file_exists( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/app-environment.php' ) ) {
	require_once( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/app-environment.php' );
}

class App_Constants {


}