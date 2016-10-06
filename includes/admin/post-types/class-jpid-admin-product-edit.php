<?php

/**
 * Product post edit admin screen.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/post-types
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Product_Edit {

  /**
	 * @since    1.0.0
	 * @var      string    Nonce action.
	 */
  const NONCE_ACTION = 'jpid_save_product';

  /**
	 * @since    1.0.0
	 * @var      string    Nonce name.
	 */
  const NONCE_NAME = 'jpid_product_nonce';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
  public function __construct() {
    $this->includes();
    $this->setup_hooks();
  }

  /**
	 * Load the required meta box classes.
	 *
	 * @since    1.0.0
	 */
  private function includes() {
    require_once JPID_PLUGIN_DIR . 'includes/admin/post-types/meta-boxes/class-jpid-meta-box-product-data.php';
  }

  /**
   * Setup class hooks.
   *
   * @since    1.0.0
   */
  private function setup_hooks() {
    add_action( 'add_meta_boxes_jpid_product', array( $this, 'remove_meta_boxes'), 10 );
		add_action( 'add_meta_boxes_jpid_product', array( $this, 'add_meta_boxes' ), 20 );
		add_action( 'save_post_jpid_product', array( $this, 'save_meta_boxes' ), 10, 2 );
  }

  /**
	 * Add custom meta boxes.
	 *
	 * @since    1.0.0
	 */
  public function add_meta_boxes() {
    add_meta_box(
      'jpid_product_data', __( 'Product Data', 'jpid' ), array( 'JPID_Meta_Box_Product_Data', 'display' ),
      'jpid_product', 'normal', 'high'
    );
  }

  /**
	 * Remove default meta boxes.
	 *
	 * @since    1.0.0
	 */
  public function remove_meta_boxes() {
    remove_meta_box( 'jpid_product_categorydiv', 'jpid_product', 'side' );
  }

  /**
	 * Save meta boxes value when post is saved.
	 *
	 * @since    1.0.0
	 * @param    int        $post_id    The post ID.
	 * @param    WP_Post    $post       The post object.
	 */
  public function save_meta_boxes( $post_id, $post ) {
    if ( empty( $post_id ) || empty( $post ) ) {
      return;
    }

    if ( ! $this->can_save_post( $post_id ) ) {
      return;
    }

    $post_id = absint( $post_id );

    if ( get_post_type( $post_id ) === 'jpid_product' ) {
      JPID_Meta_Box_Product_Data::save( $post_id );
    }
  }

  /**
	 * Check if current edited post can be saved.
	 *
	 * @since     1.0.0
	 * @param     int        $post_id    The post ID.
	 * @return    boolean                True if post can be saved, otherwise false.
	 */
  private function can_save_post( $post_id ) {
    $is_auto_save   = ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || is_int( wp_is_post_autosave( $post_id ) );
    $is_revision    = is_int( wp_is_post_revision( $post_id ) );
    $is_valid_nonce = isset( $_POST[ self::NONCE_NAME ] ) && wp_verify_nonce( $_POST[ self::NONCE_NAME ], self::NONCE_ACTION );
    $is_user_can    = current_user_can( 'edit_post', $post_id );

    return ! ( $is_auto_save || $is_revision ) && $is_valid_nonce && $is_user_can;
  }

}
