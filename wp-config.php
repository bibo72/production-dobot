<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */

define('WP_CACHE', true); //Added by WP-Cache Manager

define( 'WPCACHEHOME', '/var/www/dobot/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager

define('DB_NAME', 'dobot');
/** MySQL database username */
define('DB_USER', 'dobot');
/** MySQL database password */
define('DB_PASSWORD', 'PVD7@rxSxBi');
/** MySQL hostname */
define('DB_HOST', 'localhost');
/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');
/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '!(1*u&zke3|3CflEho8U,A&Wq~Ovep|[8eo-,yC}]Cm%s_E_xCURt{y#y9OeB%Q0');
define('SECURE_AUTH_KEY',  'yly84eomRa-&~5V!s*Yu{@mWZ_8b&8EfSSVP:Q:@fH4e$)c@vC FC[;u? k0l]fg');
define('LOGGED_IN_KEY',    'sk4v&Tot}5t~?[MtyJV#w4Q[ ltJ 4@-@ZmLUy#Dz2{a+ILf,(7q]TP2icCIoT2V');
define('NONCE_KEY',        'u9Y-x?|{(~D_#Wp(sEZ@Es&4:V#|9e*f^8fiVQXwgo0*lD#Jjm81h@#nX|Rxu`-F');
define('AUTH_SALT',        '=tsSE]2O^OKDY/T0lBbfW^-1U`a%Rjl++am~Y:>4u6fK7e$w7a.@!2#*xX=j[:+O');
define('SECURE_AUTH_SALT', 'p]>szc^]66dkv}^j<Vq}VZD{{Yf=Dr;[!viF&6uF>z_wRr~h(lXAYX2<DisrdB^P');
define('LOGGED_IN_SALT',   '=>cEK@@fOBuYJU2)UCh1+bi{l6qY}9PbX3y7d}HR%H)GqJwWx|C]2wYIgC^,Zh#3');
define('NONCE_SALT',       'n3>f;QM8:Nbc]D*^2pUxY%4<-+uf689Rm2k@0,a1]JU<s,+4^xte!)4>$g6-b*/z');
/**#@-*/
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
/* That's all, stop editing! Happy blogging. */
/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');
/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
