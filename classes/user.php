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
		API::add_key( 'users' );

	}

	public static function generate_pswd() {

		$pswd = rand( 1000000, 999999999 );
		$pswd = Encryption::encrypt( $pswd );
		$pswd = str_replace( '/', 'A', $pswd );
		$pswd = str_replace( '+', 'u', $pswd );
		$pswd = str_replace( '=', '7', $pswd );
		$pswd = substr( $pswd, 0, 12 );
		$pswd = strrev( $pswd );
		return $pswd;

	}

	public static function generate_token() {

		$token = rand( 1000000000, 99999999999 );
		$token = Encryption::encrypt( $token );
		$token = str_replace( '/', '_', $token );
		$token = str_replace( '+', '_', $token );
		$token = str_replace( '=', '-', $token );
		return $token;

	}

	public static function authenticate( $email, $token ) {

		global $db;
		$email = strtolower( $email );
		$user = $db->get_row( static::$table, 'email', $email );
		return ( !empty( $user['email'] ) && !empty( $user['token'] ) && ( $user['token'] == $token ) ) ? true : false;

	}

	public static function login( $email, $pswd ) {

		global $db;
		$email = strtolower( $email );
		$resp = array();

		$resp['status'] = 'error';
		$resp['type'] = 'invalid-user';
		$resp['message'] = 'User could not be found';
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
				$user['last_login_time'] = date( 'H:i:s', time() );

				if( $db->update_row( static::$table, $user ) ) :

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

	public static function create_new( $email, $pswd, $confirm ) {

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
			$resp['display'] = 'Passwords must be at least 8 characters in length.';

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
							'create_time' => date( 'H:i:s', time() )
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

	public static function delete( $email, $token, $user ) {

		global $db;
		$email = strtolower( $email );
		$user = strtolower( $user );
		$resp = array();

		$resp['status'] = 'error';
		$resp['type'] = 'unauthorized-access';
		$resp['message'] = 'Unauthorized Access';
		$resp['display'] = 'Unauthorized Access';

		if( static::authenticate( $email, $token ) ) :

			$resp['type'] = 'invalid-user';
			$resp['message'] = 'User could not be found';
			$resp['display'] = 'The user you are trying to delete doesn\'t exist.';

			$user_data = $db->get_row( static::$table, 'email', $user );

			if( !empty( $user_data['email'] ) ) :

				$resp['type'] = 'database-error';
				$resp['message'] = 'Database communication error';
				$resp['display'] = 'Sorry, but something went wrong. Please try again later.';

				if( $db->delete_row( static::$table, 'email', $user ) ) :

					$resp['status'] = 'success';
					$resp['type'] = 'user-deleted';
					$resp['message'] = 'User has been deleted';
					$resp['display'] = 'The user has been successfully removed.';

				endif;

			endif;

		endif;

		return $resp;

	}

	public static function reset( $email ) {

		global $db;
		$email = strtolower( $email );
		$new_pswd = static::generate_pswd();
		$resp = array();

		$resp['status'] = 'error';
		$resp['type'] = 'invalid-user';
		$resp['message'] = 'User could not be found';
		$resp['display'] = 'The user account does not exist.';

		$user = $db->get_row( static::$table, 'email', $email );

		if( !empty( $user['email'] ) ) :

			$resp['type'] = 'database-error';
			$resp['message'] = 'Database communication error';
			$resp['message'] = 'Sorry, but something went wrong. Please try again later.';

			$user['password'] = $new_pswd;
			$user['status'] = 'reset';

			if( $db->update_row( static::$table, $user ) ) :

				$resp['status'] = 'success';
				$resp['type'] = 'reset-user';
				$resp['message'] = 'User has been reset';
				$resp['display'] = 'The user account has been successfully reset.';

				$reset_email = [
					'sender' => EMAIL_ADDRESS,
					'recipient' => $email,
					'subject' => SITE_NAME . ' Password Recovery',
					'template' => 'user-reset',
					'data' => [
						'new_password' => $new_pswd
					]
				];

				//new Email( $reset_email );

			endif;

		endif;

		return $resp;

	}

	public static function update( $email, $old_pswd, $new_pswd, $confirm ) {

		global $db;
		$email = strtolower( $email );
		$resp = array();

		$resp['status'] = 'error';
		$resp['type'] = 'invalid-user';
		$resp['message'] = 'User could not be found';
		$resp['display'] = 'Your user account does not exist.';

		$user = $db->get_row( static::$table, 'email', $email );

		if( !empty( $user['email'] ) ) :

			$resp['type'] = 'unauthorized-access';
			$resp['message'] = 'Unauthorized Access';
			$resp['display'] = 'Unauthorized Access';

			if( $user['password'] == $old_pswd ) :

				$resp['type'] = 'invalid-password';
				$resp['message'] = 'Password does not match required length of 8 characters';
				$resp['display'] = 'Passwords must be at least 8 characters in length.';

				if( strlen( $new_pswd ) >= 8 ) :

					$resp['message'] = 'Password confirmation required';
					$resp['display'] = 'Please confirm the password you entered.';

					if( !empty( $confirm ) ) :

						$resp['message'] = 'Password and confirmation do not match';
						$resp['display'] = 'The password and confirmation you entered don\'t match. Please try again.';

						if( $new_pswd == $confirm ) :

							$resp['type'] = 'database-error';
							$resp['message'] = 'Database communication error';
							$resp['display'] = 'Sorry, but something went wrong. Please try again later.';

							$user['password'] = $new_pswd;
							$user['token'] = static::generate_token();
							$user['status'] = 'active';

							if( $db->update_row( static::$table, $user ) ) :

								$resp['status'] = 'success';
								$resp['type'] = 'password-reset';
								$resp['message'] = 'Password has been reset';
								$resp['display'] = 'Your password has been successfully reset.';

								$confirmation_email = [
									'sender' => EMAIL_ADDRESS,
									'recipient' => $email,
									'subject' => SITE_NAME . ' Password Reset Confirmation',
									'template' => 'user-update'
								];

								//new Email( $confirmation_email );

							endif;

						endif;

					endif;

				endif;

			endif;

		endif;

		return $resp;

	}

	public static function get( $email, $token ) {

		global $db;
		$email = strtolower( $email );
		$resp = array();

		$resp['status'] = 'error';
		$resp['type'] = 'unauthorized-access';
		$resp['message'] = 'Unauthorized Access';
		$resp['display'] = 'Unauthorized Access';

		if( static::authenticate( $email, $token ) ) :

			$data = $db->get_data( static::$table );
			unset( $data['password'] );
			unset( $data['token'] );
			$resp = $data;

		endif;

		return $resp;

	}

	public static function export( $email, $token ) {

		global $db;
		$email = strtolower( $email );
		$resp = array();

		$resp['status'] = 'error';
		$resp['type'] = 'unauthorized-access';
		$resp['message'] = 'Unauthorized Access';
		$resp['display'] = 'Unauthorized Access';

		if( static::authenticate( $email, $token ) ) :

			$resp['type'] = 'database-error';
			$resp['message'] = 'Database communication error';
			$resp['display'] = 'Sorry, but something went wrong. Please try again later.';

			$user = $db->get_row( static::$table, 'email', $email );
			$data = $db->get_data( static::$table );
			unset( $data['password'] );
			unset( $data['token'] );

			if( !empty( $user['id'] ) && !empty( $data ) ) :

				$resp['status'] = 'success';
				$resp['type'] = 'report-generated';
				$resp['message'] = 'Report successfully generated';
				$resp['display'] = 'The data you requested has been successfully exported.';

				$report = [
					'user_id' => $user['id'],
					'name' => 'Users',
					'data' => $data
				];

				new Report( $report );

			endif;

		endif;

		return $resp;

	}

}