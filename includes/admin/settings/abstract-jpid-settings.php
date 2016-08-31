<?php

/**
 * Abstract class for settings page.
 *
 * Extend this class when creating new individual settings page.
 * Like the one appears in settings page tab.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id/includes/admin/settings
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

abstract class JPID_Settings {

  /**
   * @since    1.0.0
   * @var      string    Settings page tab slug.
   */
  protected $settings_page_tab;

  /**
   * Class constructor.
   *
   * @since    1.0.0
   */
  public function __construct( $settings_page_tab ) {
    $this->settings_page_tab = $settings_page_tab;
  }

  /**
   * Register settings for this settings page.
   *
   * @since    1.0.0
   */
  public function register_settings() {
    $sections = $this->get_settings();

    foreach ( $sections as $section_id => $section_args ) {
      add_settings_section(
        $section_id,
        $section_args['title'],
        array( $this, 'display_sections' ),
        $this->settings_page_tab
      );

      foreach ( $section_args['fields'] as $field_id => $field_args ) {
        add_settings_field(
          $field_id,
          $field_args['title'],
          array( $this, 'display_fields' ),
          $this->settings_page_tab,
          $section_id,
          array(
            'label_for'   => $field_id,
            'description' => $field_args['description'] ? $field_args['description'] : ''
          )
        );

        register_setting( $this->settings_page_tab, $field_id, $field_args['sanitize_callback'] );
      }
    }
  }

  abstract protected function get_settings();

  abstract public function display_sections( $args );

  abstract public function display_fields( $args );

}
