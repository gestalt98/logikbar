<?php


// ** MySQL settings ** //
/** The name of the database for WordPress */
define('DB_NAME', 'logikbar');

/** MySQL database username */
define('DB_USER', 'mysql.logikbar');

/** MySQL database password */
define('DB_PASSWORD', 'universe-busybody-heard');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('AUTH_KEY',         'Pne`dvb9Um)m?@0B;0o^PZ}KRqEv6jBZIv2umv)yQSy+j+-HWF`07^[BUTCcd6! ');
define('SECURE_AUTH_KEY',  'r~a&m7(GZ]yc|J4A;yAxthf<;liXuDW~O|+,]MMCd-_e{zruD`4J.oal/x!*l+,o');
define('LOGGED_IN_KEY',    '%i}hS.ZBff)/S!|6C|nU-):C`^m0Q:.}T;eCeQAI2_8Ml/WbB?xi`M4jHGP<|V~D');
define('NONCE_KEY',        'qrR0=8PqjHlVr?6V1mE#6h@=u`zzJO@_od^#PAg;|`m~_Dz+$f,~Sx)(N)fa#8:+');
define('AUTH_SALT',        'u6$5!fV-bKYG)F:0sFjc_Y[4OK?lYEX<m0.|}=-A+|O)twu2l{hd1yFqaRi#LQO$');
define('SECURE_AUTH_SALT', ':$HbvCSD#+ky@]K#%f.}S.$QS+}~mK@H5Tb{@WXRg%M8gFqF>2CDmzNHs{-h0oj=');
define('LOGGED_IN_SALT',   '`US@=%@!j0uwebE7t!)$Z4/]U}KwzmLqjw3j2W&%5MZ%J$zzz6lb#H?+6-+=|W9P');
define('NONCE_SALT',       '/DRT}qLbqCz8fGegr7aDnf8}zp2jGjvb3EC>(p=h=h{$Jl3PQPkD^%euuR#8X&b<');


$table_prefix = 'wp_';





/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
