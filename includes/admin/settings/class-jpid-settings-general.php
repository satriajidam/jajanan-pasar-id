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
          'jpid_order_available_date' => array(
            'title'             => __( 'Accepting Order Date', 'jpid' ),
            'description'       => __( 'Show the date when the order will be available again on the customer facing area of the website.', 'jpid' ),
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
        $section_desc = __( 'The following options are used to enable/disable snack ordering on the customer facing area.', 'jpid' );
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
        $pages = get_pages( array(
          'sort_order'  => 'asc',
          'sort_column' => 'post_title',
          'post_type'   => 'page',
          'post_status' => array( 'publish', 'draft' )
        ) );

        echo '<select id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $field_id ) . '" class="">';

        echo '<option value="0">- ' . __( 'Select Page', 'jajanan-pasar-id' ) . ' -</option>';

        if ( ! is_null( $pages ) ) {
          $selected_page = absint( get_option( $field_id ) );

          foreach ( $pages as $page ) {
            echo '<option value="' . esc_attr( $page->ID ) . '" ' . selected( $selected_page, $page->ID, false ) . '>' . esc_html( $page->post_title ) . '</option>';
          }
        }

        echo '</select>';

        echo '<p class="description">' . $args['description'] . '</p>';
        break;
      case 'jpid_order_full_status':
        $order_full_status = (int) get_option( $field_id );

        echo '<input type="checkbox" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $field_id ) . '" class="" value="1" ' . checked( $order_full_status, 1, false ) . ' />&nbsp;<p class="description jpid-inline-paragraph">' . esc_html( $args['description'] ) . '</p>';
        break;
      case 'jpid_order_available_date':
        $order_available_date = (string) get_option( $field_id );

        echo '<input type="text" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $field_id ) . '" class="" data-dateformat="dd-mm-yy" value="' . esc_attr( $order_available_date ) . '" />';

        echo '<p class="description">' . esc_html( $args['description'] ) . '</p>';
        break;
    }
  }

}
