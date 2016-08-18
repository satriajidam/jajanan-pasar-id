<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
class JPID_Deactivator {

	/**
	 * Run plugin deactivation functions.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

		check_admin_referer( "deactivate-plugin_{$plugin}" );

		self::remove_custom_roles();
	}

	/**
	 * Remove plugin's custom user roles.
	 *
	 * @since    1.0.0
	 */
	private static function remove_custom_roles() {
		global $wp_roles;

    if ( ! class_exists( 'WP_Roles' ) ) {
      return;
    }

    if ( ! isset( $wp_roles ) ) {
      $wp_roles = new WP_Roles();
    }

    remove_role( 'customer' );
    remove_role( 'shop_manager' );
	}

}
