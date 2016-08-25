<?php

/**
 * Manage jpid_product category screen.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/admin/screens/products
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
class JPID_Screen_Product_Category {

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
  const NONCE_ACTION = 'jpid_save_term_meta';

  /**
	 * Nonce name.
	 *
	 * @since    1.0.0
	 */
  const NONCE_NAME = 'jpid_term_meta_nonce';

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
   * Add term meta field to add term screen.
   *
   * @since    1.0.0
   */
  public function add_term_meta_fields() {
    include_once JPID_PLUGIN_DIR . 'admin/screens/products/views/html-jpid-meta-field-add-product-category.php';
  }

  /**
   * Add term meta field to edit term screen.
   *
   * @since    1.0.0
   * @param    int    $term        The term object.
	 * @param    int    $taxonomy    The term taxonomy object.
   */
  public function edit_term_meta_fields( $term, $taxonomy) {
    include_once JPID_PLUGIN_DIR . 'admin/screens/products/views/html-jpid-meta-field-edit-product-category.php';
  }

  /**
   * Save term meta field value to term.
   *
   * @since    1.0.0
   * @param    int    $term_id        The term ID.
	 * @param    int    $taxonomy_id    The term taxonomy ID.
   */
  public function save_term_meta_fields( $term_id, $taxonomy_id ) {
    if ( empty( $term_id ) || empty( $taxonomy_id ) ) {
      return;
    }

    if ( ! $this->can_save_term() ) {
      return;
    }

    $term_id = absint( $term_id );

    if ( isset( $_POST['jpid_product_type'] ) && $_POST['jpid_product_type'] !== '' ) {
      $jpid_product_type = absint( $_POST['jpid_product_type'] );

      update_term_meta( $term_id, 'jpid_product_type', $jpid_product_type );
    }

    /**
     * NOTE:
     * There's seem to be a "bug" on WordPress where after we update a custom taxonomy term
     * the page will redirect to WordPress post category edit screen instead of the custom
     * taxonomy edit screen.
     *
     * So doing this hack is necessary to make sure it works properly:
     */
    $current_screen = get_current_screen();
    if ( ! is_null( $current_screen ) && $current_screen->id === 'edit-jpid_product_category' ) {
      wp_redirect( admin_url( 'edit-tags.php?taxonomy=jpid_product_category&post_type=jpid_product' ) );
      exit();
    }
  }

  /**
	 * Check if current edited term can be saved.
	 *
	 * @since     1.0.0
	 * @return    boolean    Whether the edited term can be saved or not.
	 */
  private function can_save_term() {
    $is_valid_nonce = isset( $_POST[ self::NONCE_NAME ] ) && wp_verify_nonce( $_POST[ self::NONCE_NAME ], self::NONCE_ACTION );
    $is_user_can = current_user_can( 'manage_categories' );

    return $is_valid_nonce && $is_user_can;
  }

  /**
   * Add columns for term meta data.
   *
   * @since     1.0.0
   * @param     array    $existing_columns    Default columns collection.
   * @return    array                         Modified columns collection.
   */
  public function add_term_meta_columns( $existing_columns ) {
    if ( empty( $existing_columns ) || ! is_array( $existing_columns ) ) {
      return;
    }

    $new_columns                = array();
    $new_columns['cb']          = $existing_columns['cb'];
    $new_columns['name']        = $existing_columns['name'];
    $new_columns['description'] = $existing_columns['description'];
    $new_columns['slug']        = $existing_columns['slug'];
    $new_columns['type']        = __( 'Type', 'jpid' );
    $new_columns['posts']       = $existing_columns['posts'];

    return $new_columns;
  }

  /**
   * Add term meta data content to term meta data column.
   *
   * @since     1.0.0
   * @param     string    $content        Blank content.
   * @param     string    $column_name    Column name.
   * @param     int       $term_id        The term ID.
   * @return    string                    The content to display.
   */
  public function add_term_meta_columns_content( $content, $column_name, $term_id ) {
    if ( empty( $column_name ) || empty( $term_id ) ) {
      return;
    }

    $term_id = absint( $term_id );

    switch ( $column_name ) {
      case 'type':
        $product_type_id = get_term_meta( $term_id, 'jpid_product_type', true );

        if ( ! empty( $product_type_id ) ) {
          $product_type = get_term( $product_type_id, 'jpid_product_type' );
          $content .= esc_html( $product_type->name );
        } else {
          $content .= '-';
        }
        break;
    }

    return $content;
  }

  /**
   * Set sortable columns for term meta data column.
   *
   * @since     1.0.0
   * @param     array      $sortable_columns    Default collection of sortable columns.
   * @return    array                           Modified collection of sortable columns.
   */
  public function set_term_meta_sortable_columns( $sortable_columns ) {
    if ( empty( $sortable_columns ) || ! is_array( $sortable_columns ) ) {
      return;
    }

    $sortable_columns['type'] = 'type';

    return $sortable_columns;
  }

  /**
   * Set custom column sorting.
   *
   * NOTE:
   * This function only works in WordPress version 4.6 and above. It depends on hooking
   * to 'pre_get_terms' action and modifying the query arguments of WP_Term_Query object
   * that recently introduced in WordPress version 4.6.0.
   *
   * @since    1.0.0
   * @param    WP_Term_Query    $term_query    The term query object.
   */
  public function set_term_meta_custom_sorting( $term_query ) {
    if ( is_null( $term_query ) || ! ( $term_query instanceof WP_Term_Query ) ) {
      return;
    }

    if ( ! $this->valid_screen() ) {
      return;
    }

    $custom_query_vars = $term_query->query_vars;
    $orderby = $custom_query_vars['orderby'];

    switch ( $orderby ) {
      case 'type':
        $custom_query_vars['meta_key'] = 'jpid_product_type';
        $custom_query_vars['orderby'] = 'meta_value_num';

        $term_query->query( $custom_query_vars );
        break;
    }
  }

  /**
   * Add term meta field to term quick edit.
   *
   * @since    1.0.0
   * @param    string    $column_name    Column name.
   */
  public function add_term_meta_quick_edit( $column_name ) {
    if ( empty( $column_name ) ) {
      return;
    }

    if ( ! $this->valid_screen() ) {
      return;
    }

    if ( $column_name !== 'type' ) {
      return;
    }

    include_once JPID_PLUGIN_DIR . 'admin/screens/products/views/html-jpid-quick-edit-product-category.php';
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

    return $current_screen->id === 'edit-jpid_product_category'
      && ( $current_screen->base === 'edit-tags' || $current_screen->base === 'term' )
      && $current_screen->taxonomy === 'jpid_product_category';
  }

  /**
   * Remove default term fields.
   *
   * @since     1.0.0
   */
  public function remove_fields() {
    if ( ! $this->valid_screen() ) {
      return;
    }

    ?>
    <!-- Remove fields by hiding their displays with CSS. -->
    <style>
      /* Parent selector   on add new term screen. */
      #addtag > div.form-field.term-parent-wrap {
        display: none !important;
      }

      /* Parent selector field on edit term screen. */
      #edittag > table > tbody > tr.form-field.term-parent-wrap {
        display: none !important;
      }
    </style>
    <?php
  }

}
