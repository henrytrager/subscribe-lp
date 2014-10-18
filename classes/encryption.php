<?php
/**
 * Encryption Class: Manages the secure encryption and decryption of data
 *
 * @package		Subscribe LP
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/subscribe-lp
 * @version		Release: 0.1 (ALPHA)
 */

class Encryption {

	public static function encrypt( $data, $key = NULL ) {

		$k = !empty( $key ) ? $key : SITE_AUTH;
		$encodedData = json_encode( $data );
		$encryptedData = trim( base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $k ), $encodedData, MCRYPT_MODE_ECB, mcrypt_create_iv( mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND ) ) ) );
		return $encryptedData;

	}

	public static function decrypt( $data, $key = NULL ) {

		$k = !empty( $key ) ? $key : SITE_AUTH;
		$decryptedArray = trim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $k ), base64_decode( $data ), MCRYPT_MODE_ECB, mcrypt_create_iv( mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND ) ) );
		$decryptedData = json_decode( $decryptedArray, true );
		return $decryptedData;

	}

}