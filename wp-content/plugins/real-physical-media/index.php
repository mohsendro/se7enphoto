<?php
/**
 * Main file for WordPress.
 *
 * @wordpress-plugin
 * Plugin Name: 	Real Physical Media
 * Plugin URI:		https://devowl.io
 * Description: 	Reflect the folder structure of your Real Media Library in your file system.
 * Author:          20script
 * Author URI:		https://www.20script.ir
 * Version: 		1.3.12
 * Text Domain:		real-physical-media
 * Domain Path:		/languages
 */

require_once dirname(__FILE__) . '/feed.class.php';

defined('ABSPATH') or die('No script kiddies please!'); // Avoid direct file request

update_site_option( 'wpls_license_real-physical-media', 'activated' );
update_site_option( 'wpls_activation_id_real-physical-media', 'activated' );

/**
 * Plugin constants. This file is procedural coding style for initialization of
 * the plugin core and definition of plugin configuration.
 */
if (defined('RPM_PATH')) {
    return;
}
define('RPM_FILE', __FILE__);
define('RPM_PATH', dirname(RPM_FILE));
define('RPM_ROOT_SLUG', 'devowl-wp');
define('RPM_SLUG', basename(RPM_PATH));
define('RPM_INC', trailingslashit(path_join(RPM_PATH, 'inc')));
define('RPM_MIN_PHP', '7.0.0'); // Minimum of PHP 5.3 required for autoloading and namespacing
define('RPM_MIN_WP', '5.2.0'); // Minimum of WordPress 5.0 required
define('RPM_MIN_RML', '4.0.10'); // Minimum version of Real Media Library
define('RPM_NS', 'DevOwl\\RealPhysicalMedia');
define('RPM_DB_PREFIX', 'realphysicalmedia'); // The table name prefix wp_{prefix}
define('RPM_OPT_PREFIX', 'rpm'); // The option name prefix in wp_options
define('RPM_SLUG_CAMELCASE', lcfirst(str_replace('-', '', ucwords(RPM_SLUG, '-'))));
//define('RPM_TD', ''); This constant is defined in the core class. Use this constant in all your __() methods
//define('RPM_VERSION', ''); This constant is defined in the core class
//define('RPM_DEBUG', true); This constant should be defined in wp-config.php to enable the Base#debug() method

// Check PHP Version and print notice if minimum not reached, otherwise start the plugin core
require_once RPM_INC .
    'base/others/' .
    (version_compare(phpversion(), RPM_MIN_PHP, '>=') ? 'start.php' : 'fallback-php-version.php');
