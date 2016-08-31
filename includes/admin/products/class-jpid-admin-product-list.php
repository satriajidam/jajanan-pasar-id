<?php

/**
 * JPID product post list admin screen.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/products
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Product_List {

  /**
	 * @since    1.0.0
	 * @var      string    Nonce action.
	 */
  const NONCE_ACTION = 'jpid_save_qe_product';

  /**
	 * @since    1.0.0
	 * @var      string    Nonce name.
	 */
  const NONCE_NAME = 'jpid_qe_product_nonce';

  /**
	 * Class constructor.
	 *
	 * @since    1.0.0
	 */
  public function __construct() {
    $this->setup_hooks();
  }

  /**
   * Setup class hooks.
   *
   * @since    1.0.0
   */
  private function setup_hooks() {
    add_action( 'manage_jpid_product_posts_columns', array( $this, 'set_column_headers' ) );
		add_action( 'manage_jpid_product_posts_custom_column', array( $this, 'display_column_data' ) );
		add_action( 'manage_edit-jpid_product_sortable_columns', array( $this, 'set_sortable_column' ) );
		add_action( 'pre_get_posts', array( $this, 'set_custom_sorting' ) );
		add_action( 'restrict_manage_posts', array( $this, 'set_filter_options' ) );
		add_action( 'pre_get_posts', array( $this, 'set_custom_filters' ) );
		add_action( 'quick_edit_custom_box', array( $this, 'set_quick_edit' ) );
		add_action( 'save_post_jpid_product', array( $this, 'save_quick_edit' ) );
  }

  /**
   * Set column headers for list table.
   *
   * @since     1.0.0
   * @param     array    $existing_columns    Default columns collection.
   * @return    array                         Modified columns collection.
   */
  public function set_column_headers( $existing_columns ) {
    if ( empty( $existing_columns ) || ! is_array( $existing_columns ) ) {
      return;
    }

    $new_columns             = array();
    $new_columns['cb']       = $existing_columns['cb'];
    $new_columns['name']     = __( 'Name', 'jpid' );
    $new_columns['price']    = __( 'Price', 'jpid' );
    $new_columns['type']     = __( 'Type', 'jpid' );
    $new_columns['category'] = __( 'Category', 'jpid' );
    $new_columns['image']    = '<span class="dashicons dashicons-format-image"></span>';
    $new_columns['date']     = $existing_columns['date'];

    return $new_columns;
  }

  /**
   * Display column data on list table.
   *
   * @since     1.0.0
   * @param     string    $column_name    Column name.
   */
  public function display_column_data( $column_name ) {
    $current_post    = get_post();
    $current_product = jpid_get_product( $current_post->ID );

    switch ( $column_name ) {
      case 'name':
        $edit_link = $current_product->get_editlink();
        $title     = _draft_or_post_title();

        echo '<strong><a href="' . esc_url( $edit_link ) . '" class="row-title">' . esc_html( $title ) . '</a>';

        _post_states( $current_post );

        echo '</strong>';

        // Get default post inline data for quick edit.
        get_inline_data( $current_post );

        // Set custom post inline data for quick edit.
        echo '
          <div class="hidden" id="jpid_inline_' . esc_attr( $current_product->get_id() ) . '">
            <div class="jpid_product_price">' . esc_html( $current_product->get_price() ) . '</div>
            <div class="jpid_product_type">' . esc_html( $current_product->get_type_id() ) . '</div>
            <div class="jpid_product_category">' . esc_html( $current_product->get_category_id() ) . '</div>
          </div>
        ';
        break;
      case 'price':
        if ( ! $price = $current_product->get_price() ) {
          echo '-';
        } else {
          echo esc_html( jpid_to_rupiah( $price ) );
        }
        break;
      case 'type':
        if ( ! $type = $current_product->get_type_name() ) {
          echo '-';
        } else {
          echo esc_html( $type );
        }
        break;
      case 'category':
        if ( ! $category = $current_product->get_category_name() ) {
          echo '-';
        } else {
          echo esc_html( $category );
        }
        break;
      case 'image':
        $image = $current_product->has_image() ? $current_product->get_image( array( 50, 50) ) : '-';
        $link  = $current_product->get_editlink();

        echo '<a class="row-title" href="' . esc_url( $link ) . '">' . $image . '</a>';
        break;
    }
  }

  /**
   * Set sortable columns for this list table.
   *
   * @since     1.0.0
   * @param     array      $sortable_columns    Default collection of sortable columns.
   * @return    array                           Modified collection of sortable columns.
   */
  public function set_sortable_column( $sortable_columns ) {
    if ( empty( $sortable_columns ) || ! is_array( $sortable_columns ) ) {
      return;
    }

    $custom = array(
      'name'  => 'name',
      'price' => 'price'
    );

    return wp_parse_args( $custom, $sortable_columns );
  }

  /**
   * Set custom column sorting for list table.
   *
   * @since    1.0.0
   * @param    WP_Query    $post_query    The post query object.
   */
  public function set_custom_sorting( $post_query ) {
    if ( is_null( $post_query ) || ! ( $post_query instanceof WP_Query ) ) {
      return;
    }

    if ( ! $this->valid_screen() ) {
      return;
    }

    $orderby = $post_query->get( 'orderby' );

    switch ( $orderby ) {
      case 'name':
        $post_query->set( 'orderby', 'title' );
        break;
      case 'price':
        $post_query->set( 'meta_key', '_jpid_product_price' );
        $post_query->set( 'orderby', 'meta_value_num' );
        break;
    }
  }

  /**
   * Set filter selection options for list table.
   *
   * @since    1.0.0
   * @param    string    $post_type    The post type.
   */
  public function set_filter_options( $post_type ) {
    if ( $post_type !== 'jpid_product' ) {
      return;
    }

    if ( ! $this->valid_screen() ) {
      return;
    }

    // Product types filter
    $product_type_args = array(
      'show_option_all' => __( 'All types', 'jpid' ),
      'taxonomy'        => 'jpid_product_type',
      'orderby'         => 'name',
      'order'           => 'asc',
      'name'            => 'product_type_filter',
      'show_count'      => true,
      'hide_empty'      => true
    );

    if ( isset( $_GET['product_type_filter'] ) ) {
      $product_type_args['selected'] = sanitize_text_field( $_GET['product_type_filter'] );
    }

    wp_dropdown_categories( $product_type_args );

    // Product categories filter
    $product_categories_args = array(
      'show_option_all' => __( 'All categories', 'jpid' ),
      'taxonomy'        => 'jpid_product_category',
      'orderby'         => 'name',
      'order'           => 'asc',
      'name'            => 'product_category_filter',
      'show_count'      => true,
      'hide_empty'      => true
    );

    if ( isset( $_GET['product_category_filter'] ) ) {
      $product_categories_args['selected'] = sanitize_text_field( $_GET['product_category_filter'] );
    }

    wp_dropdown_categories( $product_categories_args );
  }

  /**
   * Set custom filters for list table.
   *
   * @since    1.0.0
   * @param    WP_Query    $post_query    The post query object.
   */
  public function set_custom_filters( $post_query ) {
    if ( is_null( $post_query ) || ! ( $post_query instanceof WP_Query ) ) {
      return;
    }

    if ( ! $this->valid_screen() ) {
      return;
    }

    if ( isset( $_GET['product_type_filter'] ) && isset( $_GET['product_category_filter'] ) ) {
      $product_type     = sanitize_text_field( $_GET['product_type_filter'] );
      $product_category = sanitize_text_field( $_GET['product_category_filter'] );

      $product_type_args = array(
        'taxonomy' => 'jpid_product_type',
        'field'    => 'term_id',
        'terms'    => array( $product_type )
      );

      $product_categories_args = array(
        'taxonomy' => 'jpid_product_category',
        'field'    => 'term_id',
        'terms'    => array( $product_category )
      );

      $tax_query_args = array();

      if ( ! empty( $product_type ) ) {
        $tax_query_args[] = $product_type_args;
      }

      if ( ! empty( $product_category ) ) {
        $tax_query_args[] = $product_categories_args;
      }

      if ( ! empty( $product_type ) && ! empty( $product_category ) ) {
        $tax_query_args['relation'] = 'AND';
      }

      $post_query->query_vars['tax_query'] = $tax_query_args;
    }
  }

  /**
   * Set quick edit fields for list table.
   *
   * @since    1.0.0
   * @param    string    $column_name    Column name.
   */
  public function set_quick_edit( $column_name ) {
    if ( empty( $column_name ) ) {
      return;
    }

    if ( ! $this->valid_screen() ) {
      return;
    }

    if ( $column_name !== 'price' ) {
      return;
    }

    include_once JPID_PLUGIN_DIR . 'includes/admin/products/views/html-jpid-quick-edit-product-list.php';
  }

  /**
	 * Save quick edit values when post is updated.
	 *
	 * @since    1.0.0
	 * @param    int        $post_id    The post ID.
	 */
  public function save_quick_edit( $post_id ) {
    if ( empty( $post_id ) ) {
      return;
    }

    if ( ! $this->can_save_post( $post_id ) ) {
      return;
    }

    if ( isset( $_POST['jpid_product_price'] ) ) {
      $product_price = sanitize_text_field( $_POST['jpid_product_price'] );
      $product_price = floatval( $product_price );

      update_post_meta( $post_id, '_jpid_product_price', $product_price );
    }

    if ( isset( $_POST['jpid_product_type'] ) ) {
      $product_type_id = absint( $_POST['jpid_product_type'] );

      wp_set_object_terms( $post_id, array( $product_type_id ), 'jpid_product_type' );

      $product_type = get_term( $product_type_id, 'jpid_product_type' );

      if ( ! is_null( $product_type ) ) {
        if ( $product_type->name === 'Snack' && isset( $_POST['jpid_product_category_snack'] ) ) {
          $product_category_id = absint( $_POST['jpid_product_category_snack'] );
        }

        if ( $product_type->name === 'Drink' && isset( $_POST['jpid_product_category_drink'] ) ) {
          $product_category_id = absint( $_POST['jpid_product_category_drink'] );
        }

        if ( isset( $product_category_id ) ) {
          wp_set_object_terms( $post_id, array( $product_category_id ), 'jpid_product_category' );
        }
      }
    }
  }

  /**
   * Check if current quick-edited post can be saved.
   *
   * @since     1.0.0
   * @param     int        $post_id    The post ID.
   * @return    boolean                True if post can be saved, otherwise false.
   */
  private function can_save_post( $post_id ) {
    $is_valid_nonce = isset( $_POST[ self::NONCE_NAME ] ) && wp_verify_nonce( $_POST[ self::NONCE_NAME ], self::NONCE_ACTION );
    $is_user_can    = current_user_can( 'edit_post', $post_id );

    return $is_valid_nonce && $is_user_can;
  }

  /**
   * Check if current active screen is the right screen.
   *
   * @since     1.0.0
   * @return    boolean    True if active screen is the right screen, otherwise false.
   */
  private function valid_screen() {
    if ( ! function_exists( 'get_current_screen' ) ) {
      return false;
    }

    $current_screen = get_current_screen();

    if ( is_null( $current_screen ) ) {
      return false;
    }

    return $current_screen->id === 'edit-jpid_product'
      && $current_screen->base === 'edit'
      && $current_screen->post_type === 'jpid_product';
  }

}
