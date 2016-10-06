<?php
/**
 * Quick-edit view for product list screen.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/post-types/views
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
            $product_types = jpid_get_product_type_terms();
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
        <?php foreach ( $product_types as $product_type ) : ?>
          <?php
            $type_name  = strtolower( $product_type->name );
            $categories = jpid_get_product_category_terms( $product_type->term_id );
          ?>
          <select id="jpid_product_category_<?php esc_attr_e( $type_name ); ?>" name="jpid_product_category_<?php esc_attr_e( $type_name ); ?>" class="jpid-field-select-short">
            <?php if ( ! empty( $categories ) ) : ?>
              <?php foreach ( $categories as $category ) : ?>
                <option value="<?php esc_attr_e( $category->term_id );  ?>"><?php esc_html_e( $category->name ); ?></option>
              <?php endforeach; ?>
            <?php else: ?>
              <option><?php esc_html_e( 'No category found', 'jpid' ); ?></option>
            <?php endif; ?>
          </select>
        <?php endforeach; ?>
      </span>
    </label>
  </div>
</fieldset>
