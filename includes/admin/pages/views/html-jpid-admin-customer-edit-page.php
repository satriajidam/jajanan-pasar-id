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
  <?php JPID_Admin_Notices::print_notices(); ?>
  <form id="jpid_edit_customer_form" method="post">
    <input type="hidden" id="jpid_customer_id" name="jpid_customer_id" value="<?php esc_attr_e( $this->customer->get_id() ); ?>" />
    <div id="poststuff">
      <div id="post-body" class="metabox-holder columns-2">
        <div id="postbox-container-1" class="postbox-container">
          <div id="side-sortables" class="meta-box-sortables ui-sortable">
            <?php include_once JPID_PLUGIN_DIR . 'includes/admin/pages/views/html-jpid-admin-customer-edit-page-actions.php'; ?>
          </div>
        </div>
        <div id="postbox-container-2" class="postbox-container">
          <div id="normal-sortables" class="meta-box-sortables ui-sortable">
            <?php include_once JPID_PLUGIN_DIR . 'includes/admin/pages/views/html-jpid-admin-customer-edit-page-details.php'; ?>
            <?php if ( $this->customer->get_id() > 0 ) : ?>
              <?php include_once JPID_PLUGIN_DIR . 'includes/admin/pages/views/html-jpid-admin-customer-edit-page-orders.php'; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
