<?php

/**
 * Product object class.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/post-types
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Product {

  /**
	 * @since    1.0.0
	 * @var      int      Product ID.
	 */
  private $id = 0;

  /**
	 * @since    1.0.0
	 * @var      WP_Post    Product post object.
	 */
  private $post = null;

  /**
	 * @since    1.0.0
	 * @var      string    Product publish status.
	 */
  private $status = '';

  /**
	 * @since    1.0.0
	 * @var      string    Product name.
	 */
  private $name = '';

  /**
	 * @since    1.0.0
	 * @var      string    Product description.
	 */
  private $description = '';

  /**
	 * @since    1.0.0
	 * @var      float    Product price.
	 */
  private $price = 0;

  /**
	 * @since    1.0.0
	 * @var      string    Product ingredients.
	 */
  private $ingredients = '';

  /**
	 * @since    1.0.0
	 * @var      WP_Term    Product type term object.
	 */
  private $type = null;

  /**
	 * @since    1.0.0
	 * @var      WP_Term    Product category term object.
	 */
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
    } else {
      return null;
    }
  }

  /**
	 * Populate product object's properties with post data.
	 *
	 * @since    1.0.0
	 */
  private function populate_data() {
    $this->status      = (string) $this->post->post_status;
    $this->name        = (string) $this->post->post_title;
    $this->description = (string) $this->post->post_content;
    $this->price       = (float) get_post_meta( $this->id, '_jpid_product_price', true );
    $this->ingredients = (string) get_post_meta( $this->id, '_jpid_product_ingredients', true );

    $product_types      = get_the_terms( $this->id, 'jpid_product_type' );
    $this->type         = $product_types ? array_pop( $product_types ) : null;

    $product_categories = get_the_terms( $this->id, 'jpid_product_category' );
    $this->category     = $product_categories ? array_pop( $product_categories ) : null;
  }

  /**
   * Get product ID.
   *
   * @since     1.0.0
   * @return    int      Product ID.
   */
  public function get_id() {
    return $this->id;
  }

  /**
   * Get product status.
   *
   * @since     1.0.0
   * @return    int      Product status.
   */
  public function get_status() {
    return $this->status;
  }

  /**
   * Check if this product object exists in database.
   *
   * @since     1.0.0
   * @return    boolean    True if exists, otherwise false.
   */
  public function exists() {
    return ! is_null( $this->post ) ? true : false;
  }

  /**
   * Get product name.
   *
   * @since     1.0.0
   * @return    string    Product name.
   */
  public function get_name() {
    return $this->name;
  }

  /**
   * Get product name prefixed with ID.
   *
   * @since     1.0.0
   * @return    string    Product formatted name.
   */
  public function get_formatted_name() {
    return '#' . $this->get_id() . ' - ' . $this->get_name();
  }

  /**
   * Get product description.
   *
   * @since     1.0.0
   * @return    string    Product description.
   */
  public function get_description() {
    return $this->description;
  }

  /**
   * Get product price.
   *
   * @since     1.0.0
   * @return    float    Product price.
   */
  public function get_price() {
    return $this->price;
  }

  /**
   * Get product ingredients.
   *
   * @since     1.0.0
   * @return    string    Product ingredients.
   */
  public function get_ingredients() {
    return $this->ingredients;
  }

  /**
   * Get product type ID.
   *
   * @since     1.0.0
   * @return    int      Product type ID.
   */
  public function get_type_id() {
    return ! is_null( $this->type ) ? $this->type->term_id : 0;
  }

  /**
   * Get product type name.
   *
   * @since     1.0.0
   * @return    string    Product type name.
   */
  public function get_type_name() {
    return ! is_null( $this->type ) ? $this->type->name : '';
  }

  /**
   * Check if this product is of certain type.
   *
   * @since     1.0.0
   * @param     int|string|WP_Term    $type    Type ID, name, or term object to check against.
   * @return    boolean                        True if this product is of the checked type,
   *                                           otherwise false.
   */
  public function is_type( $type ) {
    if ( is_null( $this->type ) ) {
      return false;
    }

    if ( is_numeric( $type ) ) {
      return intval( $this->type->term_id ) === intval( $type );
    } elseif ( is_string( $type ) ) {
      return strtolower( $this->type->name ) === strtolower( $type );
    } elseif ( isset( $type->term_id ) && $type->taxonomy === 'jpid_product_type' ) {
      return intval( $this->type->term_id ) === intval( $type->term_id );
    }

    return false;
  }

  /**
   * Get product category ID.
   *
   * @since     1.0.0
   * @return    int      Product category ID.
   */
  public function get_category_id() {
    return ! is_null( $this->category ) ? $this->category->term_id : 0;
  }

  /**
   * Get product category name.
   *
   * @since     1.0.0
   * @return    string    Product category name.
   */
  public function get_category_name() {
    return ! is_null( $this->category ) ? $this->category->name : '';
  }

  /**
   * Check if this product is of certain category.
   *
   * @since     1.0.0
   * @param     int|string|WP_Term    $category    Category ID, name, or term object to check against.
   * @return    boolean                            True if this product is of the checked category,
   *                                               otherwise false.
   */
  public function is_category( $category ) {
    if ( is_null( $this->category ) ) {
      return false;
    }

    if ( is_numeric( $category ) ) {
      return intval( $this->category->term_id ) === intval( $category );
    } elseif ( is_string( $category ) ) {
      return strtolower( $this->category->name ) === strtolower( $category );
    } elseif ( isset( $category->term_id ) && $category->taxonomy === 'jpid_product_category' ) {
      return intval( $this->category->term_id ) === intval( $category->term_id );
    }

    return false;
  }

  /**
   * Get product permalink.
   *
   * @since     1.0.0
   * @return    string    Product permalink.
   */
  public function get_permalink() {
    return get_permalink( $this->get_id() );
  }

  /**
   * Get product editlink.
   *
   * @since     1.0.0
   * @return    string    Product editlink.
   */
  public function get_editlink() {
    if ( ! current_user_can( 'edit_post', $this->get_id() ) ) {
      return '#';
    }

    return get_edit_post_link( $this->get_id() );
  }

  /**
   * Check if this product has an image.
   *
   * @since     1.0.0
   * @return    boolean    True if has image, otherwise false.
   */
  private function has_image() {
    return has_post_thumbnail( $this->get_id() );
  }

  /**
   * Get product image html tag.
   *
   * @since     1.0.0
   * @param     string    $size    Image size.
   * @param     array     $attr    Image attributes.
   * @return    string             Product image html tag.
   */
  public function get_image( $size = 'post-thumbnail', $attr = array() ) {
    if ( $this->has_image() ) {
      $image = get_the_post_thumbnail( $this->get_id(), $size, $attr );
    } else {
      $image = null;
    }

    return $image;
  }

  /**
   * Get product image ID.
   *
   * @since     1.0.0
   * @return    int      Product image ID.
   */
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

    if ( $this->get_status() !== 'publish' ) {
      return false;
    }

    return true;
  }

}
