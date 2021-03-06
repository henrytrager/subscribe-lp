<?php
/**
 * Gather Required Assets on Page Load
 *
 * @package    Subscribe LP
 * @author     Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright  2014 Hazard Media Group LLC
 * @license    MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link       https://github.com/Alekhen/subscribe-lp
 * @version    Release: 0.1 (ALPHA)
 */

// Include global functions
require_once dirname( __FILE__ ) . '/functions.php';

// Include required classes
require_once API_CLASS;
require_once DATABASE_CLASS;
require_once EMAIL_CLASS;
require_once ENCRYPTION_CLASS;
require_once REPORT_CLASS;
require_once SUBSCRIBE_CLASS;
require_once USER_CLASS;

// Instantiations
$db = new Database();

// Run class setup methods
API::setup();
User::setup();
Subscribe::setup();

// Initialize session tracking
session_start();