<?php

/**
 * Payment DB class.
 *
 * Handles interaction with payments database table.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/payment
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_DB_Payments extends JPID_DB {

  /**
   * Class constructor.
   *
   * @since    1.0.0
   */
  public function __construct() {
    global $wpdb;

    $this->table_name  = $wpdb->prefix . 'jpid_payments';
    $this->primary_key = 'payment_id';

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
      'payment_id'              => '%d',
      'order_invoice'           => '%s',
      'date_submitted'          => '%s',
      'receipt_id'              => '%d',
      'payment_status'          => '%s',
      'payment_bank'            => '%s',
      'payment_account_name'    => '%s',
      'payment_account_number'  => '%s',
      'transfer_bank'           => '%s',
      'transfer_account_name'   => '%s',
      'transfer_account_number' => '%s',
      'transfer_amount'         => '%f',
      'transfer_date'           => '%s',
      'transfer_note'           => '%s'
    );
  }

  /**
   * Get default column values.
   *
   * @since     1.0.0
   * @return    array    Collection of column default values.
   */
  protected function get_column_defaults() {
    return array(
      'order_invoice'           => '',
      'date_submitted'          => $this->date_now(),
      'receipt_id'              => 0,
      'payment_status'          => JPID_Payment_Status::UNVERIFIED,
      'payment_bank'            => '',
      'payment_account_name'    => '',
      'payment_account_number'  => '',
      'transfer_bank'           => '',
      'transfer_account_name'   => '',
      'transfer_account_number' => '',
      'transfer_amount'         => 0.00,
      'transfer_date'           => '',
      'transfer_note'           => ''
    );
  }

  /**
   * Get single payment from database based on payment's ID.
   *
   * @since     1.0.0
   * @param     int       $payment_id    Payment's ID to search.
   * @return    object                   Payment database object on success, false on failure.
   */
  public function get( $payment_id ) {
    if ( ! is_numeric( $payment_id ) ) {
      return false;
    }

    $payment_id = absint( $payment_id );

    if ( $payment_id < 1 ) {
      return false;
    }

    $payment = parent::get( $payment_id );

		if ( ! $payment ) {
			return false;
		}

		return $payment;
  }

  /**
   * Get single payment from database based on payment's ID or invoice.
   *
   * @since     1.0.0
   * @param     string        $field    payment_id.
   * @param     int|string    $value    The value of payment's ID.
   * @return    object                  Payment database object on success, false on failure.
   */
  public function get_by( $field, $value ) {
    if ( $field === 'payment_id' ) {

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

    $payment = parent::get_by( $field, $value );

		if ( ! $payment ) {
			return false;
		}

		return $payment;
  }

  /**
   * Get payments from database based on provided query arguments.
   *
   * @since     1.0.0
   * @param     array     $args    Payments query arguments.
   * @return    array              Array of order database objects.
   */
  public function get_all( $args = array(), $use_cache = false ) {
    global $wpdb;

    // List of accepted query arguments
    $accepted_args = array(
      'payment_id',
      'order_invoice',
      'date_submitted',
      'payment_status',
      'transfer_account_name',
      'orderby',
      'order',
      'number',
      'offset'
    );

    $args = $this->filter_args( $args, $accepted_args );

    $defaults = array(
      'orderby' => 'payment_id',
      'order'   => 'DESC',
      'number'  => 20,
      'offset'  => 0
    );

    $args = wp_parse_args( $args, $defaults );

    // Setup cache
    $cache_key = md5( 'jpid_payments_' . serialize( $args ) );
    $payments = false;

    if ( $use_cache ) {
      $payments = wp_cache_get( $cache_key, 'payments' );
    }

    if ( $payments === false ) {
      $query    = $this->build_query( $args, " SELECT * FROM {$this->table_name} " );
      $payments = $wpdb->get_results( $query );

      wp_cache_set( $cache_key, $payments, 'payments', 3600 );
    }

    return $payments;
  }

  /**
   * Count the total numbers of payments in the database.
   *
   * @since     1.0.0
   * @param     array     $args    Payment query arguments.
   * @return    int                Total numbers of payments.
   */
  public function count( $args = array(), $use_cache = false ) {
    global $wpdb;

    // List of accepted query arguments
    $accepted_args = array(
      'payment_id',
      'order_invoice',
      'date_submitted',
      'payment_status'
    );

    $args = $this->filter_args( $args, $accepted_args );

    // Setup cache
    $cache_key = md5( 'jpid_payments_count_' . serialize( $args ) );
    $count     = false;

    if ( $use_cache ) {
      $count = wp_cache_get( $cache_key, 'payments' );
    }

    if ( $count === false ) {
      $query = $this->build_query( $args, " SELECT COUNT({$this->primary_key}) FROM {$this->table_name} " );
      $count = $wpdb->get_var( $query );

      wp_cache_set( $cache_key, $count, 'payments', 3600 );
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
   * @param     string    $orderby    Default ORDER BY clause.
   * @param     string    $limit      Default LIMIT clause.
   * @return    string                Newly created SQL query.
   */
  private function build_query( $args, $select, $where = " WHERE 1=1 ", $orderby = "", $limit = "" ) {
    global $wpdb;

    // Prepare the SELECT clause
    $select = esc_sql( $select );

    // Prepare the WHERE clause
    $where = esc_sql( $where );

    // Get specific payments based on payment's ID
    if ( ! empty( $args['payment_id'] ) ) {

      if ( is_array( $args['payment_id'] ) ) {
        $count       = count( $args['payment_id'] );
        $payment_ids = array_map( 'absint', $args['payment_id'] );
      } else {
        $count       = 1;
        $payment_ids = absint( $args['payment_id'] );
      }

      $placeholder = implode( ', ', array_fill( 0, $count, '%d' ) );

      $where .= $wpdb->prepare( " AND payment_id IN( {$placeholder} ) ", $payment_ids );

    }

    // Get specific payments based on order's invoice
		if ( ! empty( $args['order_invoice'] ) ) {

			if ( is_array( $args['order_invoice'] ) ) {
				$count            = count( $args['order_invoice'] );
				$payment_invoices = array_map( 'sanitize_text_field', array_map( 'trim', $args['order_invoice'] ) );
			} else {
        $count            = 1;
        $payment_invoices = sanitize_text_field( trim( $args['order_invoice'] ) );
      }

      $placeholder = implode( ', ', array_fill( 0, $count, '%s' ) );

      $where .= $wpdb->prepare( " AND order_invoice IN( {$placeholder} ) ", $payment_invoices );

		}

    // Get payments made on specific date or in a date range
    if ( ! empty( $args['date_submitted'] ) ) {

      if ( is_array( $args['date_submitted'] ) ) {

        if ( ! empty( $args['date_submitted']['start'] ) ) {
          $start = date( 'Y-m-d 00:00:00', strtotime( $args['date_submitted']['start'] ) );

          $where .= $wpdb->prepare( " AND date_submitted >= %s ", $start );
        }

        if ( ! empty( $args['date_submitted']['end'] ) ) {
          $end = date( 'Y-m-d 23:59:59', strtotime( $args['date_submitted']['end'] ) );

          $where .= $wpdb->prepare( " AND date_submitted <= %s ", $end );
        }

      } else {
        $year  = date( 'Y', strtotime( $args['date_submitted'] ) );
        $month = date( 'm', strtotime( $args['date_submitted'] ) );
        $day   = date( 'd', strtotime( $args['date_submitted'] ) );

        $where .= $wpdb->prepare( " AND YEAR ( date_submitted ) = %s AND MONTH ( date_submitted ) = %s AND DAY ( date_submitted ) = %s ", $year, $month, $day  );
      }

    }

    // Get specific payments by status
    if ( ! empty( $args['payment_status'] ) ) {

      if ( is_array( $args['payment_status'] ) ) {
        $count            = count( $args['payment_status'] );
        $payment_statuses = array_map( 'sanitize_text_field', array_map( 'trim', $args['payment_status'] ) );
      } else {
        $count            = 1;
        $payment_statuses = sanitize_text_field( trim( $args['payment_status'] ) );
      }

      $placeholder = implode( ', ', array_fill( 0, $count, '%s' ) );

      $where .= $wpdb->prepare( " AND payment_status IN( {$placeholder} ) ", $payment_statuses );

    }

    // Get specific payments by transfer account name
    if ( ! empty( $args['transfer_account_name'] ) ) {
      $where .= $wpdb->prepare( " AND transfer_account_name LIKE '%%%%" . '%s' . "%%%%' ", $args['transfer_account_name'] );
    }

    // Prepare the ORDER BY clause
    $orderby = esc_sql( $orderby );

    if ( ! empty( $args['orderby'] ) ) {
      $args['orderby'] = ! array_key_exists( $args['orderby'], $this->get_column_formats() ) ? 'payment_id' : $args['orderby'];
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
   * Add new payment to the database.
   *
   * @since     1.0.0
   * @param     array    $data    Payment's data.
   * @return    int               The newly created payment's ID on success, false on failure.
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
   * Update existing payment in the database.
   *
   * @since     1.0.0
   * @param     int|string    $id      Payment's ID.
   * @param     array         $data    Payment's data.
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

    $payment = $this->get_by( 'payment_id', $id );

    if ( $payment ) {
      return parent::update( $payment->payment_id, $data );
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
        case 'payment_id':
          if ( ! is_integer( $value ) || ( $value < 1 ) ) {
            $value = null;
          }
          break;
        case 'order_invoice':
        case 'payment_status':
        case 'payment_bank':
        case 'payment_account_name':
        case 'payment_account_number':
        case 'transfer_bank':
        case 'transfer_account_name':
        case 'transfer_account_number':
          if ( ! is_string( $value ) ) {
            $value = null;
          } else {
            $value = sanitize_text_field( trim( $value ) );

            if ( empty( $value ) ) {
              $value = null;
            }
          }
          break;
        case 'date_submitted':
        case 'transfer_date':
          if ( ! is_string( $value ) ) {
            $value = null;
          } else {
            $time = strtotime( $value );

            if ( empty( $time ) ) {
              $value = null;
            } else {
              $value = date( 'Y-m-d H:i:s', $time );
            }
          }
          break;
        case 'receipt_id':
          if ( ! is_integer( $value ) || ( $value < 0 ) ) {
            $value = null;
          }
          break;
        case 'transfer_note':
          if ( ! is_string( $value ) ) {
            $value = null;
          } else {
            $value = sanitize_text_field( trim( $value ) );
          }
          break;
        case 'transfer_amount':
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
   * Delete existing payment in the database.
   *
   * @since     1.0.0
   * @param     int|string    $id    Payment's ID.
   * @return    int                  The deleted payment's ID on success, false on failure.
   */
  public function delete( $id ) {
    if ( empty( $id ) ) {
      return false;
    }

    $payment = $this->get_by( 'payment_id', $id );

    if ( $payment ) {
      return parent::delete( $payment->payment_id );
    }

    return false;
  }

  /**
   * Check if a payment exists.
   *
   * @since     1.0.0
   * @param     mixed      $value    The value of payment's field to check.
   * @param     string     $field    The name of payment's field to check.
   * @return    boolean              True if payment exists, false if not.
   */
  public function exists( $value, $field = 'payment_id' ) {
    if ( ! array_key_exists( $field, $this->get_column_formats() ) ) {
      return false;
    }

    return (bool) $this->get_column_by( 'payment_id', $field, $value );
  }

}
