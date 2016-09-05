<?php

/**
 * Deliverys settings page.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id/includes/admin/settings
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Settings_Delivery extends JPID_Settings {

  /**
   * Get settings options for this settings page.
   *
   * @since     1.0.0
   * @return    array    Collection of settings options for this settings page.
   */
  protected function get_settings() {
    return array(

      // Delivery settings section
      'jpid_delivery_section' => array(
        'title'  => __( 'Delivery Settings', 'jpid' ),
        'fields' => array(
          'jpid_delivery_days_range' => array(
            'title'             => __( 'Delivery Days Range', 'jpid' ),
            'description'       => __( 'This setting defines how soon can customer ask for his/her order to be delivered.', 'jpid' ),
            'sanitize_callback' => 'intval'
          ),
          'jpid_delivery_hours' => array(
            'title'             => __( 'Delivery Hours', 'jpid' ),
            'description'       => __( 'The delivery time the customer can choose for his/her order.', 'jpid' ),
            'sanitize_callback' => array( $this, 'sanitize_delivery_hours' )
          ),
          'jpid_delivery_cost_method' => array(
            'title'             => __( 'Delivery Cost Method', 'jpid' ),
            'description'       => __( 'The method used to calculate delivery cost.', 'jpid' ),
            'sanitize_callback' => 'sanitize_text_field'
          ),
          'jpid_delivery_cost_amount' => array(
            'title'             => __( 'Delivery Cost Amount', 'jpid' ),
            'description'       => __( 'The delivery cost the customer must pay. Setting this to 0 means the delivery cost is free.', 'jpid' ),
            'sanitize_callback' => 'floatval'
          ),
          'jpid_delivery_locations' => array(
            'title'             => __( 'Delivery Locations', 'jpid' ),
            'description'       => __( 'List locations that are going to be covered for delivery. Insert district/city names using comma (,) separated value.', 'jpid' ),
            'sanitize_callback' => array( $this, 'sanitize_delivery_locations' )
          )
        )
      )

    );
  }

  /**
   * Get delivery cost calculation methods.
   *
   * @since     1.0.0
   * @return    array    Collection of delivery cost calculation methods.
   */
  private function get_delivery_cost_methods() {
    return array(
      'fixed' => __( 'Fixed', 'jpid' )
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
      case 'jpid_delivery_section':
        $section_desc = __( 'These settings are used to control the delivery process of customer orders.', 'jpid' );
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
      case 'jpid_delivery_days_range':
        $delivery_days = (int) get_option( $field_id );
        ?>
        <input type="number" id="<?php esc_attr_e( $field_id ); ?>" name="<?php esc_attr_e( $field_id ); ?>" value="<?php esc_attr_e( $delivery_days ); ?>" class="small-text" />

        &nbsp;<?php esc_html_e( __( 'days after order', 'jpid' ) ); ?>

        <p class="description"><?php esc_html_e( $args['description'] ); ?>'</p>
        <?php
        break;

      case 'jpid_delivery_hours':
        $delivery_hours = get_option( $field_id );

        if ( empty( $delivery_hours ) ) {
          $delivery_hours['start'] = '';
          $delivery_hours['end']   = '';
        }
        ?>
        <input type="text" id="<?php echo esc_attr( $field_id ) . '_start'; ?>" name="<?php echo esc_attr( $field_id ) . '[start]'; ?>" value="<?php esc_attr_e( $delivery_hours['start'] ); ?>" class="" data-timeformat="HH:mm" />

        &nbsp;<?php esc_html_e( __( 'to', 'jpid' ) ); ?>&nbsp;

        <input type="text" id="<?php echo esc_attr( $field_id ) . '_end'; ?>" name="<?php echo esc_attr( $field_id ) . '[end]'; ?>" value="<?php esc_attr_e($delivery_hours['end'] ); ?>" class="" data-timeformat="HH:mm" />

        &nbsp;<?php esc_html_e( __( 'WIB', 'jpid' ) ); ?>

        <p class="description"><?php esc_html_e( $args['description'] ); ?></p>
        <?php
        break;

      case 'jpid_delivery_cost_method':
        $selected_method = (string) get_option( $field_id );
        $cost_methods    = $this->get_delivery_cost_methods();
        ?>
        <select id="<?php esc_attr_e( $field_id ); ?>" name="<?php esc_attr_e( $field_id ); ?>" class="">
          <?php foreach ( $cost_methods as $cost_method => $method_description ) : ?>
            <option value="<?php esc_attr_e( $cost_method ); ?>" <?php selected( $selected_method, $cost_method, true ); ?>><?php esc_html_e( $method_description ); ?></option>
          <?php endforeach; ?>
        </select>

        <p class="description"><?php esc_html_e( $args['description'] ); ?></p>
        <?php
        break;

      case 'jpid_delivery_cost_amount':
        $delivery_cost_amount = (float) get_option( $field_id );
        ?>
        <input type="number" id="<?php esc_attr_e( $field_id ); ?>" name="<?php esc_attr_e( $field_id ); ?>" value="<?php esc_attr_e( $delivery_cost_amount ); ?>" class="" />&nbsp;<?php esc_html_e( __( 'IDR', 'jpid' ) ); ?>

        <p class="description"><?php esc_html_e( $args['description'] ); ?></p>
        <?php
        break;

      case 'jpid_delivery_locations':
        $delivery_locations = get_option( $field_id );
        $locations_count    = ! empty( $delivery_locations ) ? count( $delivery_locations ) : 1;
        ?>
        <p><?php esc_html_e( $args['description'] ); ?></p>
        <div id="jpid_locations_container" class="ui-sortable" style="height: auto;">
          <?php for ( $i = 0; $i < $locations_count; $i++ ) : ?>
            <table class="jpid-location-table wp-list-table widefat fixed ui-sortable-handle">
              <thead>
                <tr>
                  <th scope="col" class="jpid-location-table__province-header"><?php esc_html_e( 'Province', 'jpid' ); ?></th>
                  <th scope="col" class="jpid-location-table__cities-header"><?php esc_html_e( 'Districts / Cities', 'jpid' ); ?></th>
                  <th scope="col" class="jpid-location-table__remove-header"><?php esc_html_e( 'Remove', 'jpid'); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $provinces        = jpid_get_province_list();
                  $current_province = '';
                  $current_cities   = array();

                  if ( ! empty( $delivery_locations[ $i ] ) ) {
                    $current_province = $delivery_locations[ $i ]['province'];
                    $current_cities   = $delivery_locations[ $i ]['cities'];
                  }

                  sort( $provinces );
                ?>
                <tr>
                  <td class="jpid-location-table__province-data">
                    <select name="<?php echo esc_attr( $field_id ) . '[' . $i . '][province]' ?>" class="">
                      <option value="">-<?php esc_html_e( 'Select Province', 'jpid' ); ?>-</option>
                      <?php foreach ( $provinces as $province ) : ?>
                        <option value="<?php esc_attr_e( $province ); ?>" <?php selected( $current_province, $province, true ); ?>><?php esc_html_e( $province ); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </td>
                  <td class="jpid-location-table__cities-data">
                    <textarea name="<?php echo esc_attr( $field_id ) . '[' . $i . '][cities]'?>" class="large-text"><?php esc_html_e( implode( ', ', $current_cities ) ); ?></textarea>
                  </td>
                  <td class="jpid-location-table__remove-data">
                    <span class="button-secondary jpid-remove-location"><?php esc_html_e( 'Remove Location', 'jpid' ); ?></span>
                  </td>
                </tr>
              </tbody>
            </table>
          <?php endfor; ?>
        </div>
        <p><span id="jpid_add_location" class="button-secondary"><?php _e( 'Add Location', 'jpid' ); ?></span></p>
        <?php
        break;
    }
  }

  /**
   * Sanitize delivery hours value.
   *
   * @since     1.0.0
   * @param     array    $delivery_hours    Delivery start & end hours.
   * @return    array                       Sanitized delivery hours.
   */
  public function sanitize_delivery_hours( $delivery_hours ) {
    $delivery_hours['start'] = sanitize_text_field( $delivery_hours['start'] );
    $delivery_hours['end']   = sanitize_text_field( $delivery_hours['end'] );

    return $delivery_hours;
  }

  /**
   * Sanitize delivery locations value.
   *
   * @since     1.0.0
   * @param     array    $delivery_locations    Collection of delivery locations.
   * @return    array                           Sanitized delivery locations.
   */
  public function sanitize_delivery_locations( $delivery_locations ) {
    $sanitized_locations = array();
    $index               = 0;

    foreach ( $delivery_locations as $location ) {
      $province       = sanitize_text_field( $location['province'] );
      $cities         = sanitize_text_field( $location['cities'] );
      $cities         = explode( ',', $cities );
      $trimmed_cities = array();

      foreach ( $cities as $city ) {
        $city = trim( $city );

        if ( $city != '' ) {
          $trimmed_cities[] = $city;
        }
      }

      $sanitized_locations[ $index ]['province'] = $province;
      $sanitized_locations[ $index ]['cities']   = $trimmed_cities;

      $index++;
    }

    return $sanitized_locations;
  }

}
