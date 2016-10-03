<?php

/**
 * Handle creation of customer edit page.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id/includes/admin/pages
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Page_Customer_Edit extends JPID_Admin_Page {

  /**
   * @since    1.0.0
   * @var      JPID_Customer    Current active customer.
   */
  private $customer = null;

  /**
	 * Class constructor.
	 *
	 * @since    1.0.0
	 */
  public function __construct() {
    $this->set_slug( 'jpid-customer' );
  }

  /**
   * Load page.
   *
   * Use this function as callback in load-{page} action hook.
   *
   * @since    1.0.0
   */
  public function load_page() {
    $action = empty( $_GET['action'] ) ? 'new' : $_GET['action'];

    if ( $action === 'new' ) {

      $this->customer = new JPID_Customer();

    } elseif ( $action === 'edit' ) {

      $customer_id = empty( $_GET['customer'] ) ? 0 : $_GET['customer'];

      $this->customer = new JPID_Customer( $customer_id );

      if ( $this->customer->get_id() == 0 ) {
        $message  = '<p class="error">';
        $message .= __( 'You attempted to edit customer that doesn’t exist. Perhaps he/she was deleted?', 'jpid' );
        $message .= '</p>';

        wp_die( $message );
      }

    } else {

      $message  = '<p class="error">';
      $message .= __( 'Invalid action: <i>' . $action . '</i>', 'jpid' );
      $message .= '</p>';

      wp_die( $message );

    }
  }

  /**
   * Display page.
   *
   * Use this function as callback in add_menu_page() or add_submenu_page() function.
   *
   * @since    1.0.0
   */
  public function display_page() {
    include_once JPID_PLUGIN_DIR . 'includes/admin/pages/views/html-jpid-admin-customer-edit-page.php';
  }

}
