<?php
/**
 * Footer for default die handler
 *
 * @package App_Package
 * @subpackage Administration
 *
 * @see app-includes/functions.php
 */

/**
 * Die footer message
 *
 * Looks for a user-defined name for the
 * website management system.
 *
 * @since 1.0.0
 *
 * @see id-config.sample.php in the root directory.
 */
if ( defined( 'APP_NAME' ) && APP_NAME ) {
	$message = sprintf(
		'<p>%1s %2s</p>',
		__( 'This is the error and warning handler for' ),
		APP_NAME
	);
} else {
	$message = sprintf(
		'<p>%1s</p>',
		__( 'This is the error and warning handler for the website management system.' )
	);
}

// Apply a filter to the message.
$message = apply_filters( 'app_die_handler_footer', $message );

?>
<footer id="colophon" class="app-footer">
	<div class="footer-content">
		<?php echo $message; ?>
	</div>
</footer>