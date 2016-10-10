<?php

/**
 * Collection of helper functions to manage plugin option.
 *
 * All functions declared here are available in the global scope.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/helpers
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Wrapper for get_options() function of JPID_Options class.
 *
 * Original function is located in includes/class-jpid-options.php.
 *
 * @since     1.0.0
 * @return    array    Collection of current plugin options' values.
 */
function jpid_get_options() {
  return JPID()->option->get_options();
}

/**
 * Wrapper for get_option() function of JPID_Options class.
 *
 * Original function is located in includes/class-jpid-options.php.
 *
 * @since     1.0.0
 * @param     string    $option_name    Option name.
 * @param     mixed     $default        Default value if option doesn't exist.
 * @return    mixed                     Option value.
 */
function jpid_get_option( $option_name, $default = false ) {
  return JPID()->option->get_option( $option_name, $default );
}

/**
 * Wrapper for update_option() function of JPID_Options class.
 *
 * Original function is located in includes/class-jpid-options.php.
 *
 * @since     1.0.0
 * @param     string    $option_name     Name of option to update.
 * @param     mixed     $option_value    The new option value.
 * @return    boolean                    Wheter the update successfully performed or not.
 */
function jpid_update_option( $option_name, $option_value ) {
  return JPID()->option->update_option( $option_name, $option_value );
}
