<?php
/**
 * Plugin about page.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/pages/views
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
?>
<div class="wrap">
  <h2><?php esc_html_e( 'Jajanan Pasar Indonesia', 'jpid' ); ?></h2>
  <p><?php esc_html_e( 'Web application plugin for snack box ordering system, made by Heru Purwito and Agastyo Satriaji Idam from Gerbit Creative.', 'jajanan-pasar-id' ); ?></p>
</div>
<?php

  $customers_db = new JPID_DB_Customers();

  $data = array(
    'customer_email' => 'jasontodd@wayneenterprise.com'
  );

  // die( var_dump( $customers_db->get_next_id() ) );
  // die( var_dump( $customers_db->insert( $data ) ) );
  // die( var_dump( $customers_db->delete( 9 ) ) );
  // die( var_dump( $customers_db->update( 1, $data ) ) );
  // die( var_dump( $customers_db->get_all( array() ) ) );
  // die( var_dump( $customers_db->get( 1 ) ) );
  // die( var_dump( $customers_db->exists( 'brucewayne@wayneenterprises.com' ) ) );
  // die( var_dump( $customers_db->increase_order_stats( 1, 1000000 ) ) );

  $orders_db = new JPID_DB_Orders();

  $data = array(
    'customer_id'       => 2,
    'recipient_name'    => 'Richard Grayson',
    'recipient_phone'   => '678-345-1278',
    'delivery_date'     => '2016-10-01 10:00:00',
    'delivery_address'  => 'Grayson Circus',
    'delivery_province' => 'New Jersey',
    'delivery_city'     => 'Bludhaven',
    'delivery_cost'     => 0.00,
    'order_cost'        => 1000000,
  );

  // Available product: 1, 2

  // var_dump( $orders_db->get_next_id() );
  // var_dump( $orders_db->insert( $data ) );
  // var_dump( $orders_db->delete( 'JPID5250916' ) );
  // var_dump( $orders_db->update( 6, array( 'delivery_cost' => 0, 'order_cost' => 0 ) ) );
  // var_dump( $orders_db->get_all( array( 'delivery_date' => '2016-10-01 00:00:00' ) ) );
  // var_dump( $orders_db->get( 3 ) );
  // var_dump( $orders_db->exists( 'JPID6250916' ) );
  // var_dump( $orders_db->add_item( 3, 1, 50 ) );
  // var_dump( $orders_db->remove_item( 3, 1 ) );
  // var_dump( $orders_db->get_items( 3 ) );

  $snack_boxes_db = new JPID_DB_Snack_Boxes();

  $data = array(
    'snack_box_price' => 5000
  );

  // Available product: 69, 94, 95, 97

  // die( var_dump( $snack_boxes_db->update( 1, $data ) ) );
  // die( var_dump( $snack_boxes_db->get( 1 ) ) );
  // die( var_dump( $snack_boxes_db->get_all( array( 'snack_box_type' => array( 'custom' )  ) ) ) );
  // die( var_dump( $snack_boxes_db->exists( 3 ) ) );
  // die( var_dump( $snack_boxes_db->add_item( 1, 97 ) ) );
  // die( var_dump( $snack_boxes_db->remove_item( 1, 95 ) ) );
  // die( var_dump( $snack_boxes_db->get_items( 1 ) ) );

  $payments_db = new JPID_DB_Payments();

  $data = array(
    'order_invoice'           => 'JPID4250916',
    'payment_bank'            => 'Mandiri',
    'payment_account_name'    => 'Nasihun Amien',
    'payment_account_number'  => '123456789',
    'transfer_bank'           => 'Gotham Federal',
    'transfer_account_name'   => 'Richard Grayson',
    'transfer_account_number' => '181317233',
    'transfer_amount'         => 1500000,
    'transfer_date'           => '2016-09-25'
  );

  // die( var_dump( $payments_db->insert( $data ) ) );
  // die( var_dump( $payments_db->update( 6, array( 'receipt_id' => 101 ) ) ) );
  // die( var_dump( $payments_db->get( 6 ) ) );
  // die( var_dump( $payments_db->get_all( array( 'transfer_account_name' => 'Bruce'  ) ) ) );
  // die( var_dump( $payments_db->exists( 6 ) ) );

  $order = new JPID_Order( 3 );

  // var_dump( $order->add_item( 1, 50 ) );
  // var_dump( $order->remove_item( 1 ) );
  // var_dump( $order );

  $snack_box = new JPID_Snack_Box( 1 );

  // var_dump( $snack_box->add_item( 95 ) );
  // var_dump( $snack_box->remove_item( 94 ) );
  // var_dump( $snack_box );

?>
