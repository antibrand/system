<?php
/**
 * Class autoloader
 *
 * @package App_Package
 * @subpackage Administration
 */

declare(strict_types=1);

/*
 * A map of classes to files to be used when determining which file to load
 * when a class needs to be loaded.
 *
 * The index is the fully qualified class name which includes the namespace,
 * the value is the path to the relevant file.
 */
const MAP = [
	'AppNamespace/Dashboard' => __DIR__ . '/classes/class-dashboard.php',
];

spl_autoload_register(
	function ( string $classname ) {
		if ( isset( MAP[ $classname ] ) ) {
			require MAP[ $classname ];
		}
	}
);