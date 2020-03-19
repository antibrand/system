<?php
/**
 * Help tab: Usage
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
<h4><?php _e( 'Using the Data Export Tool' ); ?></h4>

<p>
	<?php _e( '' ); ?>
	<?php _e( '' ); ?>
</p>

<ol>
	<li>
		<?php _e( 'Specify the username or email address for the account then click Send Request. If the confirmation request initiated successfully the request will be shown in below list and the status will show that it\'s pending.' ); ?>
		<?php esc_html_e( 'The user will receive an mail with subject ‘[<site_name>] Confirm Action: Extract Personal Data’.' ); ?>
	</li>
	<li><?php _e( 'If user click link in the mail the status of request will be changed to Confirmed, and Next Steps shows Email Data button.' ); ?></li>
	<li>
		<?php _e( 'Click Email Data. Next Steps will be changed to Email Sent. ' ); ?>
		<?php _e( 'Link is available only 48 hours.' ); ?>
	</li>
	<li>
		<?php _e( 'If user click the link, .zip file will be downloaded that includes one index.html file. For detail format, refer below section Exported Personal Data.' ); ?>
		<?php _e( 'At the same time, the status of request will be changed to Completed, and Next Steps displays Remove request button.' ); ?>
	</li>
	<li>
		<?php _e( 'Click Remove request. The request will be removed.' ); ?>
		<?php _e( 'Note that there is no ‘Trash’ status such as for posts, so removal is permanent.' ); ?>
	</li>
</ol>