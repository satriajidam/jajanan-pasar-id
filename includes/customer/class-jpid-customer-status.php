<?php

/**
 * List all available customer status.
 *
 * This class means to simulate enum data type for customer status.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/customer
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Customer_Status {

  /**
	 * @since    1.0.0
	 * @var      string    Customer guest status.
	 */
  const GUEST = 'guest';

  /**
	 * @since    1.0.0
	 * @var      string    Customer registered status.
	 */
  const REGISTERED = 'registered';

}
