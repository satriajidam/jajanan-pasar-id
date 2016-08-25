<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/admin
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
class JPID_Admin {

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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$current_post   = get_post();
		$current_screen = get_current_screen();

		wp_enqueue_style( 'jpid-admin' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$current_post   = get_post();
		$current_screen = get_current_screen();

		wp_enqueue_script( 'accounting' );
		wp_enqueue_script( 'select2' );

		/**
		 * The core admin script responsible for handling all JavaScript tasks in the admin
		 * area of this plugin.
		 */
		wp_enqueue_script( 'jpid-admin' );

		$jpid_admin_args = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'screen_id' => isset( $current_screen ) ? $current_screen->id : '',
			'post_id' => isset( $current_post ) ? $current_post->ID : 0,
			'load_product_categories_nonce' => wp_create_nonce( 'load_product_categories' )
		);

		if ( $current_screen->id === 'edit-jpid_product' ) {
			$snack_type = get_term_by( 'slug', 'snack', 'jpid_product_type' );
			$jpid_admin_args['snack_term_id'] = ! is_null( $snack_type ) ? (int) $snack_type->term_id : 0;

			$drink_type = get_term_by( 'slug', 'drink', 'jpid_product_type' );
			$jpid_admin_args['drink_term_id'] = ! is_null( $drink_type ) ? (int) $drink_type->term_id : 0;
		}

		wp_localize_script( 'jpid-admin', 'jpid_admin', $jpid_admin_args );
	}

	/**
	 * Add custom post updated messages.
	 *
	 * @since    1.0.0
	 * @param    array    $messages    Default collection of post updated messages.
	 * @return   array                 Modified collection of post updated messages.
	 */
	public function post_updated_messages( $messages ) {
		$post = get_post();

		$messages['jpid_product'] = array(
			0 => '',
			1 => sprintf( __( 'Product updated. <a href="%s">View Product</a>', 'jpid' ), esc_url( get_permalink( $post->ID ) ) ),
			2 => __( 'Custom field updated.', 'jpid' ),
			3 => __( 'Custom field deleted.', 'jpid' ),
			4 => __( 'Product updated.', 'jpid' ),
			5 => isset( $_GET['revision'] ) ? sprintf( __( 'Product restored to revision from %s', 'jpid' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __( 'Product published. <a href="%s">View Product</a>', 'jpid' ), esc_url( get_permalink( $post->ID ) ) ),
			7 => __( 'Product saved.', 'jpid' ),
			8 => sprintf( __( 'Product submitted. <a target="_blank" href="%s">Preview Product</a>', 'jpid' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) ),
			9 => sprintf( __( 'Product scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Product</a>'), date_i18n( __( 'M j, Y @ G:i', 'textdomain' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post->ID ) ) ),
			10 => sprintf( __( 'Product draft updated. <a target="_blank" href="%s">Preview Product</a>', 'jpid' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) )
		);

		return $messages;
	}

	/**
	 * Add custom bulk post updated messages.
	 *
	 * @since    1.0.0
	 * @param    array    $bulk_messages    Default collection of bulk post updated messages.
	 * @param    int      $bulk_counts      Number of posts being updated.
	 * @return   array                      Modified collection of bulk post updated messages.
	 */
	public function bulk_post_updated_messages( $bulk_messages, $bulk_counts ) {
		$bulk_messages['jpid_product'] = array(
			'updated'   => _n( '%s product updated.', '%s products updated.', $bulk_counts['updated'], 'jpid' ),
			'locked'    => _n( '%s product not updated, somebody is editing it.', '%s products not updated, somebody is editing them.', $bulk_counts['locked'], 'jpid' ),
			'deleted'   => _n( '%s product permanently deleted.', '%s products permanently deleted.', $bulk_counts['deleted'], 'jpid' ),
			'trashed'   => _n( '%s product moved to the Trash.', '%s products moved to the Trash.', $bulk_counts['trashed'], 'jpid' ),
			'untrashed' => _n( '%s product restored from the Trash.', '%s products restored from the Trash.', $bulk_counts['untrashed'], 'jpid' ),
		);

		return $bulk_messages;
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
	public function check_product_types() {
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
				$message = __( 'Can\'t find product type: ' . $product_type . '. You might\'ve accidentally replaced\deleted them in the database. Please deactivate then reactivate ' . $this->plugin_name . ' plugin to restore them.', 'jpid' );

	      wp_die( $message );
			}
		}
	}

}
