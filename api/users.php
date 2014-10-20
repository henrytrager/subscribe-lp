<?php
/**
 * Users API
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
if( empty( $api_key ) || !API::key_auth( 'users', $api_key ) ) :

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

				case 'login':
					if( !empty( $pswd ) ) {
						$resp = User::login( $email, $pswd );
					}
					break;

				case 'add':
					if( !empty( $pswd ) && !empty( $confirm ) ) {
						$resp = User::new( $email, $pswd, $confirm );
					}
					break;

				case 'remove':
					if( !empty( $token ) && !empty( $user ) ) {
						$resp = User::delete( $email, $token, $user );
					}
					break;

				case 'reset':
					$resp = User::reset( $email );
					break;

				case 'update':
					if( !empty( $old_pswd ) && !empty( $new_pswd ) && !empty( $confirm ) ) {
						$resp = User::update( $email, $old_pswd, $new_pswd, $confirm );
					}
					break;

				case 'get':
					if( !empty( $token ) ) {
						$resp = User::get( $email, $token );
					}
					break;

				default:
					$resp['type'] = 'invalid-action';
					$resp['message'] = 'Defined API action cannot be performed';
					$resp['display'] = 'Sorry, something went wrong. Please try again later.';
					break;

			}

		endif;

	endif;

endif;

// Return JSON response string
echo json_encode( $resp );