<?php

/**
 * Collection of helper functions to manage order object.
 *
 * All functions declared here are available in the global scope.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/helpers
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Get order item based on provided item id and type.
 *
 * NOTE: For now there isn't any official form of contract for an object
 * to be considered as order item. An object that can be created throught
 * this function only need to implement a get_price() function in its class.
 * Because get_price() function is used to calculate the total cost of an order
 * inside update_order_cost_on_items_change() function in JPID_DB_Orders class.
 *
 * Since there is only 1 type of order item this is okay for now, but when more
 * types of order item are introduced an official form of contract should be forced
 * to an object to be considered as order item.
 *
 * @since     1.0.0
 * @param     int       $item_id      Item's ID.
 * @param     string    $item_type    Item's type.
 * @return    object                  Order item object.
 */
function jpid_get_order_item( $item_id = 0, $item_type = '' ) {
  $item_id = absint( $item_id );

  if ( empty( $item_id ) ) {
    $order_item = null;
  }

  switch ( $item_type ) {
    case self::SNACK_BOX:
      $order_item = new JPID_Snack_Box( $item_id );
      break;
    default:
      $order_item = null;
      break;
  }

  return $order_item;
}
