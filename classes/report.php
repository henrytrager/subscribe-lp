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
		'user_id' => '',		// ID of the user generating the CSV report
		'name' => 'report',		// Name of the generated CSV report
		'delimeter' => ',',		// CSV column delimeter
		'data' => array()		// Array of data to be used by the report
	];

	public $table = [
		'name' => 'reports',
		'version' => '1.0',
		'structure' => [
			'user_id' => [
				'sql' => 'BIGINT(20)'
			],
			'report' => [
				'sql' => 'VARCHAR(255)'
			],
			'date' => [
				'sql' => 'DATE'
			],
			'time' => [
				'sql' => 'TIME'
			]
		]
	];

	public function __construct( $args ) {

		if( isset( $args ) && is_array( $args ) ) {
			foreach( $args as $arg => $value ) {
				$this->args[$arg] = $value;
			}
		}

		$this->setup();
		$this->create_report();

	}

	protected function setup() {

		global $db;
		$db->create_table( $this->table );

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

		// Log CSV report
		$this->log( $user, $name );

		// Send the generated CSV to the browser
		fpassthru( $csv );

	}

	protected function log( $user_id, $report ) {

		global $db;

		$data = [
			'user_id' => $user_id,
			'report' => $report,
			'date' => date( 'Y-m-d', time() ),
			'time' => date( 'H:i:s', time() )
		];

		$db->insert_row( $this->table, $data );

	}

}