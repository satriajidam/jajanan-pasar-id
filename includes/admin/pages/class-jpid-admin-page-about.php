<?php

/**
 * Handle creation of admin about page.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id/includes/admin
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Page_About extends JPID_Admin_Page {

  /**
   * Class constructor.
   *
   * @since    1.0.0
   */
  public function __construct() {
    $this->slug = 'jpid-about';
  }

  /**
   * Display about page.
   *
   * @since    1.0.0
   */
  public function display_page() {
    include_once JPID_PLUGIN_DIR . 'includes/admin/pages/views/html-jpid-admin-about-page.php';
  }

}
