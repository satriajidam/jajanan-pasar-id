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

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

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

		flush_rewrite_rules();
	}

	/**
	 * Remove plugin's custom user roles.
	 *
	 * @since    1.0.0
	 */
	private static function remove_custom_roles() {
		$plugin_roles = new JPID_Roles();

    $plugin_roles->remove_roles();
	}

}
