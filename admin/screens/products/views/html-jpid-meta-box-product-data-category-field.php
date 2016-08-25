<?php
/**
 * Product data meta-box category field view.
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
  <?php
    $current_category = ! empty( $product->get_category_id() ) ? $product->get_category_id() : $product_categories[0]->term_id;
  ?>
  <select id="jpid_product_category"  name="jpid_product_category" class="jpid-field-wrapper__field">
    <?php foreach ( $product_categories as $product_category ) : ?>
      <option value="<?php esc_attr_e( $product_category->term_id );  ?>" <?php selected( $current_category, $product_category->term_id, true ); ?>><?php esc_html_e( $product_category->name ); ?></option>
    <?php endforeach; ?>
  </select>
  <p class="description"><?php _e( 'Please select the category of this product.', 'jpid' ); ?></p>
<?php else : ?>
  <p class="description"><?php _e( 'Can\'t find any category in current product type.', 'jpid' ); ?></p>
<?php endif; ?>
