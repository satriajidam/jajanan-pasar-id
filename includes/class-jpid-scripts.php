<?php

/**
 * Register all scripts and styles for the plugin.
 *
 * List and register all scripts and styles in the assets/ folder
 * to be loaded later in front-end and admin pages.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Scripts {

  /**
	 * Class constructor.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->setup_hooks();
	}

  /**
   * Setup class hooks.
   *
   * @since    1.0.0
   */
	private function setup_hooks() {
    add_action( 'init', array( $this, 'register_scripts' ) );
    add_action( 'init', array( $this, 'register_styles' ) );
	}

  /**
   * Register all required scripts.
   *
   * @since    1.0.0
   */
  public function register_scripts() {
    self::register_vendor_scripts();

    is_admin() ? self::register_admin_scripts() : self::register_public_scripts();
  }

  /**
   * Register all vendor scripts.
   *
   * Vendor scripts are used both in front-end and admin pages.
   *
   * @since    1.0.0
   */
  private function register_vendor_scripts() {
    wp_register_script(
      'accounting',
      JPID_JS_URL . 'vendor/accounting/accounting' . JPID_SCRIPT_SUFFIX . '.js',
      array(),
      '0.4.2',
      true
    );
    wp_register_script(
      'jquery-blockUI',
      JPID_JS_URL . 'vendor/jquery-blockUI/jquery.blockUI' . JPID_SCRIPT_SUFFIX . '.js',
      array( 'jquery' ),
      '2.70.0',
      true
    );
    wp_register_script(
      'jquery-ui-timepicker',
      JPID_JS_URL . 'vendor/jquery-ui-timepicker/jquery-ui-timepicker-addon' . JPID_SCRIPT_SUFFIX . '.js',
      array( 'jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-datepicker' ),
      '1.6.3',
      true
    );
    wp_register_script(
      'select2',
      JPID_JS_URL . 'vendor/select2/select2' . JPID_SCRIPT_SUFFIX . '.js',
      array( 'jquery' ),
      '4.0.3',
      true
    );
    wp_register_script(
      'stupidtable',
      JPID_JS_URL . 'vendor/stupidtable/stupidtable' . JPID_SCRIPT_SUFFIX . '.js',
      array( 'jquery' ),
      '1.0.0',
      true
    );
  }

  /**
   * Register all admin scripts.
   *
   * @since    1.0.0
   */
  private function register_admin_scripts() {
    wp_register_script(
      'jpid-admin',
      JPID_JS_URL . 'admin/jpid-admin' . JPID_SCRIPT_SUFFIX . '.js',
      array( 'jquery' ),
      JPID_VERSION,
      true
    );
  }

  /**
   * Register all public scripts.
   *
   * @since    1.0.0
   */
  private function register_public_scripts() {
    // TODO: Register public scripts.
  }

  /**
   * Register all required styles.
   *
   * @since    1.0.0
   */
  public function register_styles() {
    self::register_vendor_styles();

    is_admin() ? self::register_admin_styles() : self::register_public_styles();
  }

  /**
   * Register all vendor styles.
   *
   * Vendor styles are used both in front-end and admin pages.
   *
   * @since    1.0.0
   */
  private function register_vendor_styles() {
    wp_register_style(
      'jquery-ui-timepicker',
      JPID_CSS_URL . 'vendor/jquery-ui-timepicker/jquery-ui-timepicker-addon' . JPID_SCRIPT_SUFFIX . '.css',
      array(),
      '1.6.3'
    );
    wp_register_style(
      'select2',
      JPID_CSS_URL . 'vendor/select2/select2' . JPID_SCRIPT_SUFFIX . '.css',
      array(),
      '4.0.3'
    );
  }

  /**
   * Register all admin styles.
   *
   * @since    1.0.0
   */
  private function register_admin_styles() {
    wp_register_style(
      'jpid-admin',
      JPID_CSS_URL . 'admin/jpid-admin' . JPID_SCRIPT_SUFFIX . '.css',
      array(),
      JPID_VERSION
    );
  }

  /**
   * Register all public styles.
   *
   * @since    1.0.0
   */
  private function register_public_styles() {
    // TODO: Register public styles.
  }

}
