<?php

/**
 * Customers list table.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/tables
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class JPID_Table_Customers extends WP_List_Table {

  /**
   * @since    1.0.0
   * @var      int      Number of customers per page.
   */
  private $per_page = 20;

  /**
   * @since    1.0.0
   * @var      int      Number of customers found.
   */
  private $count = 0;

  /**
   * @since    1.0.0
   * @var      int      Total customers.
   */
  private $total = 0;

  /**
   * @since    1.0.0
   * @var      int      The arguments for data set.
   */
  private $args = array();

  public function __construct() {
    global $status, $page;

    parent::__construct( array(
      'singular' => __( 'Customer', 'jpid' ),
      'plural'   => __( 'Customers', 'jpid' ),
      'ajax'     => false,
    ) );
  }

  public function get_columns() {
    $columns = array(
      'cb'                => '<input type="checkbox" />',
      'customer_name'     => __( 'Customer', 'jpid' ),
      'customer_contacts' => __( 'Contact Details', 'jpid' ),
      'customer_value'    => __( 'Value', 'jpid' ),
      'date_created'      => __( 'Created On', 'jpid' ),
      'customer_actions'  => __( 'Actions', 'jpid' )
    );

    return $columns;
  }

  public function get_sortable_columns() {
    $sortable_columns = array(
      'customer_name'  => array( 'customer_name', true ),
      'customer_value' => array( 'order_value', true ),
      'date_created'   => array( 'date_created', true )
    );

    return $sortable_columns;
  }

  public function column_default( $item, $column_name ) {
    switch ( $column_name ) {
      case 'customer_name':
      case 'customer_contacts':
      case 'customer_value':
      case 'date_created':
        return $item[ $column_name ];
      default:
        return print_r($item,true);
    }
  }

  public function column_cb( $item ) {
    $id   = $item['customer_id'];
    $name = $item['customer_name'];

    $cb  = '<label class="screen-reader-text" for="cb-select-' . $id . '">';
    $cb .= sprintf( __( 'Select %s' ), $name );
    $cb .= '</label>';
    $cb .= '<input type="checkbox" id="cb-select-' . $id . '" name="customer_id[]" value="' . $id . '" />';

    return $cb;
  }

  public function column_customer_name( $item ) {
    $id        = $item['customer_id'];
    $status    = $item['customer_status'];
    $name      = $item['customer_name'];
    $email     = $item['customer_email'];
    $avatar    = get_avatar( $email, 32 );
    $edit_link = 'admin.php?page=' . JPID_Admin_Page_Customer_Edit::SLUG . '&customer=' . $id;

    if ( empty( $name ) ) {
      $name = __( 'Customer', 'jpid' );
    }

    $customer_name  = '<a href="' . $edit_link . '">' . $avatar . '</a>';
    $customer_name .= '<strong><a href="' . $edit_link . '">' . $name . '</a></strong>';
    $customer_name .= '<small class="meta">' . $status . '</small>';

    return $customer_name;
  }

  public function column_customer_contacts( $item ) {
    $email = $item['customer_email'];
    $phone = $item['customer_phone'];

    $customer_contacts  = '<a href="mailto:' . $email . '" title="' . esc_attr( sprintf( __( 'Email: %s' ), $email ) ) . '">';
    $customer_contacts .= $email;
    $customer_contacts .= '</a><br />';
    $customer_contacts .= '<span class="">' . $phone . '</span>';

    return $customer_contacts;
  }

  public function column_customer_value( $item ) {
    $order_count = sprintf( _n( '%d order', '%d orders', $item['order_count'], 'jpid' ), $item['order_count'] );
    $order_value = jpid_to_rupiah( $item['order_value'] );

    $customer_value  = $order_value . '<br />';
    $customer_value .= '<small class="meta">' . $order_count . '</small>';

    return $customer_value;
  }

  public function column_date_created( $item ) {
    return date_i18n( get_option( 'date_format' ), strtotime( $item['date_created'] ) );
  }

  public function column_customer_actions( $item ) {
    // TODO: edit & delete actions
  }

  public function prepare_items() {
    $columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

    $this->_column_headers = array( $columns, $hidden, $sortable );

    $this->args = $this->build_args();

    $this->items = $this->get_items( $this->args );

    $this->total = jpid_count_total_customers( $this->args );

    $this->set_pagination_args( array(
      'total_items' => $this->total,
      'total_pages' => ceil( $this->total / $this->per_page ),
      'per_page'    => $this->per_page,
    ) );
  }

  private function build_args() {
		$paged   = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
		$offset  = $this->per_page * ( $paged - 1 );
		$search  = ! empty( $_GET['s'] ) ? urldecode( trim( $_GET['s'] ) ) : false;
		$order   = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'DESC';
		$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'customer_id';

    $args = array(
      'number'  => $this->per_page,
      'offset'  => $offset,
      'order'   => $order,
      'orderby' => $orderby
    );

    if ( is_email( $search ) ) {
			$args['customer_email'] = $search;
		} elseif ( is_numeric( $search ) ) {
			$args['customer_id'] = $search;
		} elseif ( strpos( $search, 'user:' ) !== false ) {
			$args['user_id'] = trim( str_replace( 'user:', '', $search ) );
		} else {
			$args['customer_name'] = $search;
		}

    return $args;
  }

  private function get_items( $args = array() ) {
    $items     = array();
    $customers = jpid_get_customers( $args );

    if ( ! empty( $customers ) ) {
      foreach ( $customers as $customer ) {
				$items = array(
					'customer_id'     => $customer->get_id(),
					'user_id'         => $customer->get_user_id(),
          'date_created'    => $customer->get_created_date(),
          'customer_status' => $customer->get_status(),
					'customer_name'   => $customer->get_name(),
					'customer_email'  => $customer->get_email(),
          'customer_phone'  => $customer->get_phone(),
					'order_count'     => $customer->get_order_count(),
					'order_value'     => $customer->get_order_value()
				);
			}
    }

    return $items;
  }

  public function extra_tablenav( $which ) {
    if ( $which === 'top' ) {
      echo 'Hello, I\'m before the table';
    }
  }

}
