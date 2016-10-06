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
        <div id="minor-publishing-actions">
          <select id="jpid_customer_action" name="jpid_customer_action">
            <option value="save_customer"><?php $this->customer->get_id() > 0 ? esc_html_e( 'Update Customer', 'jpid' ) : esc_html_e( 'Create Customer', 'jpid' ); ?></option>
            <?php if ( $this->customer->get_id() > 0 ) : ?>
            <?php endif; ?>
          </select>
          <div class="clear"></div>
        </div>
      </div>
      <div id="major-publishing-actions">
        <?php if ( $this->customer->get_id() > 0 ) : ?>
          <div id="delete-action">
            <a id="jpid_delete_customer" href="<?php echo add_query_arg('jpid_customer_action', 'delete'); ?>" class="submitdelete deletion"><?php esc_html_e( 'Delete Customer', 'jpid' ); ?></a>
          </div>
        <?php endif; ?>
        <div id="publishing-action">
          <input type="submit" style="float: right;" class="button button-primary" value="<?php esc_attr_e( 'Save Customer', 'jpid' ); ?>">
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
