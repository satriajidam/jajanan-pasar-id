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
  $decimal_count = strlen( substr( strrchr( $price, '.' ), 1 ) );

  if ( $decimal_count == 0 ) {
    $decimal_count = 2;
  }

  return 'Rp' . number_format( $price, $decimal_count, ',', '.' );
}

/**
 * Get a list of provinces in Indonesia.
 *
 * @since    1.0.0
 * @return   array    A list of available provinces.
 */
function jpid_get_province_list() {
  return array(
    // Sumatera
    'Aceh',
    'Sumatera Utara',
    'Sumatera Barat',
    'Riau',
    'Kepulauan Riau',
    'Jambi',
    'Sumatera Selatan',
    'Bangka Belitung',
    'Bengkulu',
    'Lampung',

    // Jawa
    'Jakarta',
    'Jawa Barat',
    'Banten',
    'Jawa Tengah',
    'Yogyakarta',
    'Jawa Timur',

    // Bali & Nusa Tenggara
    'Bali',
    'Nusa Tenggara Barat',
    'Nusa Tenggara Timur',

    // Kalimantan
    'Kalimantan Barat',
    'Kalimantan Tengah',
    'Kalimantan Selatan',
    'Kalimantan Timur',
    'Kalimantan Utara',

    // Sulawesi
    'Sulawesi Utara',
    'Sulawesi Barat',
    'Sulawesi Tengah',
    'Sulawesi Tenggara',
    'Sulawesi Selatan',
    'Gorontalo',

    // Maluku & Papua
    'Maluku',
    'Maluku Utara',
    'Papua',
    'Papua Barat'
  );
}
