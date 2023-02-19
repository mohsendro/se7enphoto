<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'se7enphoto' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'mG<w2w-hb^(Df-G::yHlXW<^PRTmR.]5g)#y8yk^!Uja)k9SHsBom)iC:Q3e d$t');
define('SECURE_AUTH_KEY',  '|G*OcEkjc5^-Z6_%lnui%jMW%8xvP<y1G5Z-4,HZ0[a}7uhZE5G&>(]sMj~DF+ZP');
define('LOGGED_IN_KEY',    'v<)y]]&3YAMDBA)m fJY:y[OFJ|95w<0_$1X&GC &bCR3e;xzX}38V(pz=J5g vO');
define('NONCE_KEY',        'nusN1?JOlAkx?c|y7W2 s7MO!axoZwPaq>qOzPy hQr<Q0wc&ESHqZ4<.@ [K*7J');
define('AUTH_SALT',        'y+gI#o--$cugJd= Uk_Aknh^oNuzg!1Fkc$]muP<W5_J 5+jhZ/9#]95t L$tx]+');
define('SECURE_AUTH_SALT', '7%_cx6cyy6ehP9E{aL+3?xZDGQ*4+s$20k$|GR4w<p-0jySNL18PXq5TNdad*yy[');
define('LOGGED_IN_SALT',   '-TJyC+YA4r3j~k#xZRuu3[I~DVlKCF??S{507TRN4T}921FYS}%:u2$B0dj#?yZz');
define('NONCE_SALT',       'P)7_T^yi7n>q}!6b8Q9RuU7j$kF;Vz1)VlExq00!5nD+AP&,M#G$tNC&Oub(]?-~');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'se7en_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG',     true );
// define( 'WP_DEBUG_LOG',     true );
// define( 'WP_DEBUG_DISPLAY', true );
// define( 'SCRIPT_DEBUG',     true );
// define( 'SAVEQUERIES',      true );
// define( 'WP_DISABLE_FATAL_ERROR_HANDLER', true );   // 5.2 and later
// @ini_set( 'log_errors', 'Off' );
// @ini_set( 'display_errors', 'On' );
// @ini_set( 'error_log', '/home/example.com/logs/php_error.log' );


/* Add any custom values between this line and the "stop editing" line. */
/* SSL */
// define( 'FORCE_SSL_LOGIN', true );
// define( 'FORCE_SSL_ADMIN', true );

/* Custom WordPress URL. */
// define( 'WP_CONTENT_DIR', dirname(__FILE__) . '/extensions' );
// define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . 'wpplus-content' );
// define( 'UPLOADS',        'wpplus-uploads' );
// define( 'WP_PLUGIN_DIR', dirname(__FILE__) . '/extensions/plugins' );
// define( 'WP_PLUGIN_URL',  'http://' . $_SERVER['HTTP_HOST'] . 'wpplus-plugins' );
// define( 'PLUGINDIR', dirname(__FILE__) . '/blog/wp-content/plugins' );
// register_theme_directory( dirname( __FILE__ ) . '/themes-dev' );
// define('WP_DEFAULT_THEME', 'twentyeleven');
// define( 'WPMU_PLUGIN_DIR', dirname(__FILE__) . '/extensions/builtin' );
// define( 'WPMU_PLUGIN_URL', 'http://mywebsite.com/extensions/builtin' );
// define( 'NOBLOGREDIRECT', 'http://example.com' );

/* Disable Post Revisions. */
define( 'WP_POST_REVISIONS', false );
// define( 'WP_POST_REVISIONS', 3 );
define('AUTOSAVE_INTERVAL', 86400 );
/* Media Trash. */
define( 'MEDIA_TRASH', true );
/* Trash Days. */
define( 'EMPTY_TRASH_DAYS', '60' );

/* PHP Memory */
// define( 'WP_MEMORY_LIMIT', '64M' );
// define( 'WP_MAX_MEMORY_LIMIT', '256M' );

/* WordPress Cache */
// define( 'WP_CACHE', true );

/* Compression */
// define( 'COMPRESS_CSS',        true );
// define( 'COMPRESS_SCRIPTS',    true );
// define( 'CONCATENATE_SCRIPTS', true );
// define( 'ENFORCE_GZIP',        true );
// define( 'DO_NOT_UPGRADE_GLOBAL_TABLES', true );

/* CRON */
// define( 'DISABLE_WP_CRON',      'false' );
// define( 'WP_CRON_LOCK_TIMEOUT', 30 );

/* Updates */
define( 'WP_AUTO_UPDATE_CORE', false );
define( 'DISALLOW_FILE_MODS', true );
define( 'DISALLOW_FILE_EDIT', true );
define( 'AUTOMATIC_UPDATER_DISABLED', true );

// define( 'WP_HTTP_BLOCK_EXTERNAL', true );
// define( 'WP_ACCESSIBLE_HOSTS', 'api.wordpress.org,*.github.com' );
// define( 'IMAGE_EDIT_OVERWRITE', true );
/* That's all, stop editing! Happy publishing. */


/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
