<?php
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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u959182364_codecraaft' );

/** Database username */
define( 'DB_USER', 'u959182364_codecraaft' );

/** Database password */
define( 'DB_PASSWORD', '|zS7irfi' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

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
define( 'AUTH_KEY',          '2|VF=OK5/;E4pB4tKMUu@k~t&$UJH>E/t8Y,wl-3wV$=@6/)M3 5m%!R9oRMm395' );
define( 'SECURE_AUTH_KEY',   '<ZO>/8|Yku._jD!UEW#xV@b,f58k`,7.[0n=y!@fqV!nypJh GK)SRYt=k6; TUh' );
define( 'LOGGED_IN_KEY',     'ttB+B%jw8y)PlZS?*<YWY:39haD23=q+sJZ4YuW`$OzL+a+EY),u6~.,uzU}[~r&' );
define( 'NONCE_KEY',         'PExifF;*GCKErjP*8(jcj}@_&}=,K3hnl~oHB  |L8T@/EMHCW=wKYbIDkVy Fh8' );
define( 'AUTH_SALT',         'B^ AY[3K3uG%JrbzW;_TcVBZ@tA5)n.F|U!rjM^q<T)C3~ViAG, Z-PZu(HQ4(M,' );
define( 'SECURE_AUTH_SALT',  'TcbW@MerWU.*&WE1Yxfl?lUbj~?;c HLEJsRJ?>!;QQ1{kc0r]pjM{a^s0l;,_Bn' );
define( 'LOGGED_IN_SALT',    '49IfF]yF|^6&1Rr->(K?fa3d_jry;M4NWV0=koU(d0)Dx~Zs4O-y?%8#lzc0oVV]' );
define( 'NONCE_SALT',        'nMK;!Qto.I&C)j*$:D&XxW~4W~#P:]K;J^$ubu7FIUy@4o:=^P3LC*=Y)9CG+64T' );
define( 'WP_CACHE_KEY_SALT', 'j9Pke!~orXe;/6N>CYO(aLS}P16>;Ltqy{O?E~`U><82oIx8e*eyU@zVckz%EK:W' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );


/* Add any custom values between this line and the "stop editing" line. */



define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
