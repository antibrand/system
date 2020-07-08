<?php
/**
 * Footer for login and logout pages
 *
 * @package App_Package
 * @subpackage Administration
 */

/**
 * Login/logout footer message
 *
 * Looks for a user-defined name for the
 * website management system.
 *
 * @since 1.0.0
 *
 * @see id-config.sample.php in the root directory.
 */
if ( '' ) {
	$message = sprintf(
		'<p>%1s %2s</p>',
		__( '' ),
		''
	);
} else {
	$message = sprintf(
		'<p>%1s</p>',
		__( '' )
	);
}

?>
<footer id="colophon" class="app-footer">
	<div class="footer-content">
		<?php echo $message; ?>
	</div>
</footer>