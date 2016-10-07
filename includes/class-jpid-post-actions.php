<?php

/**
 * Registers all custom post type and custom content type actions.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Post_Actions {

  /**
   * Class constructor.
   *
   * @since    1.0.0
   */
  public function __construct() {
    $this->includes();
    $this->setup_hooks();
  }
  
  /**
	 * Include required files.
	 *
	 * @since    1.0.0
	 */
	private function includes() {

  }

  /**
   * Setup class hooks.
   *
   * @since    1.0.0
   */
  private function setup_hooks() {

  }

}
