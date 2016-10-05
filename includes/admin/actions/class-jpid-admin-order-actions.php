<?php

/**
 * Order actions manager.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/actions
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Order_Actions {

  /**
   * @since    1.0.0
   * @var      string    Nonce action.
   */
  const NONCE_ACTION = 'jpid_save_order';

  /**
   * @since    1.0.0
   * @var      string    Nonce name.
   */
  const NONCE_NAME = 'jpid_order_nonce';

  /**
	 * Class constructor.
	 *
	 * @since    1.0.0
	 */
  public function __construct() {}

}
