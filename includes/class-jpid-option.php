<?php

/**
 * Manages plugin's options.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Option {

  /**
   * @since    1.0.0
   * @var      array    Cache to save plugin's current options.
   */
  private static $options_cache = array();

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
    add_action( 'init', array( $this, 'load_options' ) );
  }

  /**
   * Get option names and their default values.
   *
   * @since     1.0.0
   * @return    array    Plugin's options and their default values.
   */
  private function get_default_options() {
    // 'option_name' => option_value
    return array(
      // Non-setting options:
      'jpid_version'									 => '',
      'jpid_db_version'                => '',

      // Setting options:
      'jpid_snacks_selection_page'     => 0,
      'jpid_drinks_selection_page'     => 0,
      'jpid_checkout_page'             => 0,
      'jpid_payment_confirmation_page' => 0,
      'jpid_customer_page'             => 0,
      'jpid_order_full_status'         => 0,
      'jpid_order_full_notice'         => '',
      'jpid_order_available_date'      => '',
      'jpid_delivery_days_range'       => 4,
      'jpid_delivery_hours'            => array( 'start' => '06:00', 'end' => '21:00' ),
      'jpid_delivery_cost_method'      => 'flat',
      'jpid_delivery_cost_amount'      => 0,
      'jpid_delivery_locations'        => array(),
      'jpid_bank_payment_title'        => '',
      'jpid_bank_payment_description'  => '',
      'jpid_bank_payment_instructions' => '',
      'jpid_bank_payment_accounts'     => array(),
    );
  }

  /**
   * Load all current plugin options and cache them in class property.
   *
   * The class property is used to reduce database access when the plugin wants
   * to retrieve an option. Instead of going to the database to find the option value,
   * the plugin can refer it from the class property.
   *
   * @since     1.0.0
   */
  public function load_options() {
    $default_options = $this->get_default_options();

    foreach ( $default_options as $option_name => $default_value ) {
      $saved_value = get_option( $option_name );
      self::$options_cache[ $option_name ] = $saved_value ? $saved_value : $default_value;
    }
  }

  /**
   * Get all current plugin options.
   *
   * @since     1.0.0
   * @return    array    Collection of current plugin options' values.
   */
  public function get_options() {
    return self::$options_cache;
  }

  /**
   * Get specified plugin option.
   *
   * Only use this function to get option exclusive to this plugin's.
   * For other options, use WP default get_option() function.
   *
   * @since     1.0.0
   * @param     string    $option_name    Option name.
   * @param     mixed     $default        Default value if option doesn't exist.
   * @return    mixed                     Option value.
   */
  public function get_option( $option_name, $default = false ) {
  	return array_key_exists( $option_name, self::$options_cache ) ? self::$options_cache[ $option_name ] : $default;
  }

  /**
   * Update specified plugin option.
   *
   * Update option value in the database and in the class property.
   *
   * @since     1.0.0
   * @param     string    $option_name     Name of option to update.
   * @param     mixed     $option_value    The new option value.
   * @return    boolean                    Wheter the update successfully performed or not.
   */
  public function update_option( $option_name, $option_value ) {
    if ( ! array_key_exists( $option_name, self::$options_cache ) ) {
      return false;
    }

  	$updated = update_option( $option_name, $option_value );

  	if ( $updated ) {
  		self::$options_cache[ $option_name ] = $option_value;
  	}

  	return $updated;
  }

}
