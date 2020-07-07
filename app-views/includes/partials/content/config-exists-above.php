<?php
/**
 * Configuration file found above the root directory
 *
 * @package App_Package
 * @subpackage Administration
 */

?>
<div class="setup-install-wrap">

	<main class="config-content">

		<h2><?php _e( 'Configuration File Found' ); ?></h2>

		<?php echo sprintf(
			'<p>%1s <code>%2s</code> %3s</p>',
			__( 'The file' ),
			'app-config.php',
			__( 'already exists one directory level above the root directory of the website management system.' )
		); ?>

		<p><?php _e( 'If you need to reset any of the configuration items in this file you can use a text editor to rewrite the configuration details, or delete the file the refresh this page to use the configuration form.' ); ?></p>

		<?php echo sprintf(
			'<p class="step"><a href="%1s" class="button next">%2s</a></p>',
			esc_url( 'install.php' ),
			__( 'Install Now' )
		); ?>

	</main>
</div>
