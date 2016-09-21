<?php

/**
 * Payment settings page.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id/includes/admin/settings
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Settings_Payment extends JPID_Settings {

  /**
   * Get settings options for this settings page.
   *
   * @since     1.0.0
   * @return    array    Collection of settings options for this settings page.
   */
  protected function get_settings() {
    return array(

      // Bank settings section
      'jpid_bank_section' => array(
        'title'  => __( 'Bank Payment', 'jpid' ),
        'fields' => array(
          'jpid_bank_payment_title' => array(
            'title'             => __( 'Payment Title', 'jpid' ),
            'description'       => __( 'The payment title that the customer will see during checkout.', 'jpid' ),
            'sanitize_callback' => 'sanitize_text_field'
          ),
          'jpid_bank_payment_description' => array(
            'title'             => __( 'Payment Description', 'jpid' ),
            'description'       => __( 'The payment method description that the customer will see during checkout.', 'jpid' ),
            'sanitize_callback' => 'sanitize_text_field'
          ),
          'jpid_bank_payment_instructions' => array(
            'title'             => __( 'Payment Instructions', 'jpid' ),
            'description'       => __( 'The payment method instructions that the customer will see during checkout and confirmation emails.', 'jpid' ),
            'sanitize_callback' => 'sanitize_text_field'
          ),
          'jpid_bank_payment_accounts' => array(
            'title'             => __( 'Payment Accounts', 'jpid' ),
            'description'       => __( 'List the payment accounts the customer can transfer to.', 'jpid' ),
            'sanitize_callback' => array( $this, 'sanitize_bank_accounts' )
          )
        )
      )

    );
  }

  /**
   * Display sections of this settings page.
   *
   * @since    1.0.0
   * @param    array    $args    Array of display options for settings sections.
   */
  public function display_sections( $args ) {
    $section_id = $args['id'];

    switch ( $section_id ) {
      case 'jpid_bank_section':
        $section_desc = __( 'Allows customers to pay their orders through bank transfer.', 'jpid' );
        break;
    }

    echo  '<p>' . esc_html( $section_desc ) . '</p>';
  }

  /**
   * Display fields of this settings page.
   *
   * @since    1.0.0
   * @param    array    $args    Array of display options for settings fields.
   */
  public function display_fields( $args ) {
    $field_id = $args['label_for'];

    switch ( $field_id ) {
      case 'jpid_bank_payment_title':
        $bank_payment_title = (string) jpid_get_option( $field_id );
        ?>
        <input type="text" id="<?php esc_attr_e( $field_id ); ?>" name="<?php esc_attr_e( $field_id ); ?>" class="regular-text" value="<?php esc_attr_e(  $bank_payment_title ); ?>" />

        <p class="description"><?php esc_html_e( $args['description'] ); ?></p>
        <?php
        break;
      case 'jpid_bank_payment_description':
      case 'jpid_bank_payment_instructions':
        $payment_text = (string) jpid_get_option( $field_id );
        ?>
        <textarea id="<?php esc_attr_e( $field_id ); ?>" name="<?php esc_attr_e( $field_id ); ?>" class="large-text"><?php esc_html_e( $payment_text ); ?></textarea>

        <p class="description"><?php esc_html_e( $args['description'] ); ?></p>
        <?php
        break;
      case 'jpid_bank_payment_accounts':
        $payment_accounts = jpid_get_option( $field_id );
        $accounts_count   = ! empty( $payment_accounts ) ? count( $payment_accounts ) : 1;
        ?>
        <p><?php esc_html_e( $args['description'] ); ?></p>
        <table id="jpid_accounts_table" class="wp-list-table widefat fixed">
          <thead>
            <tr>
              <th scope="col" class="sort">&nbsp;</th>
              <th scope="col" class=""><?php esc_html_e( 'Bank Name', 'jpid' ); ?></th>
              <th scope="col" class=""><?php esc_html_e( 'Account Name', 'jpid' ); ?></th>
              <th scope="col" class=""><?php esc_html_e( 'Account Number', 'jpid' ); ?></th>
            </tr>
          </thead>
          <tbody class="ui-sortable">
            <?php for ( $i = 0; $i < $accounts_count; $i++ ) : ?>
              <?php
                $bank_name      = '';
                $account_name   = '';
                $account_number = '';

                if ( ! empty( $payment_accounts[ $i ] ) ) {
                  $bank_name      = (string) $payment_accounts[ $i ]['bank_name'];
                  $account_name   = (string) $payment_accounts[ $i ]['account_name'];
                  $account_number = (string) $payment_accounts[ $i ]['account_number'];
                }
              ?>
              <tr class="ui-sortable-handle">
                <td class="sort"></td>
                <td class=""><input type="text" name="<?php echo esc_attr( $field_id ) . '[' . $i . '][bank_name]'; ?>" value="<?php esc_attr_e( $bank_name ); ?>" class="" /></td>
                <td class=""><input type="text" name="<?php echo esc_attr( $field_id ) . '[' . $i . '][account_name]'; ?>" value="<?php esc_attr_e( $account_name ); ?>" class="" /></td>
                <td class=""><input type="text" name="<?php echo esc_attr( $field_id ) . '[' . $i . '][account_number]'; ?>" value="<?php esc_attr_e( $account_number ); ?>" class="" /></td>
              </tr>
            <?php endfor; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="4">
                <span id="jpid_add_account" class="button-secondary">+ <?php esc_html_e( 'Add Account', 'jpid' ); ?></span>
                <span id="jpid_remove_account" class="button-secondary">- <?php esc_html_e( 'Remove Selected Account', 'jpid' ); ?></span>
              </th>
            </tr>
          </tfoot>
        </table>
        <?php
        break;
    }
  }

  /**
   * Sanitize bank payment accounts value.
   *
   * @since     1.0.0
   * @param     array    $bank_accounts    Collection of bank payment accounts.
   * @return    array                      Sanitized bank payment accounts.
   */
  public function sanitize_bank_accounts( $bank_accounts ) {
    $sanitized_accounts = array();
    $index              = 0;

    foreach ( $bank_accounts as $account ) {
      $sanitized_accounts[ $index ]['bank_name']      = sanitize_text_field( $account['bank_name'] );
      $sanitized_accounts[ $index ]['account_name']   = sanitize_text_field( $account['account_name'] );
      $sanitized_accounts[ $index ]['account_number'] = sanitize_text_field( $account['account_number'] );

      $index++;
    }

    return $sanitized_accounts;
  }

}
