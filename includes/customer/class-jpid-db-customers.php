<?php

/**
 * Customer DB class.
 *
 * Handles interaction with customers database table.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/customer
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_DB_Customers extends JPID_DB {

  /**
   * Class constructor.
   *
   * @since    1.0.0
   */
  public function __construct() {
    global $wpdb;

    $this->table_name  = $wpdb->prefix . 'jpid_customers';
    $this->primary_key = 'customer_id';

    $this->setup_hooks();
  }

  /**
   * Setup class hooks.
   *
   * @since    1.0.0
   */
  private function setup_hooks() {
    add_action( 'profile_update', array( $this, 'update_customer_email_on_user_update' ) );
  }

  /**
   * Get column names & formats.
   *
   * @since     1.0.0
   * @return    array    Collection of column names & their formats.
   */
  protected function get_column_formats() {
    return array(
      'customer_id'       => '%d',
      'user_id'           => '%d',
      'date_created'      => '%s',
      'customer_status'   => '%s',
      'customer_name'     => '%s',
      'customer_email'    => '%s',
      'customer_phone'    => '%s',
      'customer_address'  => '%s',
      'customer_province' => '%s',
      'customer_city'     => '%s',
      'order_count'       => '%d',
      'order_value'       => '%f'
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
      'user_id'           => 0,
      'date_created'      => $this->date_now(),
      'customer_status'   => JPID_Customer_Status::GUEST,
      'customer_name'     => '',
      'customer_email'    => '',
      'customer_phone'    => '',
      'customer_address'  => '',
      'customer_province' => '',
      'customer_city'     => '',
      'order_count'       => 0,
      'order_value'       => 0.00
    );
  }

  /**
   * Get single customer from database based on customer's ID.
   *
   * @since     1.0.0
   * @param     int       $customer_id    Customer's ID to search.
   * @return    object                    Customer database object on success, false on failure.
   */
  public function get( $customer_id ) {
    if ( ! is_numeric( $customer_id ) ) {
      return false;
    }

    $customer_id = absint( $customer_id );

    if ( $customer_id < 1 ) {
      return false;
    }

    $customer = parent::get( $customer_id );

		if ( ! $customer ) {
			return false;
		}

		return $customer;
  }

  /**
   * Get single customer from database based on customer ID, user ID, or customer email.
   *
   * @since     1.0.0
   * @param     string        $field    customer_id, user_id, or customer_email.
   * @param     int|string    $value    The value of customer ID, user ID, or customer email.
   * @return    object                  Customer database object on success, false on failure.
   */
  public function get_by( $field, $value ) {
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

		} else {
      return false;
    }

		if ( ! $value ) {
			return false;
		}

    $customer = parent::get_by( $field, $value );

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
  public function get_all( $args = array(), $use_cache = false ) {
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

    $args = $this->filter_args( $args, $accepted_args );

    $defaults = array(
      'orderby' => 'customer_id',
      'order'   => 'DESC',
      'number'  => 20,
      'offset'  => 0,
    );

    $args = wp_parse_args( $args, $defaults );

    // Setup cache
    $cache_key = md5( 'jpid_customers_' . serialize( $args ) );
    $customers = false;

    if ( $use_cache ) {
      $customers = wp_cache_get( $cache_key, 'customers' );
    }

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
  public function count( $args = array(), $use_cache = false ) {
    global $wpdb;

    // List of accepted query arguments
    $accepted_args = array(
      'customer_id',
      'user_id',
      'customer_status',
      'customer_email',
      'date_created'
    );

    $args = $this->filter_args( $args, $accepted_args );

    // Setup cache
    $cache_key = md5( 'jpid_customers_count_' . serialize( $args ) );
    $count     = false;

    if ( $use_cache ) {
      $count = wp_cache_get( $cache_key, 'customers' );
    }

    if ( $count === false ) {
      $query = $this->build_query( $args, " SELECT COUNT({$this->primary_key}) FROM {$this->table_name} " );
      $count = $wpdb->get_var( $query );

      wp_cache_set( $cache_key, $count, 'customers', 3600 );
    }

    return $count;
  }

  /**
   * Build SQL query using provided query arguments.
   *
   * @since     1.0.0
   * @param     array     $args       Customer query arguments.
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
  public function insert( $data ) {
    $data = wp_parse_args( $data, $this->get_column_defaults() );
    $data = $this->sanitize_data( $data );

    if ( ! $this->valid_data( $data ) ) {
      return false;
    }

    return parent::insert( $data );
  }

  /**
   * Update existing customer in the database.
   *
   * @since     1.0.0
   * @param     int|string    $id_or_email    Customer's ID or email.
   * @param     array         $data           Customer's data.
   * @return    int                           The updated customer's ID on success, false on failure.
   */
  public function update( $id_or_email, $data ) {
    $data = $this->sanitize_data( $data );

    if ( ! $this->valid_data( $data ) ) {
      return false;
    }

    if ( empty( $id_or_email ) ) {
      return false;
    }

    $column   = is_email( $id_or_email ) ? 'customer_email' : 'customer_id';
    $customer = $this->get_by( $column, $id_or_email );

    if ( $customer ) {
      return parent::update( $customer->customer_id, $data );
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
        case 'customer_id':
          if ( ! is_integer( $value ) || ( $value < 1 ) ) {
            $value = null;
          }
          break;
        case 'user_id':
          if ( ! is_integer( $value ) || ( $value < 0 ) ) {
            $value = null;
          }
          break;
        case 'date_created':
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
        case 'customer_status':
          if ( ! is_string( $value ) ) {
            $value = null;
          } else {
            $value = sanitize_text_field( trim( $value ) );

            if ( empty( $value ) ) {
              $value = null;
            }
          }
          break;
        case 'customer_email':
          if ( ! is_string( $value ) ) {
            $value = null;
          } else {
            $value = sanitize_email( trim( $value ) );

            if ( empty( $value ) ) {
              $value = null;
            }
          }
          break;
        case 'customer_name':
        case 'customer_phone':
        case 'customer_address':
        case 'customer_province':
        case 'customer_city':
          if ( ! is_string( $value ) ) {
            $value = null;
          } else {
            $value = sanitize_text_field( trim( $value ) );
          }
          break;
        case 'order_count':
          if ( ! is_integer( $value ) ) {
            $value = null;
          }
          break;
        case 'order_value':
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
   * Delete existing customer in the database.
   *
   * @since     1.0.0
   * @param     int|string    $id_or_email    Customer's ID or email.
   * @return    int                           The deleted customer's ID on success, false on failure.
   */
  public function delete( $id_or_email ) {
    if ( empty( $id_or_email ) ) {
      return false;
    }

    $column   = is_email( $id_or_email ) ? 'customer_email' : 'customer_id';
    $customer = $this->get_by( $column, $id_or_email );

    if ( $customer ) {
      return parent::delete( $customer->customer_id );
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
  public function exists( $value, $field = 'customer_email' ) {
    if ( ! array_key_exists( $field, $this->get_column_formats() ) ) {
      return false;
    }

    return (bool) $this->get_column_by( 'customer_id', $field, $value );
  }

  /**
   * Update customer's email when its coresponding user account's is updated.
   *
   * @since     1.0.0
   * @param     int      $user_id    User's ID.
   * @return    int                  The updated customer's ID on success, false on failure.
   */
  public function update_customer_email_on_user_update( $user_id ) {
    if ( $user_id < 1 ) {
      return false;
    }

    $customer = $this->get_by( 'user_id', $user_id );

    if ( ! $customer ) {
      return false;
    }

    $user = get_userdata( $user_id );

    if ( ! empty( $user ) && $user->user_email !== $customer->customer_email ) {
      return $this->update( $customer->customer_id, array( 'customer_email' => $user->user_email ) );
    }

    return false;
  }

  /**
   * Increase customer's order count.
   *
   * @since     1.0.0
   * @param     int      $customer_id    Customer's ID.
   * @param     int      $count          Increase count.
   * @return    int                      The new order count on success, false on failure.
   */
  public function increase_order_count( $customer_id, $count = 1 ) {
    if ( $customer_id < 1 ) {
      return false;
    }

    $customer = $this->get( $customer_id );

    if ( ! $customer ) {
      return false;
    }

    $order_count = (int) $customer->order_count + (int) $count;
    $updated     = $this->update( $customer_id, array( 'order_count' => $order_count ) );

    if ( $updated ) {
      return $order_count;
    }

    return false;
  }

  /**
   * Decrease customer's order value.
   *
   * @since     1.0.0
   * @param     int      $customer_id    Customer's ID.
   * @param     int      $count          Decrease count.
   * @return    int                      The new order count on success, false on failure.
   */
  public function decrease_order_count( $customer_id, $count = 1 ) {
    if ( $customer_id < 1 ) {
      return false;
    }

    $customer = $this->get( $customer_id );

    if ( ! $customer ) {
      return false;
    }

    $order_count = (int) $customer->order_count - (int) $count;

    if ( $order_count < 0 ) {
      $order_count = 0;
    }

    $updated = $this->update( $customer_id, array( 'order_count' => $order_count ) );

    if ( $updated ) {
      return $order_count;
    }

    return false;
  }

  /**
   * Increase customer's order value.
   *
   * @since     1.0.0
   * @param     int      $customer_id    Customer's ID.
   * @param     int      $value          Increase value.
   * @return    int                      The new order value on success, false on failure.
   */
  public function increase_order_value( $customer_id, $value ) {
    if ( $customer_id < 1 ) {
      return false;
    }

    $customer = $this->get( $customer_id );

    if ( ! $customer ) {
      return false;
    }

    $order_value = (float) $customer->order_value + (float) $value;
    $updated     = $this->update( $customer_id, array( 'order_value' => $order_value ) );

    if ( $updated ) {
      return $order_value;
    }

    return false;
  }

  /**
   * Decrease customer's order value.
   *
   * @since     1.0.0
   * @param     int      $customer_id    Customer's ID.
   * @param     int      $value          Decrease value.
   * @return    int                      The new order value on success, false on failure.
   */
  public function decrease_order_value( $customer_id, $value ) {
    if ( $customer_id < 1 ) {
      return false;
    }

    $customer = $this->get( $customer_id );

    if ( ! $customer ) {
      return false;
    }

    $order_value = (float) $customer->order_value - (float) $value;

    if ( $order_value < 0 ) {
      $order_value = 0.00;
    }

    $updated = $this->update( $customer_id, array( 'order_value' => $order_value ) );

    if ( $updated ) {
      return $order_value;
    }

    return false;
  }

  /**
   * Increase customer's order count & value.
   *
   * @since     1.0.0
   * @param     int      $customer_id    Customer's ID.
   * @param     int      $value          Increase value.
   * @param     int      $count          Increase count.
   * @return    int                      True on success, false on failure.
   */
  public function increase_order_stats( $customer_id, $value, $count = 1 ) {
    if ( $customer_id < 1 ) {
      return false;
    }

    $customer = $this->get( $customer_id );

    if ( ! $customer ) {
      return false;
    }

    $order_count = (int) $customer->order_count + (int) $count;
    $order_value = (float) $customer->order_value + (float) $value;

    return (bool) $this->update( $customer_id, array(
      'order_count' => $order_count,
      'order_value' => $order_value
    ) );
  }

  /**
   * Decrease customer's order count & value.
   *
   * @since     1.0.0
   * @param     int      $customer_id    Customer's ID.
   * @param     int      $value          Decrease value.
   * @param     int      $count          Decrease count.
   * @return    int                      True on success, false on failure.
   */
  public function decrease_order_stats( $customer_id, $value, $count = 1 ) {
    if ( $customer_id < 1 ) {
      return false;
    }

    $customer = $this->get( $customer_id );

    if ( ! $customer ) {
      return false;
    }

    $order_count = (int) $customer->order_count - (int) $count;

    if ( $order_count < 0 ) {
      $order_count = 0;
    }

    $order_value = (float) $customer->order_value - (float) $value;

    if ( $order_value < 0 ) {
      $order_value = 0.00;
    }

    return (bool) $this->update( $customer_id, array(
      'order_count' => $order_count,
      'order_value' => $order_value
    ) );
  }

}
