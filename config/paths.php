<?php
/**
 * File and Directory Path Definitions
 *
 * @package    Subscribe LP
 * @author     Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright  2014 Hazard Media Group LLC
 * @license    MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link       https://github.com/Alekhen/subscribe-lp
 * @version    Release: 0.1 (ALPHA)
 */

// Current Page
$request_uri = explode( '?', $_SERVER['REQUEST_URI'] );
define( 'CURRENT_PAGE', $request_uri[0] );

// General Directories
define( 'ROOT_DIR', dirname( dirname( __FILE__ ) ) . '/' );
define( 'API_DIR', ROOT_DIR . 'api/' );
define( 'CLASSES_DIR', ROOT_DIR . 'classes/' );
define( 'EMAIL_DIR', ROOT_DIR . 'email/' );
define( 'INC_DIR', ROOT_DIR . 'inc/' );
define( 'REPORTS_DIR', ROOT_DIR . 'reports/' );
define( 'VIEWS_DIR', ROOT_DIR . 'views/' );

// APIs
define( 'SUBSCRIBE_API', SITE_URL . '/api/subscribe.php' );
define( 'USERS_API', SITE_URL . '/api/users.php' );

// Classes
define( 'API_CLASS', CLASSES_DIR . 'api.php' );
define( 'DATABASE_CLASS', CLASSES_DIR . 'database.php' );
define( 'EMAIL_CLASS', CLASSES_DIR . 'email.php' );
define( 'ENCRYPTION_CLASS', CLASSES_DIR . 'encryption.php' );
define( 'REPORT_CLASS', CLASSES_DIR . 'report.php' );
define( 'SUBSCRIBE_CLASS', CLASSES_DIR . 'subscribe.php' );
define( 'USER_CLASS', CLASSES_DIR . 'user.php' );