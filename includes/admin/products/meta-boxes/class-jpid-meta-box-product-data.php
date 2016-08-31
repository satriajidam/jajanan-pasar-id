<?php

/**
 * Product data meta box for JPID product post edit admin screen.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/products
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Meta_Box_Product_Data {

  /**
   * Display the html output of this meta box.
   *
	 * @since    1.0.0
   * @param    WP_Post    $post    The post object.
   */
  public static function display( $post ) {
    $product = jpid_get_product( $post->ID );

    include_once JPID_PLUGIN_DIR . 'includes/admin/products/views/html-jpid-meta-box-product-data.php';
  }

  /**
   * Save field values of this meta box.
   *
	 * @since    1.0.0
   * @param    int    $post_id    The post ID.
   */
  public static function save( $post_id ) {
    if ( isset( $_POST['jpid_product_price'] ) ) {
      $product_price = sanitize_text_field( $_POST['jpid_product_price'] );
      $product_price = floatval( $product_price );

      update_post_meta( $post_id, '_jpid_product_price', $product_price );
    }

    if ( isset( $_POST['jpid_product_ingredients'] ) ) {
      $product_ingredients = sanitize_text_field( $_POST['jpid_product_ingredients'] );
      $product_ingredients = trim( $product_ingredients );

      update_post_meta( $post_id, '_jpid_product_ingredients', $product_ingredients );
    }

    if ( isset( $_POST['jpid_product_type'] ) ) {
      $product_type_id = absint( $_POST['jpid_product_type'] );

      wp_set_object_terms( $post_id, array( $product_type_id ), 'jpid_product_type' );
    }

    if ( isset( $_POST['jpid_product_category'] ) ) {
      $product_category_id = absint( $_POST['jpid_product_category'] );

      wp_set_object_terms( $post_id, array( $product_category_id ), 'jpid_product_category' );
    }
  }

}
