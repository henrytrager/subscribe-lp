<?php
/**
 * Database Class: Manages ALL interactions with the database
 *
 * @package    Subscribe LP
 * @author     Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright  2014 Hazard Media Group LLC
 * @license    MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link       https://github.com/Alekhen/subscribe-lp
 * @version    Release: 0.1 (ALPHA)
 */

class Database {

	public $connection = NULL;

	public $table = [
		'name' => 'tables',
		'version' => '1.0',
		'structure' => [
			'table_name' => [
				'sql' => 'VARCHAR(255)'
			],
			'table_version' => [
				'sql' => 'DECIMAL(4,1)',
				'default' => '0.0'
			]
		]
	];

	public function __construct() {

		$this->db_connect();
		$this->db_setup();

	}

	protected function db_connect() {

		try {
			$conn = new PDO( 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS );
			$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$this->connection = $conn;
		} catch( PDOException $e ) {
			$this->connection = NULL;
			die( 'Error: Could not connect to database: ' . $e->getMessage() );
		}

	}

	protected function db_setup() {

		$this->create_table( $this->table );

	}

	public function create_table( $table_array ) {

		extract( $table_array );
		$table = TABLE_PREFIX . $name;

		$sql = "CREATE TABLE IF NOT EXISTS $table ( ";
		$sql .= "id BIGINT(20) NOT NULL AUTO_INCREMENT, ";

		foreach( $structure as $column => $args ) {
			$default = ( !empty( $args['default'] ) && $args['sql'] !== 'LONGTEXT' ) ? !empty( $args['encrypt'] ) ? " DEFAULT '" . Encryption::encrypt( $args['default'], $key ) . "'" : " DEFAULT '{$args['default']}'" : "";
			$sql .= "{$column} {$args['sql']} NOT NULL{$default}, ";
		}

		$sql .= "UNIQUE KEY id (id) )";

		$stmt = $this->connection->prepare( $sql );
		$results = $stmt->execute();
		$this->set_table_version( $table_array );
		return $results;

	}

	public function set_table_version( $table_array ) {

		extract( $table_array );

		$data = $this->get_row( $this->table, 'table_name', $name );

		if( empty( $data['table_name'] ) ) :

			$data['table_name'] = $name;
			$data['table_version'] = $version;
			$this->insert_row( $this->table, $data );

		else :

			if( $data['table_version'] !== $version ) :

					$data['table_version'] = $version;
					$this->update_table( $table_array );
					$this->update_row( $this->table, $data );

			endif;

		endif;

	}

	public function update_table( $table_array ) {

		extract( $table_array );
		$table = TABLE_PREFIX . $name;
		$columns = array();
		$count = 0;

		$sql = "SHOW COLUMNS FROM $table";
		$stmt = $this->connection->prepare( $sql );
		$result = $stmt->execute();

		while( $column_data = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
			$columns[$column_data['Field']] = $column_data;
		}

		foreach( $structure as $column => $args ) {

			if( !array_key_exists( $column, $columns ) ) :

				$default = ( !empty( $args['default'] ) ) ? $args['default'] : NULL;
				$this->add_column( $table, $column, $args['sql'], $default );

			else :

				foreach( $columns as $col => $col_values ) {

					if( !array_key_exists( $col, $structure ) && $count == 0 ) {
						$this->delete_column( $table, $col );
					}

				}

			endif;
			$count++;

		}

	}

	public function delete_table( $table_array ) {

		extract( $table_array );
		$table = TABLE_PREFIX . $name;

		$sql = "DROP TABLE IF EXISTS $table";
		$stmt = $this->connection->prepare( $sql );
		return $stmt->execute();

	}

	protected function add_column( $table, $column, $datatype, $default = NULL ) {

		if( !empty( $table ) && !empty( $column ) && !empty( $datatype ) ) :

			$default = ( !empty( $default ) ) ? " DEFAULT '$default'" : "";
			$sql = "ALTER TABLE $table ADD $column $datatype NOT NULL$default";
			$stmt = $this->connection->prepare( $sql );
			return $stmt->execute();

		endif;

		return false;

	}

	protected function update_column_datatype( $table, $column, $datatype ) {

		if( !empty( $table ) && !empty( $column ) && !empty( $datatype ) ) :

			$sql = "ALTER TABLE $table MODIFY COLUMN $column $datatype";
			$stmt = $this->connection->prepare( $sql );
			return $stmt->execute();

		endif;

		return false;

	}

	protected function delete_column( $table, $column ) {

		if( !empty( $table ) && !empty( $column ) )	:

			$sql = "ALTER TABLE $table DROP COLUMN $column";
			$stmt = $this->connection->prepare( $sql );
			return $stmt->execute();

		endif;

		return false;

	}

	public function db_query( $sql, $bindings = array() ) {

		$stmt = $this->connection->prepare( $sql );
		$stmt->execute( $bindings );
		$results = $stmt->fetchAll();
		return $results ? $results : false;

	}

	public function have_rows( $table_array ) {

		return empty( $this->row_count( $table_array ) ) ? false : true;

	}

	public function row_count( $table_array ) {

		extract( $table_array );
		$table = TABLE_PREFIX . $name;
		$results = $this->db_query( "SELECT COUNT(*) FROM $table" );
		return (int)$results[0][0];

	}

	public function get_data( $table_array, $column_array = NULL, $match_array = NULL ) {

		extract( $table_array );
		$data = array();

		if( !empty( $name ) && !empty( $structure ) ) :

			$table = TABLE_PREFIX . $name;
			$cols = "*";
			$match = "";

			// Build column statement
			if( !empty( $column_array ) && is_array( $column_array ) ) {
				$cols = "";
				for( $i = 0; $i < count( $column_array ); $i++ ) {
					$cols .= ( ( $i + 1 ) !== count( $column_array ) ) ? $column_array[$i] . "," : $column_array[$i];
				}
			}

			// Build match statement
			if( !empty( $match_array ) && is_array( $match_array ) ) {
				$count = 1;
				foreach( $match_array as $m_col => $m_val ) {
					$m_val = !empty( $structure[$m_col]['encrypt'] ) ? Encryption::encrypt( $m_val, $key ) : $m_val;
					$match .= ( $count !== count( $match_array ) ) ? "$m_col = '$m_val' OR " : "$m_col = '$m_val'";
					$count++;
				}
			}

			// Run query
			$results = isset( $match_array )
				? $this->db_query( "SELECT $cols FROM $table WHERE $match" )
				: $this->db_query( "SELECT $cols FROM $table" );

			// Return query results as associative array
			if( !empty( $results ) ) :

				foreach( $results as $row => $col ) {

					$row_data = array();

					// NEED TO PROPERLY FORMAT DATA TO DELIVER (decrypt encrypted data)

					array_push( $data, $row_data );

				}

			else :

				// Return default values
				if( empty( $match_array ) ) {
					$default_data = array();
					foreach( $structure as $column => $args ) {
						if( !empty( $column_array ) && is_array( $column_array ) && in_array( $column, $column_array ) ) {
							$default_data[$column] = isset( $args['default'] ) ? $args['default'] : "";
						} else {
							$default_data[$column] = isset( $args['default'] ) ? $args['default'] : "";
						}
					}
					array_push( $data, $default_data );
				}

			endif;

		endif;

		return $data;

	}

	public function get_joined_data( $table_array ) {



	}

	public function get_row( $table_array, $unique_key, $unique_value ) {

		extract( $table_array );
		$data = array();

		if( !empty( $name ) && !empty( $structure ) ) :

			$table = TABLE_PREFIX . $name;
			$unique_value = !empty( $structure[$unique_key]['encrypt'] ) ? Encryption::encrypt( $unique_value, $key ) : $unique_value;
			$results = $this->db_query( "SELECT * FROM $table WHERE $unique_key = '$unique_value'" );

			if( !empty( $results ) ) :

				// Return query results as associative array
				$structure['id'] = array();
				foreach( $structure as $column => $args ) {
					$value = !empty( $args['encrypt'] ) ? Encryption::decrypt( $results[0][$column], $key ) : html_entity_decode( $results[0][$column] );
					$value = stripslashes( $value );
					$data[$column] = $value;
				}

			else :

				// Return default values
				foreach( $structure as $column => $args ) {
					$data[$column] = !empty( $args['default'] ) ? $args['default']: "";
				}

			endif;

		endif;

		return $data;

	}

	public function save_data( $table_array, $data ) {

		return !empty( $data['id'] )
			? $this->update_row( $table_array, $data )
			: $this->insert_row( $table_array, $data );

	}

	public function insert_row( $table_array, $data ) {

		extract( $table_array );
		$table = TABLE_PREFIX . $name;
		$columns = "( ";
		$values = "( ";
		$count = 1;

		foreach( $data as $column => $value ) {
			$value = !empty( $structure[$column]['encrypt'] ) ? Encryption::encrypt( $value, $key ) : htmlentities( $value );
			$columns .= ( $count !== count( $data ) ) ? "$column, " : "$column ) " ;
			$values .= ( $count !== count( $data ) ) ? "'$value', " : "'$value' )";
			$count++;
		}

		$sql = "INSERT INTO $table $columns VALUES $values";
		$stmt = $this->connection->prepare( $sql );
		return $stmt->execute();

	}

	public function update_row( $table_array, $data ) {

		extract( $table_array );
		$table = TABLE_PREFIX . $name;
		$id = $data['id'];
		$sets = "";
		$count = 2;

		foreach( $data as $column => $value ) {
			if( $column !== 'id' ) {
				$value = !empty( $structure[$column]['encrypt'] ) ? Encryption::encrypt( $value, $key ) : htmlentities( $value );
				$sets .= ( $count !== count( $data ) ) ? "$column = '$value', " : "$column = '$value' ";
				$count++;
			}
		}

		$sql = "UPDATE $table SET $sets WHERE id = '$id'";
		$stmt = $this->connection->prepare( $sql );
		return $stmt->execute();

	}

	public function delete_row( $table_array, $unique_key, $unique_value ) {

		extract( $table_array );
		$table = TABLE_PREFIX . $name;
		$unique_value = !empty( $structure[$unique_key]['encrypt'] ) ? Encryption::encrypt( $unique_value, $key ) : $unique_value;
		$sql = "DELETE FROM $table WHERE $unique_key = '$unique_value'";
		$stmt = $this->connection->prepare( $sql );
		return $stmt->execute();

	}

}