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

			$('.input-product-category').empty();

			let data = {
				action: 'load_product_categories',
				security: jpid_admin.load_product_categories_nonce,
				current_screen: jpid_admin.screen_id,
				current_type: productType
			};

			let loadCategoriesSelector = function (data, productCategory) {
				$.post(jpid_admin.ajax_url, data, function (response) {
					$('.input-product-category').html(response);

					$('select[name="jpid_product_category"] option:selected').attr('selected', false).change();
					$('select[name="jpid_product_category"] option[value="' + productCategory + '"]').attr('selected', 'selected').change();
				});
			}

			loadCategoriesSelector(data, productCategory);

			$('#jpid_product_type').on('change', function (event) {
				data.current_type = $(this).val();

				loadCategoriesSelector(data, productCategory);
			});

			$('.inline-edit-save .cancel').on('click', function (event) {
				$('#jpid_product_type').off('change');
			});
		});
	};

	/**
	 * Module: Product Edit.
	 */
	var productEdit = function () {
		// Prepare ajax data to be sent.
		let data = {
			action: 'load_product_categories',
			security: jpid_admin.load_product_categories_nonce,
			current_screen: jpid_admin.screen_id,
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
