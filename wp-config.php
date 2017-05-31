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
define('DB_NAME', 'finntheo_development');

/** MySQL database username */
define('DB_USER', 'finntheo_pavel');

/** MySQL database password */
define('DB_PASSWORD', 'pco82493');

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
define('AUTH_KEY',         'zw2fkryptdba62gkqib7lgh2c31uuw37acoeiwvbiwcwkhrdeg34i3fxlhyadb0f');
define('SECURE_AUTH_KEY',  'ju3hnje7xlvilcltjj6zhabqryaxsmyq4sxzvtewbygpvddqxn5q529ndq9836zg');
define('LOGGED_IN_KEY',    'skrzyoeqs3qgjedh89cawha8htilbhn1fsbv5ciw9krt0bdinxl0rbtvqxd8is17');
define('NONCE_KEY',        'z6fpqrtpfhfot2jykz7bpf5y5ph3iiij7kn5rhanjjgchtr6kpvls9hwlo2s9bnp');
define('AUTH_SALT',        '6ghyud1p5f7jx8siicaupugq4zrocvwxdbeaqvpe9frrf6wed74lnp0cwdhbm0iu');
define('SECURE_AUTH_SALT', 'mucxfnq6se78skdn2ni5ekltsf3w0l5tuljnosqiuxxmo0u5vl1rz5p3orhkqywn');
define('LOGGED_IN_SALT',   '4uq3vybm6hbdyl4ustenehyamgvsmzpf4h4tflmaqcbe7hrtluk331guhvbmqj4v');
define('NONCE_SALT',       'tzzgixpbxuszulbkwbs3co0ccmleq3nezjbud1czipeh8alyczdikinksrhfhv2d');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpms_';

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
