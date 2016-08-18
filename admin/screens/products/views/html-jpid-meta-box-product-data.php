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
<div class="jpid-field-container">
  <div class="jpid-label-wrapper">
    <label for="jpid_product_price" class="jpid-label-wrapper__label">
      <?php esc_html_e( 'Product Price', 'jpid' ); ?>
    </label>
  </div>
  <div class="jpid-field-wrapper">
    <input type="number" id="jpid_product_price" name="jpid_product_price" class="jpid-field-wrapper__field" value="" placeholder="<?php esc_attr_e( 'e.g. 3500', 'jpid' ); ?>" />
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
    <input type="text" id="jpid_product_ingredients" name="jpid_product_ingredients" class="jpid-field-wrapper__field" value="" placeholder="<?php esc_attr_e( 'e.g. Chicken Meat, Cucumber, Carrot, Mayonnaise', 'jpid' ); ?>" />
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
  </div>
</div>
<div class="jpid-field-container">
  <div class="jpid-label-wrapper">
    <label for="jpid_product_category" class="jpid-label-wrapper__label">
      <?php esc_html_e( 'Product Category', 'jpid' ); ?>
    </label>
  </div>
  <div class="jpid-field-wrapper">
  </div>
</div>
