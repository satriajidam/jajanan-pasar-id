<?php

/**
 * Ajax for admin area.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Ajax {

  /**
	 * Class constructor.
	 *
	 * @since    1.0.0
	 */
  public function __construct() {
    $this->includes();
    $this->setup_ajax();
    $this->setup_hooks();
  }

  /**
   * Include required files.
   *
   * @since    1.0.0
   */
  private function includes() {
    require_once JPID_PLUGIN_DIR . 'includes/admin/ajax/class-jpid-admin-product-ajax.php';
    require_once JPID_PLUGIN_DIR . 'includes/admin/ajax/class-jpid-admin-customer-ajax.php';
  }

  /**
   * Setup ajax objects.
   *
   * @since    1.0.0
   */
  private function setup_ajax() {
    $product_ajax  = new JPID_Admin_Product_Ajax();
    $customer_ajax = new JPID_Admin_Customer_Ajax();
  }

  /**
   * Setup class hooks.
   *
   * @since    1.0.0
   */
  private function setup_hooks() {
    // TODO: do something...
  }

}
