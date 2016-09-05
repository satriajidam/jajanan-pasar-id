<?php

/**
 * General settings page.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id/includes/admin/settings
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Settings_General extends JPID_Settings {

  /**
   * Get settings options for this settings page.
   *
   * @since     1.0.0
   * @return    array    Collection of settings options for this settings page.
   */
  protected function get_settings() {
    return array(

      // Page settings section
      'jpid_page_section' => array(
        'title'  => __( 'Page Settings', 'jpid' ),
        'fields' => array(
          'jpid_snacks_selection_page' => array(
            'title'             => __( 'Snacks Selection Page', 'jpid' ),
            'description'       => __( 'This is the page where customers will choose snacks for their snack box.', 'jpid' ),
            'sanitize_callback' => 'absint'
          ),
          'jpid_drinks_selection_page' => array(
            'title'             => __( 'Drinks Selection Page', 'jpid' ),
            'description'       => __( 'This is the page where customers will choose drinks for their snack box.', 'jpid' ),
            'sanitize_callback' => 'absint'
          ),
          'jpid_checkout_page' => array(
            'title'             => __( 'Checkout Page', 'jpid' ),
            'description'       => __( 'This is the page where customers will complete their snack box orders.', 'jpid' ),
            'sanitize_callback' => 'absint'
          ),
          'jpid_payment_confirmation_page' => array(
            'title'             => __( 'Payment Confirmation Page', 'jpid' ),
            'description'       => __( 'This is the page where customers will submit their payment receipts.', 'jpid' ),
            'sanitize_callback' => 'absint'
          ),
          'jpid_customer_page' => array(
            'title'             => __( 'Customer Page', 'jpid' ),
            'description'       => __( 'This is the page where customers can see their orders history.', 'jpid' ),
            'sanitize_callback' => 'absint'
          )
        )
      ),

      // Order settings section
      'jpid_order_section' => array(
        'title'  => __( 'Order Settings', 'jpid' ),
        'fields' => array(
          'jpid_order_full_status' => array(
            'title'             => __( 'Stop Accepting Order', 'jpid' ),
            'description'       => __( 'Prevent customer from placing order and show order full notice on the customer facing area of the website.', 'jpid' ),
            'sanitize_callback' => 'intval'
          ),
          'jpid_order_full_notice' => array(
            'title'             => __( 'Order Full Notice', 'jpid' ),
            'description'       => __( 'The notice that the customer will see on the order form.', 'jpid' ),
            'sanitize_callback' => 'sanitize_text_field'
          ),
          'jpid_order_available_date' => array(
            'title'             => __( 'Accepting Order Date', 'jpid' ),
            'description'       => __( 'Show the date when the order will be available again on the order form.', 'jpid' ),
            'sanitize_callback' => 'sanitize_text_field'
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
      case 'jpid_page_section':
        $section_desc = __( 'Setup pages that will be used to handle snack ordering process on the customer facing area.', 'jpid' );
        break;
      case 'jpid_order_section':
        $section_desc = __( 'The following settings are used to enable/disable snack ordering on the customer facing area.', 'jpid' );
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
      case 'jpid_snacks_selection_page':
      case 'jpid_drinks_selection_page':
      case 'jpid_checkout_page':
      case 'jpid_payment_confirmation_page':
      case 'jpid_customer_page':
        $selected_page = absint( get_option( $field_id ) );

        $pages = get_pages( array(
          'sort_order'  => 'asc',
          'sort_column' => 'post_title',
          'post_type'   => 'page',
          'post_status' => array( 'publish', 'draft' )
        ) );
        ?>
        <select id="<?php esc_attr_e( $field_id ); ?>" name="<?php esc_attr_e( $field_id ); ?>" class="">
          <option value="0">-<?php esc_html_e( __( 'Select Page', 'jpid' ) ); ?>-</option>
          <?php if ( ! is_null( $pages ) ) : ?>
            <?php foreach ( $pages as $page ) : ?>
              <option value="<?php esc_attr_e( $page->ID ); ?>" <?php selected( $selected_page, $page->ID, true ); ?>><?php esc_html_e( $page->post_title ); ?></option>
            <?php endforeach ?>
          <?php endif; ?>
        </select>

        <p class="description"><?php esc_html_e( $args['description'] ); ?></p>
        <?php
        break;

      case 'jpid_order_full_status':
        $order_full_status = (int) get_option( $field_id );
        ?>
        <input type="checkbox" id="<?php esc_attr_e( $field_id ); ?>" name="<?php esc_attr_e( $field_id ); ?>" class="" value="1" <?php checked( $order_full_status, 1, true ); ?> />

        <p class="description jpid-inline-paragraph"><?php esc_html_e( $args['description'] ); ?></p>
        <?php
        break;

      case 'jpid_order_full_notice':
        $order_full_notice = (string) get_option( $field_id );
        ?>
        <textarea id="<?php esc_attr_e( $field_id ); ?>" name="<?php esc_attr_e( $field_id ); ?>" class="large-text"><?php esc_html_e( $order_full_notice ); ?></textarea>

        <p class="description"><?php esc_html_e( $args['description'] ); ?></p>
        <?php
        break;

      case 'jpid_order_available_date':
        $order_available_date = (string) get_option( $field_id );
        ?>
        <input type="text" id="<?php esc_attr_e( $field_id ); ?>" name="<?php esc_attr_e( $field_id ); ?>" class="" data-dateformat="dd-mm-yy" value="<?php esc_attr_e( $order_available_date ); ?>" />

        <p class="description"><?php esc_html_e( $args['description'] ); ?></p>
        <?php
        break;
    }
  }

}
