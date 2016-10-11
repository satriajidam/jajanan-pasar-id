<?php

/**
 * Collection of helper functions to manage customer object.
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

function jpid_get_customer( $customer_id = null ) {
	global $thecustomer;

	$_customer = null;

	if ( is_null( $customer_id ) && isset( $thecustomer ) && jpid_is_customer( $thecustomer ) ) {
		$_customer = $thecustomer;
	} elseif ( is_numeric( $customer_id ) ) {
		$customer_id = intval( $customer_id );

		if ( $customer_id > 0 ) {
			if ( jpid_is_customer( $thecustomer ) ) {
				$_customer = ( $customer_id === $thecustomer->get_id() ) ? $thecustomer : null;
			}

			if ( is_null( $_customer ) ) {
				$_customer   = new JPID_Customer( $customer_id );
				$thecustomer = $_customer;
			}
		}
	}

	return $_customer;
}

function jpid_is_customer( $customer ) {
	return is_object( $customer ) && ( $customer instanceof JPID_Customer );
}

/**
 * Get customer object based on custoemr_id, customer_email, or user_id.
 *
 * @since     1.0.0
 * @param     string           $field    Field name.
 * @param     int|string       $value    Field value.
 * @return    JPID_Customer              Customer object.
 */
function jpid_get_customer_by( $field, $value ) {
	$field = sanitize_key( $field );

	if ( ! in_array( $field, array( 'customer_id', 'customer_email', 'user_id' ) ) ) {
		return null;
	}

	if ( is_numeric( $value ) && $value < 1 ) {
		return null;
	}

	$by_user_id = ( $field === 'user_id' ) ? true : false;
	$customer   = new JPID_Customer( $value, $by_user_id );

	if ( $customer->get_id() > 0 ) {
		return $customer;
	} else {
		return null;
	}
}

 /**
	* Wrapper for get_next_id() function of JPID_DB_Customers class.
  *
  * Original function is located in includes/class-jpid-db-customers.php.
	*
	* @since     1.0.0
	* @return    int      Next auto increment ID.
	*/
function jpid_next_customer_id() {
	return JPID()->db_customers->get_next_id();
}

/**
 * Wrapper for exists() function of JPID_DB_Customers class.
 *
 * Original function is located in includes/class-jpid-db-customers.php.
 *
 * @since     1.0.0
 * @param     string     $search_field    The name of customer's field to check.
 * @param     mixed      $search_value    The value of customer's field to check.
 * @return    boolean                     True if customer exists, false if not.
 */
function jpid_is_customer_exists( $search_field, $search_value ) {
	return JPID()->db_customers->exists( $search_value, $search_field );
}
