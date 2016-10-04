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

class JPID_Admin_Page_Customer_List extends JPID_Admin_Page {

  /**
	 * Class constructor.
	 *
	 * @since    1.0.0
	 */
  public function __construct() {
    $this->set_slug( 'jpid-customers' );
  }

  /**
   * Load page.
   *
   * Use this function as callback in load-{page} action hook.
   *
   * @since    1.0.0
   */
  public function load_page() {

  }

  /**
   * Display page.
   *
   * Use this function as callback in add_menu_page() or add_submenu_page() function.
   *
   * @since    1.0.0
   */
  public function display_page() {

  }

}
