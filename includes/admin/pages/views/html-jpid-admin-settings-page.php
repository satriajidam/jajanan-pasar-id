<?php
/**
 * Admin settings page view.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/admin/pages/views
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */
?>
<div class="wrap">
  <?php
    settings_errors();

    $settings_page = $this->get_slug();
    $settings_tabs = $this->get_tabs();
    $active_tab    = isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $settings_tabs ) ? $_GET['tab'] : 'general';
  ?>
  <h1 class="nav-tab-wrapper">
    <?php foreach ( $settings_tabs as $tab => $tab_options ) : ?>
      <a href="?page=<?php esc_attr_e( $settings_page ); ?>&amp;tab=<?php esc_attr_e( $tab ); ?>" class="nav-tab <?php echo ( $active_tab === $tab ) ? 'nav-tab-active' : ''; ?>" ><?php echo esc_html( $tab_options['title'] ); ?></a>
    <?php endforeach; ?>
  </h1>
  <form method="post" action="options.php">
    <table class="form-table">
      <?php
        settings_fields( $settings_tabs[ $active_tab ]['group'] );
        do_settings_sections( $settings_tabs[ $active_tab ]['group'] );
      ?>
    </table>
    <?php submit_button(); ?>
  </form>
</div>
