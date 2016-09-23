<?php

/**
 * Order DB class.
 *
 * Handles interaction with customers database table.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/abstracts
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_DB_Orders extends JPID_DB {

  /**
   * Class constructor.
   *
   * @since    1.0.0
   */
  public function __construct() {
    global $wpdb;

    $this->table_name  = $wpdb->prefix . 'jpid_customers';
    $this->primary_key = 'customer_id';
  }

  /**
   * Get column names & formats.
   *
   * @since     1.0.0
   * @return    array    Collection of column names & their formats.
   */
  protected function get_column_formats() {
    return array(
      'order_id' => '%d',
      'order_invoice' => '%d',
      'order_date' => '%s',
      'order_status' => '%s',
      'customer_id' => '%d',
      'recipient_name' => '%s',
      'recipient_phone' => '%s',
      'delivery_date' => '%s',
      'delivery_address' => '%s',
      'delivery_province' => '%s',
      'delivery_city' => '%s',
      'delivery_cost' => '%f',
      'delivery_note' => '%s',
      'order_cost' => '%f',
      'modified_date' => '%s'
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
      'delivery_note' => '',
      'modified_date' => ''
    );
  }

  /**
   * Get single order from database based on order's ID.
   *
   * @since     1.0.0
   * @param     int       $order_id    Order's ID to search.
   * @return    object                 Order database object on success, false on failure.
   */
  public function get_order( $order_id ) {
    if ( ! is_numeric( $order_id ) ) {
      return false;
    }

    $order_id = absint( $order_id );

    if ( $order_id < 1 ) {
      return false;
    }

    $order = $this->get( $order_id );

    if ( ! $order ) {
      return false;
    }

    return $order;
  }

  /**
   * Get single customer from database based on customer ID, user ID, or customer email.
   *
   * @since     1.0.0
   * @param     string        $field    customer_id, user_id, or customer_email.
   * @param     int|string    $value    The value of customer ID, user ID, or customer email.
   * @return    object                  Customer database object on success, false on failure.
   */
  public function get_customer_by( $field, $value ) {
    $field = strtolower( $field );

    if ( ! in_array( $field, array( 'customer_id', 'user_id', 'customer_email' ) ) ) {
      return false;
    }

    if ( $field === 'customer_id' || $field === 'user_id' ) {

      if ( ! is_numeric( $value ) ) {
        return false;
      }

      $value = absint( $value );

      if ( $value < 1 ) {
        return false;
      }

    } elseif ( $field === 'customer_email' ) {

      if ( ! is_email( $value ) ) {
        return false;
      }

      $value = sanitize_text_field( trim( $value ) );

    }

    if ( ! $value ) {
      return false;
    }

    $customer = $this->get_by( $field, $value );

    if ( ! $customer ) {
      return false;
    }

    return $customer;
  }

  /**
   * Get customers from database based on provided query arguments.
   *
   * @since     1.0.0
   * @param     array     $args    Customers query arguments.
   * @return    array              Array of customer database objects.
   */
  public function get_customers( $args = array() ) {
    global $wpdb;

    // List of accepted query arguments
    $accepted_args = array(
      'customer_id',
      'user_id',
      'customer_status',
      'customer_email',
      'date_created',
      'customer_name',
      'orderby',
      'order',
      'number',
      'offset'
    );

    $args = $this->strip_args( $args, $accepted_args );

    $defaults = array(
      'orderby' => 'customer_id',
      'order'   => 'DESC',
      'number'  => 20,
      'offset'  => 0,
    );

    $args = wp_parse_args( $args, $defaults );

    // Setup cache
    $cache_key = md5( 'jpid_customers_' . serialize( $args ) );

    $customers = wp_cache_get( $cache_key, 'customers' );

    if ( $customers === false ) {
      $query     = $this->build_query( $args, " SELECT * FROM {$this->table_name} " );
      $customers = $wpdb->get_results( $query );

      wp_cache_set( $cache_key, $customers, 'customers', 3600 );
    }

    return $customers;
  }

  /**
   * Count the total numbers of customers in the database.
   *
   * @since     1.0.0
   * @param     array     $args    Customer query arguments.
   * @return    int                Total numbers of customers.
   */
  public function count_customers( $args = array() ) {
    global $wpdb;

    // List of accepted query arguments
    $accepted_args = array(
      'customer_id',
      'user_id',
      'customer_status',
      'customer_email',
      'date_created',
      'customer_name'
    );

    $args = $this->strip_args( $args, $accepted_args );

    // Setup cache
    $cache_key = md5( 'jpid_customers_count_' . serialize( $args ) );

    $count = wp_cache_get( $cache_key, 'customers' );

    if ( $count === false ) {
      $query = $this->build_query( $args, " SELECT COUNT({$this->primary_key}) FROM {$this->table_name} " );
      $count = $wpdb->get_var( $query );

      wp_cache_set( $cache_key, $count, 'customers', 3600 );
    }

    return intval( $count );
  }

  /**
   * Build SQL query from using provided query arguments.
   *
   * @since     1.0.0
   * @param     array     $args       Customer query arguments.
   * @param     string    $select     Default SELECT clause.
   * @param     string    $where      Default WHERE clause.
   * @param     string    $orderby    Default ORDER BY clause.
   * @param     string    $limit      Default LIMIT clause.
   * @return    string                Newly created SQL query.
   */
  protected function build_query( $args, $select, $where = " WHERE 1=1 ", $orderby = "", $limit = "" ) {
    global $wpdb;

    // Prepare the SELECT clause
    $select = esc_sql( $select );

    // Prepare the WHERE clause
    $where = esc_sql( $where );

    // Get specific customers
    if ( ! empty( $args['customer_id'] ) ) {

      if ( is_array( $args['customer_id'] ) ) {
        $count        = count( $args['customer_id'] );
        $customer_ids = array_map( 'absint', $args['customer_id'] );
      } else {
        $count        = 1;
        $customer_ids = absint( $args['customer_id'] );
      }

      $placeholder = implode( ', ', array_fill( 0, $count, '%d' ) );

      $where .= $wpdb->prepare( " AND customer_id IN( {$placeholder} ) ", $customer_ids );

    }

    // Get customers for specific user accounts
    if ( ! empty( $args['user_id'] ) ) {

      if ( is_array( $args['user_id'] ) ) {
        $count    = count( $args['user_id'] );
        $user_ids = array_map( 'absint', $args['user_id'] );
      } else {
        $count    = 1;
        $user_ids = absint( $args['user_id'] );
      }

      $placeholder = implode( ', ', array_fill( 0, $count, '%d' ) );

      $where .= $wpdb->prepare( " AND user_id IN( {$placeholder} ) ", $user_ids );

    }

    // Get specific customers by status
    if ( ! empty( $args['customer_status'] ) ) {

      if ( is_array( $args['customer_status'] ) ) {
        $count             = count( $args['customer_status'] );
        $customer_statuses = array_map( 'sanitize_text_field', array_map( 'trim', $args['customer_status'] ) );
      } else {
        $count             = 1;
        $customer_statuses = sanitize_text_field( trim( $args['customer_status'] ) );
      }

      $placeholder = implode( ', ', array_fill( 0, $count, '%s' ) );

      $where .= $wpdb->prepare( " AND customer_status IN( {$placeholder} ) ", $customer_statuses );

    }

    // Get specific customers by email
    if ( ! empty( $args['customer_email'] ) ) {

      if ( is_array( $args['customer_email'] ) ) {
        $count           = count( $args['customer_email'] );
        $customer_emails = array_map( 'sanitize_text_field', array_map( 'trim', $args['customer_email'] ) );
      } else {
        $count           = 1;
        $customer_emails = sanitize_text_field( trim( $args['customer_email'] ) );
      }

      $placeholder = implode( ', ', array_fill( 0, $count, '%s' ) );

      $where .= $wpdb->prepare( " AND customer_email IN( {$placeholder} ) ", $customer_emails );

    }

    // Get customers created on specific date or in a date range
    if ( ! empty( $args['date_created'] ) ) {

      if ( is_array( $args['date_created'] ) ) {

        if ( ! empty( $args['date_created']['start'] ) ) {
          $start = date( 'Y-m-d 00:00:00', strtotime( $args['date_created']['start'] ) );

          $where .= $wpdb->prepare( " AND date_created >= %s ", $start );
        }

        if ( ! empty( $args['date_created']['end'] ) ) {
          $end = date( 'Y-m-d 23:59:59', strtotime( $args['date_created']['end'] ) );

          $where .= $wpdb->prepare( " AND date_created <= %s ", $end );
        }

      } else {
        $year  = date( 'Y', strtotime( $args['date_created'] ) );
        $month = date( 'm', strtotime( $args['date_created'] ) );
        $day   = date( 'd', strtotime( $args['date_created'] ) );

        $where .= $wpdb->prepare( " AND YEAR ( date_created ) = %s AND MONTH ( date_created ) = %s AND DAY ( date_created ) = %s ", $year, $month, $day  );
      }

    }

    // Get specific customers by name
    if ( ! empty( $args['customer_name'] ) ) {
      $where .= $wpdb->prepare( " AND customer_name LIKE '%%%%" . '%s' . "%%%%' ", $args['customer_name'] );
    }

    // TODO: Get customers with specific total spendings range.

    // Prepare the ORDER BY clause
    $orderby = esc_sql( $orderby );

    if ( ! empty( $args['orderby'] ) ) {
      $args['orderby'] = ! array_key_exists( $args['orderby'], $this->get_column_formats() ) ? 'customer_id' : $args['orderby'];
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
   * Add new customer to the database.
   *
   * @since     1.0.0
   * @param     array    $data    Customer's data.
   * @return    int               The newly created customer's ID on success, false on failure.
   */
  public function insert_customer( $data ) {
    $data = wp_parse_args( $data, $this->get_column_defaults() );

    if ( empty( $data['customer_email'] ) || ! is_email( $data['customer_email'] ) ) {
      return false;
    }

    $customer_id = $this->insert( $data );

    if ( $customer_id <= 0 ) {
      return false;
    }

    return $customer_id;
  }

  /**
   * Update existing customer in the database.
   *
   * @since     1.0.0
   * @param     int|string    $id_or_email    Customer's ID or email.
   * @param     array         $data           Customer's data.
   * @return    int                           The updated customer's ID on success, false on failure.
   */
  public function update_customer( $id_or_email, $data ) {
    if ( empty( $id_or_email ) ) {
      return false;
    }

    $column   = is_email( $id_or_email ) ? 'customer_email' : 'customer_id';
    $customer = $this->get_customer_by( $column, $id_or_email );

    if ( $customer ) {
      $update_success = $this->update( $customer->customer_id, $data );

      if ( ! $update_success ) {
        return false;
      }

      return $customer->customer_id;
    }

    return false;
  }

  /**
   * Delete existing customer in the database.
   *
   * @since     1.0.0
   * @param     int|string    $id_or_email    Customer's ID or email.
   * @return    int                           The updated customer's ID on success, false on failure.
   */
  public function delete_customer( $id_or_email ) {
    if ( empty( $id_or_email ) ) {
      return false;
    }

    $column   = is_email( $id_or_email ) ? 'customer_email' : 'customer_id';
    $customer = $this->get_customer_by( $column, $id_or_email );

    if ( $customer->customer_id > 0 ) {
      return $this->delete( $customer->customer_id );
    }

    return false;
  }

  /**
   * Check if a customer exists.
   *
   * @since     1.0.0
   * @param     mixed      $value    The value of customer's field to check.
   * @param     string     $field    The name of customer's field to check.
   * @return    boolean              True if customer exists, false if not.
   */
  public function customer_exists( $value, $field = 'customer_email' ) {
    if ( ! array_key_exists( $field, $this->get_column_formats() ) ) {
      return false;
    }

    return (bool) $this->get_column_by( 'customer_id', $field, $value );
  }

}