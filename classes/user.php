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

	public static function generate_token() {

		$num = rand( 1000000000, 99999999999 );
		$hash = Encryption::encrypt( $num );
		$token = str_replace( '/', '_', $hash );
		$token = str_replace( '+', '_', $token );
		$token = str_replace( '=', '-', $token );
		return $token;

	}

	public static function login( $email, $pswd ) {

		global $db;
		$email = strtolower( $email );
		$resp = array();

		$resp['status'] = 'error';
		$resp['type'] = 'invalid-username';
		$resp['message'] = 'Username does not exist';
		$resp['display'] = 'The username you entered does not exist.';

		$user = $db->get_row( static::$table, 'email', $email );

		if( !empty( $user['email'] ) ) :

			$resp['type'] = 'invalid-password';
			$resp['message'] = 'Incorrect password';
			$resp['display'] = 'The password you entered is invalid. Please try again.';

			if( $user['password'] = $pswd ) :

				$resp['type'] = 'database-error';
				$resp['message'] = 'Database communication error';
				$resp['display'] = 'Sorry, but something went wrong. Please try again later.';

				$user['last_login_date'] = date( 'Y-m-d', time() );
				$user['last_login_time'] => date( 'H:i:s', time() );

				if( $db->save_data( static::$table, $user ) ) :

					$resp['status'] = 'success';
					$resp['type'] = 'authenticated';
					$resp['message'] = 'User has been successfully validated';
					$resp['display'] = 'Welcome back!';
					$resp['token'] = $user['token'];

				endif;

			endif;

		endif;

		return $resp;

	}

	public static function new( $email, $pswd, $confirm ) {

		global $db;
		$email = strtolower( $email );
		$resp = array();

		$resp['status'] = 'error';
		$resp['type'] = 'user-conflict';
		$resp['message'] = 'User already exists';
		$resp['display'] = 'Sorry, but the email address you entered already exists';

		$user = $db->get_row( static::$table, 'email', $email );

		if( empty( $user['email'] ) ) :

			$resp['type'] = 'invalid-password';
			$resp['message'] = 'Password does not match required length of 8 characters';
			$resp['display'] = 'Passwords must be at least 8 characters in length';

			if( strlen( $pswd ) >= 8 ) :

				$resp['message'] = 'Password confirmation required';
				$resp['display'] = 'Please confirm the password you entered.';

				if( !empty( $confirm ) ) :

					$resp['message'] = 'Password and confirmation do not match';
					$resp['display'] = 'The password and confirmation you entered don\'t match. Please try again.';

					if( $pswd == $confirm ) :

						$resp['type'] = 'database-error';
						$resp['message'] = 'Database communication error';
						$resp['display'] = 'Sorry, but something went wrong. Please try again later.';

						$data = [
							'email' => $email,
							'password' => $pswd,
							'token' => static::generate_token(),
							'create_date' => date( 'Y-m-d', time() ),
							'create_time' => date( 'H:i:s', time() ),
							'last_login_date' => date( 'Y-m-d', time() ),
							'last_login_time' => date( 'H:i:s', time() )
						];

						if( $db->save_data( static::$table, $data ) ) :

							$resp['status'] = 'success';
							$resp['type'] = 'user-added';
							$resp['message'] = 'New user has been successfully added to the database';
							$resp['display'] = 'Your account has been successfully created.';
							$resp['token'] = $data['token'];

						endif;

					endif;

				endif;

			endif;

		endif;

		return $resp;

	}

	public static function delete( $email, $token ) {

		global $db;
		$email = strtolower( $email );
		$resp = array();

		return $resp;

	}

	public static function reset( $email ) {

		global $db;
		$email = strtolower( $email );
		$resp = array();

		return $resp;

	}

	public static function get( $email, $token ) {

		global $db;
		$email = strtolower( $email );
		$resp = array();

	}

}