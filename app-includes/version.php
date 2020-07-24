<?php
/**
 * The version of this system
 *
 * @global string $app_version
 */
$app_version = '1.0.0';

/**
 * The compatability version checked by plugins & themes
 *
 * This is may be defined in the app-config.php file.
 * Fallback is the version from whence this was derived.
 *
 * @global string $wp_version
 */
if ( defined( 'COMPAT_VERSION' ) && COMPAT_VERSION ) {
	$wp_version = COMPAT_VERSION;
} else {
	$wp_version = '4.9.8';
}

/**
 * Holds the DB revision, increments when changes are made to the DB schema.
 *
 * @global int $wp_db_version
 */
$wp_db_version = 38590;

/**
 * Holds the TinyMCE version
 *
 * @global string $tinymce_version
 */
$tinymce_version = '4800-20180716';

/**
 * Holds the required PHP version
 *
 * @global string $required_php_version
 */
$required_php_version = '7.0';

/**
 * Holds the required MySQL version
 *
 * @global string $required_mysql_version
 */
$required_mysql_version = '5.0';