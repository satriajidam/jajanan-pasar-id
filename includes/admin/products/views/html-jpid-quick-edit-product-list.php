<?php
/**
 * Quick-edit view for product list screen.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/products/views
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
        <select id="jpid_product_category_snack"  name="jpid_product_category_snack" class="jpid-field-select-short">
          <?php
            $snack_categories = jpid_get_product_category_terms( $product_types[0]->term_id );
          ?>
          <?php if ( ! empty( $snack_categories ) ) : ?>
            <?php foreach ( $snack_categories as $snack_category ) : ?>
              <option value="<?php esc_attr_e( $snack_category->term_id );  ?>"><?php esc_html_e( $snack_category->name ); ?></option>
            <?php endforeach; ?>
          <?php else : ?>
            <option><?php esc_html_e( 'No category found', 'jpid' ); ?></option>
          <?php endif; ?>
        </select>
        <select id="jpid_product_category_drink"  name="jpid_product_category_drink" class="jpid-field-select-short hidden">
          <?php
            $drink_categories = jpid_get_product_category_terms( $product_types[1]->term_id );
          ?>
          <?php if ( ! empty( $drink_categories ) ) : ?>
            <?php foreach ( $drink_categories as $drink_category ) : ?>
              <option value="<?php esc_attr_e( $drink_category->term_id );  ?>"><?php esc_html_e( $drink_category->name ); ?></option>
            <?php endforeach; ?>
          <?php else : ?>
            <option><?php esc_html_e( 'No category found', 'jpid' ); ?></option>
          <?php endif; ?>
        </select>
      </span>
    </label>
  </div>
</fieldset>
