<?php

/**
 * Snack box DB class.
 *
 * Handles interaction with snack boxes database table.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/snack-box
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_DB_Snack_Boxes extends JPID_DB {

  /**
   * Class constructor.
   *
   * @since    1.0.0
   */
  public function __construct() {
    global $wpdb;

    $this->table_name  = $wpdb->prefix . 'jpid_snack_box';
    $this->primary_key = 'snack_box_id';

    $this->setup_hooks();
  }

  /**
   * Setup class hooks.
   *
   * @since    1.0.0
   */
  private function setup_hooks() {

  }

  /**
   * Get column names & formats.
   *
   * @since     1.0.0
   * @return    array    Collection of column names & their formats.
   */
  protected function get_column_formats() {
    return array(
      'snack_box_id'    => '%d',
      'date_created'    => '%s',
      'snack_box_name'  => '%s',
      'snack_box_type'  => '%s',
      'snack_box_price' => '%s',
    );
  }

  /**
   * Get default column values.
   *
   * @since     1.0.0
   * @return    array    Collection of column default values.
   */
  protected function get_column_defaults() {
    // Available snack box types:
    // - packet
    // - custom
    return array(
      'date_created'    => $this->date_now(),
      'snack_box_name'  => '',
      'snack_box_type'  => 'custom',
      'snack_box_price' => 0.00
    );
  }

  /**
   * Get single snack box from database based on snack box's ID.
   *
   * @since     1.0.0
   * @param     int       $snack_box_id    Snack box's ID to search.
   * @return    object                     Snack box database object on success, false on failure.
   */
  public function get( $snack_box_id ) {
    if ( ! is_numeric( $snack_box_id ) ) {
      return false;
    }

    $snack_box_id = absint( $snack_box_id );

    if ( $snack_box_id < 1 ) {
      return false;
    }

    $snack_box = parent::get( $snack_box_id );

		if ( ! $snack_box ) {
			return false;
		}

		return $snack_box;
  }

  /**
   * Get single snack box from database based on snack box's ID or invoice.
   *
   * @since     1.0.0
   * @param     string        $field    snack_box_id.
   * @param     int|string    $value    The value of snack box's ID.
   * @return    object                  Snack box database object on success, false on failure.
   */
  public function get_by( $field, $value ) {
    if ( $field === 'snack_box_id' ) {

			if ( ! is_numeric( $value ) ) {
				return false;
			}

			$value = absint( $value );

			if ( $value < 1 ) {
				return false;
			}

		} else {
      return false;
    }

    $snack_box = parent::get_by( $field, $value );

		if ( ! $snack_box ) {
			return false;
		}

		return $snack_box;
  }

  /**
   * Get snack boxes from database based on provided query arguments.
   *
   * @since     1.0.0
   * @param     array     $args    Snack boxes query arguments.
   * @return    array              Array of snack box database objects.
   */
  public function get_all( $args = array() ) {
    global $wpdb;

    // List of accepted query arguments
    $accepted_args = array(
      'snack_box_id',
      'snack_box_name',
      'snack_box_type',
      'orderby',
      'order',
      'number',
      'offset'
    );

    $args = $this->filter_args( $args, $accepted_args );

    $defaults = array(
      'orderby' => 'snack_box_id',
      'order'   => 'DESC',
      'number'  => 20,
      'offset'  => 0
    );

    $args = wp_parse_args( $args, $defaults );

    // Setup cache
    $cache_key = md5( 'jpid_snack_boxes_' . serialize( $args ) );

    $snack_boxs = wp_cache_get( $cache_key, 'snack_boxes' );

    if ( $snack_boxs === false ) {
      $query      = $this->build_query( $args, " SELECT * FROM {$this->table_name} " );
      $snack_boxs = $wpdb->get_results( $query );

      wp_cache_set( $cache_key, $snack_boxs, 'snack_boxes', 3600 );
    }

    return $snack_boxs;
  }

  /**
   * Count the total numbers of snack boxes in the database.
   *
   * @since     1.0.0
   * @param     array     $args    Snack box query arguments.
   * @return    int                Total numbers of snack boxes.
   */
  public function count( $args = array() ) {
    global $wpdb;

    // List of accepted query arguments
    $accepted_args = array(
      'snack_box_id',
      'snack_box_type',
    );

    $args = $this->filter_args( $args, $accepted_args );

    // Setup cache
    $cache_key = md5( 'jpid_snack_boxes_count_' . serialize( $args ) );

    $count = wp_cache_get( $cache_key, 'snack_boxes' );

    if ( $count === false ) {
      $query = $this->build_query( $args, " SELECT COUNT({$this->primary_key}) FROM {$this->table_name} " );
      $count = $wpdb->get_var( $query );

      wp_cache_set( $cache_key, $count, 'snack_boxes', 3600 );
    }

    return $count;
  }

  /**
   * Build SQL query using provided query arguments.
   *
   * @since     1.0.0
   * @param     array     $args       Order query arguments.
   * @param     string    $select     Default SELECT clause.
   * @param     string    $where      Default WHERE clause.
   * @param     string    $snack_boxby    Default ORDER BY clause.
   * @param     string    $limit      Default LIMIT clause.
   * @return    string                Newly created SQL query.
   */
  private function build_query( $args, $select, $where = " WHERE 1=1 ", $snack_boxby = "", $limit = "" ) {
    global $wpdb;

    // Prepare the SELECT clause
    $select = esc_sql( $select );

    // Prepare the WHERE clause
    $where = esc_sql( $where );

    // Get specific snack boxes based on snack box's ID
    if ( ! empty( $args['snack_box_id'] ) ) {

      if ( is_array( $args['snack_box_id'] ) ) {
        $count     = count( $args['snack_box_id'] );
        $snack_box_ids = array_map( 'absint', $args['snack_box_id'] );
      } else {
        $count     = 1;
        $snack_box_ids = absint( $args['snack_box_id'] );
      }

      $placeholder = implode( ', ', array_fill( 0, $count, '%d' ) );

      $where .= $wpdb->prepare( " AND snack_box_id IN( {$placeholder} ) ", $snack_box_ids );

    }

    // Get specific snack boxes by snack box name
    if ( ! empty( $args['snack_box_name'] ) ) {
      $where .= $wpdb->prepare( " AND snack_box_name LIKE '%%%%" . '%s' . "%%%%' ", $args['snack_box_name'] );
    }

    // Get specific snack boxes by type
    if ( ! empty( $args['snack_box_type'] ) ) {

      if ( is_array( $args['snack_box_type'] ) ) {
        $count              = count( $args['snack_box_type'] );
        $snack_box_statuses = array_map( 'sanitize_text_field', array_map( 'trim', $args['snack_box_type'] ) );
      } else {
        $count              = 1;
        $snack_box_statuses = sanitize_text_field( trim( $args['snack_box_type'] ) );
      }

      $placeholder = implode( ', ', array_fill( 0, $count, '%s' ) );

      $where .= $wpdb->prepare( " AND snack_box_type IN( {$placeholder} ) ", $snack_box_statuses );

    }

    // Prepare the ORDER BY clause
    $orderby = esc_sql( $orderby );

    if ( ! empty( $args['orderby'] ) ) {
      $args['orderby'] = ! array_key_exists( $args['orderby'], $this->get_column_formats() ) ? 'snack_box_id' : $args['orderby'];
      $args['orderby'] = esc_sql( $args['orderby'] );

      $args['order'] = ! in_array( strtoupper( $args['order'] ), array( 'ASC', 'DESC' ) ) ? 'DESC' : $args['order'];
      $args['order'] = esc_sql( $args['order'] );

      $orderby .= " ORDER BY {$args['orderby']} {$args['order']} ";
    }

    // Prepare the LIMIT clause
    $limit = esc_sql( $limit );

    if ( ! empty( $args['number'] ) ) {

      if ( $args['number'] > 0 ) {
        $limit .= $wpdb->prepare( " LIMIT %d OFFSET %d ", absint( $args['number'] ), absint( $args['offset'] ) );
      }

    }

    return $select . $where . $orderby . $limit . ";";
  }

  /**
   * Add new snack box to the database.
   *
   * @since     1.0.0
   * @param     array    $data    Snack box's data.
   * @return    int               The newly created snack box's ID on success, false on failure.
   */
  public function insert( $data ) {
    $data = wp_parse_args( $data, $this->get_column_defaults() );
    $data = $this->sanitize_data( $data );

    if ( ! $this->valid_data( $data ) ) {
      return false;
    }

    return parent::insert( $data );
  }

  /**
   * Update existing snack box in the database.
   *
   * @since     1.0.0
   * @param     int|string    $id      Sncak's ID.
   * @param     array         $data    Snack box's data.
   * @return    int                    The updated order's ID on success, false on failure.
   */
  public function update( $id, $data ) {
    $data = $this->sanitize_data( $data );

    if ( ! $this->valid_data( $data ) ) {
      return false;
    }

    if ( empty( $id ) ) {
      return false;
    }

    $snack_box = $this->get_by( 'snack_box_id', $id );

    if ( $snack_box ) {
      return parent::update( $snack_box->payment_id, $data );
    }

    return false;
  }

  /**
   * Sanitize all insert/update data.
   *
   * This function will set a data to null if its value doesn't fit
   * the supposed constraints.
   *
   * Since MySQL has loosely checking system on data, this action is
   * needed to make sure that every data goes into the database has
   * valid value and type.
   *
   * @since     1.0.0
   * @param     array    $data    Insert/update data.
   * @return    array             Sanitized data.
   */
  protected function sanitize_data( $data ) {
    foreach ( $data as $key => $value ) {
      switch ( $key ) {
        case 'snack_box_id':
          if ( ! is_integer( $value ) || ( $value < 1 ) ) {
            $value = null;
          }
          break;
        case 'date_created':
        case 'snack_box_type':
          if ( ! is_string( $value ) ) {
            $value = null;
          } else {
            $value = trim( $value );

            if ( empty( $value ) ) {
              $value = null;
            }
          }
          break;
        case 'snack_box_name':
          if ( ! is_string( $value ) ) {
            $value = null;
          } else {
            $value = trim( $value );
          }
          break;
        case 'snack_box_price':
          if ( ! is_float( $value ) && ! is_integer( $value ) ) {
            $value = null;
          }
          break;
      }

      $data[ $key ] = $value;
    }

    return $data;
  }

  /**
   * Check for insert/update data validity.
   *
   * If there is a data with null value, then that data is invalid.
   * Empty data should be given empty string ('') or zero (0) value.
   *
   * @since     1.0.0
   * @param     array      $data    Insert/update data.
   * @return    boolean             True if all data are valid, otherwise false.
   */
  private function valid_data( $data ) {
    foreach ( $data as $key => $value ) {
      if ( is_null( $value ) ) {
        return false;
      }
    }

    return true;
  }

  /**
   * Delete existing snack box in the database.
   *
   * @since     1.0.0
   * @param     int|string    $id    Snack box's ID or email.
   * @return    int                  The deleted snack box's ID on success, false on failure.
   */
  public function delete( $id ) {
    if ( empty( $id ) ) {
      return false;
    }

    $snack_box = $this->get_by( 'snack_box_id', $id_or_invoice );

    if ( $snack_box ) {
      return parent::delete( $snack_box->payment_id );
    }

    return false;
  }

  /**
   * Check if a snack box exists.
   *
   * @since     1.0.0
   * @param     mixed      $value    The value of snack box's field to check.
   * @param     string     $field    The name of snack box's field to check.
   * @return    boolean              True if snack box exists, false if not.
   */
  public function exists( $value, $field = 'snack_box_id' ) {
    if ( ! array_key_exists( $field, $this->get_column_formats() ) ) {
      return false;
    }

    return (bool) $this->get_column_by( 'snack_box_id', $field, $value );
  }

}
