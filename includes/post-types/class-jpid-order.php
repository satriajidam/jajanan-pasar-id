<?php

/**
 * Order object class.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/post-types
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Order {

  /**
   * @since    1.0.0
   * @var      JPID_DB    Database manager.
   */
  private $db = null;

  /**
   * @since    1.0.0
   * @var      int      Order's ID.
   */
  private $id = 0;

  /**
   * @since    1.0.0
   * @var      string    Order's invoice.
   */
  private $invoice = '';

  /**
   * @since    1.0.0
   * @var      string    Order's date.
   */
  private $order_date = '';

  /**
   * @since    1.0.0
   * @var      string    Order's status.
   */
  private $status = '';

  /**
   * @since    1.0.0
   * @var      int      Order's customer ID.
   */
  private $customer_id = 0;

  /**
   * @since    1.0.0
   * @var      string    Order's recipient name.
   */
  private $recipient_name = '';

  /**
   * @since    1.0.0
   * @var      string    Order's recipient phone.
   */
  private $recipient_phone = '';

  /**
   * @since    1.0.0
   * @var      string    Order's delivery date.
   */
  private $delivery_date = '';

  /**
   * @since    1.0.0
   * @var      string    Order's delivery address.
   */
  private $delivery_address = '';

  /**
   * @since    1.0.0
   * @var      string    Order's delivery province.
   */
  private $delivery_province = '';

  /**
   * @since    1.0.0
   * @var      string    Order's delivery city.
   */
  private $delivery_city = '';

  /**
   * @since    1.0.0
   * @var      float    Order's delivery cost.
   */
  private $delivery_cost = 0;

  /**
   * @since    1.0.0
   * @var      string    Order's delivery note.
   */
  private $delivery_note = '';

  /**
   * @since    1.0.0
   * @var      float    Order's cost.
   */
  private $order_cost = 0;

  /**
   * @since    1.0.0
   * @var      string    Order's modified date.
   */
  private $modified_date = '';

  /**
   * @since    1.0.0
   * @var      array    Order's items.
   */
  private $order_items = array();

  /**
   * Initialize order object and set all its properties.
   *
   * @since    1.0.0
   * @param    int|string    $id_or_invoice    Order's ID or invoice
   */
  public function __construct( $id_or_invoice = false ) {
    $this->db = new JPID_DB_Orders();

    if ( is_numeric( $id_or_invoice ) ) {
      $id_or_invoice = absint( $id_or_invoice );
    }

    $order = null;

    if ( ! empty( $id_or_invoice ) ) {
      if ( is_string( $id_or_invoice ) ) {
        $field = 'order_invoice';
      } else {
        $field = 'order_id';
      }

      $order = $this->db->get_by( $field, $id_or_invoice );
    }

    if ( ! empty( $order ) || is_object( $order ) ) {
      $this->populate_data( $order );
    }
  }

  /**
   * Populate order object's properties.
   *
   * @since    1.0.0
   * @param    object    $order    Order database object.
   */
  private function populate_data( $order ) {
    $this->id                = (int) $order->order_id;
    $this->invoice           = (string) $order->order_invoice;
    $this->order_date        = (string) $order->order_date;
    $this->status            = (string) $order->order_status;
    $this->customer_id       = (int) $order->customer_id;
    $this->recipient_name    = (string) $order->recipient_name;
    $this->recipient_phone   = (string) $order->recipient_phone;
    $this->delivery_date     = (string) $order->delivery_date;
    $this->delivery_address  = (string) $order->delivery_address;
    $this->delivery_province = (string) $order->delivery_province;
    $this->delivery_city     = (string) $order->delivery_city;
    $this->delivery_cost     = (float) $order->delivery_cost;
    $this->delivery_note     = (string) $order->delivery_note;
    $this->order_cost        = (float) $order->order_cost;
    $this->modified_date     = (string) $order->modified_date;
    $this->order_items       = $this->load_order_items();
  }

  /**
   * Load order's items.
   *
   * @since     1.0.0
   * @return    array    Collection of order's items.
   */
  private function load_order_items() {
    $new_items   = array();
    $order_items = $this->db->get_items( $this->get_id() );

    if ( ! empty( $order_items ) ) {
      foreach ( $order_items as $order_item ) {
        $item = JPID_Order_Item::create( $order_item->item_id, $order_item->item_type );

        if ( ! empty( $item ) && is_object( $item ) ) {
          $new_items[] = $item;
        }
      }
    }

    return $new_items;
  }

  /**
   * Load order's cost.
   *
   * @since     1.0.0
   * @return    float    Order's cost.
   */
  private function load_order_cost() {
    $old_cost = $this->get_order_cost();
    $new_cost = (float) $this->db->get_column( 'order_cost', $this->get_id() );

    if ( is_null( $new_cost ) ) {
      return $old_cost;
    }

    return $new_cost;
  }

  /**
   * Save order data.
   *
   * Create new order or update if it already exists.
   *
   * @since     1.0.0
   * @param     array       $data    Order data.
   * @return    int|bool             Order's ID on success, false on failure.
   */
  public function save( $data = array() ) {
    if ( empty( $data ) ) {
      return false;
    }

    $do_update = ( $this->get_id() > 0 ) && ! empty( $this->get_invoice() );
    $order_id  = false;

    if ( $do_update ) {
      $order_id = $this->db->update( $this->get_id(), $data );
    } else {
      $order_id = $this->db->insert( $data );
    }

    if ( $order_id > 0 ) {
      $order = $this->db->get_by( 'order_id', $order_id );

      $this->populate_data( $order );
    }

    return $order_id;
  }

  /**
   * Get order's ID.
   *
   * @since     1.0.0
   * @return    int      Order's ID.
   */
  public function get_id() {
    return $this->id;
  }

  /**
   * Get order's invoice.
   *
   * @since     1.0.0
   * @return    string    Order's invoice.
   */
  public function get_invoice() {
    return $this->invoice;
  }

  /**
   * Get order's' date.
   *
   * @since     1.0.0
   * @param     string     $format       Date format.
   * @param     boolean    $translate    Wheter to translate the date or not.
   * @return    string                   Order's' date.
   */
  public function get_order_date( $format = 'Y-m-d H:i:s', $translate = true ) {
    return mysql2date( $format, $this->order_date, $translate );
  }

  /**
   * Get order's status.
   *
   * @since     1.0.0
   * @return    string    Order's status.
   */
  public function get_status() {
    return $this->status;
  }

  /**
   * Get order's customer ID.
   *
   * @since     1.0.0
   * @return    int      Order's customer ID.
   */
  public function get_customer_id() {
    return $this->customer_id;
  }

  /**
   * Get order's recipient name.
   *
   * @since     1.0.0
   * @return    string    Order's recipient name.
   */
  public function get_recipient_name() {
    return $this->recipient_name;
  }

  /**
   * Get order's recipient phone.
   *
   * @since     1.0.0
   * @return    string    Order's recipient phone.
   */
  public function get_recipient_phone() {
    return $this->recipient_phone;
  }

  /**
   * Get order's delivery date.
   *
   * @since     1.0.0
   * @param     string     $format       Date format.
   * @param     boolean    $translate    Wheter to translate the date or not.
   * @return    string                   Order's delivery date.
   */
  public function get_delivery_date( $format = 'Y-m-d H:i:s', $translate = true ) {
    return mysql2date( $format, $this->delivery_date, $translate );
  }

  /**
   * Get order's delivery address.
   *
   * @since     1.0.0
   * @return    string    Order's delivery address.
   */
  public function get_delivery_address() {
    return $this->delivery_address;
  }

  /**
   * Get order's delivery province.
   *
   * @since     1.0.0
   * @return    string    Order's delivery province.
   */
  public function get_delivery_province() {
    return $this->delivery_province;
  }

  /**
   * Get order's delivery city.
   *
   * @since     1.0.0
   * @return    string    Order's delivery city.
   */
  public function get_delivery_city() {
    return $this->delivery_city;
  }

  /**
   * Get order's full delivery address.
   *
   * @since     1.0.0
   * @return    string    Order's full delivery address.
   */
  public function get_full_address() {
    return $this->get_delivery_address() . ', ' . $this->get_delivery_city() . ', ' . $this->get_delivery_province();
  }

  /**
   * Get order's delivery cost.
   *
   * @since     1.0.0
   * @return    float    Order's delivery cost.
   */
  public function get_delivery_cost() {
    return $this->delivery_cost;
  }

  /**
   * Get order's delivery note.
   *
   * @since     1.0.0
   * @return    string    Order's delivery note.
   */
  public function get_delivery_note() {
    return $this->delivery_note;
  }

  /**
   * Get order's cost.
   *
   * @since     1.0.0
   * @return    float    Order's cost.
   */
  public function get_order_cost() {
    return $this->order_cost;
  }

  /**
   * Get order's total cost.
   *
   * @since     1.0.0
   * @return    float    Order's total cost.
   */
  public function get_total_cost() {
    return $this->get_order_cost() + $this->get_delivery_cost();
  }

  /**
   * Get order's modified date.
   *
   * @since     1.0.0
   * @param     string     $format       Date format.
   * @param     boolean    $translate    Wheter to translate the date or not.
   * @return    string                   Order's modified date.
   */
  public function get_modified_date( $format = 'Y-m-d H:i:s', $translate = true ) {
    return mysql2date( $format, $this->modified_date, $translate );
  }

  /**
   * Get order's items.
   *
   * @since     1.0.0
   * @return    int      Order's items.
   */
  public function get_items() {
    return $this->order_items;
  }

  /**
   * Add new item or update old item in order.
   *
   * @since     1.0.0
   * @param     int        $item_id     Item's ID.
   * @param     float      $item_qty    Item's quantity.
   * @return    boolean                 True on success, false on failure.
   */
  public function add_item( $item_id, $item_qty = 1, $item_type = '' ) {
    $item_id  = absint( $item_id );
    $item_qty = intval( $item_qty );

    if ( empty( $item_id ) || ( $item_qty < 1 ) ) {
      return false;
    }

    $added = $this->db->add_item( $this->get_id(), $item_id, $item_qty, $item_type );

    if ( $added ) {
      $this->order_items = $this->load_order_items();
      $this->order_cost  = $this->load_order_cost();

      return true;
    }

    return false;
  }

  /**
   * Remove an item from order.
   *
   * @since     1.0.0
   * @param     int        $item_id     Item's ID.
   * @return    boolean                 True on success, false on failure.
   */
  public function remove_item( $item_id ) {
    $item_id = absint( $item_id );

    if ( empty( $item_id ) ) {
      return false;
    }

    $removed = $this->db->remove_item( $this->get_id(), $item_id );

    if ( $removed ) {
      $this->order_items = $this->load_order_items();
      $this->order_cost  = $this->load_order_cost();

      return true;
    }

    return false;
  }

}
