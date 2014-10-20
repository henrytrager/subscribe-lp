<?php
/**
 * Report Class: Manages the creation of CSV reports
 *
 * @package    Subscribe LP
 * @author     Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright  2014 Hazard Media Group LLC
 * @license    MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link       https://github.com/Alekhen/subscribe-lp
 * @version    Release: 0.1 (ALPHA)
 */

class Report {

	public $args = [
		'name' => 'report',		// Name of the generated CSV report
		'delimeter' => ',',		// CSV column delimeter
		'data' => array()		// Array of data to be used by the report
	];

	public function __construct( $args ) {

		if( isset( $args ) && is_array( $args ) ) {
			foreach( $args as $arg => $value ) {
				$this->args[$arg] = $value;
			}
		}

		$this->create_report();

	}

	protected function create_report() {

		extract( $this->args );

		$csv = fopen( 'php://memory', 'w' );
		$columns = array();
		$count = 0;

		foreach( $data as $row ) {
			if( $count < 1 ) {
				foreach( $row as $name => $value ) {
					array_push( $columns, $name );
				}
				fputcsv( $csv, $columns, $delimeter );
			}
			fputcsv( $csv, $row, $delimeter );
			$count++;
		}

		// Rewind the CSV file
		fseek( $csv, 0 );

		// Set CSV file headers
		header( 'Content-Type: application/csv' );
		header( 'Content-Disposition: attachement; filename="' . $name . '"' );

		// Send the generated CSV to the browser
		fpassthru( $csv );

	}

}