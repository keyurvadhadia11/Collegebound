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

define ( 'FS_METHOD', 'direct');
define('FORCE_SSL_ADMIN', true);


define('FORCE_SSL_ADMIN', true);
// in some setups HTTP_X_FORWARDED_PROTO might contain 
// a comma-separated list e.g. http,https
// so check for https existence
if (strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false)
       $_SERVER['HTTPS']='on';

/**#@-*/

define('WP_HOME','https://devoqdesign.com/projects/college');
define('WP_SITEURL','https://devoqdesign.com/projects/college');
$_SERVER['HTTPS'] = 'on';

define('FORCE_SSL_ADMIN', true);
define('RELOCATE', TRUE);
$_SERVER['HTTPS'] = 'on';


// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'devoq_collegebound' );

/** Database username */
define( 'DB_USER', 'omenyaall' );

/** Database password */
define( 'DB_PASSWORD', '9998811943' );

/** Database hostname */
define( 'DB_HOST', 'omenyaall.c7z5lcldir3v.us-east-2.rds.amazonaws.com' );

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
define( 'AUTH_KEY',         'Zf&R!o=LP|7aBcO4s4Yeo+]3nJTQu!=/%0UbXzB7&;2ywLF-o.CI@GZq:4ag#JS`' );
define( 'SECURE_AUTH_KEY',  'C*CLZqBkjRq/nPEH|8U!}}Wbrh12 (`I~@4q`}CZNTU5 9wQ/.c%*SDpj9qn5nNB' );
define( 'LOGGED_IN_KEY',    '&OS,%ne^mXU@k!blkvE8o3XQD>OAt8a *HVjq!Yf~1t][vRgWo0F@zZtJ8<W))$q' );
define( 'NONCE_KEY',        '{MmMCLeU%sFv*oX}`n@up4>@q/d-:c4+>m^=$B3Qk4eEbLBwT>%-8c+9bLEZirpY' );
define( 'AUTH_SALT',        '+NtwF9q/ %u.S?+C?=(e]m^l6TStw{?w;TcI}4R;/0fd}Q$3+J~J]I~R&)Vl*)yn' );
define( 'SECURE_AUTH_SALT', 'SBx&bqzk`)}LrsWFFaQaIlIJ59>^jDV2g&4)8.>,w:YHbU41`zcgCyqp#xPfHa7S' );
define( 'LOGGED_IN_SALT',   'mg?}hNnH1=,,8};^gS))`j|dnkw67@bVg#X#mluCMP7]1hP1L:U*ziSxc_AQ8I/+' );
define( 'NONCE_SALT',       'm>2y`=j)J0(n?cl[.]atE5Z]W8M/p{kd9dn/U3~)1AVks%Pk{OAr<|C1`nw3VQle' );


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



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
