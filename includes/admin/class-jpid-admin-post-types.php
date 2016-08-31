<?php

/**
 * Post type functions for admin area.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Admin_Post_Types {

  /**
	 * Class constructor.
	 *
	 * @since    1.0.0
	 */
  public function __construct() {
    $this->setup_hooks();
  }

  /**
   * Setup class hooks.
   *
   * @since    1.0.0
   */
  private function setup_hooks() {
    add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
		add_filter( 'bulk_post_updated_messages', array( $this, 'bulk_post_updated_messages' ), 10, 2 );
    add_filter( 'enter_title_here', array( $this, 'enter_title_here' ), 10, 2 );
    add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );
  }

  /**
   * Add custom post updated messages.
   *
   * @since    1.0.0
   * @param    array    $messages    Default collection of post updated messages.
   * @return   array                 Modified collection of post updated messages.
   */
  public function post_updated_messages( $messages ) {
    $post = get_post();

    $messages['jpid_product'] = array(
      0  => '',
      1  => sprintf( __( 'Product updated. <a href="%s">View Product</a>', 'jpid' ), esc_url( get_permalink( $post->ID ) ) ),
      2  => __( 'Custom field updated.', 'jpid' ),
      3  => __( 'Custom field deleted.', 'jpid' ),
      4  => __( 'Product updated.', 'jpid' ),
      5  => isset( $_GET['revision'] ) ? sprintf( __( 'Product restored to revision from %s', 'jpid' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
      6  => sprintf( __( 'Product published. <a href="%s">View Product</a>', 'jpid' ), esc_url( get_permalink( $post->ID ) ) ),
      7  => __( 'Product saved.', 'jpid' ),
      8  => sprintf( __( 'Product submitted. <a target="_blank" href="%s">Preview Product</a>', 'jpid' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) ),
      9  => sprintf( __( 'Product scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Product</a>'), date_i18n( __( 'M j, Y @ G:i', 'textdomain' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post->ID ) ) ),
      10 => sprintf( __( 'Product draft updated. <a target="_blank" href="%s">Preview Product</a>', 'jpid' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) )
    );

    return $messages;
  }

  /**
   * Add custom bulk post updated messages.
   *
   * @since    1.0.0
   * @param    array    $bulk_messages    Default collection of bulk post updated messages.
   * @param    int      $bulk_counts      Number of posts being updated.
   * @return   array                      Modified collection of bulk post updated messages.
   */
  public function bulk_post_updated_messages( $bulk_messages, $bulk_counts ) {
    $bulk_messages['jpid_product'] = array(
      'updated'   => _n( '%s product updated.', '%s products updated.', $bulk_counts['updated'], 'jpid' ),
      'locked'    => _n( '%s product not updated, somebody is editing it.', '%s products not updated, somebody is editing them.', $bulk_counts['locked'], 'jpid' ),
      'deleted'   => _n( '%s product permanently deleted.', '%s products permanently deleted.', $bulk_counts['deleted'], 'jpid' ),
      'trashed'   => _n( '%s product moved to the Trash.', '%s products moved to the Trash.', $bulk_counts['trashed'], 'jpid' ),
      'untrashed' => _n( '%s product restored from the Trash.', '%s products restored from the Trash.', $bulk_counts['untrashed'], 'jpid' ),
    );

    return $bulk_messages;
  }

  /**
   * Change placeholder text in post title input.
   *
   * @since     1.0.0
   * @param     string     $title    Default placeholder title text.
   * @param     WP_Post    $post     The post object.
   * @return    string               Modified placeholder title text.
   */
  public function enter_title_here( $title, $post ) {
    switch ( $post->post_type ) {
      case 'jpid_product':
        $title = __( 'Enter product Name', 'jpid' );
        break;
    }

    return $title;
  }

  /**
   * Add custom row actions in post list table.
   *
   * @since     1.0.0
   * @param     array      $actions    Default collection of row actions.
   * @param     WP_Post    $post       The post object.
   * @return    array                  Modified collection of row actions.
   */
  public function post_row_actions( $actions, $post ) {
  	switch ( $post->post_type ) {
      case 'jpid_product':
        $actions = array_merge( array( 'id' => 'ID: ' . $post->ID ), $actions );
        break;
    }

  	return $actions;
  }

}
