<?php

/**
 * Plugin Name:       Jajanan Pasar Indonesia
 * Plugin URI:        http://jajananpasar.id
 * Description:       Web application plugin for snack box ordering system in jajananpasar.id.
 * Version:           1.0.0
 * Author:            Gerbit Creative
 * Author URI:        http://gerbitcreative.com/
 * License:           GNU General Public License v3.0
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Copyright: 				(c) 2016 Gerbit Creative & Jajanan Pasar Indonesia
 * Text Domain:       jpid
 * Domain Path:       /languages
 *
 * @link              http://jajananpasar.id
 * @since             1.0.0
 * @package           jajanan-pasar-id
 * @author						Gerbit Creative
 * @category					WordPress Plugin
 * @copyright					Copyright (c) 2016, Gerbit Creative & Jajanan Pasar Indonesia
 * @license						https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

/**
 * The core JPID class.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'JPID' ) ) :

final class JPID {

	/**
	 * @since    1.0.0
	 * @var      JPID    Instance of JPID object.
	 */
	private static $instance = null;

	/**
	 * Get main JPID instance.
	 *
	 * Insures that only one instance of this class exists in memory at any one time.
	 * Also prevents needing to define globals all over the place.
	 *
	 * @since     1.0.0
	 * @return    JPID    Instance of JPID object.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new JPID();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 *
	 * @since    1.0.0
	 */
	private function __construct() {
		$this->define_constants();
		$this->includes();
		$this->setup_session();
		$this->setup_hooks();
	}

	/**
	 * A dummy magic method to prevent this class from being cloned.
	 *
	 * @since    1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'jpid' ), '2.1' );
	}

	/**
	 * A dummy magic method to prevent this class from being unserialized.
	 *
	 * @since    1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'jpid' ), '2.1' );
	}

	/**
	 * Define constants.
	 *
	 * @since    1.0.0
	 */
	private function define_constants() {
		$this->define( 'JPID_VERSION', '1.0.0' );
		$this->define( 'JPID_DB_VERSION', '1.0.0' );
		$this->define( 'JPID_SLUG', 'jajanan-pasar-id' );
		$this->define( 'JPID_PLUGIN_FILE', __FILE__ );
		$this->define( 'JPID_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'JPID_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		$this->define( 'JPID_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		$this->define( 'JPID_ASSETS_URL', JPID_PLUGIN_URL . 'assets/' );
		$this->define( 'JPID_CSS_URL', JPID_ASSETS_URL . 'css/' );
		$this->define( 'JPID_JS_URL', JPID_ASSETS_URL . 'js/' );
		$this->define( 'JPID_FONTS_URL', JPID_ASSETS_URL . 'fonts/' );
		$this->define( 'JPID_IMAGES_URL', JPID_ASSETS_URL . 'images/' );
		$this->define( 'JPID_SCRIPT_SUFFIX', ( WP_DEBUG ? '' : '.min' ) );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @since    1.0.0
	 * @param    string            $name     Constant name.
	 * @param    string|boolean    $value    Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Include required files.
	 *
	 * @since    1.0.0
	 */
	private function includes() {
		// Core files:
		require_once JPID_PLUGIN_DIR . 'includes/class-jpid-options.php';
		require_once JPID_PLUGIN_DIR . 'includes/class-jpid-post-types.php';
		require_once JPID_PLUGIN_DIR . 'includes/class-jpid-post-actions.php';
		require_once JPID_PLUGIN_DIR . 'includes/class-jpid-roles.php';
		require_once JPID_PLUGIN_DIR . 'includes/class-jpid-scripts.php';
		require_once JPID_PLUGIN_DIR . 'includes/class-jpid-session.php';

		// Database files:
		require_once JPID_PLUGIN_DIR . 'includes/database/abstract-jpid-db.php';
		require_once JPID_PLUGIN_DIR . 'includes/database/class-jpid-db-customers.php';
		require_once JPID_PLUGIN_DIR . 'includes/database/class-jpid-db-orders.php';
		require_once JPID_PLUGIN_DIR . 'includes/database/class-jpid-db-payments.php';
		require_once JPID_PLUGIN_DIR . 'includes/database/class-jpid-db-snack-boxes.php';

		// Post Type files:
		require_once JPID_PLUGIN_DIR . 'includes/post-types/class-jpid-product.php';
		require_once JPID_PLUGIN_DIR . 'includes/post-types/class-jpid-customer.php';
		require_once JPID_PLUGIN_DIR . 'includes/post-types/class-jpid-customer-status.php';
		require_once JPID_PLUGIN_DIR . 'includes/post-types/class-jpid-order.php';
		require_once JPID_PLUGIN_DIR . 'includes/post-types/class-jpid-order-status.php';
		require_once JPID_PLUGIN_DIR . 'includes/post-types/class-jpid-order-item.php';
		require_once JPID_PLUGIN_DIR . 'includes/post-types/class-jpid-payment.php';
		require_once JPID_PLUGIN_DIR . 'includes/post-types/class-jpid-payment-status.php';
		require_once JPID_PLUGIN_DIR . 'includes/post-types/class-jpid-snack-box.php';

		// Helper files:
		require_once JPID_PLUGIN_DIR . 'includes/helpers/jpid-general-functions.php';
		require_once JPID_PLUGIN_DIR . 'includes/helpers/jpid-product-functions.php';

		if ( is_admin() ) {
			require_once JPID_PLUGIN_DIR . 'includes/admin/class-jpid-admin.php';
		}

		// Activation files:
		require_once JPID_PLUGIN_DIR . 'includes/class-jpid-activator.php';
		require_once JPID_PLUGIN_DIR . 'includes/class-jpid-deactivator.php';
	}

	/**
   * Setup session.
   *
   * @since    1.0.0
   */
	private function setup_session() {
		$this->session = new JPID_Session();
	}

	/**
   * Setup class hooks.
   *
   * @since    1.0.0
   */
	private function setup_hooks() {
		register_activation_hook( __FILE__, array( 'JPID_Activator', 'activate' ) );
		register_deactivation_hook( __FILE__, array( 'JPID_Deactivator', 'deactivate' ) );

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		add_action( 'init', array( $this, 'init' ), 0 );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Load language files.
	 *
	 * @since    1.0.0
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'jpid', false, JPID_PLUGIN_DIR . 'languages/' );
	}

	/**
	 * Init core functionalities when WordPress initialises.
	 *
	 * @since    1.0.0
	 */
	public function init() {
		$this->options      = new JPID_Options();
		$this->post_types   = new JPID_Post_Types();
		$this->post_actions = new JPID_Post_Actions();
		$this->scripts      = new JPID_Scripts();

		if ( is_admin() ) {
			$this->admin = new JPID_Admin();
		}
	}

	/**
	 * Load JavaScript for the public area.
	 *
	 * @since    1.0.0
	 */
  public function enqueue_scripts() {
    // Vendor scripts:

    // Plugin scripts:
  }

  /**
	 * Load CSS for the public area.
	 *
	 * @since    1.0.0
	 */
  public function enqueue_styles() {
    // Vendor styles:

    // Plugin styles:
  }

}

endif;

/**
 * The main function responsible for returning the one true JPID instance
 * to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @author     Agastyo Satrijai Idam <play.satriajidam@gmail.com>
 */
function JPID() {
	return JPID::instance();
}

// Run the plugin
JPID();
