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
   * @since     1.0.0
   * @param     int      $customer_id    Customer's ID.
   */
  public function save( $customer_id ) {
    if ( ! $this->can_save() ) {
      return;
    }

    $data = array();

    if ( ! empty( $_POST['jpid_customer_account'] ) ) {
      $data['user_id'] = absint( $_POST['jpid_customer_account'] );
    }

    if ( ! empty( $_POST['jpid_customer_name'] ) ) {
      $data['customer_name'] = sanitize_text_field( trim( $_POST['jpid_customer_name'] ) );
    }

    if ( ! empty( $_POST['jpid_customer_email'] ) ) {
      $data['customer_email'] = sanitize_email( trim( $_POST['jpid_customer_email'] ) );
    }

    if ( ! empty( $_POST['jpid_customer_phone'] ) ) {
      $data['customer_phone'] = sanitize_text_field( trim( $_POST['jpid_customer_phone'] ) );
    }

    if ( ! empty( $_POST['jpid_customer_address'] ) ) {
      $data['customer_address'] = sanitize_text_field( trim( $_POST['jpid_customer_address'] ) );
    }

    if ( ! empty( $_POST['jpid_customer_province'] ) ) {
      $data['customer_province'] = sanitize_text_field( trim( $_POST['jpid_customer_province'] ) );
    }

    if ( ! empty( $_POST['jpid_customer_city'] ) ) {
      $data['customer_city'] = sanitize_text_field( trim( $_POST['jpid_customer_city'] ) );
    }

    if ( $customer_id > 0 ) {

      $customer = new JPID_Customer( $customer_id );

    } else {

      if ( ! array_key_exists( 'customer_email', $data ) ) {

        if ( empty( $data['customer_email'] ) || ! is_email( $data['customer_email'] ) ) {
          return;
        }

        return;
      }

      $customer = new JPID_Customer();

    }

    $new_customer_id = $customer->save( $data );

    if ( $new_customer_id ) {
      $customer_edit_page = admin_url() . 'admin.php?page=' . JPID_Admin_Page_Customer_Edit::SLUG . '&customer=' . $new_customer_id;

      JPID_Admin_Notices::add_notice( 'Testing some notices duuude!!!' );

      wp_safe_redirect( $customer_edit_page );
    } else {

    }
  }

  /**
	 * Check if current edited customer can be saved.
	 *
	 * @since     1.0.0
	 * @return    boolean    True if customer can be saved, otherwise false.
	 */
  private function can_save() {
    $is_valid_nonce = isset( $_POST[ self::NONCE_NAME ] ) && wp_verify_nonce( $_POST[ self::NONCE_NAME ], self::NONCE_ACTION );
    // $is_user_can    = current_user_can( 'edit_post' );

    return $is_valid_nonce; //&& $is_user_can;
  }

  /**
   * Delete customer action.
   *
   * @since     1.0.0
   * @param     int      $customer_id    Customer's ID.
   */
  public function delete( $customer_id ) {

  }

}
