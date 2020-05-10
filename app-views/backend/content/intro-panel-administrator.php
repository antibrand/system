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
 * Welcome panel description
 *
 * Uses the white label tagline if available.
 */
if ( defined( 'APP_TAGLINE' ) ) {
	$description = APP_TAGLINE;
} else {
	$description = __( 'Following are some links to help manage your website:' );
}

?>
<div class="welcome-panel-content">

	<h2>
	<?php echo sprintf(
		'%1s %2s %3s',
		__( 'Welcome to' ),
		APP_NAME,
		$version
	); ?>
	</h2>
	<p class="description welcome-description"><?php echo $description; ?></p>

</div>