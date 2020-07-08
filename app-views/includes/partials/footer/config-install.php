<?php
/**
 * Footer for config and install pages
 *
 * @package App_Package
 * @subpackage Administration
 */

/**
 * Config & install footer message
 *
 * Looks for a user-defined name for the
 * website management system.
 *
 * @since 1.0.0
 *
 * @see id-config.sample.php in the root directory.
 */
if ( defined( 'APP_NAME' && APP_NAME ) ) {
	$message = sprintf(
		'<p>%1s %2s</p>',
		__( 'This is the configuration and installation process for' ),
		APP_NAME
	);
} else {
	$message = sprintf(
		'<p>%1s</p>',
		__( 'This is the configuration and installation process for the website management system.' )
	);
}

?>
<footer id="colophon" class="app-footer">
	<div class="footer-content">
		<?php echo $message; ?>
	</div>
</footer>