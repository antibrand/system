<?php
/**
 * Dashboard intro panel: Administrator
 *
 * @package App_Package
 * @subpackage Administration
 */

// App version.
$version = get_bloginfo( 'version' );

/**
 * Intro panel description
 *
 * Uses the white label tagline if available.
 */
if ( defined( 'APP_TAGLINE' ) ) {
	$description = APP_TAGLINE;
} else {
	$description = __( 'Following are some links to help manage your website:' );
}

$current_user = wp_get_current_user();
$user_data    = get_userdata( $current_user );
// $user_name    = $user_data->display_name;

?>
<div class="intro-panel-content"></div>