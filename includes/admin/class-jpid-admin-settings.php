<?php

/**
 * Handle creation of admin settings page.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id/includes/admin
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Settings {

  /**
   * @since    1.0.0
   * @var      string    Settings page slug.
   */
  private $settings_page_slug;

  /**
   * @since    1.0.0
   * @var      array    Collection of settings tabs.
   */
  private $tabs;

  /**
   * @since    1.0.0
   * @var      array    Collection of settings objects.
   */
  private $settings;

  /**
	 * Class constructor.
	 *
	 * @since    1.0.0
	 */
  public function __construct( $settings_page_slug ) {
    $this->settings_page_slug = $settings_page_slug;

    $this->includes();
    $this->setup_tabs();
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
   * Setup settings page tabs.
   *
   * @since    1.0.0
   */
  private function setup_tabs() {
    $this->tabs = array(
      'general' => array(
        'name' => __( 'General', 'jpid' ),
        'page' => 'jpid_general_settings'
      ),
      'delivery' => array(
        'name' => __( 'Delivery', 'jpid' ),
        'page' => 'jpid_delivery_settings'
      ),
      'payment' => array(
        'name' => __( 'Payment', 'jpid' ),
        'page' => 'jpid_payment_settings'
      )
    );
  }

  /**
	 * Setup settings.
	 *
	 * @since    1.0.0
	 */
  private function setup_settings() {
    $this->settings   = array();
    $this->settings[] = new JPID_Settings_General( $this->tabs['general']['page'] );
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
    if ( ! isset( $this->settings ) ) {
      return;
    }

    foreach ( $this->settings as $setting ) {
      $setting->register_settings();
    }
  }

  /**
   * Display settings page.
   *
   * @since    1.0.0
   */
  public function display_settings_page() {
    include_once JPID_PLUGIN_DIR . 'includes/admin/views/html-jpid-admin-settings-page.php';
  }

}
