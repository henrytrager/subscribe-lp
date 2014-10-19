<?php
/**
 * User Class: Manages all functionality associated with admin users
 *
 * @package    Subscribe LP
 * @author     Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright  2014 Hazard Media Group LLC
 * @license    MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link       https://github.com/Alekhen/subscribe-lp
 * @version    Release: 0.1 (ALPHA)
 */

class User {

	public static $table = [
		'name' => 'users',
		'version' => '1.0',
		'key' => USER_AUTH,
		'structure' => [
			'email' => [
				'sql' => 'VARCHAR(255)',
				'encrypt' => true
			],
			'password' => [
				'sql' => 'VARCHAR(255)',
				'encrypt' => true
			],
			'token' => [
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
			'last_login_date' => [
				'sql' => 'DATE'
			],
			'last_login_time' => [
				'sql' => 'TIME'
			]
		]
	];

	public static function setup() {

		global $db;
		$db->create_table( static::$table );
		API::new_api_key( 'users' );

	}

	public static function run_action( $action, $params = array() ) {

		$resp = array();
		$resp['status'] = 'error';
		$resp['type'] = 'invalid-action';
		$resp['message'] = 'Defined API action cannot be performed';
		$resp['display'] = 'Sorry, something went wrong. Please try again later.';

		switch( $action ) {

			case 'login':
				$resp = static::login( $params['email'], $params['password'] );
				break;

			case 'add':
				$resp = static::new();
				break;

			case 'remove':
				$resp = static::delete( $params['email'], $params['token'] );
				break;

			case 'reset':
				$resp = static::reset();
				break;

		}

		return $resp;

	}

	protected static function login( $email, $password ) {

		global $db;
		$resp = array();

		return $resp;

	}

	protected static function new() {

		global $db;
		$resp = array();

		return $resp;

	}

	protected static function delete( $email, $token ) {

		global $db;
		$resp = array();

		return $resp;

	}

	protected static function reset() {

		global $db;
		$resp = array();

		return $resp;

	}

}