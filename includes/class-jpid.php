<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
class JPID {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @var      JPID_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->plugin_name = JPID_PLUGIN_SLUG;
		$this->version = JPID_PLUGIN_VERSION;

		$this->load_dependencies();
		$this->define_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - JPID_Loader. Orchestrates the hooks of the plugin.
	 * - JPID_i18n. Defines internationalization functionality.
	 * - JPID_Admin. Defines all hooks for the admin area.
	 * - JPID_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once JPID_PLUGIN_DIR . 'includes/class-jpid-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once JPID_PLUGIN_DIR . 'includes/class-jpid-i18n.php';

		/**
		 * The class responsible for registering custom post types of the plugin.
		 */
		require_once JPID_PLUGIN_DIR . 'includes/class-jpid-post-types.php';

		/**
		 * The class responsible for registering scripts and styles of the plugin.
		 */
		require_once JPID_PLUGIN_DIR . 'includes/class-jpid-scripts.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once JPID_PLUGIN_DIR . 'admin/screens/products/class-jpid-screen-products-category.php';
		require_once JPID_PLUGIN_DIR . 'admin/screens/products/class-jpid-screen-products-list.php';
		require_once JPID_PLUGIN_DIR . 'admin/screens/products/class-jpid-screen-products-edit.php';

		require_once JPID_PLUGIN_DIR . 'admin/class-jpid-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once JPID_PLUGIN_DIR . 'public/class-jpid-public.php';

		$this->loader = new JPID_Loader();
	}

	/**
	 * Register all of the hooks related to both admin area and public-facing
	 * functionality of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function define_hooks() {
		$plugin_i18n = new JPID_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

		$plugin_post_types = new JPID_Post_Types();

		$this->loader->add_action( 'init', $plugin_post_types, 'register_taxonomies' );
		$this->loader->add_action( 'init', $plugin_post_types, 'register_post_types' );
		$this->loader->add_action( 'init', $plugin_post_types, 'register_post_statuses' );

		$plugin_scripts = new JPID_Scripts( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_scripts, 'register_scripts' );
		$this->loader->add_action( 'init', $plugin_scripts, 'register_styles' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function define_admin_hooks() {
		if ( ! is_admin() ) {
			return;
		}

		$plugin_admin = new JPID_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'current_screen', $plugin_admin, 'add_screen_help' );
		$this->loader->add_action( 'current_screen', $plugin_admin, 'check_product_types' );

		$products_category_edit = new JPID_Screen_Products_Category( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'jpid_product_category_add_form_fields', $products_category_edit, 'add_term_meta_fields' );
		$this->loader->add_action( 'jpid_product_category_edit_form_fields', $products_category_edit, 'edit_term_meta_fields', 10, 2 );
		$this->loader->add_action( 'created_jpid_product_category', $products_category_edit, 'save_term_meta_fields', 10, 2 );
		$this->loader->add_action( 'edited_jpid_product_category', $products_category_edit, 'save_term_meta_fields', 10, 2 );
		$this->loader->add_action( 'manage_edit-jpid_product_category_columns', $products_category_edit, 'add_term_meta_columns' );
		$this->loader->add_action( 'manage_jpid_product_category_custom_column', $products_category_edit, 'add_term_meta_columns_content', 10, 3 );
		$this->loader->add_action( 'manage_edit-jpid_product_category_sortable_columns', $products_category_edit, 'set_term_meta_sortable_columns' );
		$this->loader->add_action( 'pre_get_terms', $products_category_edit, 'set_term_meta_custom_sorting' );
		$this->loader->add_action( 'quick_edit_custom_box', $products_category_edit, 'add_term_meta_quick_edit' );

		$products_edit = new JPID_Screen_Products_Edit( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'add_meta_boxes_jpid_product', $products_edit, 'remove_meta_boxes' );
		$this->loader->add_action( 'add_meta_boxes_jpid_product', $products_edit, 'add_meta_boxes' );
		$this->loader->add_action( 'save_post_jpid_product', $products_edit, 'add_meta_boxes', 10, 3 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function define_public_hooks() {
		$plugin_public = new JPID_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
