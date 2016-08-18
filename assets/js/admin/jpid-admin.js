(function($) {
	'use strict';

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   */

  /**
   * Module: Product Category Quick Edit
   */
  let productCategoryQuickEdit = function () {
    $(document).ready(function () {
      $('#the-list').on('click', '.editinline', function() {
        // Get the term object containing the inline datas
        var termObject = $(this).closest('tr');

        // Get the value of all inline datas
        var productType = termObject.find('.column-type').text();

        // Set value of all custom quick edit fields
        $('select[name="jpid_product_type"] option:selected').attr('selected', false).change();
        $('select[name="jpid_product_type"] option:contains("' + productType + '")').attr('selected', 'selected').change();
      });
    });
  };

  // Run module based on current active screen.
  switch (jpid_admin.screen_id) {
    case 'edit-jpid_product_category':
      productCategoryQuickEdit();
      break;
    default:
      break;
  }

})(jQuery);
