<?php

/**
 * Snack box object class.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/post-types
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Snack_Box {

  /**
	 * @since    1.0.0
	 * @var      JPID_DB    Database manager.
	 */
  private $db = null;

  /**
	 * @since    1.0.0
	 * @var      int      Snack box's ID.
	 */
  private $id = 0;

  /**
	 * @since    1.0.0
	 * @var      string    Snack box's creation date.
	 */
  private $date_created = '';

  /**
	 * @since    1.0.0
	 * @var      string    Snack box's name.
	 */
  private $name = '';

  /**
	 * @since    1.0.0
	 * @var      string    Snack box's type.
	 */
  private $type = '';

  /**
	 * @since    1.0.0
	 * @var      float    Snack box's price.
	 */
  private $price = 0;

  /**
	 * @since    1.0.0
	 * @var      array    Snack box's items.
	 */
  private $items = array();

  /**
	 * Initialize snack box object and set all its properties.
	 *
	 * @since    1.0.0
	 * @param    int|string    $id    Snack box's ID.
	 */
  public function __construct( $id = false ) {
    $this->db = new JPID_DB_Snack_Boxes();

    $id        = absint( $id );
    $snack_box = null;

    if ( ! empty( $id ) ) {
      $snack_box = $this->db->get( $id );
    }

    if ( ! empty( $snack_box ) || is_object( $snack_box ) ) {
      $this->populate_data( $snack_box );
    }
  }

  /**
   * Populate snack box object's properties.
   *
   * @since    1.0.0
   * @param    object    $snack_box    Snack box database object.
   */
  private function populate_data( $snack_box ) {
    $this->id           = (int) $snack_box->snack_box_id;
    $this->date_created = (string) $snack_box->date_created;
    $this->name         = (string) $snack_box->snack_box_name;
    $this->type         = (string) $snack_box->snack_box_type;
    $this->price        = (float) $snack_box->snack_box_price;
    $this->items        = $this->load_items();
  }

  /**
   * Load snack box's items.
   *
   * @since     1.0.0
   * @return    array    Collection of snack box's items.
   */
  private function load_items() {
    $new_items = array();
    $items     = $this->db->get_items( $this->id );

    if ( ! empty( $items ) ) {
      foreach ( $items as $item ) {
        $product = jpid_get_product( $item->product_id );

        if ( ! empty( $product ) && is_object( $product ) ) {
          $new_items[] = $product;
        }
      }
    }

    return $new_items;
  }

  /**
   * Load snack box's price.
   *
   * @since     1.0.0
   * @return    float    Snack box's price.
   */
  private function load_price() {
    $old_price = $this->get_price();
    $new_price = (float) $this->db->get_column( 'snack_box_price', $this->get_id() );

    if ( is_null( $new_price ) ) {
      return $old_price;
    }

    return $new_price;
  }

  /**
   * Save snack box data.
   *
   * Create new snack box or update if it already exists.
   *
   * @since     1.0.0
   * @param     array       $data    Snack box data.
   * @return    int|bool             Snack box's ID on success, false on failure.
   */
  public function save( $data = array() ) {
    $do_update    = $this->get_id() > 0;
    $snack_box_id = false;

    if ( $do_update ) {
      $snack_box_id = $this->db->update( $this->get_id(), $data );
    } else {
      $snack_box_id = $this->db->insert( $data );
    }

    if ( $snack_box_id > 0 ) {
      $snack_box = $this->db->get_by( 'snack_box_id', $snack_box_id );

      $this->populate_data( $snack_box );
    }

    return $snack_box_id;
  }

  /**
   * Get snack box's ID.
   *
   * @since     1.0.0
   * @return    int      snack box's ID.
   */
  public function get_id() {
    return $this->id;
  }

  /**
   * Get snack boxs creation date.
   *
   * @since     1.0.0
   * @param     string     $format       Date format.
   * @param     boolean    $translate    Wheter to translate the date or not.
   * @return    string                   Snack boxs creation date.
   */
  public function get_created_date( $format = 'Y-m-d H:i:s', $translate = true ) {
    return mysql2date( $format, $this->date_created, $translate );
  }

  /**
   * Get snack box's name.
   *
   * @since     1.0.0
   * @return    string    snack box's type.
   */
  public function get_name() {
    return $this->name;
  }

  /**
   * Get snack box's type.
   *
   * @since     1.0.0
   * @return    string    Snack box's type.
   */
  public function get_type() {
    return $this->type;
  }

  /**
   * Get snack box's price.
   *
   * @since     1.0.0
   * @return    float    Snack box's price.
   */
  public function get_price() {
    return $this->price;
  }

  /**
   * Get snack box's items.
   *
   * @since     1.0.0
   * @return    float    Snack box's items.
   */
  public function get_items() {
    return $this->items;
  }

  /**
   * Add new item or update old item in snack box.
   *
   * @since     1.0.0
   * @param     int        $product_id     Item's ID.
   * @param     float      $product_qty    Item's quantity.
   * @return    boolean                    True on success, false on failure.
   */
  public function add_item( $product_id, $product_qty = 1 ) {
    $product_id  = absint( $product_id );
    $product_qty = intval( $product_qty );

    if ( empty( $product_id ) || ( $product_qty < 1 ) ) {
      return false;
    }

    $added = $this->db->add_item( $this->get_id(), $product_id, $product_qty );

    if ( $added ) {
      $this->items = $this->load_items();
      $this->price = $this->load_price();

      return true;
    }

    return false;
  }

  /**
   * Remove an item from snack box.
   *
   * @since     1.0.0
   * @param     int        $product_id     Item's ID.
   * @return    boolean                    True on success, false on failure.
   */
  public function remove_item( $product_id ) {
    $product_id = absint( $product_id );

    if ( empty( $product_id ) ) {
      return false;
    }

    $removed = $this->db->remove_item( $this->get_id(), $product_id );

    if ( $removed ) {
      $this->items = $this->load_items();
      $this->price = $this->load_price();

      return true;
    }

    return false;
  }

}
