<?php
/**
 * Help tab: Overview
 *
 * For the export personal data page.
 *
 * @package App_Package
 * @subpackage Administration
 */

// Stop if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Not allowed.' );
}

?>
<h4><?php _e( 'Overview of the Data Export Tool' ); ?></h4>
<p>
	<?php _e( 'The export personal data tool generates a file in .zip format containing the personal data which exists for a user account.' ); ?>
	<?php _e( 'The tool uses email validation to send a user’s request to an administrator.' ); ?>
</p>
<p>
	<?php _e( 'This tool only gathers data from the website management system and participating plugins.' ); ?>
	<?php _e( 'Other efforts may be necessary to fulfill export requests.' ); ?>
</p>
<p>
	<?php _e( 'The following information is exported as personal data:' ); ?>
</p>
<ul>
	<li><?php _e( 'About the Website' ); ?></li>
	<li><?php _e( 'User Information' ); ?></li>
	<li><?php _e( 'Comments' ); ?></li>
	<li><?php _e( 'Media' ); ?></li>
</ul>