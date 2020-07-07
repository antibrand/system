<?php
/**
 * Successful database connection message for the config page
 *
 * @package App_Package
 * @subpackage Administration
 */

?>
<div class="setup-install-wrap setup-install-connection-success">

	<main class="config-content">

		<h2><?php _e( 'Successful Database Connection' ); ?></h2>

		<p><?php _e( 'The website management system can now communicate with the database and you can finish the installation.' ); ?></p>

		<p class="step">
			<a href="<?php echo $install; ?>" class="button button-large next"><?php _e( 'Run the installation' ); ?></a>
		</p>

	</main>
</div>
