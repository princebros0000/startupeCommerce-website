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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'Porto' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ':$x0lgaB:{l/QIp?f)!aNNi%zp#D:)mItt+M94ZNgZjz`,zB/q6qQ4@mG[%8S>Mu' );
define( 'SECURE_AUTH_KEY',  '=B^!q#LfSoS|xqJ}A]m0&&_k[~j0TQ@t|C@T=)T ]:J{*6L:r&O<F(#FX6-YGgL]' );
define( 'LOGGED_IN_KEY',    '0rbZF2h@;ZES@`QUw)DaTz6pM&xG?rhb;$A,`p*5}E[X)),uan/Dj-g;O~hj)Avf' );
define( 'NONCE_KEY',        'v3u{?vWCuLiygPIlC-0l!0?S[kdoV4x45X(TqH(.&. / `,M-U2DOo^F)Y2wf0h8' );
define( 'AUTH_SALT',        'Q*s5RO_gf*-H&7chL%cJyv#b^GR/_C_3ZdTKb>A1I2PMZK%8iq*q4QA(Hahb+aXO' );
define( 'SECURE_AUTH_SALT', 'mA?0b!mSqk5hocbK,lQH-}E8[?k|{K]uIl!$*.dAn<&7%^Pv@UB^(G-S|[G%4nZA' );
define( 'LOGGED_IN_SALT',   'i%%eC>8L2fPS4^t`<)C)t;cM}#?RB/P{`hXj+wZfduOob/_3sBPy<V!GxPJ8-YG@' );
define( 'NONCE_SALT',       'Ct.|]p~9&N.&/_I0SPHA/q(nQ#@Fi`)&T3xv}w,9oA4(.p,DK8Nn?m=Hf-fE:FcK' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
