<?php

/**
 * Manage jpid_product post edit screen.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/admin/screens/products
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
class JPID_Screen_Product_Edit {

  /**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

  /**
   * Nonce action.
   *
   * @since    1.0.0
   */
  const NONCE_ACTION = 'jpid_save_product';

  /**
   * Nonce name.
   *
   * @since    1.0.0
   */
  const NONCE_NAME = 'jpid_product_nonce';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name    The name of this plugin.
	 * @param    string    $version        The version of this plugin.
	 */
  public function __construct( $plugin_name, $version ) {
    $this->plugin_name = $plugin_name;
    $this->version = $version;

    $this->load_meta_boxes();
  }

  /**
	 * Load the required meta box classes.
	 *
	 * @since    1.0.0
	 */
  private function load_meta_boxes() {
    require_once JPID_PLUGIN_DIR . 'admin/screens/products/meta-boxes/class-jpid-meta-box-product-data.php';
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

  /**
	 * Change placeholder text in post title input.
	 *
	 * @since     1.0.0
	 * @param     string     $text    Default placeholder text.
	 * @param     WP_Post    $post    The post object.
	 * @return    string              Modified placeholder text.
	 */
  public function enter_title_here( $text, $post ) {
    if ( $post->post_type !== 'jpid_product' ) {
      return;
    }

    $text = __( 'Product Name', 'jpid' );

    return $text;
  }

}
