<?php

/**
 * JPID product admin ajax.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/product
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Product_Ajax {

  /**
	 * Class constructor.
	 *
	 * @since    1.0.0
	 */
  public function __construct() {
    $this->setup_hooks();
  }

  /**
   * Setup class hooks.
   *
   * @since    1.0.0
   */
  private function setup_hooks() {
    add_action( 'wp_ajax_load_product_categories', array( $this, 'load_product_categories' ) );
    add_action( 'wp_ajax_load_product_categories_display', array( $this, 'load_product_categories_display' ) );
  }

  /**
   * Load product categories in specific product type.
   *
   * Loaded product categories will be send as an array of term_name => term_id pairs.
   *
   * @since    1.0.0
   */
  public function load_product_categories() {
    check_ajax_referer( 'load_product_categories', 'security' );

    $current_type = absint( $_POST['current_type'] );

    if ( ! is_numeric( $current_type ) || $current_type <= 0 ) {
      die();
    }

    $product_categories = jpid_get_product_category_terms( $current_type );
    $send_categories    = array();

    if ( ! empty( $product_categories ) ) {
      foreach ( $product_categories as $product_category ) {
        $send_categories[] = array(
          'name' => $product_category->name,
          'id'   => $product_category->term_id
        );
      }
    } else {
      $send_categories[] = array(
        'name' => __( 'No category found', 'jpid' ),
        'id'   => 0
      );
    }

    wp_send_json( $categories );
  }

  /**
   * Load HTML display for product category selector in product edit screen.
   *
   * @since    1.0.0
   */
  public function load_product_categories_display() {
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

}
