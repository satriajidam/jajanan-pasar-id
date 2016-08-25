<?php
/**
 * Product data meta-box view.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/admin/screens/products/views
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
?>
<?php wp_nonce_field( JPID_Screen_Product_Edit::NONCE_ACTION, JPID_Screen_Product_Edit::NONCE_NAME ); ?>
<div class="jpid-field-container">
  <div class="jpid-label-wrapper">
    <label for="jpid_product_price" class="jpid-label-wrapper__label">
      <?php esc_html_e( 'Product Price', 'jpid' ); ?>
    </label>
  </div>
  <div class="jpid-field-wrapper">
    <?php $current_price = ! empty( $product->get_price() ) ? $product->get_price() : ''; ?>
    <input type="number" id="jpid_product_price" name="jpid_product_price" class="jpid-field-wrapper__field" value="<?php esc_attr_e( $current_price ); ?>" placeholder="<?php esc_attr_e( 'e.g. 3500', 'jpid' ); ?>" />
    <p class="description"><?php esc_html_e( 'Insert product price without the currency prefix.', 'jpid' ); ?></p>
  </div>
</div>
<div class="jpid-field-container">
  <div class="jpid-label-wrapper">
    <label for="jpid_product_ingredients" class="jpid-label-wrapper__label">
      <?php esc_html_e( 'Product Ingredients', 'jpid' ); ?>
    </label>
  </div>
  <div class="jpid-field-wrapper">
    <?php $current_ingredients = ! empty( $product->get_ingredients() ) ? $product->get_ingredients() : ''; ?>
    <textarea type="text" id="jpid_product_ingredients" name="jpid_product_ingredients" class="jpid-field-wrapper__field" placeholder="<?php esc_attr_e( 'e.g. Chicken Meat, Cucumber, Carrot, Mayonnaise', 'jpid' ); ?>"><?php esc_html_e( $current_ingredients ); ?></textarea>
    <p class="description"><?php esc_html_e( 'Insert ingredients using comma separated list.', 'jpid' ); ?></p>
  </div>
</div>
<div class="jpid-field-container">
  <div class="jpid-label-wrapper">
    <label for="jpid_product_type" class="jpid-label-wrapper__label">
      <?php esc_html_e( 'Product Type', 'jpid' ); ?>
    </label>
  </div>
  <div class="jpid-field-wrapper">
    <?php
      $product_types = get_terms( array(
        'taxonomy' => 'jpid_product_type',
        'hide_empty' => false,
        'orderby' => 'id',
        'order' => 'ASC'
      ) );

      $current_type = ! empty( $product->get_type_id() ) ? $product->get_type_id() : $product_types[0]->term_id;
    ?>
    <select id="jpid_product_type" name="jpid_product_type" class="jpid-field-wrapper__field">
      <?php foreach ( $product_types as $product_type ) : ?>
        <option value="<?php esc_attr_e( $product_type->term_id );  ?>" <?php selected( $current_type, $product_type->term_id, true ); ?>><?php esc_html_e( $product_type->name ); ?></option>
      <?php endforeach; ?>
    </select>
    <p class="description"><?php esc_html_e( 'Please select the type of this product.', 'jpid' ); ?></p>
  </div>
</div>
<div class="jpid-field-container">
  <div class="jpid-label-wrapper">
    <label for="jpid_product_category" class="jpid-label-wrapper__label">
      <?php esc_html_e( 'Product Category', 'jpid' ); ?>
    </label>
  </div>
  <div class="jpid-field-wrapper">
    <!-- Separate the view for this field in different file so we can use it in AJAX call. -->
    <?php include JPID_PLUGIN_DIR . 'admin/screens/products/views/html-jpid-meta-box-product-data-category-field.php'; ?>
  </div>
</div>
