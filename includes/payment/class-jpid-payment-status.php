<?php

/**
 * List all available payment status.
 *
 * This class means to simulate enum data type for payment status.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/payment
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Payment_Status {

  /**
	 * @since    1.0.0
	 * @var      string    Payment unverified status.
	 */
  const UNVERIFIED = 'unverified';

  /**
	 * @since    1.0.0
	 * @var      string    Payment verified status.
	 */
  const VERIFIED = 'verified';

  /**
   * Get list of available payment statuses.
   *
   * @since     1.0.0
   * @return    array    List of available payment statuses.
   */
  public static function get_statuses() {
    return array(
      self::UNVERIFIED,
      self::VERIFIED
    );
  }

}
