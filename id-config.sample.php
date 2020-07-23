<?php
/**
 * Identity configuration
 *
 * To define your own identity for the website management system
 * copy or rename the `id-config.sample.php` file as `id-config.php`
 * and include in the root directory.
 *
 * Support is included for `id-config.php` to be included
 * one directory above the website management system root.
 *
 * If using this system as your own for further development then
 * change the identity defaults in the `id-config.sample.php` file.
 *
 * @see `app-includes/default-constants.php` for fallback definitions.
 *
 * @package App_Package
 */

/**
 * Identity in code
 *
 * Following is a list of strings to find and replace in files.
 *
 * 1. Package
 *
 *    The `@package` tag is used to categorize structural elements into
 *    logical subdivisions.
 *
 *    Find `App_Package` and replace with something unique to your system,
 *    include underscores between words.
 *
 * 2. Namespace
 *
 *    Namespaces encapsulate code and help solve naming conflicts.
 *
 *    Find `AppNamespace` and replace with something unique to your system,
 *    underscores between words is acceptable.
 *
 *    The namespace name PHP and compound names starting with this name
 *    are reserved for internal language use and should not be used.
 *
 * 3. Description
 *
 *    This software is typically described in file documentation and in
 *    display text as a website management system. You may want to find
 *    `website management system` and `system` to
 *    replace with your own description.
 */

// Define a name of the website management system.
define( 'APP_NAME', 'system' );

// Define a tagline of the website management system.
define( 'APP_TAGLINE', 'generic, white-label website management' );

// Define a URL for the website management system.
define( 'APP_WEBSITE', '' );

// Define a logo or icon path for the website management system.
define( 'APP_IMAGE', dirname( dirname( $_SERVER['PHP_SELF'] ) ) . '/app-assets/images/app-icon.jpg' );

/**
 * Identity colors
 *
 * Used for configurataion and installation pages.
 *
 * This is implemented in a style block so use CSS values:
 * name, hex, rgb, rgba, hsl, hsla, or inherit.
 *
 * @todo Use for a custom admin color scheme,
 *       available for front end use as well.
 */
define( 'APP_COLOR', 'inherit' );
define( 'APP_DARK_COLOR', 'white' );
define( 'APP_BG_COLOR', 'white' );
define( 'APP_DARK_BG_COLOR', '#222222' );
define( 'APP_PRIMARY_COLOR', '#ffee00' );
define( 'APP_SECONDARY_COLOR', '#3ad4fb' );