<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'zappy');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '+:R-8]CuJU_u^4`Yq~%#?okv@G5eaIVAKi-QqFuy>q2`ga =|9pUhWWgpP?KU/n!');
define('SECURE_AUTH_KEY',  '8>Iw)+Z*(:?F^JcbghZ>>9YZzOichQR8&i`oTE}qX8c|D!c_ohvf[?k}o F~G+vl');
define('LOGGED_IN_KEY',    ',kcBb)J0!7Dk#|#CKsr&wBB7Mz@YiU)[LDw6L<w+th-,Yx3`n`@[wn7zLk>3-+|H');
define('NONCE_KEY',        '6;/ReLL6l7|xfYRc4+D*8xBo/K:IfFYSubM;3wPi7|rv={(O~nx|?v|#)|7I#WwK');
define('AUTH_SALT',        'RbN5gCb9KRp}ErIaJ3.82h,:,-5v]v46b]lO6+DV Y9<!15-xMx{_@<Dc+dQjf3+');
define('SECURE_AUTH_SALT', 'Im&t)AJ/MI)?sDPF|peB+?aKj4LQ8^Cp&`g=Jt?Erny85hyAr]]&:0T;$)]ckv<W');
define('LOGGED_IN_SALT',   '!8M)Y}jYj-7+CZJS8bYo#aj(d=,-R-lj.)WCWLh0N>3e8r~+o!zok-N|tWJk+q(A');
define('NONCE_SALT',       'pzglR=pm4oJr)CdmH{.zjsmj*%*oxM3v+/7b@{:Z|RtZYi&.nZ_Tij_{qW8kZJuv');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'edu_';

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
