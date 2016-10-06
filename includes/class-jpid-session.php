<?php

/**
 * JPID session manager.
 *
 * This is a wrapper class for WP_Session / PHP $_SESSION and
 * handles the storage of session items.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Session {

  /**
   * Class constructor.
   *
   * @since    1.0.0
   */
  public function __construct() {
    $this->includes();
    $this->setup_hooks();
  }

  /**
   * Include required files.
   *
   * @since    1.0.0
   */
  private function includes() {
    // let users change the session cookie name
    if( ! defined( 'WP_SESSION_COOKIE' ) ) {
    	define( 'WP_SESSION_COOKIE', 'jpid_wp_session' );
    }

    if ( ! class_exists( 'Recursive_ArrayAccess' ) ) {
    	require_once JPID_PLUGIN_DIR . 'includes/libraries/wp-session/class-recursive-arrayaccess.php';
    }

    // Include utilities class
    if ( ! class_exists( 'WP_Session_Utils' ) ) {
    	require_once JPID_PLUGIN_DIR . 'includes/libraries/wp-session/class-wp-session-utils.php';
    }

    // Include WP_CLI routines early
    if ( defined( 'WP_CLI' ) && WP_CLI ) {
    	require_once JPID_PLUGIN_DIR . 'includes/libraries/wp-session/wp-cli.php';
    }

    // Only include the functionality if it's not pre-defined.
    if ( ! class_exists( 'WP_Session' ) ) {
    	require_once JPID_PLUGIN_DIR . 'includes/libraries/wp-session/class-wp-session.php';
    	require_once JPID_PLUGIN_DIR . 'includes/libraries/wp-session/wp-session.php';
    }
  }

  /**
   * Setup class hooks.
   *
   * @since    1.0.0
   */
  private function setup_hooks() {

  }

}
