<?php
/**
 * Subscribe Landing Page Configuration
 *
 * @package    Subscribe LP
 * @author     Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright  2014 Hazard Media Group LLC
 * @license    MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link       https://github.com/Alekhen/subscribe-lp
 * @version    Release: 0.1 (ALPHA)
 */

// Database credentials
define( 'DB_HOST', 'localhost' );
define( 'DB_NAME', 'database_name_here' );
define( 'DB_USER', 'username_here' );
define( 'DB_PASS', 'password_here' );

// Database table prefix
define( 'TABLE_PREFIX', 'lp_' );

// Authentication and encryption keys
define( 'SITE_AUTH', 'put your unique phrase here' );
define( 'USER_AUTH', 'put your unique phrase here' );
define( 'SUBS_AUTH', 'put your unique phrase here' );

// General site definitions
define( 'SITE_NAME', 'your site' );
define( 'SITE_URL', 'http:// the remote url' );
define( 'EMAIL_ADDRESS', 'example@example.com' );

// Google Analytics
define( 'GA', false );
define( 'GA_ACCOUNT', 'your account ID' );

require_once dirname( __FILE__ ) . '/paths.php';
require_once dirname( __FILE__ ) . '/load.php';