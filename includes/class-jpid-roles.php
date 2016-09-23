<?php

/**
 * Adds plugin custom roles and capabilities.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Roles {

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
    // TODO: do something...
  }

  /**
   * Add plugin's custom user roles.
   *
   * @since    1.0.0
   */
  public function add_roles() {
    global $wp_roles;

    if ( ! class_exists( 'WP_Roles' ) ) {
      return;
    }

    if ( ! isset( $wp_roles ) ) {
      $wp_roles = new WP_Roles();
    }

    // Add customer role:
    add_role( 'customer', __( 'Customer', 'jpid' ), array(
      'read' => true
    ) );

    // Add shop manager role:
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
	 * Remove plugin's custom user roles.
	 *
	 * @since    1.0.0
	 */
  public function remove_roles() {
    global $wp_roles;

    if ( ! class_exists( 'WP_Roles' ) ) {
      return;
    }

    if ( ! isset( $wp_roles ) ) {
      $wp_roles = new WP_Roles();
    }

    if ( $this->role_exists( 'customer' ) ) {
      remove_role( 'customer' );
    }

    if ( $this->role_exists( 'shop_manager' ) ) {
      remove_role( 'shop_manager' );
    }
  }

  /**
	 * Check if role exists.
	 *
	 * @since     1.0.0
	 * @return    boolean    True if role exists, otherwise false.
	 */
  private function role_exists( $role ) {
    global $wp_roles;

    return $wp_roles->is_role( $role );
  }

}
