<?php
/**
 * Meta-field view for add product category screen.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/post-types/views
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
?>
<?php wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME ); ?>
<div class="form-field">
  <label for="jpid_product_type"><?php esc_html_e( 'Type', 'jpid' ); ?></label>
  <select id="jpid_product_type" name="jpid_product_type" class="jpid-field-select-short">
    <?php
      $product_types = jpid_get_product_type_terms();
    ?>
    <?php foreach ( $product_types as $product_type ) : ?>
      <option value="<?php esc_attr_e( $product_type->term_id );  ?>"><?php esc_html_e( $product_type->name ); ?></option>
    <?php endforeach; ?>
  </select>
  <p class="description"><?php esc_html_e( 'The product type this category belongs to.', 'jpid' ) ?></p>
</div>
