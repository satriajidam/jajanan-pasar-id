<?php

/**
 * Customer object class.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/post-types
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Customer {

  /**
	 * @since    1.0.0
	 * @var      JPID_DB    Database manager.
	 */
  private $db = null;

  /**
	 * @since    1.0.0
	 * @var      int      Customer's ID.
	 */
  private $id = 0;

  /**
	 * @since    1.0.0
	 * @var      int      Customer's user ID.
	 */
  private $user_id = 0;

  /**
   * @since    1.0.0
   * @var      string    Customer's creation date.
   */
  private $date_created = '';

  /**
   * @since    1.0.0
   * @var      string    Customer's status.
   */
  private $status = '';

  /**
   * @since    1.0.0
   * @var      string    Customer's name.
   */
  private $name = '';

  /**
   * @since    1.0.0
   * @var      string    Customer's email.
   */
  private $email = '';

  /**
   * @since    1.0.0
   * @var      string    Customer's phone number.
   */
  private $phone = '';

  /**
   * @since    1.0.0
   * @var      string    Customer's address.
   */
  private $address = '';

  /**
   * @since    1.0.0
   * @var      string    Customer's province.
   */
  private $province = '';

  /**
   * @since    1.0.0
   * @var      string    Customer's city
   */
  private $city = '';

  /**
   * @since    1.0.0
   * @var      int      Customer's order count.
   */
  private $order_count = 0;

  /**
   * @since    1.0.0
   * @var      float    Customer's order value.
   */
  private $order_value = 0;

  /**
	 * Initialize customer object and set all its properties.
	 *
	 * @since    1.0.0
	 * @param    int|string    $id_or_email    Customer's ID, email, or user ID.
	 * @param    boolean       $by_user_id     Wheter to use customer's ID or user ID to get
	 *                                         customer database object.
	 */
  public function __construct( $id_or_email = false, $by_user_id = false ) {
    $this->db = JPID()->db_customers;

    if ( is_numeric( $id_or_email ) ) {
      $id_or_email = absint( $id_or_email );
      $by_user_id  = is_bool( $by_user_id ) ? $by_user_id : false;
    }

    $customer = null;

    if ( ! empty( $id_or_email ) ) {
      if ( is_string( $id_or_email ) ) {
        $field = 'customer_email';
      } else {
        $field = $by_user_id ? 'user_id' : 'customer_id';
      }

      $customer = $this->db->get_by( $field, $id_or_email );
    }

    if ( ! empty( $customer ) || is_object( $customer ) ) {
      $this->populate_data( $customer );
    }
  }

  /**
   * Populate customer object's properties.
   *
   * @since    1.0.0
   * @param    object    $customer    Customer database object.
   */
  private function populate_data( $customer ) {
    $this->id           = (int) $customer->customer_id;
    $this->user_id      = (int) $customer->user_id;
    $this->date_created = (string) $customer->date_created;
    $this->status       = (string) $customer->customer_status;
    $this->name         = (string) $customer->customer_name;
    $this->email        = (string) $customer->customer_email;
    $this->phone        = (string) $customer->customer_phone;
    $this->address      = (string) $customer->customer_address;
    $this->province     = (string) $customer->customer_province;
    $this->city         = (string) $customer->customer_city;
    $this->order_count  = (float) $customer->order_count;
    $this->order_value  = (float) $customer->order_value;
  }

  /**
   * Save customer data.
   *
   * Create new customer or update it it already exists.
   *
   * @since     1.0.0
   * @param     array       $data    Customer data.
   * @return    int|bool             Customer's ID on success, false on failure.
   */
  public function save( $data = array() ) {
    if ( empty( $data ) ) {
      return false;
    }

    $do_update   = ( $this->get_id() > 0 ) && ( ! empty( $this->get_email() ) && is_email( $this->get_email() ) );
    $customer_id = false;

    if ( $do_update ) {
      $customer_id = $this->db->update( $this->get_id(), $data );
    } else {
      $customer_id = $this->db->insert( $data );
    }

    if ( $customer_id > 0 ) {
      $customer = $this->db->get_by( 'customer_id', $customer_id );

      $this->populate_data( $customer );
    }

    return $customer_id;
  }

  /**
   * Get customer's ID.
   *
   * @since     1.0.0
   * @return    int      Customer's ID.
   */
  public function get_id() {
    return $this->id;
  }

  /**
   * Get customer's user ID.
   *
   * @since     1.0.0
   * @return    int      Customer's user ID.
   */
  public function get_user_id() {
    return $this->user_id;
  }

  /**
   * Register guest customer into registered user.
   *
   * @since     1.0.0
   * @param     int         $user_id    Customer's user ID.
   * @return    int|bool                Updated customer's ID on success, false on failure.
   */
  public function register_as_user( $user_id ) {
    if ( ( $this->get_id() < 1 ) && ( $this->get_status() !== JPID_Customer_Status::GUEST ) ) {
      return false;
    }

    $user_id = absint( $user_id );

    if ( ( $user_id < 1 ) || $this->db->exists( $user_id, 'user_id' ) ) {
      return false;
    }

    $user_data = get_userdata( $user_id );

    if ( $user_data ) {
      if ( $user_data->user_email !== $this->get_email() ) {
        return false;
      }

      $new_customer_name  = $user_data->first_name . ' ' . $user_data->last_name;
      $new_customer_email = $user_data->user_email;

      $data = array(
        'user_id' => $user_data->ID,
        'customer_status' => JPID_Customer_Status::REGISTERED,
        'customer_name' => $new_customer_name,
        'custoemr_email' => $new_customer_email
      );

      $updated = $this->db->update( $this->get_id(), $data );

      if ( $updated ) {
        $this->user_id = $data['user_id'];
        $this->status  = $data['customer_status'];
      }

      return $updated;
    }

    return false;
  }

  /**
   * Unregister registered customer into guest user.
   *
   * @since     1.0.0
   * @return    int|bool    Updated customer's ID on success, false on failure.
   */
  public function unregister_as_user() {
    if ( ( $this->get_user_id() < 1 ) && ( $this->get_status() !== JPID_Customer_Status::REGISTERED ) ) {
      return false;
    }

    $data = array(
      'user_id' => 0,
      'customer_status' => JPID_Customer_Status::GUEST
    );

    $updated = $this->db->update( $this->get_id(), $data );

    if ( $updated ) {
      $this->user_id = $data['user_id'];
      $this->status  = $data['customer_status'];
    }

    return $updated;
  }

  /**
   * Get customer's creation date.
   *
   * @since     1.0.0
   * @param     string     $format       Date format.
   * @param     boolean    $translate    Wheter to translate the date or not.
   * @return    string                   Customer's creation date.
   */
  public function get_created_date( $format = 'Y-m-d H:i:s', $translate = true ) {
    return mysql2date( $format, $this->date_created, $translate );
  }

  /**
   * Get customer's status.
   *
   * @since     1.0.0
   * @return    string    Customer's status.
   */
  public function get_status() {
    return $this->status;
  }

  /**
   * Get customer's name.
   *
   * @since     1.0.0
   * @return    string    Customer's name.
   */
  public function get_name() {
    return $this->name;
  }

  /**
   * Get customer's email.
   *
   * @since     1.0.0
   * @return    string    Customer's email.
   */
  public function get_email() {
    return $this->email;
  }

  /**
   * Get customer's phone.
   *
   * @since     1.0.0
   * @return    string    Customer's phone.
   */
  public function get_phone() {
    return $this->phone;
  }

  /**
   * Get customer's address.
   *
   * @since     1.0.0
   * @return    string    Customer's address.
   */
  public function get_address() {
    return $this->address;
  }

  /**
   * Get customer's province.
   *
   * @since     1.0.0
   * @return    string    Customer's province.
   */
  public function get_province() {
    return $this->province;
  }

  /**
   * Get customer's city.
   *
   * @since     1.0.0
   * @return    string    Customer's city.
   */
  public function get_city() {
    return $this->city;
  }

  /**
   * Get customer's full address.
   *
   * @since     1.0.0
   * @return    string    Customer's full address.
   */
  public function get_full_address() {
    return $this->get_address() . ', ' . $this->get_city() . ', ' . $this->get_province();
  }

  /**
   * Get customer's order count.
   *
   * @since     1.0.0
   * @return    string    Customer's order count.
   */
  public function get_order_count() {
    return $this->order_count;
  }

  /**
   * Increase customer's order count.
   *
   * @since     1.0.0
   * @param     int      $count    Increase count.
   * @return    int                The new order count on success, false on failure.
   */
  public function increase_order_count( $count = 1 ) {
    if ( $this->get_id() < 1 && empty( $this->get_email() ) ) {
      return false;
    }

    $count = intval( $count );

    if ( $count < 0 ) {
      return false;
    }

    $new_count = $this->db->increase_order_count( $this->get_id(), $count );

    if ( $new_count ) {
      $this->order_count = $new_count;
    }

    return $new_count;
  }

  /**
   * Decrease customer's order count.
   *
   * @since     1.0.0
   * @param     int      $count    Decrease count.
   * @return    int                The new order count on success, false on failure.
   */
  public function decrease_order_count( $count = 1 ) {
    if ( $this->get_id() < 1 && empty( $this->get_email() ) ) {
      return false;
    }

    $count = intval( $count );

    if ( $count < 0 ) {
      return false;
    }

    $new_count = $this->db->decrease_order_count( $this->get_id(), $count );

    if ( $new_count ) {
      $this->order_count = $new_count;
    }

    return $new_count;
  }

  /**
   * Get customer's order value.
   *
   * @since     1.0.0
   * @return    string    Customer's order value.
   */
  public function get_order_value() {
    return $this->order_value;
  }

  /**
   * Increase customer's order value.
   *
   * @since     1.0.0
   * @param     int      $value    Increase value.
   * @return    int                The new order count on success, false on failure.
   */
  public function increase_order_value( $value = 0.00 ) {
    if ( $this->get_id() < 1 && empty( $this->get_email() ) ) {
      return false;
    }

    $value = floatval( $value );

    if ( $value < 0.00 ) {
      return false;
    }

    $new_value = $this->db->increase_order_value( $this->get_id(), $value );

    if ( $new_value ) {
      $this->order_value = $new_value;
    }

    return $new_value;
  }

  /**
   * Decrease customer's order value.
   *
   * @since     1.0.0
   * @param     int      $value    Decrease value.
   * @return    int                The new order count on success, false on failure.
   */
  public function decrease_order_value( $value = 0.00 ) {
    if ( $this->get_id() < 1 && empty( $this->get_email() ) ) {
      return false;
    }

    $value = floatval( $value );

    if ( $value < 0.00 ) {
      return false;
    }

    $new_value = $this->db->decrease_order_value( $this->get_id(), $value );

    if ( $new_value ) {
      $this->order_value = $new_value;
    }

    return $new_value;
  }

}
