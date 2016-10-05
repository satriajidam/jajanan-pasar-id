<?php
/**
 * Customer edit page - Customer actions.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/pages/views
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
?>
<div id="submitdiv" class="postbox" style="display: block;">
  <button type="button" class="handlediv button-link" aria-expanded="true">
		<span class="toggle-indicator" aria-hidden="true"></span>
	</button>
  <h2 class="hndle ui-sortable-handle">
    <span><?php esc_html_e( 'Customer Actions', 'jpid' ); ?></span>
  </h2>
  <div class="inside">
    <div id="submitpost" class="submitbox">
      <div id="minor-publishing">
        <?php if ( $this->customer->get_id() > 0 ) : ?>
          <div id="minor-publishing-actions">
            <div id="jpid_customer_actions">
              <select name="jpid_customer_action">
                <option value="">- <?php esc_html_e( 'Select Action', 'jpid' ); ?> -</option>
              </select>
              <button class="button" title="<?php esc_attr_e( 'Apply', 'jpid' ); ?>">
                <span><?php esc_html_e( 'Apply', 'jpid' ) ?></span>
              </button>
            </div>
            <div class="clear"></div>
          </div>
        <?php else : ?>
          <input type="hidden" name="jpid_customer_action" value="create_customer">
        <?php endif; ?>
      </div>
      <div id="major-publishing-actions">
        <?php if ( $this->customer->get_id() > 0 ) : ?>
          <div id="delete-action">
            <a id="jpid_delete_customer" href="<?php echo add_query_arg('action', 'delete'); ?>" class="submitdelete deletion"><?php esc_html_e( 'Delete Customer', 'jpid' ); ?></a>
          </div>
        <?php endif; ?>
        <div id="publishing-action">
          <span class="spinner"></span>
          <input type="submit" id="jpid_save_customer" name="jpid_save_customer" style="float: right;" class="button button-primary" value="<?php $this->customer->get_id() > 0 ? esc_attr_e( 'Update', 'jpid' ) : esc_attr_e( 'Save', 'jpid' ); ?>">
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
