<?php
/**
 * Customer edit page - Customer details.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/pages/views
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
?>
<div id="jpid_customer_details" class="postbox">
  <div class="inside">
    <?php wp_nonce_field( JPID_Admin_Customer_Actions::NONCE_ACTION, JPID_Admin_Customer_Actions::NONCE_NAME ); ?>
    <div class="panel-wrap">
      <div class="jpid-panel panel">
        <div class="jpid-header-container">
          <?php if ( $is_update ) : ?>
            <?php echo get_avatar( $this->customer->get_email(), 60 ); ?>
            <?php $header = esc_html( 'Customer', 'jpid' ) . ' #' . $this->customer->get_id() . ' ' . esc_html( 'details', 'jpid' ); ?>
            <h2><?php echo $header; ?></h2>
            <p id="jpid_order_value" class=""><?php echo jpid_to_rupiah( $this->customer->get_order_value() ); ?></p>
            <p id="jpid_order_count" class=""><?php echo sprintf( _n( '1 order', '%s orders', $this->customer->get_order_count(), 'jpid' ), $this->customer->get_order_count() ); ?></p>
          <?php else : ?>
            <h2>
              <?php
                $header = esc_html( 'Customer', 'jpid' ) . ' #' . jpid_next_customer_id() . ' ' . esc_html( 'details', 'jpid' );

                echo $header;
              ?>
  					</h2>
          <?php endif; ?>
        </div>
        <div class="jpid-field-container">
          <div class="jpid-label-wrapper">
            <label for="jpid_user_id" class="jpid-label-wrapper__label">
              <?php esc_html_e( 'User Account', 'jpid' ); ?>
            </label>
          </div>
          <div class="jpid-field-wrapper">
            <select id="jpid_user_id" name="jpid_user_id" class="jpid-field-wrapper__field" style="width: 100%;">
              <?php if ( $is_registered ) : ?>
                <option value="<?php echo $this->customer->get_user_id(); ?>" selected>#<?php echo $this->customer->get_id(); ?> - <?php echo $this->customer->get_name(); ?> (<?php echo $this->customer->get_email(); ?>)</option>
              <?php endif; ?>
            </select>
            <?php if ( $is_registered ) : ?>
              <a href="user-edit.php?user_id=<?php esc_attr_e( $this->customer->get_user_id() ); ?>" class="" style="float: right; margin-top: 0.5em;"><?php esc_html_e( 'View User Profile', 'jpid' ); ?></a>
            <?php endif; ?>
          </div>
        </div>
        <div class="jpid-field-container">
          <div class="jpid-label-wrapper">
            <label for="jpid_customer_name" class="jpid-label-wrapper__label">
              <?php esc_html_e( 'Full Name', 'jpid' ); ?>
            </label>
          </div>
          <div class="jpid-field-wrapper">
            <input type="text" id="jpid_customer_name" name="jpid_customer_name" class="jpid-field-wrapper__field <?php if ( $is_registered ) echo 'disabled'; ?>" value="<?php esc_attr_e( $this->customer->get_name() ); ?>" <?php if ( $is_registered ) echo 'readonly'; ?> />
          </div>
        </div>
        <div class="jpid-field-container">
          <div class="jpid-label-wrapper">
            <label for="jpid_customer_email" class="jpid-label-wrapper__label jpid-label-required">
              <?php esc_html_e( 'Email Address', 'jpid' ); ?>
            </label>
          </div>
          <div class="jpid-field-wrapper">
            <input type="email" id="jpid_customer_email" name="jpid_customer_email" class="jpid-field-wrapper__field <?php if ( $is_registered ) echo 'disabled'; ?>" value="<?php esc_attr_e( $this->customer->get_email() ); ?>" <?php if ( $is_registered ) echo 'readonly'; ?> required="required" />
          </div>
        </div>
        <div class="jpid-field-container">
          <div class="jpid-label-wrapper">
            <label for="jpid_customer_phone" class="jpid-label-wrapper__label">
              <?php esc_html_e( 'Phone Number', 'jpid' ); ?>
            </label>
          </div>
          <div class="jpid-field-wrapper">
            <input type="text" id="jpid_customer_phone" name="jpid_customer_phone" class="jpid-field-wrapper__field" value="<?php esc_attr_e( $this->customer->get_phone() ); ?>" />
          </div>
        </div>
        <div class="jpid-field-container">
          <div class="jpid-label-wrapper">
            <label for="jpid_customer_address" class="jpid-label-wrapper__label">
              <?php esc_html_e( 'Street Address', 'jpid' ); ?>
            </label>
          </div>
          <div class="jpid-field-wrapper">
            <textarea type="text" id="jpid_customer_address" name="jpid_customer_address" class="jpid-field-wrapper__field"><?php esc_html_e( $this->customer->get_address() ); ?></textarea>
          </div>
        </div>
        <div class="jpid-field-container">
          <div class="jpid-label-wrapper">
            <label for="jpid_customer_province" class="jpid-label-wrapper__label">
              <?php esc_html_e( 'Province', 'jpid' ); ?>
            </label>
          </div>
          <div class="jpid-field-wrapper">
            <?php
              $locations         = jpid_get_provinces();
              $provinces         = array_keys( $locations );
              $selected_province = $this->customer->get_province();
            ?>
            <select id="jpid_customer_province" name="jpid_customer_province" class="jpid-field-wrapper__field">
              <option value="">- <?php esc_html_e( 'Select Province', 'jpid' ); ?> -</option>
              <?php foreach ( $provinces as $province ) : ?>
                <option value="<?php esc_attr_e( $province );  ?>" <?php selected( $selected_province, $province, true ); ?>><?php esc_html_e( $province ); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="jpid-field-container">
          <div class="jpid-label-wrapper">
            <label for="jpid_customer_city" class="jpid-label-wrapper__label">
              <?php esc_html_e( 'District/City', 'jpid' ); ?>
            </label>
          </div>
          <div class="jpid-field-wrapper">
            <?php
              $cities        = ! empty( $selected_province ) ? $locations[ $selected_province ] : array();
              $selected_city = $this->customer->get_city();
            ?>
            <select id="jpid_customer_city" name="jpid_customer_city" class="jpid-field-wrapper__field">
              <?php if ( $selected_province === '' ) : ?>
                <option value="">- <?php esc_html_e( 'Select City', 'jpid' ); ?> -</option>
              <?php else : ?>
                <?php foreach ( $cities as $city ) : ?>
                  <option value="<?php esc_attr_e( $city );  ?>" <?php selected( $selected_city, $city, true ); ?>><?php esc_html_e( $city ); ?></option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
