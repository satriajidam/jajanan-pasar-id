<?php

/**
 * Product object.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/product
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Product {

  private $id = 0;

  private $post = null;

  private $name = '';

  private $description = '';

  private $price = 0;

  private $ingredients = '';

  private $type = null;

  private $category = null;

  /**
	 * Initialize product object and set all its properties.
	 *
	 * @since    1.0.0
	 * @param    int|JPID_Product|WP_Post    $product    Post ID, product object, or post object.
	 */
  public function __construct( $product ) {
    // Setup product object.
    if ( is_numeric( $product ) ) {                          // IF an ID is passed.
      $this->id = absint( $product );
      $this->post = get_post( $this->id );
    } elseif ( $product instanceof JPID_Product ) {          // IF JPID_Product object is passed.
      $this->id = absint( $product->id );
      $this->post = get_post( $product->post );
    } elseif ( $product instanceof WP_Post ) {               // IF WP_Post object is passed.
      $this->id = absint( $product->ID );
      $this->post = $product;
    }

    // Setup product object's data.
    if ( $this->id && $this->post ) {
      $this->populate_data();
    }
  }

  /**
	 * Populate product object's properties with post data.
	 *
	 * @since    1.0.0
	 */
  private function populate_data() {
    $this->name        = (string) $this->post->post_title;
    $this->description = (string) $this->post->post_content;
    $this->price       = (float) get_post_meta( $this->id, '_jpid_product_price', true );
    $this->ingredients = (string) get_post_meta( $this->id, '_jpid_product_ingredients', true );

    $product_types      = get_the_terms( $this->id, 'jpid_product_type' );
    $this->type         = $product_types ? array_pop( $product_types ) : null;

    $product_categories = get_the_terms( $this->id, 'jpid_product_category' );
    $this->category     = $product_categories ? array_pop( $product_categories ) : null;
  }

  public function get_id() {
    return $this->id;
  }

  public function get_post_object() {
    return $this->post;
  }

  public function exists() {
    return ! is_null( $this->get_post_object() ) ? true : false;
  }

  public function get_name() {
    return $this->name;
  }

  public function get_formatted_name() {
    return '#' . $this->get_id() . ' - ' . $this->get_name();
  }

  public function get_description() {
    return $this->description;
  }

  public function get_price() {
    return $this->price;
  }

  public function get_ingredients() {
    return $this->ingredients;
  }

  public function get_type_object() {
    return $this->type;
  }

  public function get_type_id() {
    return ! is_null( $this->get_type_object() ) ? $this->get_type_object()->term_id : 0;
  }

  public function get_type_name() {
    return ! is_null( $this->get_type_object() ) ? $this->get_type_object()->name : '';
  }

  public function is_type( $type ) {
    if ( is_null( $this->get_type_object() ) ) {
      return false;
    }

    if ( is_numeric( $type ) ) {
      return intval( $this->get_type_object()->term_id ) === intval( $type );
    } elseif ( is_string( $type ) ) {
      return strtolower( $this->get_type_object()->name ) === strtolower( $type );
    } elseif ( isset( $type->term_id ) && $type->taxonomy === 'jpid_product_type' ) {
      return intval( $this->get_type_object()->term_id ) === intval( $type->term_id );
    }

    return false;
  }

  public function get_category_object() {
    return $this->category;
  }

  public function get_category_id() {
    return ! is_null( $this->get_category_object() ) ? $this->get_category_object()->term_id : 0;
  }

  public function get_category_name() {
    return ! is_null( $this->get_category_object() ) ? $this->get_category_object()->name : '';
  }

  public function is_category( $category ) {
    if ( is_null( $this->get_category_object() ) ) {
      return false;
    }

    if ( is_numeric( $category ) ) {
      return intval( $this->get_category_object()->term_id ) === intval( $category );
    } elseif ( is_string( $category ) ) {
      return strtolower( $this->get_category_object()->name ) === strtolower( $category );
    } elseif ( isset( $category->term_id ) && $category->taxonomy === 'jpid_product_category' ) {
      return intval( $this->get_category_object()->term_id ) === intval( $category->term_id );
    }

    return false;
  }

  public function get_permalink() {
    return get_permalink( $this->get_id() );
  }

  public function get_editlink() {
    if ( ! current_user_can( 'edit_post', $this->get_id() ) ) {
      return '#';
    }

    return get_edit_post_link( $this->get_id() );
  }

  private function has_image() {
    return has_post_thumbnail( $this->get_id() );
  }

  public function get_image( $size = 'post-thumbnail', $attr = array() ) {
    if ( $this->has_image() ) {
      $image = get_the_post_thumbnail( $this->get_id(), $size, $attr );
    } else {
      $image = null;
    }

    return $image;
  }

  public function get_image_id() {
    if ( $this->has_image() ) {
      $image_id = get_post_thumbnail_id( $this->get_id() );
    } else {
      $image_id = 0;
    }

    return $image_id;
  }

  /**
	 * Check if product is available for order.
	 *
	 * @since     1.0.0
	 * @return    boolean    True if product is available, otherwise false.
	 */
  public function is_available() {
    if ( ! $this->exists() ) {
      return false;
    }

    if ( $this->get_price() == 0 ) {
      return false;
    }

    if ( $this->get_post_object()->post_status !== 'publish' ) {
      return false;
    }

    return true;
  }

}
