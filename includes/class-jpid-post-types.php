<?php

/**
 * Register all custom post types, taxonomies, and post statuses for the plugin.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
class JPID_Post_Types {

  /**
   * Register custom taxonomies.
   *
   * @since    1.0.0
   */
  public function register_taxonomies() {
    // Register jpid_product_type custom taxonomy.
    if ( ! taxonomy_exists( 'jpid_product_type' ) ) {
      register_taxonomy( 'jpid_product_type', array( 'jpid_product'),
        array(
          'label' => __( 'Product Types', 'jpid' ),
          'public' => false,
          'hierarchical' => false,
          'show_ui' => false,
          'query_var' => is_admin(),
          'rewrite' => false,
          'show_admin_column' => false,
          'show_in_rest' => false,
          'rest_base' => '',
          'show_in_quick_edit' => false,
          'show_in_nav_menus' => false
        )
      );
    }

    // Register jpid_product_category custom taxonomy.
    if ( ! taxonomy_exists( 'jpid_product_category' ) ) {
      register_taxonomy( 'jpid_product_category', array( 'jpid_product' ),
        array(
          'label' => __( 'Product Categories', 'jpid' ),
          'labels' => array(
            'name' => __( 'Product Categories', 'jpid' ),
            'singular_name' => __( 'Product Category', 'jpid' ),
            'menu_name' => _x( 'Categories', 'Admin Menu', 'jpid' ),
            'all_items' => __( 'All Product Categories', 'jpid' ),
            'edit_item' => __( 'Edit Product Category', 'jpid' ),
            'view_item' => __( 'View Product Category', 'jpid' ),
            'update_item' => __( 'Update Product Category', 'jpid' ),
            'add_new_item' => __( 'Add New Product Category', 'jpid' ),
            'new_item_name' => __( 'New Product Category', 'jpid' ),
            'parent_item' => __( 'Parent Product Category', 'jpid' ),
            'parent_item_colon' => __( 'Parent Product Category:', 'jpid' ),
            'search_items' => __( 'Search Product Categories', 'jpid' ),
            'popular_items' => __( 'Popular Product Categories', 'jpid' ),
            'separate_items_with_commas' => __( 'Separate Product Categories with commas', 'jpid' ),
            'add_or_remove_items' => __( 'Add or remove Product Categories', 'jpid' ),
            'choose_from_most_used' => __( 'Choose from most used Product Categories', 'jpid' ),
            'not_found' => __( 'No Product Categories found', 'jpid' ),
            'no_terms' => __( 'No Product Categories', 'jpid' ),
            'items_list_navigation' => __( 'Product Categories list navigation', 'jpid' ),
            'items_list' => __( 'Product Categories list', 'jpid' )
          ),
          'public' => false,
          'hierarchical' => true,
          'show_ui' => true,
          'query_var' => 'product-category',
          'rewrite' => array(
            'slug' => 'product-category',
            'with_front' => false,
            'hierarchical' => false
          ),
          'show_admin_column' => true,
          'show_in_rest' => false,
          'rest_base' => '',
          'show_in_quick_edit' => false,
          'show_in_nav_menus' => false,
          'capabilities' => array(
        		'manage_terms' => 'manage_categories',
        		'edit_terms'   => 'manage_categories',
        		'delete_terms' => 'manage_categories',
        		'assign_terms' => 'edit_posts',
        	)
        )
      );
    }
  }

  /**
   * Register custom post types.
   *
   * @since    1.0.0
   */
  public function register_post_types() {
    // Register jpid_product custom post type.
    if ( ! post_type_exists( 'jpid_product' ) ) {
      register_post_type( 'jpid_product',
        array(
          'label' => __( 'Products', 'jpid' ),
          'labels' => array(
            'name' => __( 'Products', 'jpid' ),
            'singular_name' => __( 'Product', 'jpid' ),
            'menu_name' => _x( 'Products', 'Admin Menu' ,'jpid' ),
            'all_items' => __( 'All Products', 'jpid' ),
            'add_new' => __( 'Add Product', 'jpid' ),
            'add_new_item' => __( 'Add New Product', 'jpid' ),
            'edit_item' => __( 'Edit Product', 'jpid' ),
            'new_item' => __( 'New Product', 'jpid' ),
            'view_item' => __( 'View Product', 'jpid' ),
            'search_items' => __( 'Search Products', 'jpid' ),
            'not_found' => __( 'No Products found', 'jpid' ),
            'not_found_in_trash' => __( 'No Products found in trash', 'jpid' ),
            'parent' => __( 'Parent Product', 'jpid' ),
            'featured_image' => __( 'Product Image', 'jpid' ),
            'set_featured_image' => __( 'Set product image', 'jpid' ),
            'remove_featured_image' => __( 'Remove product image', 'jpid' ),
            'use_featured_image' => __( 'Use as product image', 'jpid' ),
            'archives' => __( 'Product archives', 'jpid' ),
            'insert_into_item' => __( 'Insert into product', 'jpid' ),
            'uploaded_to_this_item' => __( 'Uploaded to this product', 'jpid' ),
            'filter_items_list' => __( 'Filter products list', 'jpid' ),
            'items_list_navigation' => __( 'Products list navigation', 'jpid' ),
            'items_list' => __( 'Products list', 'jpid' )
          ),
          'description' => __( 'This is where you can add or edit your snack and drink products.', 'jpid' ),
          'public' => true,
          'show_ui' => true,
          'show_in_rest' => false,
          'rest_base' => '',
          'has_archive' => true,
          'show_in_menu' => true,
          'exclude_from_search' => true,
          'capability_type' => 'post',
          'map_meta_cap' => true,
          'hierarchical' => false,
          'rewrite' => array(
            'slug' => 'product',
            'with_front' => false
          ),
          'query_var' => 'product',
          'menu_position' => 26,
          'menu_icon' => 'dashicons-cart',
          'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
          'taxonomies' => array( 'jpid_product_type', 'jpid_product_category' )
        )
      );
    }
  }

  /**
   * Register custom post statuses.
   *
   * @since    1.0.0
   */
  public function register_post_statuses() {
    // Do something...
  }

}
