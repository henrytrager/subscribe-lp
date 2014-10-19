<?php
/**
 * API Class: Manages secure interactions with API's
 *
 * @package    Subscribe LP
 * @author     Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright  2014 Hazard Media Group LLC
 * @license    MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link       https://github.com/Alekhen/subscribe-lp
 * @version    Release: 0.1 (ALPHA)
 */

class API {

	public static $table = [
		'name' => 'api_keys',
		'version' => '1.0',
		'key' => SITE_AUTH,
		'structure' => [
			'name' => [
				'sql' => 'VARCHAR(255)'
			],
			'api_key' => [
				'sql' => 'VARCHAR(255)',
				'encrypt' => true
			]
		]
	];

	public static function setup() {

		global $db;
		$db->create_table( static::$table );

	}

	public static function keygen( $min = 10000000, $max = 999999999 ) {

		$num = rand( $min, $max );
		$hash = Encryption::encrypt( $num );
		$key = str_replace( '/', '_', $hash );
		$key = str_replace( '+', '-', $key );
		$key = str_replace( '=', '-', $key );
		return $key;

	}

	public static function new_api_key( $name ) {

		global $db;
		$data = $db->get_row( static::$table, 'name', $name );
		if( !empty( $data['name'] ) ) {
			$db->save_data( static::$table, [ 'name' => $name, 'api_key' => static::keygen() ] );
		}

	}

	public static function key_auth( $name, $key ) {

		global $db;
		$data = $db->get_row( static::$table, 'name', $name );
		return ( !empty( $data['name'] ) && ( $data['api_key'] == Encryption::encrypt( $key, static::$table['key'] ) ) ) ? true : false;

	}

	public static function get_api_key( $name ) {

		global $db;
		$data = $db->get_row( static::$table, 'name', $name );
		return !empty( $data['api_key'] ) ? $data['api_key'] : '';

	}

}