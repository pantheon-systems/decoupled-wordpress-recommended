<?php
/**
 * Set root path
 */
$rootPath = realpath( __DIR__ . '/..' );

/**
 * Include the Composer autoload
 */
require_once( $rootPath . '/vendor/autoload.php' );

/**
 * Pantheon platform settings.
 *
 * IMPORTANT NOTE:
 * Do not modify this file. This file is maintained by Pantheon.
 *
 * Site-specific modifications belong in wp-config.php, not this file. This
 * file may change in future releases and modifications would cause conflicts
 * when attempting to apply upstream updates.
 */

// ** MySQL settings - included in the Pantheon Environment ** //

/** MySQL hostname; on Pantheon this includes a specific port number. */
putenv('DB_HOST=' . $_ENV['DB_HOST'] . ':' . $_ENV['DB_PORT']);

/** Database Charset to use in creating database tables. */
putenv('DB_CHARSET=utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
putenv('DB_COLLATE=');

/** A couple extra tweaks to help things run well on Pantheon. **/
if ( isset( $_SERVER['HTTP_HOST'] ) ) {
    // HTTP is still the default scheme for now.
    $scheme = 'http';
    // If we have detected that the end use is HTTPS, make sure we pass that
    // through here, so <img> tags and the like don't generate mixed-mode
    // content warnings.
    if ( isset( $_SERVER['HTTP_USER_AGENT_HTTPS'] ) && $_SERVER['HTTP_USER_AGENT_HTTPS'] == 'ON' ) {
        $scheme = 'https';
    }
    putenv('WP_HOME=' . $scheme . '://' . $_SERVER['HTTP_HOST']);
    putenv('WP_SITEURL=' . $scheme . '://' . $_SERVER['HTTP_HOST'] . '/wp');
}

// Don't show deprecations; useful under PHP 5.5
error_reporting(E_ALL ^ E_DEPRECATED);
/** Define appropriate location for default tmp directory on Pantheon */
define('WP_TEMP_DIR', $_SERVER['HOME'] .'/tmp');

// FS writes aren't permitted in test or live, so we should let WordPress know to
// disable relevant UI.
if (in_array($_ENV['PANTHEON_ENVIRONMENT'], array( 'test', 'live' ) ) ) {
    if ( ! defined('DISALLOW_FILE_MODS') ) {
        define( 'DISALLOW_FILE_MODS', true );
    }
    if ( ! defined('DISALLOW_FILE_EDIT') ) {
        define( 'DISALLOW_FILE_EDIT', true );
    }
}

/**
 * Set WP_ENVIRONMENT_TYPE according to the Pantheon Environment
 */
if (getenv('WP_ENVIRONMENT_TYPE') === false) {
    switch ($_ENV['PANTHEON_ENVIRONMENT']) {
        case 'live':
            putenv('WP_ENVIRONMENT_TYPE=production');
            break;
        case 'test':
            putenv('WP_ENVIRONMENT_TYPE=staging');
            break;
        default:
            putenv('WP_ENVIRONMENT_TYPE=development');
            break;
    }
}

/**
 * Force SSL
 */
if ( ! defined('FORCE_SSL_ADMIN') ) {
    define( 'FORCE_SSL_ADMIN', true );
}