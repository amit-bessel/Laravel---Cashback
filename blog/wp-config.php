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
define('DB_NAME', 'uipl_cashback_justin');

/** MySQL database username */
define('DB_USER', 'uipl');

/** MySQL database password */

define('DB_PASSWORD', 'UXMfyFxNU4gkJfBu');
/*UXMfyFxNU4gkJfBu*/

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
define('AUTH_KEY',         'w `u(I-xlG6/u|bQa3=IF5*U^A0-H+ f:}o<g4K$-m|x/+lF[,=90yj6_P/DMuEQ');
define('SECURE_AUTH_KEY',  'M19H6SG+N9Xgl-$*c)^q .-6|]/{hjdZG1lPgB->sJ7H($)nWi)Pqo:lCwu~R@mE');
define('LOGGED_IN_KEY',    '!D{}U&!y$C1W8{b5r}+fNU4QV@-JaJYQ/5?-3;5#em`ElHwFG9mF)_]m -bYcSS+');
define('NONCE_KEY',        'Rw+DJh&Wqn<lbK,iGD*n-hC=VEm=t.+vM/W&4[.a}-i+1f3=WvAcWiRjKKhqoNvn');
define('AUTH_SALT',        'GrT3- 8uk^}@L`|-2-`xfmT-@M0`~5N5*$A6snuFcl(H8@N+`|(i}Ct{(Uz9TH3|');
define('SECURE_AUTH_SALT', 'b:9BmCMz^#Jw.Lc%uAh&xQ];nvSO&.!o-3uHl[!r |@Xsw%6v0Zn9.qs*X)3R}j(');
define('LOGGED_IN_SALT',   'g|B?7xb!Kq2si+#E-|k|6lDhqh4V[a+Z6Rcai/c,A-::}JZ/ 8}3*|?YPfA-$9*+');
define('NONCE_SALT',       'e;-P9fSC;^[k&qNDQZ:GA1b{?OgpO1wLI8o|C.K@Pghb 5Ar|-,Ie;2+AiEE[p4@');

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
