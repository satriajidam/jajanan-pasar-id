<?php

/**
 * Handle creation of customer list page.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id/includes/admin/pages
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Page_Customer_List {

  /**
   * @since    1.0.0
   * @var      string    Page slug.
   */
  const SLUG = 'jpid-customers';

  /**
   * @since    1.0.0
   * @var      JPID_Table_Customers    Customer list table.
   */
  private $customers_table = null;

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

    require_once JPID_PLUGIN_DIR . 'includes/admin/pages/tables/class-jpid-table-customers.php';

    $this->customers_table = new JPID_Table_Customers();
  }

  /**
   * Display page.
   *
   * Use this function as callback in add_menu_page() or add_submenu_page() function.
   *
   * @since    1.0.0
   */
  public function display_page() {
    include_once JPID_PLUGIN_DIR . 'includes/admin/pages/views/html-jpid-admin-customer-list-page.php';
  }

}
