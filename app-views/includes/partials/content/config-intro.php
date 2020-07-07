<?php
/**
 * Introduction for the config page
 *
 * @package App_Package
 * @subpackage Administration
 */

?>
<section id="config-intro" class="form-step">

	<h2><?php _e( 'Configuration & Installation' ); ?></h2>

	<p><?php _e( 'This is the configuration and installation process for the white-label website management system. The process should only take a few minutes to complete provided that you have some preliminary information at the ready.' ); ?></p>

	<p><?php _e( 'The website management system utilizes the PHP language to communicate with a database. The rquired version of PHP running on your server is 7.0 or greater. The database management system on your server must be MySQL version 5.6 or MariaDB version 10.1, or greater versions of either.' ); ?></p>

	<p><?php _e( 'For the configuration steps you will be asked to provide credentials to communicate with the database, which are required for this system to work. Optional information applies to the white-label identity of the system.' ); ?></p>

	<p><?php _e( 'For installation you will be required to enter a name for the new website, a user name and password, and an email address.' ); ?></p>

	<p><?php _e( 'All of this can be changed at any time from within the administration pages or by modifying the configuration files.' ); ?></p>

	<p class="step hide-if-no-js">
		<a href="#config-info-database" class="button next">
			<?php _e( 'Begin' ); ?>
		</a>
	</p>

</section>
