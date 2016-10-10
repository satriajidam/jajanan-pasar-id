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
   * @since    1.0.0
   * @var      array    Session data storage.
   */
  private $session;

  /**
   * Class constructor.
   *
   * @since    1.0.0
   */
  public function __construct() {
    if ( $this->should_start_session() ) {
      $this->includes();
      $this->setup_hooks();
    }
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
    if ( empty( $this->session ) ) {
      add_action( 'plugins_loaded', array( $this, 'init' ), -1 );
    }
  }

  /**
   * Setup WP_Session instance.
   *
   * @since     1.0.0
   * @return    WP_Session    Instance of WP_Session.
   */
  public function init() {
    $this->session = WP_Session::get_instance();

    return $this->session;
  }

  /**
   * Determine if plugin should start sessions.
   *
   * @since     1.0.0
   * @return    boolean    True if plugin should start session, otherwise false.
   */
  private function should_start_session() {
    $start_session = true;

    // Check the URI used to access current page
    if ( ! empty( $_SERVER[ 'REQUEST_URI' ] ) ) {
      $blacklist = $this->get_blacklist();
      $uri       = ltrim( $_SERVER[ 'REQUEST_URI' ], '/' );
      $uri       = untrailingslashit( $uri );

      if ( in_array( $uri, $blacklist ) ) {
        $start_session = false;
      }

      if ( false !== strpos( $uri, 'feed=' ) ) {
        $start_session = false;
      }
    }

    return $start_session;
  }

  /**
   * Get the URI blacklist.
   *
   * @since     1.0.0
   * @return    array    The URIs where plugin should never start sessions.
   */
  private function get_blacklist() {
    $blacklist = array(
			'feed',
			'feed/rss',
			'feed/rss2',
			'feed/rdf',
			'feed/atom',
			'comments/feed'
		);

		// Look to see if WordPress is in a sub folder or this is a network site that uses sub folders
		$folder = str_replace( network_home_url(), '', get_site_url() );

		if ( ! empty( $folder ) ) {
			foreach( $blacklist as $path ) {
				$blacklist[] = $folder . '/' . $path;
			}
		}

		return $blacklist;
  }

  /**
   * Get current session ID.
   *
   * @since     1.0.0
   * @return    string    Current session ID.
   */
  public function get_id() {
    return $this->session->session_id;
  }

  /**
   * Get a session variable.
   *
   * @since     1.0.0
   * @param     string    $key    Session variable key.
   * @return    mixed             Session variable value.
   */
  public function get( $key ) {
		$key = sanitize_key( $key );

		return isset( $this->session[ $key ] ) ? maybe_unserialize( $this->session[ $key ] ) : null;
	}

  /**
   * Get a session variable.
   *
   * @since     1.0.0
   * @param     string    $key      Session variable key.
   * @param     mixed     $value    Session variable value.
   * @return    mixed               Session variable value.
   */
  public function set( $key, $value ) {
    $key = sanitize_key( $key );

    if ( is_array( $value ) ) {
      $this->session[ $key ] = serialize( $value );
    } else {
      $this->session[ $key ] = $value;
    }

    return $this->session[ $key ];
  }

}
