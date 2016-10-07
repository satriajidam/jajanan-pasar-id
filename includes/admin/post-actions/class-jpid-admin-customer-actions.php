<?php

/**
 * Customer admin actions manager.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/post-actions
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Customer_Actions {

  /**
   * @since    1.0.0
   * @var      string    Nonce action.
   */
  const NONCE_ACTION = 'jpid_save_customer';

  /**
   * @since    1.0.0
   * @var      string    Nonce name.
   */
  const NONCE_NAME = 'jpid_customer_nonce';

  /**
	 * Class constructor.
	 *
	 * @since    1.0.0
	 */
  public function __construct() {}

  /**
   * Save customer action.
   *
   * @since    1.0.0
   * @param    int      $customer_id    Customer's ID.
   * @param    array    $data           Collection of customer data to be processed.
   */
  public function save_customer( $customer_id, $data ) {
    if ( ! $this->can_save( $data ) ) {
      wp_die( __( 'You do not have permission to edit this customer.', 'jpid' ) );
    }

    $customer_data = $this->prepare_customer_data( $data );

    if ( $customer_id > 0 ) {

      $customer = new JPID_Customer( $customer_id );

    } else {

      if ( ! array_key_exists( 'customer_email', $customer_data ) ) {

        if ( empty( $customer_data['customer_email'] ) || ! is_email( $customer_data['customer_email'] ) ) {
          return;
        }

        return;
      }

      $customer = new JPID_Customer();

    }

    $new_customer_id = $customer->save( $customer_data );

    if ( $new_customer_id ) {

      $customer_edit_page = admin_url() . 'admin.php?page=' . JPID_Admin_Page_Customer_Edit::SLUG . '&customer=' . $new_customer_id;

      wp_safe_redirect( $customer_edit_page );

    } else {

    }
  }

  /**
   * Sanitize and check customer data validity before being saved.
   *
   * @since     1.0.0
   * @param     array    $data    Collection of customer data to be processed.
   * @return    array             Collection of processed customer data.
   */
  private function prepare_customer_data( $data ) {
    $customer_data = array();

    if ( ! empty( $data['jpid_user_id'] ) ) {
      $user_id = absint( $data['jpid_user_id'] );

      // Check if user ID already attached to certain customer
      $customer = new JPID_Customer( $user_id, true );

      if ( $customer->get_user_id() > 0 ) {
        return;
      }

      // Check if actual user accpunt exists
      $user_account = get_user_by( 'id', $user_id );

      if ( $user_account === false ) {
        return;
      }

      $customer_data['user_id'] = $user_id;
    }

    if ( ! empty( $data['jpid_customer_name'] ) ) {
      $customer_data['customer_name'] = sanitize_text_field( trim( $data['jpid_customer_name'] ) );
    }

    if ( ! empty( $data['jpid_customer_email'] ) ) {
      $customer_data['customer_email'] = sanitize_email( trim( $data['jpid_customer_email'] ) );
    }

    if ( ! empty( $data['jpid_customer_phone'] ) ) {
      $customer_data['customer_phone'] = sanitize_text_field( trim( $data['jpid_customer_phone'] ) );
    }

    if ( ! empty( $data['jpid_customer_address'] ) ) {
      $customer_data['customer_address'] = sanitize_text_field( trim( $data['jpid_customer_address'] ) );
    }

    if ( ! empty( $data['jpid_customer_province'] ) ) {
      $customer_data['customer_province'] = sanitize_text_field( trim( $data['jpid_customer_province'] ) );
    }

    if ( ! empty( $data['jpid_customer_city'] ) ) {
      $customer_data['customer_city'] = sanitize_text_field( trim( $data['jpid_customer_city'] ) );
    }

    return $customer_data;
  }

  /**
	 * Check if current edited customer can be saved.
	 *
	 * @since     1.0.0
	 * @param     array      $data    Collection of customer data to be processed.
	 * @return    boolean             True if customer can be saved, otherwise false.
	 */
  private function can_save( $data ) {
    if ( ! array_key_exists( self::NONCE_NAME, $data ) ) {
      return false;
    }

    $is_valid_nonce = isset( $data[ self::NONCE_NAME ] ) && wp_verify_nonce( $data[ self::NONCE_NAME ], self::NONCE_ACTION );
    $is_user_can    = is_admin() && current_user_can( 'edit_users' );

    return $is_valid_nonce && $is_user_can;
  }

  /**
   * Delete customer action.
   *
   * @since     1.0.0
   * @param     int      $customer_id    Customer's ID.
   */
  public function delete_customer( $customer_id ) {

  }

}
