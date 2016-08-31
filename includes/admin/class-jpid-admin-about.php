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

class JPID_Admin_About {

  /**
   * @since    1.0.0
   * @var      string    Settings page slug.
   */
  private $page_slug;

  /**
   * Class constructor.
   *
   * @since    1.0.0
   */
  public function __construct( $page_slug ) {
    $this->page_slug = $page_slug;
  }

  /**
   * Display about page.
   *
   * @since    1.0.0
   */
  public function display_about_page() {
    include_once JPID_PLUGIN_DIR . 'includes/admin/views/html-jpid-admin-about-page.php';
  }

}
