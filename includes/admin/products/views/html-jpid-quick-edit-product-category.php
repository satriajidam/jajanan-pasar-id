<?php
/**
 * Quick-edit view for product category screen.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/products/views
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
?>
<?php wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME ); ?>
<fieldset>
  <div class="inline-edit-col">
    <label>
      <span class="title"><?php esc_html_e( 'Type', 'jpid' ); ?></span>
      <span class="input-text-wrap">
        <select name="jpid_product_type" class="jpid-field-select">
          <?php
            $product_types = jpid_get_product_type_terms();
          ?>
          <?php foreach ( $product_types as $product_type ) : ?>
            <option value="<?php esc_attr_e( $product_type->term_id );  ?>"><?php esc_html_e( $product_type->name ); ?></option>
          <?php endforeach; ?>
        </select>
      </span>
    </label>
  </div>
</fieldset>
