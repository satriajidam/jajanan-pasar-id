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
    $this->setup_hooks();
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

  private function setup_actions() {
    $this->customer_actions = new JPID_Admin_Customer_Actions();
    $this->order_actions    = new JPID_Admin_Order_Actions();
  }

  /**
   * Setup class hooks.
   *
   * @since    1.0.0
   */
  private function setup_hooks() {
    add_action( 'admin_init', array( $this, 'customer_actions' ) );
    add_action( 'admin_init', array( $this, 'order_actions' ) );
  }

  /**
   * Process customer actions.
   *
   * @since    1.0.0
   */
  public function customer_actions() {
    if ( ! array_key_exists( 'page', $_GET ) ) {
      return;
    }

    if ( ! isset( $_GET['page'] ) && $_GET['page'] !== JPID_Admin_Page_Customer_Edit::SLUG ) {
      return;
    }

    $customer_id = isset( $_GET['customer'] ) ? intval( $_GET['customer'] ) : 0;

    if ( isset( $_REQUEST['jpid_customer_action'] ) ) {
      switch ( $_REQUEST['jpid_customer_action'] ) {
        case 'save_customer':
          $this->customer_actions->save_customer( $customer_id, $_POST );
          break;
        case 'delete_customer':
          $this->customer_actions->delete_customer( $customer_id );
          break;
        default:
          $message  = '<p class="error">';
          $message .= __( 'Unrecognized action', 'jpid' );
          $message .= ': <i>' . $_REQUEST['jpid_customer_action'] . '</i>';
          $message .= '</p>';

          wp_die( $message );
          break;
      }
    }
  }

  /**
   * Process order actions.
   *
   * @since    1.0.0
   */
  public function order_actions() {

  }

}
