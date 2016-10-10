<?php

/**
 * Post actions for admin area.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Post_Actions {

  /**
	 * Class constructor.
	 *
	 * @since    1.0.0
	 */
  public function __construct() {
    $this->includes();
    $this->setup_actions();
  }

  /**
   * Include required files.
   *
   * @since    1.0.0
   */
  private function includes() {
    require_once JPID_PLUGIN_DIR . 'includes/admin/post-actions/class-jpid-admin-customer-actions.php';
    require_once JPID_PLUGIN_DIR . 'includes/admin/post-actions/class-jpid-admin-order-actions.php';
  }

  /**
   * Setup post action objects.
   *
   * @since    1.0.0
   */
  private function setup_actions() {
    $customer_actions = new JPID_Admin_Customer_Actions();
    $order_actions    = new JPID_Admin_Order_Actions();
  }

}
