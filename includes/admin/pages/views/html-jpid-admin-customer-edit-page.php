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
    $is_update     = $this->customer->get_id() > 0 ? true : false;
    $is_registered = $this->customer->get_user_id() > 0 ? true : false;

    if ( ! $is_update ) {
      $title = __( 'Add New Customer', 'jpid' );
    } else {
      $title = __( 'Edit Customer', 'jpid' );

      if ( ! $is_registered ) {
        $title .= ' (' . __( 'Guest', 'jpid' ) . ')';
      }
    }
  ?>
  <h1>
    <?php esc_html_e( $title ); ?>
    <?php if ( $is_update ) : ?>
      <a href="<?php echo admin_url() . 'admin.php?page=' . JPID_Admin_Page_Customer_Edit::SLUG ?>" class="page-title-action"><?php esc_html_e( 'Add Customer', 'jpid' ); ?></a>
    <?php endif; ?>
  </h1>
  <?php jpid_print_notices(); ?>
  <form id="jpid_edit_customer_form" method="post" action="">
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
            <?php if ( $is_update ) : ?>
              <?php include_once JPID_PLUGIN_DIR . 'includes/admin/pages/views/html-jpid-admin-customer-edit-page-orders.php'; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
