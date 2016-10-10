<?php

/**
 * Collection of helper functions to manage admin notice.
 *
 * All functions declared here are available in the global scope of admin area.
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
 * Wrapper for add_notice() function of JPID_Admin_Notices class.
 *
 * Original function is located in includes/admin/class-jpid-admin-notices.php.
 *
 * @since    1.0.0
 * @param    string    $message        The message to display in the notice.
 * @param    string    $notice_type    The type of notice to add.
 */
function jpid_add_notice( $message, $notice_type = 'success' ) {
  JPID_Admin_Notices::add_notice( $message, $notice_type );
}

/**
 * Wrapper for print_notices() function of JPID_Admin_Notices class.
 *
 * Original function is located in includes/admin/class-jpid-admin-notices.php.
 *
 * @since    1.0.0
 */
function jpid_print_notices() {
  JPID_Admin_Notices::print_notices();
}
