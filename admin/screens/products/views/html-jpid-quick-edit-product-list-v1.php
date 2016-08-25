<?php
/**
 * Quick-edit view for product list screen.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/admin/screens/products/views
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
?>
<fieldset class="inline-edit-col-right" style="margin-top: 0;">
  <div class="inline-edit-col">
    <?php wp_nonce_field( JPID_Admin_Product_List::NONCE_ACTION, JPID_Admin_Product_List::NONCE_NAME ); ?>
    <label>
      <span class="title"><?php esc_html_e( 'Price', 'jpid' ) ?></span>
      <span class="input-product-data">
        <input type="number" id="jpid_product_price" name="jpid_product_price" class="jpid-field-input-short" />
      </span>
    </label>
    <label>
      <span class="title"><?php esc_html_e( 'Type', 'jpid' ) ?></span>
      <span class="input-product-data">
        <select id="jpid_product_type" name="jpid_product_type" class="jpid-field-select-short">
          <?php
            $product_types = get_terms( array(
              'taxonomy' => 'jpid_product_type',
              'hide_empty' => false,
              'orderby' => 'id',
              'order' => 'ASC'
            ) );
          ?>
          <?php foreach ( $product_types as $product_type ) : ?>
            <option value="<?php esc_attr_e( $product_type->term_id );  ?>"><?php esc_html_e( $product_type->name ); ?></option>
          <?php endforeach; ?>
        </select>
      </span>
    </label>
    <label>
      <span class="title"><?php esc_html_e( 'Category', 'jpid' ) ?></span>
      <span class="input-product-data input-product-category">
        <!-- Separate the view for this field in different file so we can use it in AJAX call. -->
      </span>
    </label>
  </div>
</fieldset>
