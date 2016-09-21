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

			function switchCategorySelector(displaySelectorID, hideSelectorID, productCategory) {
				$('#' + displaySelectorID).removeClass('hidden');
				$('#' + hideSelectorID).addClass('hidden');

				$('select[name="' + displaySelectorID + '"] option:selected').attr('selected', false).change();
				$('select[name="' + displaySelectorID + '"] option[value="' + productCategory + '"]').attr('selected', 'selected').change();
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

			// Change product category selector when product type selector is changed.
			$('#jpid_product_type').on('change', function (evt) {
				data.current_type = $(this).val();

				$.post(jpid_admin.ajax_url, data, function (response) {
					$('#jpid_product_category').parent().html(response);
				});
			});
		},

		/**
		 * Module: Admin Settings.
		 */
		adminSettings: function () {
			// Get current tab
			let activeTab  = this.getQueryStringByName('tab') ? this.getQueryStringByName('tab') : 'general';

			if (activeTab === 'general') {
		    $('#jpid_order_available_date').datepicker({
		      showButtonPanel: true,
		      dateFormat: $(this).data('dateformat') ? $(this).data('dateformat') : 'dd-mm-yy',
		      minDate: new Date()
		    });

		    $('#jpid_order_available_date').on('keypress', function (evt) {
		      evt.preventDefault();
		    });

				$('#jpid_clear_date').on('click', function (evt) {
					$('#jpid_order_available_date').val('');

					evt.preventDefault();
				})
			}

			if (activeTab === 'delivery') {
				// Time picker
				$('#jpid_delivery_hours_start, #jpid_delivery_hours_end').datetimepicker({
					controlType: 'select',
					oneLine: true,
					timeFormat: $(this).data('timeformat') ? $(this).data('timeformat') : 'HH:mm',
					timeOnly: true,
					timeText: 'Hour : Minutes'
				});

				$('#jpid_delivery_hours_start, #jpid_delivery_hours_end').on('keypress', function (evt) {
					evt.preventDefault();
				});

				// Sortable location table
				$('#jpid_locations_container').sortable({
					items: 'table',
					cursor: 'move',
					axis: 'y',
					scrollSensitivity: 40,
					forcePlaceholderSize: true,
					placeholder: 'jpid-location-table-placeholder',
					start: function (evt, ui) {
						ui.item.css('background-color', '#f6f6f6');
					},
					stop: function (evt, ui) {
						ui.item.removeAttr('style');
						resetLocationsOrder();
					}
				});

				// Insert new location table
				$('#jpid_add_location').on('click', function (evt) {
					let table = $('#jpid_locations_container table:last');
					let clone = table.clone();

					clone.find('td select').val('');
					clone.find('td textarea').val('');

					clone.insertAfter(table);

					resetLocationsOrder();
				});

				// Remove new location table
				$('#jpid_locations_container').on('click', '.jpid-remove-location', function (evet) {
					if (confirm(jpid_admin.remove_location)) {
						let locations = $('#jpid_locations_container table');
						let count     = locations.length;

						if (count <= 1) {
							$('#jpid_locations_container select').val('');
							$('#jpid_locations_container textarea').val('');
						} else {
							$(this).closest('table').remove();
						}

						resetLocationsOrder();
					}
				});
			}

			if (activeTab === 'payment') {
				// Sortable account
				$('#jpid_accounts_table tbody').sortable({
					items: 'tr',
					cursor: 'move',
					axis: 'y',
					scrollSensitivity: 40,
					stop: function (evt, ui) {
						resetAccountsOrder();
					}
				});

				// Selected account
				$('#jpid_accounts_table tbody').on('focus', 'input', function (evt) {
					$('tr.ui-sortable-handle').removeClass('current');

					$(this).closest('tr.ui-sortable-handle').addClass('current');
				});

				// Add new account
				$('#jpid_add_account').on('click', function (evt) {
					let row   = $('#jpid_accounts_table tbody tr:last');
					let clone = row.clone();

					clone.find('td input').val('');
					clone.removeClass('current');

					clone.insertAfter(row);

					resetAccountsOrder();
				});

				// Remove selected account
				$('#jpid_remove_account').on('click', function (evt) {
					let selected = $('#jpid_accounts_table tbody').find('tr.current');

					if (selected.length <= 0) {
						return;
					}

					if (confirm(jpid_admin.remove_account)) {
						let accounts = $('#jpid_accounts_table tbody tr');
						let count    = accounts.length;

						if (count <= 1) {
							$('#jpid_accounts_table tbody').find('td input').val('');
						} else {
							selected.remove();
						}

						resetAccountsOrder();
					}
				});
			}

			function resetSortableItemsOrder(containerSelector, itemSelector, fieldSelectorArr) {
				let container = $(containerSelector);
				let items     = container.find(itemSelector);
				let fields    = fieldSelectorArr.join(',');

				items.each(function (itemIndex) {
					$(this).find(fields).each(function (fieldIndex) {
						let name = $(this).attr('name');
						name = name.replace(/\[(\d+)\]/, '[' + itemIndex + ']');
						$(this).attr('name', name);
					});
				});
			}

			function resetLocationsOrder() {
				resetSortableItemsOrder('#jpid_locations_container', 'table', ['select', 'textarea']);
			}

			function resetAccountsOrder() {
				resetSortableItemsOrder('#jpid_accounts_table tbody', 'tr', ['input']);
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

			let regex   = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)");
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
