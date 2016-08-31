(function($) {
	'use strict';

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   */
	let JPIDAdmin = {

		/**
		 * Module: Product Category Quick Edit.
		 */
		productCategoryQuickEdit: function () {
	    $('#the-list').on('click', '.editinline', function() {
	      // Get the term object containing the inline datas.
	      let termObject = $(this).closest('tr');

	      // Get the value of all inline datas.
	      let productType = termObject.find('.column-type').text();

	      // Set value of all custom quick edit fields.
	      $('select[name="jpid_product_type"] option:selected').attr('selected', false).change();
	      $('select[name="jpid_product_type"] option:contains("' + productType + '")').attr('selected', 'selected').change();
	    });
	  },

		/**
		 * Module: Product List Quick Edit.
		 */
		productListQuickEdit: function () {
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

				$('#jpid_product_type').on('change', function (evt) {
					let currentType = $(this).val();

					activateCategorySelector(currentType, productCategory);
				});

				$('.inline-edit-save .cancel').on('click', function (evt) {
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
		},

		/**
		 * Module: Product Edit.
		 */
		productEdit: function () {
			// Prepare ajax data to be sent.
			let data = {
				action: 'load_product_categories_display',
				security: jpid_admin.load_product_categories_display_nonce,
				current_post: jpid_admin.post_id
			};

			// Send ajax when product type selector is changed.
			$('#jpid_product_type').on('change', function (evt) {
				data.current_type = $(this).val();

				$.post(jpid_admin.ajax_url, data, function (response) {
					$('#jpid_product_category').parent().html(response);
				});
			});

			$('#post').submit(function (evt) {
				$('.jpid-required-field').each(function () {
					if ($(this).val() === '') {
						evt.preventDefault();
						$(this).addClass('jpid-field-error');
					}
				})
			});
		},

		/**
		 * Module: Admin Settings.
		 */
		adminSettings: function () {
			// Get current tab
			let activeTab  = this.getQueryStringByName('tab') ? this.getQueryStringByName('tab') : 'general';

			if (activeTab === 'general') {
				let orderFullStatus       = $('#jpid_order_full_status');
				let orderAvailableDate    = $('#jpid_order_available_date');
				let orderAvailableDateRow = orderAvailableDate.closest('tr');

		    if (orderFullStatus.is(':checked')) {
		      orderAvailableDateRow.removeClass('hidden').change();
		    } else {
		      orderAvailableDateRow.addClass('hidden').change();
		    }

		    orderFullStatus.on('change', function (evt) {
		      orderAvailableDateRow.toggleClass('hidden');

		      if (!$(this).is(':checked')) {
		        orderAvailableDate.val('');
		      }
		    });

		    orderAvailableDate.datepicker({
		      showButtonPanel: true,
		      dateFormat: $(this).data('dateformat') ? $(this).data('dateformat') : 'dd-mm-yy',
		      minDate: new Date()
		    });

		    orderAvailableDate.on('keypress', function (evt) {
		        evt.preventDefault();
		    });
			}

			if (activeTab === 'delivery') {
				// TODO: do something...
			}

			if (activeTab === 'payment') {
				// TODO: do something...
			}
		},

		/**
		 * Helper: Get query string value based on its name.
		 */
		getQueryStringByName: function (name, url) {
			if (!url) {
				url = window.location.href;
			}

			name = name.replace(/[\[\]]/g, "\\$&");

			let regex   = new RegExp("[?&]" + name + "( =([^&#]*)|&|#|$)");
			let results = regex.exec(url);

			if (!results) {
				return null;
			}

			if (!results[2]) {
				return '';
			}

			return decodeURIComponent(results[2].replace(/\+/g, ' '));
		}

	};

	/**
	 * Run when document has been fully loaded.
	 */
	$(function () {
		switch (jpid_admin.screen_id) {
			case 'edit-jpid_product_category':
				JPIDAdmin.productCategoryQuickEdit();
				break;
			case 'edit-jpid_product':
				JPIDAdmin.productListQuickEdit();
				break;
			case 'jpid_product':
				JPIDAdmin.productEdit();
				break;
			case 'jajanan-pasar_page_jpid-settings':
				JPIDAdmin.adminSettings();
				break;
			default:
				break;
		}
	});

})(jQuery);
