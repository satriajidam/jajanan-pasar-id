<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link              http://jajananpasar.id
 * @since             1.0.0
 * @package           jajanan-pasar-id
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Check if the file is the one that was registered during the uninstall hook.
if ( WP_UNINSTALL_PLUGIN !== 'jajanan-pasar-id/jajanan-pasar-id.php' )  {
	exit;
}

// Check if the $_REQUEST content is the plugin name.
if ( ! in_array( 'jajanan-pasar-id/jajanan-pasar-id.php', $_REQUEST['checked'] ) ) {
	exit;
}

// Check if the $_REQUEST action is the right one.
if ( $_REQUEST['action'] !== 'delete-selected' ) {
	exit;
}

// Check if current user role can delete plugin.
if ( ! current_user_can( 'delete_plugins' ) ) {
	exit;
}

// Run an admin referrer check to make sure it goes through authentication.
check_admin_referer( 'bulk-plugins' );

/**
 * Now it is safe to carry on.
 *
 * Run any number of uninstallation function after this line.
 */
