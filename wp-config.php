<?php
//Begin Really Simple SSL key
define('RSSSL_KEY', '9FAw1F8uAZjuHfJzo5v3j5ZPNWomwcOx3KykU0MgouttJzG0xsgGDnjw6xx8ksAC');
//END Really Simple SSL key

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL cookie settings
define( 'WP_CACHE', true );
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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'redcbltt_wp752' );
/** Database username */
define( 'DB_USER', 'redcbltt_wp752' );
/** Database password */
define( 'DB_PASSWORD', '](9@58K2-975pSh@' );
/** Database hostname */
define( 'DB_HOST', 'localhost' );
/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );
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
define( 'AUTH_KEY',         'hpbnhxfkw0wl7b9xr4jesoa6mgl6uot35ffdl6nm3mxuofreugrf4jb7ugqtz1q6' );
define( 'SECURE_AUTH_KEY',  'aauxbzbmaejipq7zykgyuymmg7asibunflyfnu991zd8b4kltaobpz1rrvnal1nx' );
define( 'LOGGED_IN_KEY',    '2v7begfsewtyfwfdcxk7xaolh53k7vor4katzbukar2esche9czho6bxyxs54kls' );
define( 'NONCE_KEY',        'dnl61qyijnfbqa9xrsn9ka6o2e28gefb89pizxoqvbo4bsvv5b63cnmoqkf5esmt' );
define( 'AUTH_SALT',        'u4korpymxa0dtt2ion4amgetjfvps7imuqhmr9adausx7mw0cjotcpr9e2ewppny' );
define( 'SECURE_AUTH_SALT', 'qrwa58vflug9uysz6trftqwpk4df2rnywkrisdjcxzjrzjbkhyzlyam78pfo7w63' );
define( 'LOGGED_IN_SALT',   '5doihsdknrn88akz3ats61zghryidwe5zrlfux6cxtt6nejlfdcchwbfksgkdsoq' );
define( 'NONCE_SALT',       'wg8xlm7r8o3pfpjg7q6julxkbcrm2rhgd0djen97hwk9nx6af0pswps5zrqbigyz' );
/**#@-*/
/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp97_';
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
/* Add any custom values between this line and the "stop editing" line. */
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
