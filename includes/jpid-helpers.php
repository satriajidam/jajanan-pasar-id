<?php
/**
 * Collection of helper functions for this plugin.
 *
 * All functions declared here are available in the global scope.
 *
 * NOTE:
 * If number of functions listed here are getting bigger, consider
 * moving and grouping them into separate files.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

 // Exit if accessed directly.
 if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Format an integer into IDR-based currency number.
 *
 * @since    1.0.0
 * @param    float         $price  Price to format.
 * @return   string                The new price format with IDR prefix.
 */
function jpid_to_rupiah( $price ) {
  return 'Rp' . number_format( $price, 2, ',', '.' );
}
