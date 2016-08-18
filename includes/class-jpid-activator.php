<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
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
		include_once JPID_PLUGIN_DIR . 'includes/class-jpid-post-types.php';

		$plugin_post_types = new JPID_Post_Types();

		$plugin_post_types->register_taxonomies();
		$plugin_post_types->register_post_types();
	}

	/**
	 * Save plugin version to the database.
	 *
	 * @since    1.0.0
	 */
	private static function save_plugin_version() {
		$current_version = get_option( 'jpid_version', null );
		$save_version = is_null( $current_version ) || ( version_compare( $current_version, JPID_PLUGIN_VERSION ) < 0 );

		if ( $save_version ) {
			update_option( 'jpid_version', JPID_PLUGIN_VERSION );
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

		// Add customer role
		add_role( 'customer', __( 'Customer', 'jpid' ), array(
			'read' => true
		) );

		// Add shop manager role
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
	}

	/**
	 * Save plugin's database version.
	 *
	 * @since    1.0.0
	 */
	private static function save_db_version() {
		$current_version = get_option( 'jpid_db_version', null );
		$save_version = is_null( $current_version ) || ( version_compare( $current_version, JPID_PLUGIN_VERSION ) < 0 );

		if ( $save_version ) {
			update_option( 'jpid_db_version', JPID_PLUGIN_VERSION );
		}
	}

}
