<?php

/**
 * Handle creation of admin settings page.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id/includes/admin/pages
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Page_Settings {

  /**
   * @since    1.0.0
   * @var      string    Page slug.
   */
  const SLUG = 'jpid-settings';

  /**
   * @since    1.0.0
   * @var      array    Collection of settings objects.
   */
  private $settings = array();

  /**
	 * Class constructor.
	 *
	 * @since    1.0.0
	 */
  public function __construct() {
    $this->includes();
    $this->setup_settings();
    $this->setup_hooks();
  }

  /**
	 * Include required files.
	 *
	 * @since    1.0.0
	 */
  private function includes() {
    require_once JPID_PLUGIN_DIR . 'includes/admin/settings/abstract-jpid-settings.php';
    require_once JPID_PLUGIN_DIR . 'includes/admin/settings/class-jpid-settings-general.php';
    require_once JPID_PLUGIN_DIR . 'includes/admin/settings/class-jpid-settings-delivery.php';
    require_once JPID_PLUGIN_DIR . 'includes/admin/settings/class-jpid-settings-payment.php';
  }

  /**
   * Get settings page tabs.
   *
   * @since    1.0.0
   */
  private function get_tabs() {
    return array(
      'general' => array(
        'title' => __( 'General', 'jpid' ),
        'group'  => 'jpid_general_settings'
      ),
      'delivery' => array(
        'title' => __( 'Delivery', 'jpid' ),
        'group'  => 'jpid_delivery_settings'
      ),
      'payment' => array(
        'title' => __( 'Payment', 'jpid' ),
        'group'  => 'jpid_payment_settings'
      )
    );
  }

  /**
	 * Setup settings.
	 *
	 * @since    1.0.0
	 */
  private function setup_settings() {
    $tabs = $this->get_tabs();

    // Setup settings
    $this->settings[] = new JPID_Settings_General( $tabs['general']['group'] );
    $this->settings[] = new JPID_Settings_Delivery( $tabs['delivery']['group'] );
    $this->settings[] = new JPID_Settings_Payment( $tabs['payment']['group'] );
  }

  /**
   * Setup class hooks.
   *
   * @since    1.0.0
   */
  private function setup_hooks() {
    add_action( 'admin_init', array( $this, 'register_settings' ) );
  }

  /**
	 * Register settings.
	 *
	 * @since    1.0.0
	 */
  public function register_settings() {
    if ( empty( $this->settings ) ) {
      return;
    }

    foreach ( $this->settings as $setting ) {
      $setting->register_settings();
    }
  }

  /**
   * Display page.
   *
   * Use this function as callback in add_menu_page() or add_submenu_page() function.
   *
   * @since    1.0.0
   */
  public function display_page() {
    include_once JPID_PLUGIN_DIR . 'includes/admin/pages/views/html-jpid-admin-settings-page.php';
  }

}
