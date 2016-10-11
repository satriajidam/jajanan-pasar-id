<?php

/**
 * Customer admin ajax.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/ajax
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Customer_Ajax {

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
    add_action( 'wp_ajax_search_user_account', array( $this, 'search_user_account' ) );
  }

  /**
   * Search customer user account.
   *
   * @since    1.0.0
   */
  public function search_user_account() {
    ob_start();

    check_ajax_referer( 'search_user_account', 'security' );

    $term = sanitize_text_field( $_GET[ 'term' ] );

    if ( empty( $term ) ) {
      die();
    }

    $search_term   = '*' . $term . '*';

    $userIDs = get_users( array(
      'role'   => 'customer',
      'search' => $search_term,
      'fields' => 'ID'
    ) );

    $availableIDs = array();

    foreach ( $userIDs as $userID ) {
      if ( ! jpid_is_customer_exists( 'user_id', $userID ) ) {
        $availableIDs[] = $userID;
      }
    }

    $user_accounts = array();

    if ( ! empty( $availableIDs ) ) {
      foreach ( $availableIDs as $availableID ) {
        $user = get_userdata( $availableID );

        $fullname = trim( $user->first_name . ' ' . $user->last_name );

        if ( empty( $fullname ) ) {
          $fullname = 'Customer';
        }

        $account_info = '#' . $user->ID . ' - ' . $fullname . ' (' . $user->user_email .  ')';

        $user_accounts[ $user->ID ] = $account_info;
      }
    }

    ob_end_clean();

    wp_send_json( $user_accounts );
  }

}
