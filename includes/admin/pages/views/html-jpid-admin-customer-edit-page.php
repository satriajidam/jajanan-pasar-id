<?php
/**
 * Customer edit page.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/pages/views
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
?>
<div class="wrap">
  <?php
    if ( $this->customer->get_id() == 0 ) {
      $title = __( 'Add New Customer', 'jpid' );
    } else {
      $title = __( 'Edit Customer', 'jpid' );

      if ( $this->customer->get_user_id() == 0 ) {
        $title .= ' (' . __( 'Guest', 'jpid' ) . ')';
      }
    }
  ?>
  <h1><?php esc_html_e( $title ); ?></h1>
  <form id="" method="post">

  </form>
</div>
