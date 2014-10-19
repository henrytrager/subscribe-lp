<?php
/**
 * Subscribe Class: Manages the subscription list
 *
 * @package    Subscribe LP
 * @author     Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright  2014 Hazard Media Group LLC
 * @license    MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link       https://github.com/Alekhen/subscribe-lp
 * @version    Release: 0.1 (ALPHA)
 */

class Subscribe {

	public static $table = [
		'name' => 'subscriptions',
		'version' => '1.0',
		'key' => SUBS_AUTH,
		'structure' => [
			'email' => [
				'sql' => 'VARCHAR(255)',
				'encrypt' => true
			],
			'status' => [
				'sql' => 'VARCHAR(255)',
				'encrypt' => true,
				'default' => 'active'
			],
			'create_date' => [
				'sql' => 'DATE'
			],
			'create_time' => [
				'sql' => 'TIME'
			],
			'delete_date' => [
				'sql' => 'DATE'
			],
			'delete_time' => [
				'sql' => 'TIME'
			]
		]
	];

	public static function setup() {

		global $db;
		$db->create_table( static::$table );
		API::new_api_key( 'subscribe' );

	}

	public static function run_action( $action, $params = array() ) {

		$resp = array();
		$resp['status'] = 'error';
		$resp['type'] = 'invalid-action';
		$resp['message'] = 'Defined API action cannot be performed';
		$resp['display'] = 'Sorry, something went wrong. Please try again later.';

		switch( $action ) {

			case 'add':
				$resp = static::new_subscriber( $params['email'] );
				break;

			case 'get':
				$resp = static::get_subscribers( $params['email'], $params['token'] );
				break;

		}

		return $resp;

	}

	protected static function new_subscriber( $email ) {

		$resp = array();
		$resp['status'] = 'error';
		$resp['type'] = 'invalid-format';
		$resp['message'] = 'The submitted email address does not match the required format';
		$resp['display'] = 'Your email address isn\'t valid. Please try again.';

		if( preg_match( '/^[A-Za-z0-9._%\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,4}$/', $email ) ) :

			switch( static::create_new_subscriber( $email ) ) {

				case 'error':
					$resp['type'] = 'database-error';
					$resp['message'] = 'An error occured connecting to the database - Try again later';
					$resp['display'] = 'Sorry, something went wrong. Please try again later.';
					break;

				case 'duplicate':
					$resp['status'] = 'success';
					$resp['type'] = 'duplicate';
					$resp['message'] = 'The submitted email address has already subscribed';
					$resp['display'] = 'Welcome back, it looks like you\'ve already subscribed';
					break;

				case 'success':
					$resp['status'] = 'success';
					$resp['type'] = 'subscribed';
					$resp['message'] = 'The submitted email address has been successfully added to the subscription list';
					$resp['display'] = 'Thanks for subscribing!';
					break;

			}

		endif;

		return $resp;

	}

	protected static function create_new_subscriber( $email ) {

		global $db;
		$email = strtolower( $email );
		$data = $db->get_row( static::$table, 'email', $email );

		if( !empty( $data['id'] ) ) :

			return 'duplicate';

		else :

			$new_subscriber = [
				'email' => $email,
				'create_date' => date( 'Y-m-d', time() ),
				'create_time' => date( 'H:i:s', time() )
			];

			$success_email = [
				'sender' => EMAIL_ADDRESS,
				'recipient' => $email,
				'subject' => SITE_NAME . ' Subscription Confirmation',
				'template' => 'subscribe'
			];

			//new Email( $success_email );

			return $db->save_data( static::$table, $new_subscriber ) ? 'success' : 'error';

		endif;

	}

	protected static function get_subscribers( $email, $token ) {

		$resp = array();
		$resp['status'] = 'error';
		$resp['type'] = 'unauthorized-access';
		$resp['message'] = 'Unauthorized Access';
		$resp['display'] = 'Unauthorized Access';

		// Authorize user then retrieve data from database

		return $resp;

	}

}