<?php

/**
 * Handle ajax request in the admin area.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/admin
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Load HTML display for product category selector in product edit screen.
 *
 * @since    1.0.0
 */
function jpid_load_product_categories_display() {
  check_ajax_referer( 'load_product_categories_display', 'security' );

  $current_type = absint( $_POST['current_type'] );

  if ( ! is_numeric( $current_type ) || $current_type <= 0 ) {
    die();
  }

  $current_post = absint( $_POST['current_post'] );

  if ( ! is_numeric( $current_post ) || $current_post <= 0 ) {
    die();
  }

  $product = jpid_get_product( $current_post );

  include JPID_PLUGIN_DIR . 'includes/admin/products/views/html-jpid-meta-box-product-data-category-field.php';

  die();
}
add_action( 'wp_ajax_load_product_categories_display', 'jpid_load_product_categories_display' );
