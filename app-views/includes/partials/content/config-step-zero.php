<?php
/**
 * Step zero (beginning) for the config page
 *
 * @package App_Package
 * @subpackage Administration
 */

?>
<div class="setup-install-wrap setup-install-introduction">
	<main class="config-content">

		<h2><?php _e( 'Setup Introduction' ); ?></h2>

		<p><?php _e( 'For the installation process you will need to know the following information.' ); ?></p>
		<ol id="config-database-info-list">
			<li><?php _e( 'Database name' ); ?></li>
			<li><?php _e( 'Database username' ); ?></li>
			<li><?php _e( 'Database password' ); ?></li>
			<li><?php _e( 'Database host' ); ?></li>
		</ol>
		<p><?php _e( 'If you don&#8217;t have this information then you will need to contact your web host.' ); ?></p>
		<p><?php
			printf( __( 'This information is needed to create a configuration file. If for any reason this automatic file creation doesn&#8217;t work then find the %1$s file in the root directory, open it in a text editor, fill in your information, and save it as %2$s in the same directory.' ),
				'<code>app-config.sample.php</code>',
				'<code>app-config.php</code>'
			);
		?></p>

		<h2><?php _e( 'Optional Information' ); ?></h2>

		<p><?php _e( 'You have the option to give this website management system an identity of your own. This can be changed after installation but before installing you may want to consider a name for the system:' ); ?></p>

		<ul id="config-identity-info-list">
			<li><?php _e( 'A name for the system' ); ?></li>
			<li><?php _e( 'A tagline or description' ); ?></li>
			<li><?php _e( 'An image that represents the name or identity' ); ?></li>
			<li><?php _e( 'Any website associated with the system' ); ?></li>
		</ul>

		<p class="step"><a href="<?php echo $step_1; ?>" class="button button-large"><?php _e( 'Begin Installation' ); ?></a></p>
	</main>
</div>
