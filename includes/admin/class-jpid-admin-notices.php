<?php

/**
 * Notice functions for admin area.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Notices {

  /**
   * @since    1.0.0
   * @var      string    Success notice type.
   */
  const SUCCESS = 'success';

  /**
   * @since    1.0.0
   * @var      string    Error notice type.
   */
  const ERROR = 'error';

  /**
	 * Class constructor.
	 *
	 * @since    1.0.0
	 */
  public function __construct() {}

  /**
   * Get all queued notices, optionally filtered by a notice type.
   *
   * @since     1.0.0
   * @param     string    $notice_type    The type of notice to get.
   * @return    array                     All queued notices.
   */
  public static function get_notices( $notice_type = '' ) {
  	$all_notices = array();

  	if ( isset( $_SESSION['jpid_notices'] ) ) {
  		$all_notices = $_SESSION['jpid_notices'];
  	}

  	if ( ! is_array( $all_notices ) ) {
  		$all_notices = array();
  	}

  	if ( empty ( $notice_type ) ) {
  		$notices = $all_notices;
  	} elseif ( isset( $all_notices[ $notice_type ] ) ) {
  		$notices = $all_notices[ $notice_type ];
  	} else {
  		$notices = array();
  	}

  	return $notices;
  }

  /**
   * Count the number of queued notices, optionally filtered by a notice type.
   *
   * @since     1.0.0
   * @param     string    $notice_type    The type of notice to get.
   * @return    int                       Number of queued notices.
   */
  public static function count_notices( $notice_type = '' ) {
    $notice_count = 0;
  	$all_notices  = self::get_notices();

  	if ( isset( $all_notices[ $notice_type ] ) ) {
  		$notice_count = absint( count( $all_notices[ $notice_type ] ) );
  	} elseif ( empty( $notice_type ) ) {
  		foreach ( $all_notices as $notices ) {
  			$notice_count += absint( count( $notices ) );
  		}
  	}

  	return $notice_count;
  }

  /**
   * Check if a notice has already been added.
   *
   * @since     1.0.0
   * @param     string     $message        The message to search in the notice.
   * @param     string     $notice_type    The type of notice to get.
   * @return    boolean                    Number of queued notices.
   */
  public static function has_notice( $message, $notice_type = 'success' ) {
    $notices = self::get_notices();
  	$notices = isset( $notices[ $notice_type ] ) ? $notices[ $notice_type ] : array();

  	return array_search( $message, $notices ) !== false;
  }

  /**
   * Check if a notice has already been added.
   *
   * @since    1.0.0
   * @param    string    $message        The message to display in the notice.
   * @param    string    $notice_type    The type of notice to add.
   */
  public static function add_notice( $message, $notice_type = 'success' ) {
    $notices = self::get_notices();

  	$notices[ $notice_type ][] = $message;

  	$_SESSION['jpid_notices'] = $notices;
  }

  /**
   * Unset all notices.
   *
   * @since    1.0.0
   */
  public static function clear_notices() {
    if( isset( $_SESSION['jpid_notices'] ) ){
      unset( $_SESSION['jpid_notices'] );
    }
  }

  /**
   * Prints all notices which are stored in the session, then clears them.
   *
   * @since    1.0.0
   */
  public static function print_notices() {
    $all_notices  = self::get_notices();
  	$notice_types = array( 'success', 'error' );

  	foreach ( $notice_types as $notice_type ) {
  		if ( self::count_notices( $notice_type ) > 0 ) {
  			foreach ( $all_notices[ $notice_type ] as $message ) {
  				self::print_notice( $message, $notice_type );
  			}
  		}
  	}

  	self::clear_notices();
  }

  /**
   * Print a single notice immediately.
   *
   * @since    1.0.0
   * @param    string    $message        The message to display in the notice.
   * @param    string    $notice_type    The type of notice to print.
   */
  public static function print_notice( $message, $notice_type = 'success' ) {
    $class = $notice_type === 'error' ? 'notice notice-error is-dismissible' : 'notice notice-success is-dismissible';

    ?>
    <div id="message" class="<?php echo $class; ?>">
      <p><?php echo $message; ?></p>
      <button type="button" class="notice-dismiss">
        <span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'jpid' ); ?></span>
      </button>
    </div>
    <?php
  }

  /**
   * Add notices for WordPress errors.
   *
   * @since    1.0.0
   * @param    WP_Error    $errors    WordPress errors.
   */
  public static function add_wp_error_notices( $errors ) {
  	if ( is_wp_error( $errors ) && $errors->get_error_messages() ) {
  		foreach ( $errors->get_error_messages() as $error ) {
  			self::add_notice( $error, 'error' );
  		}
  	}
  }

}
