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
				'encrypt' => true
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

}