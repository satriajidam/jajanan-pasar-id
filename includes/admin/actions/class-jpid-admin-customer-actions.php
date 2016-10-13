<?php

/**
 * Customer admin actions manager.
 *
 * The class proccess any customer related $_GET, $_POST, and $_REQUEST data
 * that get passed-in when WordPress admin initialises.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/actions
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Customer_Actions {

  /**
   * @since    1.0.0
   * @var      string    Nonce action.
   */
  const NONCE_ACTION = 'jpid_save_customer';

  /**
   * @since    1.0.0
   * @var      string    Nonce name.
   */
  const NONCE_NAME = 'jpid_customer_nonce';

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
    add_action( 'admin_init', array( $this, 'customer_edit_actions' ) );
    add_action( 'profile_update', array( $this, 'update_customer_on_user_update' ) );
  }

  /**
   * Determine which customer edit screen action to call based on request sent.
   *
   * @since    1.0.0
   */
  public function customer_edit_actions() {
    if ( ! isset( $_GET['page'] ) || $_GET['page'] !== JPID_Admin_Page_Customer_Edit::SLUG ) {
      return;
    }

    $customer_id = isset( $_GET['customer'] ) ? intval( $_GET['customer'] ) : 0;

    if ( isset( $_REQUEST['jpid_customer_action'] ) ) {
      switch ( $_REQUEST['jpid_customer_action'] ) {
        case 'save_customer':
          $this->save_customer( $customer_id );
          break;
        case 'delete_customer':
          $this->delete_customer( $customer_id );
          break;
        default:
          $message  = '<p class="error">';
          $message .= __( 'Unrecognized action', 'jpid' );
          $message .= ': <i>' . $_REQUEST['jpid_customer_action'] . '</i>';
          $message .= '</p>';

          wp_die( $message );
          break;
      }
    }
  }

  /**
   * Save customer action.
   *
   * @since    1.0.0
   * @param    int      $customer_id    Edited customer ID.
   */
  private function save_customer( $customer_id = 0 ) {
    if ( ! $this->can_save() ) {
      wp_die( __( 'You do not have permission to edit this customer.', 'jpid' ) );
    }

    $customer_data = $this->get_customer_data( $customer_id );

    if ( $customer_data === false ) {
      return;
    }

    $customer = $customer_id > 0 ? new JPID_Customer( $customer_id ) : new JPID_Customer();

    $saved_customer_id = $customer->save( $customer_data );

    if ( $saved_customer_id ) {

      if ( $customer_id === 0 ) {

        $customer_edit_page = admin_url() . 'admin.php?page=' . JPID_Admin_Page_Customer_Edit::SLUG . '&customer=' . $saved_customer_id;

        jpid_add_notice( __( 'Customer created.', 'jpid' ), JPID_Admin_Notices::SUCCESS );

        wp_safe_redirect( $customer_edit_page );

        exit;

      } else {

        jpid_add_notice( __( 'Customer updated.', 'jpid' ), JPID_Admin_Notices::SUCCESS );

      }

    } else {

      if ( $customer_id === 0 ) {
        jpid_add_notice( __( 'Failed to create customer.', 'jpid' ), JPID_Admin_Notices::ERROR );
      } else {
        jpid_add_notice( __( 'Failed to update customer.', 'jpid' ), JPID_Admin_Notices::ERROR );
      }

    }
  }

  /**
   * Check if current edited customer can be saved.
   *
   * @since     1.0.0
   * @return    boolean    True if customer can be saved, otherwise false.
   */
  private function can_save() {
    $is_valid_nonce = isset( $_POST[ self::NONCE_NAME ] ) && wp_verify_nonce( $_POST[ self::NONCE_NAME ], self::NONCE_ACTION );
    $is_user_can    = is_admin() && current_user_can( 'edit_posts' ) && current_user_can( 'edit_users' );

    return $is_valid_nonce && $is_user_can;
  }

  /**
   * Run customer $_POST data through data sanitization and validation
   * then return the results.
   *
   * @since     1.0.0
   * @param     int      $customer_id    Edited customer ID.
   * @return    array                    Sanitized customer data if no data error found.
   *                                     False if there's at least one data error.
   */
  private function get_customer_data( $customer_id ) {
    $customer_data = array();
    $data_errors   = 0;

    // Customer name
    if ( ! empty( $_POST['jpid_customer_name'] ) ) {
      $customer_data['customer_name'] = sanitize_text_field( trim( $_POST['jpid_customer_name'] ) );
    }

    // Customer phone number
    if ( ! empty( $_POST['jpid_customer_phone'] ) ) {
      $customer_data['customer_phone'] = sanitize_text_field( trim( $_POST['jpid_customer_phone'] ) );
    }

    // Customer street address
    if ( ! empty( $_POST['jpid_customer_address'] ) ) {
      $customer_data['customer_address'] = sanitize_text_field( trim( $_POST['jpid_customer_address'] ) );
    }

    // Customer province
    if ( ! empty( $_POST['jpid_customer_province'] ) ) {
      $customer_data['customer_province'] = sanitize_text_field( trim( $_POST['jpid_customer_province'] ) );
    }

    // Customer city
    if ( ! empty( $_POST['jpid_customer_city'] ) ) {
      $customer_data['customer_city'] = sanitize_text_field( trim( $_POST['jpid_customer_city'] ) );
    }

    // Customer email address
    if ( ! empty( $_POST['jpid_customer_email'] ) ) {
      $customer_email = sanitize_email( trim( $_POST['jpid_customer_email'] ) );

      if ( empty( $customer_email ) || ! is_email( $customer_email ) ) {
        jpid_add_notice( __( 'Invalid email address.', 'jpid' ), JPID_Admin_Notices::ERROR );

        $data_errors++;
      }

      $customer = jpid_get_customer_by( 'customer_email', $customer_email );

      if ( ! empty( $customer ) && ( $customer->get_id() !== $customer_id ) ) {
        jpid_add_notice( __( 'Email\'s already used by a customer.', 'jpid' ), JPID_Admin_Notices::ERROR );

        $data_errors++;
      }

      if ( $data_errors === 0 ) {
        $customer_data['customer_email'] = $customer_email;
      }
    } else {
      jpid_add_notice( __( 'Customer\'s email address is required.', 'jpid' ), JPID_Admin_Notices::ERROR );

      $data_errors++;
    }

    // Customer user ID
    if ( ! empty( $_POST['jpid_user_id'] ) ) {
      $user_id = absint( $_POST['jpid_user_id'] );
      $user    = get_user_by( 'id', $user_id );

      if ( empty( $user ) ) {
        jpid_add_notice( __( 'Invalid user ID.', 'jpid' ), JPID_Admin_Notices::ERROR );

        $data_errors++;
      } else {
        $customer = jpid_get_customer_by( 'user_id', $user->ID );

        if ( ! empty( $customer ) && ( $customer->get_id() !== $customer_id )  ) {
          jpid_add_notice( __( 'User ID\'s already attached to a customer.', 'jpid' ), JPID_Admin_Notices::ERROR );

          $data_errors++;
        }
      }

      if ( $data_errors === 0 ) {
        $customer_data['user_id']         = $user->ID;
        $customer_data['customer_name']   = $user->first_name . ' ' . $user->last_name;
        $customer_data['customer_email']  = $user->user_email;
        $customer_data['customer_status'] = JPID_Customer_Status::REGISTERED;
      }
    } else {
      $customer_data['user_id']         = 0;
      $customer_data['customer_status'] = JPID_Customer_Status::GUEST;
    }

    if ( $data_errors > 0 ) {
      return false;
    } else {
      return $customer_data;
    }
  }

  /**
   * Delete customer action.
   *
   * @since    1.0.0
   */
  private function delete_customer( $customer_id = 0 ) {
    if ( ! $this->can_delete() ) {
      wp_die( __( 'You do not have permission to delete this customer.', 'jpid' ) );
    }

    $customers_db = new JPID_DB_Customers();
    $deleted      = $customers_db->delete( $customer_id );

    if ( $deleted ) {
      $customer_list_page = admin_url() . 'admin.php?page=' . JPID_Admin_Page_Customer_List::SLUG;

      jpid_add_notice( __( 'Customer deleted.', 'jpid' ), JPID_Admin_Notices::SUCCESS );

      wp_safe_redirect( $customer_list_page );

      exit;
    } else {
      jpid_add_notice( __( 'Failed to delete customer.', 'jpid' ), JPID_Admin_Notices::ERROR );
    }
  }

  /**
   * Check if current edited customer can be deleted.
   *
   * @since     1.0.0
   * @return    boolean    True if customer can be deleted, otherwise false.
   */
  private function can_delete() {
    return is_admin() && current_user_can( 'delete_posts' ) && current_user_can( 'delete_users' );
  }

  /**
   * Determine which customer list screen bulk action to call based on request sent.
   *
   * @since    1.0.0
   */
  public function customer_bulk_actions() {
    if ( ! isset( $_GET['page'] ) || $_GET['page'] !== JPID_Admin_Page_Customer_List::SLUG ) {
      return;
    }

    $wp_list_table  = new WP_List_Table;
    $current_action = $wp_list_table->current_action();

    if ( $current_action === false ) {
      return;
    }

    switch ( $current_action ) {
      case 'delete_customers':
        break;
    }
  }

  /**
   * Update customer details when his/her coresponding user account is updated.
   *
   * @since     1.0.0
   * @param     int      $user_id    The user ID of the user being edited.
   */
  public function update_customer_on_user_update( $user_id ) {
    if ( $user_id < 1 ) {
      return;
    }

    $customer = jpid_get_customer_by( 'user_id', $user_id );

    if ( empty( $customer ) || $customer->get_user_id() < 1 ) {
      return;
    }

    $user = get_userdata( $customer->get_user_id() );

    if ( ! empty( $user ) ) {
      $new_customer_name  = $user->first_name . ' ' . $user->last_name;
      $new_customer_email = $user->user_email;

      $customer_data = array(
        'customer_name' => $new_customer_name,
        'customer_email' => $new_customer_email
      );

      $customer->save( $customer_data );
    }
  }

}
