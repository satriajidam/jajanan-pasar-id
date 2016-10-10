<?php

/**
 * Handle creation of admin about page.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id/includes/admin/pages
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Page_About {

  /**
   * @since    1.0.0
   * @var      string    Page slug.
   */
  const SLUG = 'jpid-about';

  /**
	 * Class constructor.
	 *
	 * @since    1.0.0
	 */
  public function __construct() {}

  /**
   * Display page.
   *
   * Use this function as callback in add_menu_page() or add_submenu_page() function.
   *
   * @since    1.0.0
   */
  public function display_page() {
    include_once JPID_PLUGIN_DIR . 'includes/admin/pages/views/html-jpid-admin-about-page.php';
  }

}
