<?php
/**
 * Installation already exists
 *
 * @package App_Package
 * @subpackage Administration
 */

?>

<div class="setup-install-wrap">

	<main class="config-content">

	<h2><?php _e( 'Already Installed' ); ?></h2>

	<p><?php _e( 'You appear to have already installed the website management system. To reinstall please clear your old database tables first.' ); ?></p>

	<?php echo sprintf(
		'<p class="step"><a href="%1s" class="button button-large">%2s</a></p>',
		esc_url( wp_login_url() ),
		__( 'Log In' )
	); ?>

	</main>
</div>
