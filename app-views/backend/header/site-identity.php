<?php
/**
 * Admin screen site identity header
 *
 * @package App_Package
 * @subpackage Administration
 */

?>
<header class="site-header backend-site-header">
	<p class="site-title backend-site-title">
		<a href="<?php echo esc_url( site_url() ); ?>"><?php echo get_bloginfo( 'name' ); ?></a>
	</p>
	<p class="site-description backend-site-description"><?php echo get_bloginfo( 'description' ); ?></p>
</header>