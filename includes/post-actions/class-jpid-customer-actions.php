<?php

/**
 * Customer public actions manager.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/customer
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Customer_Actions {

  /**
   * Update customer's email when its coresponding user account's is updated.
   *
   * @since     1.0.0
   * @param     int      $user_id    User's ID.
   * @return    int                  The updated customer's ID on success, false on failure.
   */
  public function update_customer_email_on_user_update( $user_id = 0 ) {
    if ( $user_id < 1 ) {
      return false;
    }

    $customer = new JPID_Customer( $user_id, true );

    if ( $customer->get_user_id() < 1 ) {
      return false;
    }

    $user = get_userdata( $customer->get_user_id() );

    if ( ! empty( $user ) && $user->user_email !== $customer->customer_email ) {
      return $customer->save( array( 'customer_email' => $user->user_email ) );
    }

    return false;
  }

}
