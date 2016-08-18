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

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Setup plugin's constants
define( 'JPID_PLUGIN_VERSION', '1.0.0' );
define( 'JPID_PLUGIN_SLUG', 'jajanan-pasar-id' );
define( 'JPID_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'JPID_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'JPID_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'JPID_ASSETS_URL', JPID_PLUGIN_URL . 'assets/' );
define( 'JPID_CSS_URL', JPID_ASSETS_URL . 'css/' );
define( 'JPID_JS_URL', JPID_ASSETS_URL . 'js/' );
define( 'JPID_IMAGES_URL', JPID_ASSETS_URL . 'images/' );
define( 'JPID_SCRIPT_SUFFIX', ( WP_DEBUG ? '' : '.min' ) );

/**
 * Collection of helper functions of this plugin.
 */
require JPID_PLUGIN_DIR . 'includes/jpid-helper-functions.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-jpid-activator.php
 */
function activate_jpid() {
	require_once JPID_PLUGIN_DIR . 'includes/class-jpid-activator.php';
	JPID_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-jpid-deactivator.php
 */
function deactivate_jpid() {
	require_once JPID_PLUGIN_DIR . 'includes/class-jpid-deactivator.php';
	JPID_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_jpid' );
register_deactivation_hook( __FILE__, 'deactivate_jpid' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require JPID_PLUGIN_DIR . 'includes/class-jpid.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_jpid() {
	$plugin = new JPID();
	$plugin->run();
}
run_jpid();
