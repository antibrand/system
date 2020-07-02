<?php
/**
 * User profile administration screen
 *
 * @package App_Package
 * @subpackage Administration
 */

/**
 * Define this as a profile page
 *
 * @since Before 2.5.0
 * @var   bool
 */
define( 'IS_PROFILE_PAGE', true );

// Load user edit page.
require_once( dirname( __FILE__ ) . '/user-edit.php' );