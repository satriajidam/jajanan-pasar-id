<?php
/**
 * Quick-edit category field view for product list screen.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/admin/screens/products/views
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
?>
<?php
  $current_type = isset( $current_type ) ? $current_type : 0;

  $product_categories = get_terms( array(
    'taxonomy' => 'jpid_product_category',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC',
    'meta_key' => 'jpid_product_type',
    'meta_value' => $current_type
  ) );
?>
<?php if ( ! empty( $product_categories ) ) : ?>
  <select id="jpid_product_category"  name="jpid_product_category" class="jpid-field-select-short">
    <?php foreach ( $product_categories as $product_category ) : ?>
      <option value="<?php esc_attr_e( $product_category->term_id );  ?>"><?php esc_html_e( $product_category->name ); ?></option>
    <?php endforeach; ?>
  </select>
<?php else : ?>
  <select id="jpid_product_category"  name="jpid_product_category" class="jpid-field-select-short" disabled>
    <option><?php esc_html_e( 'No category found', 'jpid' ); ?></option>
  </select>
<?php endif; ?>
