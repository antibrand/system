<?php
/**
 * Help tab: Download
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
	__( 'Downloading Personal Data' )
);

$help .= sprintf(
	'<p>%1s</p>',
	__( '' )
);

echo $help;