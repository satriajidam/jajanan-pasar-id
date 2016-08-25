(function($) {
	'use strict';

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   */

  /**
   * Module: Product Category Quick Edit.
   */
  var productCategoryQuickEdit = function () {
    $('#the-list').on('click', '.editinline', function() {
      // Get the term object containing the inline datas.
      let termObject = $(this).closest('tr');

      // Get the value of all inline datas.
      let productType = termObject.find('.column-type').text();

      // Set value of all custom quick edit fields.
      $('select[name="jpid_product_type"] option:selected').attr('selected', false).change();
      $('select[name="jpid_product_type"] option:contains("' + productType + '")').attr('selected', 'selected').change();
    });
  };

	/**
	 * Module: Product List.
	 */
	var productList = function () {
		$('#the-list').on('click', '.editinline', function() {
			inlineEditPost.revert();

			let postID          = $(this).closest('tr').attr('id').replace('post-', '');
			let JPIDInlineData  = $('#jpid_inline_' + postID);
			let productPrice    = JPIDInlineData.find('.jpid_product_price').text();
			let productType     = JPIDInlineData.find('.jpid_product_type').text();
			let productCategory = JPIDInlineData.find('.jpid_product_category').text();

			$('input[name="jpid_product_price"]').val(productPrice);

			$('select[name="jpid_product_type"] option:selected').attr('selected', false).change();
			$('select[name="jpid_product_type"] option[value="' + productType + '"]').attr('selected', 'selected').change();

			activateCategorySelector(productType, productCategory);

			$('#jpid_product_type').on('change', function (event) {
				let currentType = $(this).val();

				activateCategorySelector(currentType, productCategory);
			});

			$('.inline-edit-save .cancel').on('click', function (event) {
				$('#jpid_product_type').off('change');
			});
		});

		function switchCategorySelector(displaySelector, hideSelector, productCategory) {
			$('#' + displaySelector).removeClass('hidden');
			$('#' + hideSelector).addClass('hidden');

			$('select[name="' + displaySelector + '"] option:selected').attr('selected', false).change();
			$('select[name="' + displaySelector + '"] option[value="' + productCategory + '"]').attr('selected', 'selected').change();
		}

		function activateCategorySelector(productType, productCategory) {
			switch (productType) {
				case jpid_admin.snack_term_id:
					switchCategorySelector('jpid_product_category_snack', 'jpid_product_category_drink', productCategory);
					break;
				case jpid_admin.drink_term_id:
					switchCategorySelector('jpid_product_category_drink', 'jpid_product_category_snack', productCategory);
					break;
			}
		}
	};

	/**
	 * Module: Product Edit.
	 */
	var productEdit = function () {
		// Prepare ajax data to be sent.
		let data = {
			action: 'load_product_categories',
			security: jpid_admin.load_product_categories_nonce,
			current_post: jpid_admin.post_id
		};

		// Send ajax when product type selector is changed.
		$('#jpid_product_type').on('change', function (event) {
			data.current_type = $(this).val();

			$.post(jpid_admin.ajax_url, data, function (response) {
				$('#jpid_product_category').parent().html(response);
			});
		});
	};

	/**
	 * Run when document has been fully loaded.
	 */
	$(function () {
		console.log(jpid_admin);

		switch (jpid_admin.screen_id) {
			case 'edit-jpid_product_category':
				productCategoryQuickEdit();
				break;
			case 'jpid_product':
				productEdit();
				break;
			case 'edit-jpid_product':
				productList();
				break;
			default:
				break;
		}
	})

})(jQuery);
