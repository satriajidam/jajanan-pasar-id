<?php

/**
 * Order DB class.
 *
 * Handles interaction with orders database table.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/order
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_DB_Orders extends JPID_DB {

  /**
   * @since    1.0.0
   * @var      string    Order items table name.
   */
  private $items_table;

  /**
   * Class constructor.
   *
   * @since    1.0.0
   */
  public function __construct() {
    global $wpdb;

    // Core orders table
    $this->table_name  = $wpdb->prefix . 'jpid_orders';
    $this->primary_key = 'order_id';

    // Order items table
    $this->items_table = $wpdb->prefix . 'jpid_order_items';

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
      'order_id'          => '%d',
      'order_invoice'     => '%s',
      'order_date'        => '%s',
      'order_status'      => '%s',
      'customer_id'       => '%d',
      'recipient_name'    => '%s',
      'recipient_phone'   => '%s',
      'delivery_date'     => '%s',
      'delivery_address'  => '%s',
      'delivery_province' => '%s',
      'delivery_city'     => '%s',
      'delivery_cost'     => '%f',
      'delivery_note'     => '%s',
      'order_cost'        => '%f',
      'modified_date'     => '%s'
    );
  }

  /**
   * Get default column values.
   *
   * @since     1.0.0
   * @return    array    Collection of column default values.
   */
  protected function get_column_defaults() {
    $order_id      = $this->get_next_id();
    $order_date    = $this->date_now();
    $order_invoice = $this->generate_invoice( $order_id, $order_date );

    return array(
      'order_id'          => $order_id,
      'order_invoice'     => $order_invoice,
      'order_date'        => $order_date,
      'order_status'      => JPID_Order_Status::PENDING,
      'customer_id'       => 0,
      'recipient_name'    => '',
      'recipient_phone'   => '',
      'delivery_date'     => '',
      'delivery_address'  => '',
      'delivery_province' => '',
      'delivery_city'     => '',
      'delivery_cost'     => 0.00,
      'delivery_note'     => '',
      'order_cost'        => 0.00,
      'modified_date'     => ''
    );
  }

  /**
   * Generate order invoice.
   *
   * @since     1.0.0
   * @param     int       $order_id      Order's ID.
   * @param     string    $order_date    Order's date.
   * @return    string                   Order's invoice.
   */
  private function generate_invoice( $order_id, $order_date ) {
    return 'JPID' . $order_id . mysql2date( 'dmy', $order_date );
  }

  /**
   * Get single order from database based on order's ID.
   *
   * @since     1.0.0
   * @param     int       $order_id    Order's ID to search.
   * @return    object                 Order database object on success, false on failure.
   */
  public function get( $order_id ) {
    if ( ! is_numeric( $order_id ) ) {
      return false;
    }

    $order_id = absint( $order_id );

    if ( $order_id < 1 ) {
      return false;
    }

    $order = parent::get( $order_id );

		if ( ! $order ) {
			return false;
		}

		return $order;
  }

  /**
   * Get single order from database based on order's ID or invoice.
   *
   * @since     1.0.0
   * @param     string        $field    order_id or order_invoice.
   * @param     int|string    $value    The value of order's ID or invoice.
   * @return    object                  Order database object on success, false on failure.
   */
  public function get_by( $field, $value ) {
    if ( $field === 'order_id' ) {

			if ( ! is_numeric( $value ) ) {
				return false;
			}

			$value = absint( $value );

			if ( $value < 1 ) {
				return false;
			}

		} elseif ( $field === 'order_invoice' ) {

			$value = sanitize_text_field( trim( $value ) );

		} else {
      return false;
    }

		if ( ! $value ) {
			return false;
		}

    $order = parent::get_by( $field, $value );

		if ( ! $order ) {
			return false;
		}

		return $order;
  }

  /**
   * Get orders from database based on provided query arguments.
   *
   * @since     1.0.0
   * @param     array     $args    Orders query arguments.
   * @return    array              Array of order database objects.
   */
  public function get_all( $args = array(), $use_cache = false ) {
    global $wpdb;

    // List of accepted query arguments
    $accepted_args = array(
      'order_id',
      'order_invoice',
      'order_date',
      'order_status',
      'customer_id',
      'delivery_date',
      'orderby',
      'order',
      'number',
      'offset'
    );

    $args = $this->filter_args( $args, $accepted_args );

    $defaults = array(
      'orderby' => 'order_id',
      'order'   => 'DESC',
      'number'  => 20,
      'offset'  => 0
    );

    $args = wp_parse_args( $args, $defaults );

    // Setup cache
    $cache_key = md5( 'jpid_orders_' . serialize( $args ) );
    $orders    = false;

    if ( $use_cache ) {
      $orders = wp_cache_get( $cache_key, 'orders' );
    }

    if ( $orders === false ) {
      $query  = $this->build_query( $args, " SELECT * FROM {$this->table_name} " );
      $orders = $wpdb->get_results( $query );

      wp_cache_set( $cache_key, $orders, 'orders', 3600 );
    }

    return $orders;
  }

  /**
   * Count the total numbers of orders in the database.
   *
   * @since     1.0.0
   * @param     array     $args    Order query arguments.
   * @return    int                Total numbers of orders.
   */
  public function count( $args = array(), $use_cache = false ) {
    global $wpdb;

    // List of accepted query arguments
    $accepted_args = array(
      'order_id',
      'order_invoice',
      'order_date',
      'order_status',
      'customer_id',
      'delivery_date'
    );

    $args = $this->filter_args( $args, $accepted_args );

    // Setup cache
    $cache_key = md5( 'jpid_orders_count_' . serialize( $args ) );
    $count     = false;

    if ( $use_cache ) {
      $count = wp_cache_get( $cache_key, 'orders' );
    }

    if ( $count === false ) {
      $query = $this->build_query( $args, " SELECT COUNT({$this->primary_key}) FROM {$this->table_name} " );
      $count = $wpdb->get_var( $query );

      wp_cache_set( $cache_key, $count, 'orders', 3600 );
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

    // Get specific orders based on order's ID
    if ( ! empty( $args['order_id'] ) ) {

      if ( is_array( $args['order_id'] ) ) {
        $count     = count( $args['order_id'] );
        $order_ids = array_map( 'absint', $args['order_id'] );
      } else {
        $count     = 1;
        $order_ids = absint( $args['order_id'] );
      }

      $placeholder = implode( ', ', array_fill( 0, $count, '%d' ) );

      $where .= $wpdb->prepare( " AND order_id IN( {$placeholder} ) ", $order_ids );

    }

    // Get specific orders based on order's invoice
		if ( ! empty( $args['order_invoice'] ) ) {

			if ( is_array( $args['order_invoice'] ) ) {
				$count          = count( $args['order_invoice'] );
				$order_invoices = array_map( 'sanitize_text_field', array_map( 'trim', $args['order_invoice'] ) );
			} else {
        $count          = 1;
        $order_invoices = sanitize_text_field( trim( $args['order_invoice'] ) );
      }

      $placeholder = implode( ', ', array_fill( 0, $count, '%s' ) );

      $where .= $wpdb->prepare( " AND order_invoice IN( {$placeholder} ) ", $order_invoices );

		}

    // Get orders made on specific date or in a date range
    if ( ! empty( $args['order_date'] ) ) {

      if ( is_array( $args['order_date'] ) ) {

        if ( ! empty( $args['order_date']['start'] ) ) {
          $start = date( 'Y-m-d 00:00:00', strtotime( $args['order_date']['start'] ) );

          $where .= $wpdb->prepare( " AND order_date >= %s ", $start );
        }

        if ( ! empty( $args['order_date']['end'] ) ) {
          $end = date( 'Y-m-d 23:59:59', strtotime( $args['order_date']['end'] ) );

          $where .= $wpdb->prepare( " AND order_date <= %s ", $end );
        }

      } else {
        $year  = date( 'Y', strtotime( $args['order_date'] ) );
        $month = date( 'm', strtotime( $args['order_date'] ) );
        $day   = date( 'd', strtotime( $args['order_date'] ) );

        $where .= $wpdb->prepare( " AND YEAR ( order_date ) = %s AND MONTH ( order_date ) = %s AND DAY ( order_date ) = %s ", $year, $month, $day  );
      }

    }

    // Get specific orders by status
    if ( ! empty( $args['order_status'] ) ) {

      if ( is_array( $args['order_status'] ) ) {
        $count          = count( $args['order_status'] );
        $order_statuses = array_map( 'sanitize_text_field', array_map( 'trim', $args['order_status'] ) );
      } else {
        $count          = 1;
        $order_statuses = sanitize_text_field( trim( $args['order_status'] ) );
      }

      $placeholder = implode( ', ', array_fill( 0, $count, '%s' ) );

      $where .= $wpdb->prepare( " AND order_status IN( {$placeholder} ) ", $order_statuses );

    }

    // Get specific orders based on customer's ID
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

    // Get orders delivered on specific date or in a date range
    if ( ! empty( $args['delivery_date'] ) ) {

      if ( is_array( $args['delivery_date'] ) ) {

        if ( ! empty( $args['delivery_date']['start'] ) ) {
          $start = date( 'Y-m-d 00:00:00', strtotime( $args['delivery_date']['start'] ) );

          $where .= $wpdb->prepare( " AND delivery_date >= %s ", $start );
        }

        if ( ! empty( $args['delivery_date']['end'] ) ) {
          $end = date( 'Y-m-d 23:59:59', strtotime( $args['delivery_date']['end'] ) );

          $where .= $wpdb->prepare( " AND delivery_date <= %s ", $end );
        }

      } else {
        $year  = date( 'Y', strtotime( $args['delivery_date'] ) );
        $month = date( 'm', strtotime( $args['delivery_date'] ) );
        $day   = date( 'd', strtotime( $args['delivery_date'] ) );

        $where .= $wpdb->prepare( " AND YEAR ( delivery_date ) = %s AND MONTH ( delivery_date ) = %s AND DAY ( delivery_date ) = %s ", $year, $month, $day  );
      }

    }

    // Prepare the ORDER BY clause
    $orderby = esc_sql( $orderby );

    if ( ! empty( $args['orderby'] ) ) {
      $args['orderby'] = ! array_key_exists( $args['orderby'], $this->get_column_formats() ) ? 'order_id' : $args['orderby'];
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
   * Add new order to the database.
   *
   * @since     1.0.0
   * @param     array    $data    Order's data.
   * @return    int               The newly created order's ID on success, false on failure.
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
   * Update existing order in the database.
   *
   * @since     1.0.0
   * @param     int|string    $id_or_invoice    Order's ID or invoice.
   * @param     array         $data             Order's data.
   * @return    int                             The updated order's ID on success, false on failure.
   */
  public function update( $id_or_invoice, $data ) {
    $data = $this->sanitize_data( $data );

    if ( ! $this->valid_data( $data ) ) {
      return false;
    }

    if ( empty( $id_or_invoice ) ) {
      return false;
    }

    $column = ! is_numeric( $id_or_invoice ) ? 'order_invoice' : 'order_id';
    $order  = $this->get_by( $column, $id_or_invoice );

    if ( $order ) {
      return parent::update( $order->order_id, $data );
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
        case 'order_id':
          if ( ! is_integer( $value ) || ( $value < 1 ) ) {
            $value = null;
          }
          break;
        case 'customer_id':
          if ( ! is_integer( $value ) || ( $value < 0 ) ) {
            $value = null;
          }
          break;
        case 'order_date':
        case 'delivery_date':
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
        case 'order_invoice':
        case 'order_status':
        case 'recipient_name':
        case 'recipient_phone':
        case 'delivery_address':
        case 'delivery_province':
        case 'delivery_city':
          if ( ! is_string( $value ) ) {
            $value = null;
          } else {
            $value = sanitize_text_field( trim( $value ) );

            if ( empty( $value ) ) {
              $value = null;
            }
          }
          break;
        case 'delivery_note':
          if ( ! is_string( $value ) ) {
            $value = null;
          } else {
            $value = sanitize_text_field( trim( $value ) );
          }
          break;
        case 'modified_date':
          if ( ! is_string( $value ) ) {
            $value = null;
          } else {
            $time = strtotime( $value );

            if ( empty( $time ) ) {
              $value = '';
            } else {
              $value = date( 'Y-m-d H:i:s', $time );
            }
          }
          break;
        case 'delivery_cost':
        case 'order_cost':
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
   * Delete existing order in the database.
   *
   * @since     1.0.0
   * @param     int|string    $id_or_invoice    Order's ID or invoice.
   * @return    int                             The deleted customer's ID on success, false on failure.
   */
  public function delete( $id_or_invoice ) {
    if ( empty( $id_or_invoice ) ) {
      return false;
    }

    $column = ! is_numeric( $id_or_invoice ) ? 'order_invoice' : 'order_id';
    $order  = $this->get_by( $column, $id_or_invoice );

    if ( $order ) {
      return parent::delete( $order->order_id );
    }

    return false;
  }

  /**
   * Check if a order exists.
   *
   * @since     1.0.0
   * @param     mixed      $value    The value of order's field to check.
   * @param     string     $field    The name of order's field to check.
   * @return    boolean              True if order exists, false if not.
   */
  public function exists( $value, $field = 'order_invoice' ) {
    if ( ! array_key_exists( $field, $this->get_column_formats() ) ) {
      return false;
    }

    return (bool) $this->get_column_by( 'order_id', $field, $value );
  }

  /**
   * Get single order item from database.
   *
   * @since     1.0.0
   * @param     int       $order_id    Order's ID.
   * @param     int       $item_id     Order item's ID.
   * @return    object                 Order item database object.
   */
  public function get_item( $order_id, $item_id ) {
    global $wpdb;

    $order_id = absint( $order_id );
    $item_id  = absint( $item_id );

    if ( empty( $order_id ) || empty( $item_id ) ) {
      return false;
    }

    return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->items_table} WHERE order_id = %d AND item_id = %d LIMIT 1;", $order_id, $item_id ) );
  }

  /**
   * Get multiple order items from database.
   *
   * @since     1.0.0
   * @param     int       $order_id     Order's ID.
   * @param     string    $item_type    Order item's type.
   * @return    object                  Array of order item database objects.
   */
  public function get_items( $order_id, $item_type = '', $use_cache = false ) {
    global $wpdb;

    $order_id = absint( $order_id );

    if ( empty( $order_id ) ) {
      return false;
    }

    $select = " SELECT * FROM {$this->items_table} ";
    $where  = $wpdb->prepare( " WHERE order_id = %d ", $order_id );

    if ( ! empty( $item_type ) ) {
      $where .= $wpdb->prepare( " AND item_type = %s ", $item_type );
    }

    // Setup cache
    $cache_key = md5( 'jpid_orders_items_' . $order_id . $item_type );
    $items     = false;

    if ( $use_cache ) {
      $items = wp_cache_get( $cache_key, 'orders' );
    }

    if ( $items === false ) {
      $query = $select . $where . ";";
      $items = $wpdb->get_results( $query );

      wp_cache_set( $cache_key, $items, 'orders', 3600 );
    }

    return $items;
  }

  /**
   * Add order item to the database or update it if it already exists.
   *
   * @since     1.0.0
   * @param     int        $order_id     Order's ID.
   * @param     int        $item_id      Order item's ID.
   * @param     int        $item_qty     Order item's quantity.
   * @param     string     $item_type    Order item's type.
   * @return    boolean                  True on success, false on failure.
   */
  public function add_item( $order_id, $item_id, $item_qty = 1, $item_type = '' ) {
    global $wpdb;

    $order = $this->get( $order_id );

    if ( ! $order ) {
      return false;
    }

    $item_id   = absint( $item_id );
    $item_qty  = intval( $item_qty );

    if ( empty( $item_id ) || $item_qty < 0 ) {
      return false;
    }

    $item_type = sanitize_text_field( trim( $item_type ) );

    if ( empty( $item_type ) ) {
      $item_type = JPID_Order_Item::SNACK_BOX;
    }

    $order_item = $this->get_item( $order->order_id, $item_id );

    $add_success = false;

    if ( $order_item ) {

      // Setup update data
      $data  = array( 'item_qty' => $item_qty, 'item_type' => $item_type );
      $where = array( 'order_id' => $order->order_id, 'item_id' => $item_id );

      // Perform update
      $update_success = $wpdb->update( $this->items_table, $data, $where, array( '%d', '%s' ), array( '%d', '%d' ) );

      if ( $update_success !== false ) {
        $add_success = true;
      }

    } else {

      // Setup insert data
      $data = array(
        'order_id'  => $order->order_id,
        'item_id'   => $item_id,
        'item_qty'  => $item_qty,
        'item_type' => $item_type
      );

      // Perform insert
      $add_success = $wpdb->insert( $this->items_table, $data, array( '%d', '%d', '%d', '%s' ) );

    }

    // Update/insert success
    if ( $add_success ) {
      return $this->update_order_cost_on_items_change( $order->order_id );
    }

    // Update/insert failed
    return false;
  }

  /**
   * Remove existing order item in the database.
   *
   * @since     1.0.0
   * @param     int        $order_id     Order's ID.
   * @param     int        $item_id      Order item's ID.
   * @return    boolean                  True on success, false on failure.
   */
  public function remove_item( $order_id, $item_id ) {
    global $wpdb;

    $order_id = absint( $order_id );
    $item_id  = absint( $item_id );

    if ( empty( $order_id ) || empty( $item_id ) ) {
      return false;
    }

    $order_item = $this->get_item( $order_id, $item_id );

    if ( $order_item ) {
      $remove_success = $wpdb->delete( $this->items_table, array( 'order_id' => $order_item->order_id, 'item_id' => $order_item->item_id ), array( '%d', '%d' ) );

      // Delete success
      if ( $remove_success ) {
        return $this->update_order_cost_on_items_change( $order_item->order_id );
      }

      // Delete failed
      return false;
    }

    // No order item with provided IDs
    return false;
  }

  /**
   * Update order's cost when its items are updated or deleted.
   *
   * @since     1.0.0
   * @param     int        $order_id    Order's ID.
   * @return    boolean                 True on success, false on failure.
   */
  private function update_order_cost_on_items_change( $order_id ) {
    $order_items = $this->get_items( $order_id );
    $order_cost  = 0.00;

    if ( ! empty( $order_items ) ) {
      foreach ( $order_items as $order_item ) {
        $item = JPID_Order_Item::create( $order_item->item_id, $order_item->item_type );

        if ( ! $item ) {
          continue;
        }

        $order_cost += ( (float) $item->get_price() * $order_item->item_qty );
      }
    }

    return (bool) $this->update( $order_id, array( 'order_cost' => $order_cost ) );
  }

}
