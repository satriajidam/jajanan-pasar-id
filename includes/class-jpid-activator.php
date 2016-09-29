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

    self::create_options();
  	self::save_plugin_version();
  	self::create_tables();
  	self::save_db_version();
  	self::add_roles();
    self::setup_post_types();
  	self::create_terms();
    self::create_pages();

  	flush_rewrite_rules();
  }

  /**
   * Create plugin options and assign them with default values.
   *
   * @since    1.0.0
   */
  private static function create_options() {
    $plugin_options = new JPID_Options();

    $plugin_options->load_options();

    foreach ( $plugin_options->get_options() as $option_name => $option_value ) {
      if ( ! get_option( $option_name ) ) {
        update_option( $option_name, $option_value );
      }
    }
  }

  /**
   * Save plugin version to the database.
   *
   * @since    1.0.0
   */
  private static function save_plugin_version() {
  	$current_version = get_option( 'jpid_version', '' );
  	$update_version  = empty( $current_version ) || ( version_compare( $current_version, JPID_VERSION ) < 0 );

  	if ( $update_version ) {
  		update_option( 'jpid_version', JPID_VERSION );
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
  public static function get_schema() {
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
      delivery_cost decimal(10, 2) NOT NULL,
      delivery_note text,
      order_cost decimal(10, 2) NOT NULL,
      modified_date datetime,
      PRIMARY KEY  (order_id),
      UNIQUE KEY order_invoice (order_invoice),
      KEY order_date (order_date),
      KEY order_status (order_status),
      KEY customer_id (customer_id),
      KEY delivery_date (delivery_date)
    ) {$charset_collate};";

    // Order items table
    $tables .= "CREATE TABLE {$wpdb->prefix}jpid_order_items (
      order_id bigint(20) UNSIGNED NOT NULL,
      item_id bigint(20) UNSIGNED NOT NULL,
      item_qty int(20) NOT NULL,
      item_type varchar(200) NOT NULL DEFAULT '',
      PRIMARY KEY  (order_id, item_id),
      KEY order_id (order_id)
    ) {$charset_collate};";

    // Snack boxes table
    $tables .= "CREATE TABLE {$wpdb->prefix}jpid_snack_box (
      snack_box_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      date_created datetime NOT NULL,
      snack_box_name varchar(200),
      snack_box_type varchar(200) NOT NULL,
      snack_box_price decimal(10, 2) NOT NULL,
      PRIMARY KEY  (snack_box_id),
      KEY snack_box_type (snack_box_type)
    ) {$charset_collate};";

    // Snack box items table
    $tables .= "CREATE TABLE {$wpdb->prefix}jpid_snack_box_items (
      snack_box_id bigint(20) UNSIGNED NOT NULL,
      product_id bigint(20) UNSIGNED NOT NULL,
      product_qty int(20) NOT NULL,
      PRIMARY KEY  (snack_box_id, product_id),
      KEY snack_box_id (snack_box_id)
    ) {$charset_collate};";

    // Order logs table
    $tables .= "CREATE TABLE {$wpdb->prefix}jpid_order_logs (
      log_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      order_id bigint(20) UNSIGNED NOT NULL,
      user_id bigint(20) UNSIGNED NOT NULL DEFAULT 0,
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
      user_id bigint(20) UNSIGNED NOT NULL DEFAULT 0,
      date_created datetime NOT NULL,
      customer_status varchar(40) NOT NULL,
      customer_name varchar(200),
      customer_email varchar(200) NOT NULL,
      customer_phone varchar(200),
      customer_address varchar(200),
      customer_province varchar(200),
      customer_city varchar(200),
      order_count int(20),
      total_spendings decimal(10, 2),
      PRIMARY KEY  (customer_id),
      KEY user_id (user_id),
      KEY date_created (date_created),
      KEY customer_status (customer_status),
      UNIQUE KEY customer_email (customer_email)
    ) {$charset_collate};";

    // Payments table
    $tables .= "CREATE TABLE {$wpdb->prefix}jpid_payments (
      payment_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      order_invoice varchar(100) NOT NULL,
      date_submitted datetime NOT NULL,
      receipt_id bigint(20) UNSIGNED,
      payment_status varchar(40) NOT NULL,
      payment_bank varchar(200) NOT NULL,
      payment_account_name varchar(200) NOT NULL,
      payment_account_number varchar(200) NOT NULL,
      transfer_bank varchar(200) NOT NULL,
      transfer_account_name varchar(200) NOT NULL,
      transfer_account_number varchar(200) NOT NULL,
      transfer_amount decimal(10, 2) NOT NULL,
      transfer_date datetime NOT NULL,
      transfer_note text,
      PRIMARY KEY  (payment_id),
      KEY order_invoice (order_invoice),
      KEY date_submitted (date_submitted),
      KEY payment_status (payment_status)
    ) {$charset_collate};";

    return $tables;
  }

  /**
   * Save plugin's database version.
   *
   * @since    1.0.0
   */
  private static function save_db_version() {
  	$current_db_version = get_option( 'jpid_db_version', '' );
  	$update_db_version  = empty( $current_db_version ) || version_compare( $current_db_version, JPID_DB_VERSION, '<' );

  	if ( $update_db_version ) {
  		update_option( 'jpid_db_version', JPID_DB_VERSION );
  	}
  }

  /**
   * Add plugin's custom user roles.
   *
   * @since    1.0.0
   */
  private static function add_roles() {
    $plugin_roles = new JPID_Roles();

    $plugin_roles->add_roles();
  }

  /**
   * Setup custom post types & taxonomies for plugin activation.
   *
   * This action is needed so we can create default terms & posts for our
   * custom taxonomies and post types in plugin activation.
   *
   * @since    1.0.0
   */
  private static function setup_post_types() {
    $plugin_post_types = new JPID_Post_Types();

    $plugin_post_types->register_taxonomies();
    $plugin_post_types->register_post_types();
    $plugin_post_types->register_post_statuses();
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
   * Create plugin's default pages.
   *
   * TODO: This whole pages creation action should be done through an installation wizard and not
   * automatically created. Update it in the next development iteration.
   *
   * @since    1.0.0
   */
  private static function create_pages() {
    if ( ! get_option( 'jpid_snacks_selection_page', 0 ) ) {
      $snacks_selection_page_id = wp_insert_post( array(
        'post_title'     => __( 'Snacks Selection', 'jpid' ),
        'post_content'   => '[snacks_selection]',
        'post_status'    => 'publish',
        'post_author'    => 1,
        'post_type'      => 'page',
        'comment_status' => 'closed'
      ) );

      update_option( 'jpid_snacks_selection_page', $snacks_selection_page_id );
    }

    if ( ! get_option( 'jpid_drinks_selection_page', 0 ) ) {
      $drinks_selection_page_id = wp_insert_post( array(
        'post_title'     => __( 'Drinks Selection', 'jpid' ),
        'post_content'   => '[drinks_selection]',
        'post_status'    => 'publish',
        'post_author'    => 1,
        'post_type'      => 'page',
        'comment_status' => 'closed'
      ) );

      update_option( 'jpid_drinks_selection_page', $drinks_selection_page_id );
    }

    if ( ! get_option( 'jpid_checkout_page', 0 ) ) {
      $checkout_page_id = wp_insert_post( array(
        'post_title'     => __( 'Checkout', 'jpid' ),
        'post_content'   => '[order_checkout]',
        'post_status'    => 'publish',
        'post_author'    => 1,
        'post_type'      => 'page',
        'comment_status' => 'closed'
      ) );

      update_option( 'jpid_checkout_page', $checkout_page_id );
    }

    if ( ! get_option( 'jpid_payment_confirmation_page', 0 ) ) {
      $payment_confirmation_page_id = wp_insert_post( array(
        'post_title'     => __( 'Payment Confirmation', 'jpid' ),
        'post_content'   => '[payment_confirmation]',
        'post_status'    => 'publish',
        'post_author'    => 1,
        'post_type'      => 'page',
        'comment_status' => 'closed'
      ) );

      update_option( 'jpid_payment_confirmation_page', $payment_confirmation_page_id );
    }

    if ( ! get_option( 'jpid_customer_page', 0 ) ) {
      $customer_page_id = wp_insert_post( array(
        'post_title'     => __( 'Customer', 'jpid' ),
        'post_content'   => '[customer]',
        'post_status'    => 'publish',
        'post_author'    => 1,
        'post_type'      => 'page',
        'comment_status' => 'closed'
      ) );

      update_option( 'jpid_customer_page', $customer_page_id );
    }
  }

}
