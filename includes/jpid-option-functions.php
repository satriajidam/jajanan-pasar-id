<?php

/**
 * Collection of functions to manage plugin options.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Get all default plugin options.
 *
 * @since     1.0.0
 * @return    array    Collection of default plugin options.
 */
function jpid_get_default_options() {
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
    'jpid_delivery_cost_method'      => 'fixed',
    'jpid_delivery_cost_amount'      => 0,
    'jpid_delivery_locations'        => array(),
    'jpid_bank_payment_title'        => '',
    'jpid_bank_payment_description'  => '',
    'jpid_bank_payment_instructions' => '',
    'jpid_bank_payment_accounts'     => array(),
  );
}

/**
 * Load all plugin options and save them in a global option variable.
 *
 * The global variable is used to reduce database access when the plugin wants
 * to retrieve an option. Instead of going to the database to find the option value,
 * the plugin can refer it from the global option variable.
 *
 * @since     1.0.0
 * @return    array    Collection of plugin options.
 */
function jpid_load_options() {
	global $jpid_options;

	$default_options = jpid_get_default_options();

	foreach ( $default_options as $option_name => $default_value ) {
		$saved_value = get_option( $option_name );
		$jpid_options[ $option_name ] = $saved_value ? $saved_value : $default_value;
	}

	return $jpid_options;
}

/**
 * Get all current plugin options.
 *
 * @since     1.0.0
 * @return    array    Collection of current plugin options' values.
 */
function jpid_get_current_options() {
  global $jpid_options;

	if ( empty( $jpid_options ) ) {
		jpid_load_options();
	}

  return $jpid_options;
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
function jpid_get_option( $option_name, $default = false ) {
  $current_options = jpid_get_current_options();
	$option_value    = array_key_exists( $option_name, $current_options ) ? $current_options[ $option_name ] : $default;

	return $option_value;
}

/**
 * Update specified plugin option.
 *
 * Update option value in the database and in the global option variable.
 *
 * @since     1.0.0
 * @param     string    $option_name     Name of option to update.
 * @param     mixed     $option_value    The new option value.
 * @return    boolean                    Wheter the update successfully performed or not.
 */
function jpid_update_option( $option_name, $option_value ) {
	global $jpid_options;

	$did_update = update_option( $option_name, $option_value );

	if ( $did_update ) {
		$jpid_options[ $option_name ] = $option_value;
	}

	return $did_update;
}
