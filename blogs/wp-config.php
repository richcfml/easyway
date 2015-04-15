<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'easywayordering');

/** MySQL database password */
define('DB_PASSWORD', 'Yh56**ew!d');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'S-Ah< ~y`!3Eo?CMu=%_|2:+p+kb(|E3U--Av,].%;_MM)h;E4bz({|I<qon]iC~');
define('SECURE_AUTH_KEY',  '[&I5|I1gp6S6GG^~2F D(lWj5+_K->|5B!%t~X2N|8lPK[_FR($}`JrOwxN/X*n-');
define('LOGGED_IN_KEY',    '5)8_<ywPS8:,)@urFSj7K+Bps1<Q@9Y1edKV:SbZAzm4YxsP{rPIw{Pu.f|+sz6B');
define('NONCE_KEY',        'lu-%+v+@F <J}`#T}XgP+TH]!LTjao<rT+m+u7aGB9g=DbRU,zY?$W(SjcM}H|F{');
define('AUTH_SALT',        'iX4$4:BB^|a4E?<`g!{d^${a,V.-:Oe$(@vX_ekIBCSooinVmoJh5q~Di@ZH-wc|');
define('SECURE_AUTH_SALT', 'O#<IQ0-RZaQ)MywPbN!RErOSY#QM@g,9:QOkdbaBY7teoRryX^7*%FPqY*!!)^jm');
define('LOGGED_IN_SALT',   'ok)r1OTPI%Y<al:RqvGQu)I:S[|0_t6UynugdAQdA=hdl//Z[`uV+/WDN5CV~Vkl');
define('NONCE_SALT',       '`Lo?<DG<p=c.Pu%@b=W?cZ2uGZH.#[!KV-DK=`^5__zMH@IPJjf7@ra| |_ou_&J');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
