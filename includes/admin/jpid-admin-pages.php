<?php

/**
 * Setup admin menus & pages.
 *
 * NOTE:
 * Used procedural style to add JPID admin pages to WordPress admin area.
 * Change this to OOP style on the next version.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id/includes/admin
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add admin pages to the admin area.
 *
 * @since    1.0.0
 */
function jpid_add_admin_pages() {
  $top_level_slug = 'jajanan-pasar-id';

  add_menu_page(
    __( 'Jajanan Pasar', 'jpid' ), __( 'Jajanan Pasar', 'jpid' ), 'manage_options',
    $top_level_slug, null, 'dashicons-store', 50
  );

  add_submenu_page(
    $top_level_slug, __( 'About', 'jpid' ), __( 'About', 'jpid' ), 'manage_options',
    $top_level_slug, 'jpid_display_about_page'
  );

  add_submenu_page(
    $top_level_slug, __( 'Settings', 'jpid' ), __( 'Settings', 'jpid' ), 'manage_options',
    'jpid-settings', 'jpid_display_settings_page'
  );
}
add_action( 'admin_menu', 'jpid_add_admin_pages', 10 );

/**
 * Add about page.
 *
 * @since    1.0.0
 */
function jpid_display_about_page() {
  include_once JPID_PLUGIN_DIR . 'includes/admin/views/html-jpid-admin-about-page.php';
}

/**
 * Add settings page.
 *
 * @since    1.0.0
 */
function jpid_display_settings_page() {
  include_once JPID_PLUGIN_DIR . 'includes/admin/views/html-jpid-admin-settings-page.php';
}
