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
   * Display page.
   *
   * @since    1.0.0
   */
  abstract public function display_page();

}
