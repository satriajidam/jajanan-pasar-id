<?php
/**
 * Meta-field view for edit product category screen.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/product/views
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
?>
<?php wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME ); ?>
<tr class="form-field term-type-wrap">
  <th scope="row">
    <label for="jpid_product_type"><?php esc_html_e( 'Type', 'jpid' ); ?></label>
  </th>
  <td>
    <select id="jpid_product_type" name="jpid_product_type" class="jpid-field-select-short">
      <?php
        $product_types = jpid_get_product_type_terms();
        $current_type  = get_term_meta( $term->term_id, 'jpid_product_type', true );
      ?>
      <?php foreach ( $product_types as $product_type ) : ?>
        <option value="<?php esc_attr_e( $product_type->term_id );  ?>" <?php selected( $current_type, $product_type->term_id, true ); ?>><?php esc_html_e( $product_type->name ); ?></option>
      <?php endforeach; ?>
    </select>
    <p class="description"><?php esc_html_e( 'The product type this category belongs to.', 'jpid' ) ?></p>
  </td>
</tr>
