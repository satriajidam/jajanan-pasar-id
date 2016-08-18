<?php

/**
 * Manage jpid_product post edit screen.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/admin/screens/products
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
class JPID_Screen_Products_Edit {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name    The name of this plugin.
	 * @param    string    $version        The version of this plugin.
	 */
  public function __construct( $plugin_name, $version ) {
    $this->plugin_name = $plugin_name;
    $this->version = $version;
    
    $this->load_depedencies();
  }

  private function load_depedencies() {
    require_once JPID_PLUGIN_DIR . 'admin/screens/products/meta-boxes/class-jpid-meta-box-product-data.php';
  }

  public function add_meta_boxes() {
    add_meta_box(
      'jpid_product_data', __( 'Product Data', 'jpid' ), 'JPID_Meta_Box_Product_Data::display',
      'jpid_product', 'normal', 'high'
    );
  }

  public function remove_meta_boxes() {
    remove_meta_box( 'tagsdiv-jpid_product_category', 'jpid_product', 'side' );
  }

  public function save_meta_boxes( $post_id, $post, $update ) {

  }

}
