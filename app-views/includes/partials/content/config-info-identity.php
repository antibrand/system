<?php
/**
 * Identity information for the config page
 *
 * @package App_Package
 * @subpackage Administration
 */

?>
<section id="config-info-identity" class="form-step">
	<h2><?php _e( 'Identity Information' ); ?></h2>

	<p><?php _e( 'You have the option to give this website management system an identity of your own. This can be changed after installation but before installing you may want to consider a name for the system:' ); ?></p>

	<ul id="config-identity-info-list">
		<li><?php _e( 'A name for the system' ); ?></li>
		<li><?php _e( 'A tagline or description' ); ?></li>
		<li><?php _e( 'An image that represents the name or identity' ); ?></li>
		<li><?php _e( 'Any website associated with the system' ); ?></li>
	</ul>

	<p class="step hide-if-no-js">
		<a href="#config-info-database" class="button prev">
			<?php _e( 'Previous' ); ?>
		</a>
		<a href="#config-database" class="button next">
			<?php _e( 'Next' ); ?>
		</a>
	</p>
</section>
