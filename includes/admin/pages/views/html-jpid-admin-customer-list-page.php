<?php
/**
 * Customer list page.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/pages/views
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
?>
<div class="wrap">
  <h1>
    <?php esc_html_e( 'Customers', 'jpid' ); ?>
    <a href="<?php echo admin_url() . 'admin.php?page=' . JPID_Admin_Page_Customer_Edit::SLUG ?>" class="page-title-action"><?php esc_html_e( 'Add Customer', 'jpid' ); ?></a>
    <?php if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) : ?>
      <span class="subtitle"><?php echo esc_html( 'Search results for ') . $_GET['s']; ?></span>
    <?php endif; ?>
  </h1>
  <?php jpid_print_notices(); ?>
  <form id="jpid_customers_form" method="get" action="">
    <input type="hidden" name="page" value="<?php echo JPID_Admin_Page_Customer_List::SLUG ?>" />
    <p class="search-box">
      <?php $s = ! empty( $_GET['s'] ) ? $_GET['s'] : ''; ?>
      <label for="post-search-input" class="screen-reader-text"><?php esc_html_e( 'Search Customers:', 'jpid' ); ?></label>
      <input type="search" id="post-search-input" name="s" value="<?php echo $s; ?>">
      <input type="submit" id="search-submit" class="button" value="<?php esc_attr_e( 'Search Customers', 'jpid' );?>">
    </p>
    <?php
      $this->customers_table->prepare_items();
      $this->customers_table->display();
    ?>
  </form>
</div>
