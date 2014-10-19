<?php
/**
 * Subscribe API
 *
 * @package    Subscribe LP
 * @author     Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright  2014 Hazard Media Group LLC
 * @license    MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link       https://github.com/Alekhen/subscribe-lp
 * @version    Release: 0.1 (ALPHA)
 */

// Define as JSON application
header( 'Content-type: application/json' );
require_once dirname( dirname( __FILE__ ) ) . '/config/config.php';

$api = 'subscribe';
$params = $_GET;
$resp = array();

// Default API response
$resp['status'] = 'error';
$resp['type'] = 'unauthorized-access';
$resp['message'] = 'Unauthorized Access';
$resp['display'] = 'Unauthorized Access';

// Authenticate API Key
if( empty( $params['api-key'] ) || !API::key_auth( $api, $params['api-key'] ) ) :

	die( 'Unauthorized Access' );

else :

	$resp['message'] = 'Warning: unidentified robot detected';
	$resp['display'] = 'Sorry, but robots are not allowed to participate on this site.';

	// Validate captcha (must be empty)
	if( isset( $params['cc'] ) && $params['cc'] == '' ) :

		$resp['type'] = 'missing-parameters';
		$resp['message'] = 'Warning: required parameters not found';
		$resp['display'] = 'Please fill out all required fields.';

		// Verify required parameters
		if( !empty( $params['action'] ) && !empty( $params['email'] ) ) :

			$resp = Subscribe::run_action( $params['action'], $params );

		endif;

	endif;

endif;

// Return JSON response string
echo json_encode( $resp );