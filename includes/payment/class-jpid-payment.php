<?php

/**
 * Payment object class.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/payment
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Payment {

  /**
	 * @since    1.0.0
	 * @var      JPID_DB    Database manager.
	 */
  private $db = null;

  /**
	 * @since    1.0.0
	 * @var      int      Payment's ID.
	 */
  private $id = 0;

  /**
	 * @since    1.0.0
	 * @var      string    Order invoice.
	 */
  private $order_invoice = '';

  /**
	 * @since    1.0.0
	 * @var      string    Payment's submitted date.
	 */
  private $date_submitted = '';

  /**
	 * @since    1.0.0
	 * @var      int      Payment's receipt id.
	 */
  private $receipt_id = 0;

  /**
	 * @since    1.0.0
	 * @var      string    Payment's status.
	 */
  private $status = '';

  /**
   * @since    1.0.0
   * @var      string    Payment's bank.
   */
  private $payment_bank = '';

  /**
   * @since    1.0.0
   * @var      string    Payment's account name.
   */
  private $payment_account_name = '';

  /**
   * @since    1.0.0
   * @var      string    Payment's account number.
   */
  private $payment_account_number = '';

  /**
   * @since    1.0.0
   * @var      string    Transfer's bank.
   */
  private $transfer_bank = '';

  /**
   * @since    1.0.0
   * @var      string    Transfer's account name.
   */
  private $transfer_account_name = '';

  /**
   * @since    1.0.0
   * @var      string    Transfer's account number.
   */
  private $transfer_account_number = '';

  /**
   * @since    1.0.0
   * @var      float    Transfer's amount.
   */
  private $transfer_amount = 0;

  /**
   * @since    1.0.0
   * @var      string    Transfer's date.
   */
  private $transfer_date = '';

  /**
   * @since    1.0.0
   * @var      string    Transfer's note.
   */
  private $transfer_note = '';

  /**
	 * Initialize payment object and set all its properties.
	 *
	 * @since    1.0.0
	 * @param    int|string    $id    Payment's ID.
	 */
  public function __construct( $id = false ) {
    $this->db = new JPID_DB_Payments();

    $id      = absint( $id );
    $payment = null;

    if ( ! empty( $id ) ) {
      $payment = $this->db->get( $id );
    }

    if ( ! empty( $payment ) || is_object( $payment ) ) {
      $this->populate_data( $payment );
    }
  }

  /**
   * Populate payment object's properties.
   *
   * @since    1.0.0
   * @param    object    $payment    Snack box database object.
   */
  private function populate_data( $payment ) {
    $this->id                      = (int) $payment->payment_id;
    $this->order_invoice           = (string) $payment->order_invoice;
    $this->date_submitted          = (string) $payment->date_submitted;
    $this->receipt_id              = (int) $payment->receipt_id;
    $this->status                  = (string) $payment->payment_status;
    $this->payment_bank            = (string) $payment->payment_bank;
    $this->payment_account_name    = (string) $payment->payment_account_name;
    $this->payment_account_number  = (string) $payment->payment_account_number;
    $this->transfer_bank           = (string) $payment->transfer_bank;
    $this->transfer_account_name   = (string) $payment->transfer_account_name;
    $this->transfer_account_number = (string) $payment->transfer_account_number;
    $this->transfer_amount         = (float) $payment->transfer_amount;
    $this->transfer_date           = (string) $payment->transfer_date;
    $this->transfer_note           = (string) $payment->transfer_note;
  }

  /**
   * Save payment data.
   *
   * Create new payment or update if it already exists.
   *
   * @since     1.0.0
   * @param     array       $data    Payment data.
   * @return    int|bool             Payment's ID on success, false on failure.
   */
  public function save( $data = array() ) {
    if ( empty( $data ) ) {
      return false;
    }

    $do_update  = $this->get_id() > 0;
    $payment_id = false;

    if ( $do_update ) {
      $payment_id = $this->db->update( $this->get_id(), $data );
    } else {
      $payment_id = $this->db->insert( $data );
    }

    if ( $payment_id > 0 ) {
      $payment = $this->db->get_by( 'payment_id', $payment_id );

      $this->populate_data( $payment );
    }

    return $payment_id;
  }

  /**
   * Get payment's ID.
   *
   * @since     1.0.0
   * @return    int      payment's ID.
   */
  public function get_id() {
    return $this->id;
  }

  /**
   * Get order's invoice.
   *
   * @since     1.0.0
   * @return    int      Order's invoice.
   */
  public function get_order_invoice() {
    return $this->order_invoice;
  }

  /**
   * Get payment submitted date.
   *
   * @since     1.0.0
   * @param     string     $format       Date format.
   * @param     boolean    $translate    Wheter to translate the date or not.
   * @return    string                   Payment submitted date.
   */
  public function get_submitted_date( $format = 'Y-m-d H:i:s', $translate = true ) {
    return mysql2date( $format, $this->date_submitted, $translate );
  }

  /**
   * Get payment receipt's ID.
   *
   * @since     1.0.0
   * @return    int      Payment recipt's ID.
   */
  public function get_receipt_id() {
    return $this->receipt_id;
  }

  /**
   * Get payment's status.
   *
   * @since     1.0.0
   * @return    string    Payment's status.
   */
  public function get_status() {
    return $this->status;
  }

  /**
   * Get payment's bank.
   *
   * @since     1.0.0
   * @return    string    Payment's bank.
   */
  public function get_payment_bank() {
    return $this->payment_bank;
  }

  /**
   * Get payment's account name.
   *
   * @since     1.0.0
   * @return    string    Payment's account name.
   */
  public function get_payment_account_name() {
    return $this->payment_account_name;
  }

  /**
   * Get payment's account number.
   *
   * @since     1.0.0
   * @return    string    Payment's account number.
   */
  public function get_payment_account_number() {
    return $this->payment_account_number;
  }

  /**
   * Get transfer's bank.
   *
   * @since     1.0.0
   * @return    string    Transfer's bank.
   */
  public function get_transfer_bank() {
    return $this->transfer_bank;
  }

  /**
   * Get transfer's account name.
   *
   * @since     1.0.0
   * @return    string    Transfer's account name.
   */
  public function get_transfer_account_name() {
    return $this->transfer_account_name;
  }

  /**
   * Get transfer's account number.
   *
   * @since     1.0.0
   * @return    string    Transfer's account number.
   */
  public function get_transfer_account_number() {
    return $this->transfer_account_number;
  }

  /**
   * Get transfer's amount.
   *
   * @since     1.0.0
   * @return    float    Transfer's amount.
   */
  public function get_transfer_amount() {
    return $this->transfer_amount;
  }

  /**
   * Get transfer date.
   *
   * @since     1.0.0
   * @param     string     $format       Date format.
   * @param     boolean    $translate    Wheter to translate the date or not.
   * @return    string                   Transfer date.
   */
  public function get_transfer_date( $format = 'Y-m-d H:i:s', $translate = true ) {
    return mysql2date( $format, $this->transfer_date, $translate );
  }

  /**
   * Get transfer's note.
   *
   * @since     1.0.0
   * @return    string    Transfer's note.
   */
  public function get_transfer_note() {
    return $this->transfer_note;
  }

}
