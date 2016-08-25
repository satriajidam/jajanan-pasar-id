<?php

/**
 * Handle admin-specific ajax request of this plugin.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/admin
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
class JPID_Admin_Ajax {

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
	}

  /**
	 * Load product categories selector html display.
	 *
	 * @since    1.0.0
	 */
  public function load_product_categories() {
    check_ajax_referer( 'load_product_categories', 'security' );

    $current_type = absint( $_POST['current_type'] );

    if ( ! is_numeric( $current_type ) || $current_type <= 0 ) {
      die();
    }

    $current_post = absint( $_POST['current_post'] );

    if ( ! is_numeric( $current_post ) || $current_post <= 0 ) {
      die();
    }

    $product = new JPID_Product( $current_post );

    include JPID_PLUGIN_DIR . 'admin/screens/products/views/html-jpid-meta-box-product-data-category-field.php';

    die();
  }

}
