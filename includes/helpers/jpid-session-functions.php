<?php

/**
 * Collection of helper functions to manage session.
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
 * Wrapper for get_id() function of JPID_Session class.
 *
 * Original function is located in includes/class-jpid-session.php.
 *
 * @since     1.0.0
 * @return    string    Current session ID.
 */
function jpid_session_id() {
  return JPID()->session->get_id();
}

/**
 * Wrapper for get() function of JPID_Session class.
 *
 * Original function is located in includes/class-jpid-session.php.
 *
 * @since     1.0.0
 * @param     string    $key    Session variable key.
 * @return    mixed             Session variable value.
 */
function jpid_session_get( $key ) {
  return JPID()->session->get( $key );
}

/**
 * Check if a session variable has been set or not.
 *
 * @since     1.0.0
 * @param     string     $key    Session variable key.
 * @return    boolean            True if session variable has been set, otherwise false.
 */
function jpid_session_isset( $key ) {
	return JPID()->session->get( $key ) !== null;
}

/**
 * Wrapper for set() function of JPID_Session class.
 *
 * Original function is located in includes/class-jpid-session.php.
 *
 * @since     1.0.0
 * @param     string    $key      Session variable key.
 * @param     mixed     $value    Session variable value.
 * @return    mixed               Session variable value.
 */
function jpid_session_set( $key, $value ) {
  return JPID()->session->set( $key, $value );
}

/**
 * Unset a session variable.
 *
 * @since     1.0.0
 * @param     string    $key    Session variable key.
 * @return    null              Null value.
 */
function jpid_session_unset( $key ) {
	return JPID()->session->set( $key, null );
}
