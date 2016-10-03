<?php

/**
 * Abstract class for admin page.
 *
 * Extend this class when creating new individual admin page.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id/includes/admin/pages
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

abstract class JPID_Admin_Page {

  /**
   * @since    1.0.0
   * @var      string    Page slug.
   */
  protected $slug;

  /**
   * Set page slug.
   *
   * @since    1.0.0
   * @param    string    Page slug.
   */
  protected function set_slug( $slug ) {
    $this->slug = $slug;
  }

  /**
   * Get page slug.
   *
   * @since     1.0.0
   * @return    string    This page slug.
   */
  public function get_slug() {
    return $this->slug;
  }

  /**
   * Display page.
   *
   * @since    1.0.0
   */
  abstract public function display_page();

}
