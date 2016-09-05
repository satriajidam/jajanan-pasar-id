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
   * @var      string    About page slug.
   */
  private $about_page_slug;

  /**
   * Class constructor.
   *
   * @since    1.0.0
   */
  public function __construct( $about_page_slug ) {
    $this->about_page_slug = $about_page_slug;
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
