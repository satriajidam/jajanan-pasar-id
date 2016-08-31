<?php

/**
 * JPID admin class.
 *
 * Load JPID admin area.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id/includes/admin
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'JPID_Admin' ) ) :

class JPID_Admin {

  /**
   * @since    1.0.0
   * @var      array    Collection of admin page objects.
   */
  private $pages;

  /**
	 * Class constructor.
	 *
	 * @since    1.0.0
	 */
  public function __construct() {
    $this->includes();
    $this->setup_pages();
    $this->setup_hooks();
  }

  /**
	 * Include required files.
	 *
	 * @since    1.0.0
	 */
  private function includes() {
    require_once JPID_PLUGIN_DIR . 'includes/admin/class-jpid-admin-post-types.php';
    require_once JPID_PLUGIN_DIR . 'includes/admin/class-jpid-admin-about.php';
    require_once JPID_PLUGIN_DIR . 'includes/admin/class-jpid-admin-settings.php';

    // Products
    require_once JPID_PLUGIN_DIR . 'includes/admin/products/class-jpid-admin-product-category.php';
    require_once JPID_PLUGIN_DIR . 'includes/admin/products/class-jpid-admin-product-list.php';
    require_once JPID_PLUGIN_DIR . 'includes/admin/products/class-jpid-admin-product-edit.php';

    require_once JPID_PLUGIN_DIR . 'includes/admin/jpid-admin-ajax.php';
  }

  /**
   * Setup page slugs and prepare the page objects.
   *
   * @since    1.0.0
   */
  private function setup_pages() {
    $this->pages = array(
      'about' => array(
        'slug' => 'jpid-about',
        'page' => new JPID_Admin_About( 'jpid-about' )
      ),
      'settings' => array(
        'slug' => 'jpid-settings',
        'page' => new JPID_Admin_Settings( 'jpid-settings' )
      )
    );
  }

  /**
   * Setup class hooks.
   *
   * @since    1.0.0
   */
  private function setup_hooks() {
    add_action( 'admin_menu', array( $this, 'admin_menus' ) );
    add_action( 'admin_init', array( $this, 'admin_init' ) );

    add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );

    add_action( 'current_screen', array( $this, 'add_screen_help' ) );
    add_action( 'current_screen', array( $this, 'product_types_exist' ) );
  }

  /**
   * Add admin pages to the admin area.
   *
   * @since    1.0.0
   */
  public function admin_menus() {
    add_menu_page(
      __( 'Jajanan Pasar', 'jpid' ), __( 'Jajanan Pasar', 'jpid' ), 'manage_options',
      $this->pages['about']['slug'], null, 'dashicons-store', 50
    );

    add_submenu_page(
      $this->pages['about']['slug'], __( 'About', 'jpid' ), __( 'About', 'jpid' ), 'manage_options',
      $this->pages['about']['slug'], array( $this->pages['about']['page'], 'display_about_page' )
    );
    add_submenu_page(
      $this->pages['about']['slug'], __( 'Settings', 'jpid' ), __( 'Settings', 'jpid' ), 'manage_options',
      $this->pages['settings']['slug'], array( $this->pages['settings']['page'], 'display_settings_page' )
    );
  }

  /**
	 * Init JPID admin when WordPress admin initialises.
	 *
	 * @since    1.0.0
	 */
  public function admin_init() {
    $this->post_types       = new JPID_Admin_Post_Types();

    // Products
    $this->product_category = new JPID_Admin_Product_Category();
    $this->product_list     = new JPID_Admin_Product_List();
    $this->product_edit     = new JPID_Admin_Product_Edit();
  }

  /**
	 * Load JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
  public function admin_enqueue_scripts() {
    $current_post   = get_post();
    $current_screen = get_current_screen();

    // Vendor scripts:
    wp_enqueue_script( 'accounting' );
    wp_enqueue_script( 'select2' );
    wp_enqueue_script( 'stupidtable' );
    wp_enqueue_script( 'jquery-ui-timepicker' );
    wp_enqueue_script( 'jquery-blockUI' );

    // Plugin scripts:
    wp_enqueue_script( 'jpid-admin' );

    $jpid_admin_args = array(
      'ajax_url'  => admin_url( 'admin-ajax.php' ),
      'screen_id' => isset( $current_screen ) ? $current_screen->id : '',
      'post_id'   => isset( $current_post ) ? $current_post->ID : 0
    );

    if ( $current_screen->id === 'edit-jpid_product' ) {
      $snack_type = get_term_by( 'name', 'Snack', 'jpid_product_type' );
      $jpid_admin_args['snack_term_id'] = ! is_null( $snack_type ) ? (int) $snack_type->term_id : 0;

      $drink_type = get_term_by( 'name', 'Drink', 'jpid_product_type' );
      $jpid_admin_args['drink_term_id'] = ! is_null( $drink_type ) ? (int) $drink_type->term_id : 0;
    }

    if ( $current_screen->id === 'jpid_product' ) {
      $jpid_admin_args['load_product_categories_display_nonce'] = wp_create_nonce( 'load_product_categories_display' );
    }

    wp_localize_script( 'jpid-admin', 'jpid_admin', $jpid_admin_args );
  }

  /**
	 * Load CSS for the admin area.
	 *
	 * @since    1.0.0
	 */
  public function admin_enqueue_styles() {
    $current_post   = get_post();
    $current_screen = get_current_screen();

    // Vendor styles:
    wp_enqueue_style( 'select2' );
    wp_enqueue_style( 'jquery-ui-timepicker' );

    // Plugin styles:
    wp_enqueue_style( 'jpid-admin' );
  }

  /**
   * Add screen information to admin help tabs.
   *
   * The screen information will be shown only if WordPress debug mode is set to true in
   * the wp-config.php file.
   *
   * @since    1.0.0
   */
  public function add_screen_help() {
    global $hook_suffix;

    $current_screen = get_current_screen();

    // The add_help_tab function for screen was introduced in WordPress 3.3.
    if ( ! WP_DEBUG || ! method_exists( $current_screen, 'add_help_tab' ) ) {
      return;
    }

    // List screen properties
    $variables = '<ul style="width:50%;float:left;"> <strong>Screen variables </strong>'
      . sprintf( '<li> Screen id : %s</li>', $current_screen->id )
      . sprintf( '<li> Screen base : %s</li>', $current_screen->base )
      . sprintf( '<li> Parent base : %s</li>', $current_screen->parent_base )
      . sprintf( '<li> Parent file : %s</li>', $current_screen->parent_file )
      . sprintf( '<li> Hook suffix : %s</li>', $hook_suffix )
      . '</ul>';

    // Append global $hook_suffix to the hook stems
    $hooks = array(
      "load-{$hook_suffix}",
      "admin_print_styles-{$hook_suffix}",
      "admin_print_scripts-{$hook_suffix}",
      "admin_head-{$hook_suffix}",
      "admin_footer-{$hook_suffix}"
    );

    // If add_meta_boxes or add_meta_boxes_{screen_id} is used, list these too
    if ( did_action( 'add_meta_boxes_' . $current_screen->id ) ) {
      $hooks[] = 'add_meta_boxes_' . $current_screen->id;
    }

    if ( did_action( 'add_meta_boxes' ) ) {
      $hooks[] = 'add_meta_boxes';
    }

    // Get List HTML for the hooks
    $hooks = '<ul style="width:50%;float:left;"> <strong>Hooks </strong> <li>'
      . implode( '</li><li>', $hooks )
      . '</li></ul>';

    // Combine $variables list with $hooks list.
    $help_content = $variables . $hooks;

    // Add help panel
    $current_screen->add_help_tab( array(
      'id'      => 'jpid-screen-help',
      'title'   => 'Screen Information',
      'content' => $help_content,
    ) );
  }

  /**
   * Check for product types availability.
   *
   * Product types (snack & drink) should never be deleted from the database.
   * So if product types aren't available, generate error and then ask user
   * to restore them by deactivating then reactivating the plugin.
   *
   * @since    1.0.0
   */
  public function product_types_exist() {
    $current_screen = get_current_screen();

    $valid_screen = $current_screen->id === 'jpid_product'
      || $current_screen->id === 'edit-jpid_product'
      || $current_screen->id === 'edit-jpid_product_category';

    if ( ! $valid_screen ) {
      return;
    }

    $default_product_types = jpid_default_product_types();

    foreach ( $default_product_types as $product_type => $slug ) {
      if ( ! term_exists( $product_type, 'jpid_product_type' ) ) {
        $message  = '<p class="error">';
        $message .= __( 'Can\'t find product type: ' . $product_type . '. You might\'ve accidentally replaced or removed them from the database. Please deactivate then reactivate <i>j' . JPID_SLUG . '</i> plugin to restore them.', 'jpid' );
        $message .= '</p>';
        $message .= '<p style="font-style: italic;">Plugin: ' . JPID_SLUG . '<br />Version: ' . JPID_VERSION . '</p>';

        wp_die( $message );
      }
    }
  }

}

endif;