<?php

/**
 * Handle ajax request for product administration.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/admin/product
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Load product categories.
 *
 * Loaded product categories will be send as an array of term_name => term_id pairs.
 *
 * @since    1.0.0
 */
function jpid_load_product_categories() {
  check_ajax_referer( 'load_product_categories', 'security' );

  $current_type = absint( $_POST['current_type'] );

  if ( ! is_numeric( $current_type ) || $current_type <= 0 ) {
    die();
  }

  $product_categories = jpid_get_product_category_terms( $current_type );
  $categories         = array();

  if ( ! empty( $product_categories ) ) {
    foreach ( $product_categories as $product_category ) {
      $categories[] = array(
        'name' => $product_category->name,
        'id'   => $product_category->term_id
      );
    }
  } else {
    $categories[] = array(
      'name' => __( 'No category found', 'jpid' ),
      'id'   => 0
    );
  }

  wp_send_json( $categories );
}
add_action( 'wp_ajax_load_product_categories', 'jpid_load_product_categories' );

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

  include JPID_PLUGIN_DIR . 'includes/admin/product/views/html-jpid-meta-box-product-data-category-field.php';

  die();
}
add_action( 'wp_ajax_load_product_categories_display', 'jpid_load_product_categories_display' );
