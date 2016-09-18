<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during plugin's activation.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Activator {

  /**
   * Run plugin activation functions.
   *
   * @since    1.0.0
   */
  public static function activate() {
  	if ( ! current_user_can( 'activate_plugins' ) ) {
  		return;
  	}

  	$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

  	check_admin_referer( "activate-plugin_{$plugin}" );

  	self::setup_post_types();
  	self::save_plugin_version();
  	self::create_tables();
  	self::save_db_version();
  	self::add_roles();
  	self::create_terms();

  	flush_rewrite_rules();
  }

  /**
   * Setup custom post types & taxonomies for plugin activation.
   *
   * @since    1.0.0
   */
  private static function setup_post_types() {
  	include_once JPID_PLUGIN_DIR . 'includes/jpid-post-types.php';

    $plugin_post_types = new JPID_Post_Types();

  	$plugin_post_types->register_taxonomies();
  	$plugin_post_types->register_post_types();
    $plugin_post_types->register_post_statuses();
  }

  /**
   * Save plugin version to the database.
   *
   * @since    1.0.0
   */
  private static function save_plugin_version() {
  	$current_version = get_option( 'jpid_version', null );
  	$save_version    = is_null( $current_version ) || ( version_compare( $current_version, JPID_VERSION ) < 0 );

  	if ( $save_version ) {
  		update_option( 'jpid_version', JPID_VERSION );
  	}
  }
  /**
   * Add plugin's custom user roles.
   *
   * @since    1.0.0
   */
  private static function add_roles() {
  	global $wp_roles;

  	if ( ! class_exists( 'WP_Roles' ) ) {
  		return;

    }
  	if ( ! isset( $wp_roles ) ) {
  		$wp_roles = new WP_Roles();
  	}

  	// Add customer role:
  	add_role( 'customer', __( 'Customer', 'jpid' ), array(
  		'read' => true
  	) );

  	// Add shop manager role:
  	add_role( 'shop_manager', __( 'Shop Manager', 'jpid' ), array(
  		'read'                   => true,
  		'read_private_pages'     => true,
  		'read_private_posts'     => true,
  		'edit_users'             => true,
  		'edit_posts'             => true,
  		'edit_pages'             => true,
  		'edit_published_posts'   => true,
  		'edit_published_pages'   => true,
  		'edit_private_pages'     => true,
  		'edit_private_posts'     => true,
  		'edit_others_posts'      => true,
  		'edit_others_pages'      => true,
  		'publish_posts'          => true,
  		'publish_pages'          => true,
  		'delete_posts'           => true,
  		'delete_pages'           => true,
  		'delete_private_pages'   => true,
  		'delete_private_posts'   => true,
  		'delete_published_pages' => true,
  		'delete_published_posts' => true,
  		'delete_others_posts'    => true,
  		'delete_others_pages'    => true,
  		'manage_categories'      => true,
  		'manage_links'           => true,
  		'moderate_comments'      => true,
  		'unfiltered_html'        => true,
  		'upload_files'           => true,
  		'export'                 => true,
  		'import'                 => true,
  		'list_users'             => true
  	) );
  }

  /**
   * Create plugin's default terms.
   *
   * @since    1.0.0
   */
  private static function create_terms() {
  	$default_product_types = jpid_default_product_types();

  	foreach ( $default_product_types as $product_type => $slug ) {
  		if ( ! get_term_by( 'slug', $slug, 'jpid_product_type' ) ) {
  			wp_insert_term( $product_type, 'jpid_product_type', array( 'slug' => $slug ) );
  		}
  	}
  }

  /**
   * Create plugin's custom database tables.
   *
   * @since    1.0.0
   */
  private static function create_tables() {
  	global $wpdb;

  	$wpdb->hide_errors();

  	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    dbdelta( self::get_schema() );
  }

  /**
   * Get schema for custom database tables.
   *
   * @since     1.0.0
   * @return    string    Table scheme.
   */
  private static function get_schema() {
    global $wpdb;

    $charset_collate = '';

    if ( $wpdb->has_cap( 'collation' ) ) {
			$charset_collate = $wpdb->get_charset_collate();
		}

    // Orders table
    $tables = "CREATE TABLE {$wpdb->prefix}jpid_orders (
      order_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      order_invoice varchar(100) NOT NULL,
      order_date datetime NOT NULL,
      order_status varchar(40) NOT NULL,
      customer_id bigint(20) UNSIGNED NOT NULL,
      recipient_name varchar(200) NOT NULL,
      recipient_phone varchar(200) NOT NULL,
      delivery_date datetime NOT NULL,
      delivery_address varchar(200) NOT NULL,
      delivery_province varchar(200) NOT NULL,
      delivery_city varchar(200) NOT NULL,
      delivery_note text,
      delivery_cost float NOT NULL,
      snack_box_qty int(20) NOT NULL,
      snack_box_price float NOT NULL,
      order_modified datetime,
      PRIMARY KEY  (order_id),
      KEY order_invoice (order_invoice),
      KEY customer_id (customer_id),
      UNIQUE (order_invoice)
    ) {$charset_collate};";

    // Order items table
    $tables .= "CREATE TABLE {$wpdb->prefix}jpid_order_items (
      item_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      order_id bigint(20) UNSIGNED NOT NULL,
      product_id bigint(20) UNSIGNED NOT NULL,
      item_qty int(20) NOT NULL,
      PRIMARY KEY  (item_id)
      KEY order_id (order_id)
    ) {$charset_collate};";

    // EXPERIMENTAL TABLES: not intended for production use.
    //
    // $tables .= "CREATE TABLE {$wpdb->prefix}jpid_snack_box (
    //   snack_box_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    //   order_id bigint(20) UNSIGNED NOT NULL,
    //   snack_box_price float NOT NULL,
    //   snack_box_qty int(20) NOT NULL,
    //   PRIMARY KEY  (snack_box_id),
    //   KEY order_id (order_id)
    // ) {$charset_collate};";
    //
    // $tables .= "CREATE TABLE {$wpdb->prefix}jpid_snack_box_items (
    //   item_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    //   snack_box_id bigint(20) UNSIGNED NOT NULL,
    //   product_id bigint(20) UNSIGNED NOT NULL,
    //   item_qty int(20) NOT NULL,
    //   PRIMARY KEY  (item_id),
    //   KEY snack_box_id (snack_box_id)
    // ) {$charset_collate};";

    // Order logs table
    $tables .= "CREATE TABLE {$wpdb->prefix}jpid_order_logs (
      log_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      order_id bigint(20) UNSIGNED NOT NULL,
      user_id bigint(20) UNSIGNED,
      log_date datetime NOT NULL,
      log_author varchar(200) NOT NULL,
      log_type varchar(40) NOT NULL,
      log_message text NOT NULL,
      PRIMARY KEY  (log_id),
      KEY order_id (order_id)
    ) {$charset_collate};";

    // Customers table
    $tables .= "CREATE TABLE {$wpdb->prefix}jpid_customers (
      customer_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      user_id bigint(20) UNSIGNED,
      customer_status varchar(40) NOT NULL,
      customer_name varchar(200) NOT NULL,
      customer_email varchar(200) NOT NULL,
      customer_phone varchar(200),
      customer_address varchar(200),
      customer_province varchar(200),
      customer_city varchar(200),
      total_orders int(20) NOT NULL,
      total_spendings float NOT NULL,
      date_created datetime NOT NULL,
      PRIMARY KEY  (customer_id),
      KEY customer_email (customer_email)
    ) {$charset_collate};";

    // Payments table
    $tables .= "CREATE TABLE {$wpdb->prefix}jpid_payments (
      payment_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      order_invoice varchar(100) NOT NULL,
      date_submitted datetime NOT NULL,
      receipt_id bigint(20) UNSIGNED,
      payment_bank varchar(200) NOT NULL,
      payment_account_name varchar(200) NOT NULL,
      payment_account_number varchar(200) NOT NULL,
      transfer_bank varchar(200) NOT NULL,
      transfer_account_name varchar(200) NOT NULL,
      transfer_account_number varchar(200) NOT NULL,
      transfer_amount float NOT NULL,
      transfer_note text,
      transfer_date datetime NOT NULL,
      PRIMARY KEY  (payment_id),
      KEY order_invoice (order_invoice)
    ) {$charset_collate};";

    return $tables;
  }

  /**
   * Save plugin's database version.
   *
   * @since    1.0.0
   */
  private static function save_db_version() {
  	$current_version = get_option( 'jpid_db_version', null );
  	$save_version    = is_null( $current_version ) || ( version_compare( $current_version, JPID_VERSION ) < 0 );

  	if ( $save_version ) {
  		update_option( 'jpid_db_version', JPID_VERSION );
  	}
  }

}
