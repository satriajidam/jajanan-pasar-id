<?php

/**
 * Base class for database objects.
 *
 * Contains CRUD functions for custom content types.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/abstracts
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

abstract class JPID_DB {

  /**
	 * @since    1.0.0
	 * @var      string    The name of the database table.
	 */
  protected $table_name;

  /**
   * @since    1.0.0
   * @var      string    The name of the table's primary key.
   */
  protected $primary_key;

  /**
   * Class constructor.
   *
   * @since    1.0.0
   */
  public function __construct() {}

  /**
   * Get column names & formats.
   *
   * @since     1.0.0
   * @return    array    Collection of column names & their formats.
   */
  protected function get_column_formats() {
    return array();
  }

  /**
   * Get default column values.
   *
   * @since     1.0.0
   * @return    array    Collection of column default values.
   */
  protected function get_column_defaults() {
    return array();
  }

  /**
   * Get a single row of data based on its ID.
   *
   * @since     1.0.0
   * @param     int       $row_id    The ID of the row to query.
   * @return    object               Database query result.
   */
  public function get( $row_id ) {
    global $wpdb;

    return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->table_name} WHERE {$this->primary_key} = %d LIMIT 1;", $row_id ) );
  }

  /**
   * Get a single row of data based on specific column and value.
   *
   * @since     1.0.0
   * @param     string    $column_name     Specified column name to query.
   * @param     mixed     $column_value    Specified column value to query.
   * @return    object                     Database query result.
   */
  public function get_by( $column_name, $column_value ) {
    global $wpdb;

    return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->table_name} WHERE {$column_name} = %s LIMIT 1;", $column_value ) );
  }

  /**
   * Get all rows of data.
   *
   * @since     1.0.0
   * @return    array    Array of database query results.
   */
  public function get_all() {
    global $wpdb;

    return $wpdb->get_results( "SELECT * FROM {$this->table_name};" );
  }

  /**
   * Get the total numbers of data.
   *
   * @since     1.0.0
   * @return    int      Total numbers of data.
   */
  public function count() {
    global $wpdb;

    return $wpdb->get_var( "SELECT COUNT({$this->primary_key}) FROM {$this->table_name};" );
  }

  /**
   * Get a specific column's value based on the row's ID.
   *
   * @since     1.0.0
   * @param     string    $column    The column to get the value of.
   * @param     int       $row_id    The ID of the row to query.
   * @return    object               Database query result.
   */
  public function get_column( $column, $row_id ) {
    global $wpdb;

    return $wpdb->get_var( $wpdb->prepare( "SELECT {$column} FROM {$this->table_name} WHERE {$this->primary_key} = %d LIMIT 1;", $row_id ) );
  }

  /**
   * Get a specific column's value based on specific column and value.
   *
   * @since     1.0.0
   * @param     string    $column          The column to get the value of.
   * @param     string    $column_name     Specified column name to query.
   * @param     mixed     $column_value    Specified column value to query.
   * @return    object                     Database query result.
   */
  public function get_column_by( $column, $column_name, $column_value ) {
    global $wpdb;

    return $wpdb->get_var( $wpdb->prepare( "SELECT {$column} FROM {$this->table_name} WHERE {$column_name} = %s LIMIT 1;", $column_value ) );
  }

  /**
   * Get the next auto increment ID.
   *
   * @since     1.0.0
   * @return    int      Next auto increment ID.
   */
  public function get_next_id() {
    global $wpdb;

    $table_status = $wpdb->get_row( $wpdb->prepare( "SHOW TABLE STATUS LIKE '{$this->table_name}';" ) );

    if ( $table_status ) {
      return (int) $table_status->Auto_increment;
    }

    return false;
  }

  /**
   * Insert a new row of data.
   *
   * @since     1.0.0
   * @param     array    $data    Collection of data to be inserted.
   * @return    int               The ID of the newly inserted data on success, false on failure.
   */
  public function insert( $data ) {
    global $wpdb;

		// Set default values
		$data = wp_parse_args( $data, $this->get_column_defaults() );

		// Initialise column format array
		$column_formats = $this->get_column_formats();

		// Force fields to lower case
		$data = array_change_key_case( $data, CASE_LOWER );

		// White list columns
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data
		$data_keys      = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

    // Perform the insertion
		$wpdb->insert( $this->table_name, $data, $column_formats );

    // Check for the inserted ID
    if ( $wpdb->insert_id < 1 ) {
      return false;
    }

    // Return ID of the newly created row
		return $wpdb->insert_id;
  }

  /**
   * Update single row of data based on its ID.
   *
   * @since     1.0.0
   * @param     int        $row_id    The ID of the row to be updated.
   * @param     array      $data      Collection of data to be inserted.
   * @return    int                   The ID of the updated row on success, false on failure.
   */
  public function update( $row_id, $data ) {
    global $wpdb;

    $row_id = absint( $row_id );

    if ( empty( $row_id ) ) {
      return false;
    }

    // Initialise column format array
    $column_formats = $this->get_column_formats();

    // Force fields to lower case
    $data = array_change_key_case( $data, CASE_LOWER );

    // White list columns
    $data = array_intersect_key( $data, $column_formats );

    // Reorder $column_formats to match the order of columns given in $data
    $data_keys      = array_keys( $data );
    $column_formats = array_merge( array_flip( $data_keys ), $column_formats );
    $column_formats = array_intersect_key( $column_formats, array_flip( $data_keys ) );

    // Perform the update
    $update_success = $wpdb->update( $this->table_name, $data, array( $this->primary_key => $row_id ), $column_formats, array( '%d' ) );

    if ( $update_success === false ) {
      return false;
    }

    // Return the updated row's ID
    return $row_id;
  }

  /**
   * Delete single row of data based on its ID.
   *
   * @since     1.0.0
   * @param     int        $row_id    The ID of the row to be deleted.
   * @return    boolean               True on delete success, false on failure.
   */
  public function delete( $row_id ) {
    global $wpdb;

		$row_id = absint( $row_id );

		if( empty( $row_id ) ) {
			return false;
		}

    // Perform the delete
    $delete_success = $wpdb->delete( $this->table_name, array( $this->primary_key => $row_id ), array( '%d' ) );

		if ( $delete_success === false ) {
			return false;
		}

    // Return the deleted row's ID
    return $row_id;
  }

  /**
   * Sanitize insert/update data.
   *
   * @since     1.0.0
   * @param     array    $data    The provided data.
   * @return    array             Sanitized data.
   */
  protected function sanitize_data( $data ) {
    foreach ( $data as $key => $value ) {
      if ( is_string( $value ) ) {
        $data[ $key ] = trim( $value );
      }
    }

    return $data;
  }

  /**
   * Filter provided query arguments.
   *
   * @since     1.0.0
   * @param     array    $args             The provided query arguments.
   * @param     array    $accepted_args    List of accepted query arguments.
   * @return    array                      Filtered query arguments.
   */
  protected function filter_args( $args, $accepted_args = array() ) {
    foreach ( $args as $key => $value ) {
      $key = strtolower( $key );

      if ( ! in_array( $key, $accepted_args ) ) {
        unset( $args[ $key ] );
      }
    }

    return $args;
  }

  /**
   * Convert UNIX timestamp to SQL date string.
   *
   * @since     1.0.0
   * @param     int       $time    UNIX timestamp.
   * @return    string             SQL date string.
   */
  protected function time_to_date( $time ) {
    return date( 'Y-m-d H:i:s', $time );
  }

  /**
   * Get current SQL date string.
   *
   * @since     1.0.0
   * @return    string    Date string.
   */
  protected function date_now() {
    return $this->time_to_date( current_time( 'timestamp' ) );
  }

  /**
   * Convert SQL date string to UNIX timestamp.
   *
   * @since     1.0.0
   * @param     string    $date    SQL date string.
   * @return    int                UNIX timestamp.
   */
  protected function date_to_time( $date ) {
    return strtotime( $date );
  }

}
