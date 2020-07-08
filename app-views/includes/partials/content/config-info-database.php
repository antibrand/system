<?php
/**
 * Database information for the config page
 *
 * @package App_Package
 * @subpackage Administration
 */

?>
<section id="config-info-database" class="form-step">
	<h2><?php _e( 'Database Information' ); ?></h2>

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

	<p class="step hide-if-no-js">
		<a href="#config-intro" class="button prev">
			<?php _e( 'Previous' ); ?>
		</a>
		<a href="#config-info-identity" class="button next">
			<?php _e( 'Next' ); ?>
		</a>
	</p>
</section>
