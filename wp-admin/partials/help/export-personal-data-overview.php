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

$help = sprintf(
	'<h3>%1s</h3>',
	__( 'Overview of the Data Export Tool' )
);

$help .= sprintf(
	'<p>%1s <br />%2s</p>',
	__( 'The export personal data tool generates a file in .zip format containing the personal data which exists for a user account.' ),
	__( 'The tool uses email validation to send a user’s request to an administrator.' )
);

$help .= sprintf(
	'<p>%1s <br />%2s</p>',
	__( 'This tool only gathers data from the website management system and participating plugins.' ),
	__( 'Other efforts may be necessary to fulfill export requests.' )
);

$help .= sprintf(
	'<p>%1s</p>',
	__( 'The following information is exported as personal data:' )
);

$help .= '<ul>';

$help .= sprintf(
	'<li>%1s</li>',
	__( 'About the Website' )
);

$help .= sprintf(
	'<li>%1s</li>',
	__( 'User Information' )
);

$help .= sprintf(
	'<li>%1s</li>',
	__( 'Comments' )
);

$help .= sprintf(
	'<li>%1s</li>',
	__( 'Media' )
);

$help .= '</ul>';

echo $help;