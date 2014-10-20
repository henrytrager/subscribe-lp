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

extract( $_GET );

// Default API response
$resp = array();
$resp['status'] = 'error';
$resp['type'] = 'unauthorized-access';
$resp['message'] = 'Unauthorized Access';
$resp['display'] = 'Unauthorized Access';

// Authenticate API Key
if( empty( $api_key ) || !API::key_auth( 'subscribe', $api_key ) ) :

	die( 'Unauthorized Access' );

else :

	$resp['message'] = 'Warning: unidentified robot detected';
	$resp['display'] = 'Sorry, but robots are not allowed to participate on this site.';

	// Validate captcha (must be empty)
	if( isset( $cc ) && $cc == '' ) :

		$resp['type'] = 'missing-parameters';
		$resp['message'] = 'Warning: required parameters not found';
		$resp['display'] = 'Please fill out all required fields.';

		// Verify required parameters
		if( !empty( $action ) && !empty( $email ) ) :

			switch( $action ) {

				case 'add':
					$resp = Subscribe::new_subscriber( $email );
					break;

				case 'get':
					if( !empty( $token ) ) {
						$resp = Subscribe::get_subscribers( $email, $token );
					}
					break;

				case 'export':
					if( !empty( $token ) ) {
						$resp = Subscribe::export_subscribers( $email, $token );
					}
					break;

				default:
					$resp['type'] = 'invalid-action';
					$resp['message'] = 'Defined API action cannot be performed';
					$resp['display'] = 'Sorry, something went wrong. Please try again later';
					break;

			}

		endif;

	endif;

endif;

// Return JSON response string
echo json_encode( $resp );