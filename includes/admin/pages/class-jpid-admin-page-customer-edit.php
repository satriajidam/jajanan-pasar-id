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

class JPID_Admin_Page_Customer_Edit {

  /**
   * @since    1.0.0
   * @var      string    Page slug.
   */
  const SLUG = 'jpid-customer';

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
  public function __construct() {}

  /**
   * Load page.
   *
   * Use this function as callback in load-{page} action hook.
   *
   * @since    1.0.0
   */
  public function load_page() {
    if ( ! ( current_action() === 'load-' . get_current_screen()->id ) ) {
      _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'jpid' ), '4.6' );
    }

    if ( empty( $_GET['customer'] ) ) {
      $this->customer = new JPID_Customer();
    } else {
      $customer_id = absint( $_GET['customer'] );

      $this->customer = new JPID_Customer( $customer_id );

      if ( $this->customer->get_id() == 0 ) {
        $message  = '<p class="error">';
        $message .= __( 'You attempted to edit customer that doesn’t exist. Perhaps he/she was deleted?', 'jpid' );
        $message .= '</p>';

        wp_die( $message );
      }
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
