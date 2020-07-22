<?php
/**
 * Theme options page output
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 */

namespace AB_Theme\Includes\Options;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Page title.
$title = sprintf(
	'<h1>%1s %2s</h1>',
	get_bloginfo( 'name' ),
	__( 'Display Options', 'antibrand' )
);

// Page description.
$description = sprintf(
	'<p class="description">%1s</p>',
	__( 'This is a starter/example page. Use it or remove it.', 'antibrand' )
);

// Begin page output.
?>

<div class="wrap antibrand-options-page">
	<?php echo $title; ?>
	<?php echo $description; ?>
	<hr />
</div><!-- End .wrap -->