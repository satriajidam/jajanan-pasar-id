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
   * @var      string    Option group.
   */
  protected $option_group;

  /**
   * Class constructor.
   *
   * @since    1.0.0
   */
  public function __construct( $option_group ) {
    $this->option_group = $option_group;
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
        $this->option_group
      );

      foreach ( $section_args['fields'] as $field_id => $field_args ) {
        add_settings_field(
          $field_id,
          $field_args['title'],
          array( $this, 'display_fields' ),
          $this->option_group,
          $section_id,
          array(
            'label_for'   => $field_id,
            'description' => $field_args['description'] ? $field_args['description'] : ''
          )
        );

        register_setting( $this->option_group, $field_id, $field_args['sanitize_callback'] );
      }
    }
  }

  /**
   * Get settings options for this settings page.
   *
   * @since     1.0.0
   * @return    array    Collection of settings options for this settings page.
   */
  abstract protected function get_settings();

  /**
   * Display sections of this settings page.
   *
   * @since    1.0.0
   * @param    array    $args    Array of display options for settings sections.
   */
  abstract public function display_sections( $args );

  /**
   * Display fields of this settings page.
   *
   * @since    1.0.0
   * @param    array    $args    Array of display options for settings fields.
   */
  abstract public function display_fields( $args );

}
