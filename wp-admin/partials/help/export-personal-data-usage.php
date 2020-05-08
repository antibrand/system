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

$help = sprintf(
	'<h3>%1s</h3>',
	__( 'Using the Data Export Tool' )
);

$help .= sprintf(
	'<p>%1s</p>',
	__( '' )
);

$help .= '<ol>';

$help .= sprintf(
	'<li>%1s <br />%2s</li>',
	__( 'Specify the username or email address for the account then click Send Request. If the confirmation request initiated successfully the request will be shown in below list and the status will show that it\'s pending.' ),
	__( 'The user will receive an mail with subject ‘[<site_name>] Confirm Action: Extract Personal Data’.' )
);

$help .= sprintf(
	'<li>%1s</li>',
	__( 'If user click link in the mail the status of request will be changed to Confirmed, and Next Steps shows Email Data button.' )
);

$help .= sprintf(
	'<li>%1s <br />%2s</li>',
	__( 'Click Email Data. Next Steps will be changed to Email Sent.' ),
	__( 'Link is available only 48 hours.' )
);

$help .= sprintf(
	'<li>%1s <br />%2s</li>',
	__( 'If user click the link, .zip file will be downloaded that includes one index.html file. For detail format, refer below section Exported Personal Data.' ),
	__( 'At the same time, the status of request will be changed to Completed, and Next Steps displays Remove request button.' )
);

$help .= sprintf(
	'<li>%1s <br />%2s</li>',
	__( 'Click Remove request. The request will be removed.' ),
	__( 'Note that there is no ‘Trash’ status such as for posts, so removal is permanent.' )
);

$help .= '</ol>';

echo $help;