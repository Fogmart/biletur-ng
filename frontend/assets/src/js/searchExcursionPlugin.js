(function ($) {
	'use strict';
	$.fn.searchExcursionPlugin = function () {
		const SHOW_MORE_BUTTON = $('.btn-show-more');
		const LOAD_URL = $('.load-excursion-url').data('url');
		const SEARCH_FORM = $('#w0');
		const LOADING_WIDGET = $('.loading-widget');
		const BLOCK_PANEL = $('.block-panel');

		let PAGE_INPUT = $('#searchform-page');
		let PRICE_SLIDER_INPUT = $('#searchform-pricerange');
		let TIME_SLIDER_INPUT = $('#searchform-timerange');
		let SORT_TYPE_CONTROLS = $('.sort-type');
		let SORT_TYPE_HIDDEN_INPUT = $('#searchform-sorttype');

		const methods = {
			init: function () {
				//Восстановление значений к инпутах слайдера цены
				if (PRICE_SLIDER_INPUT.length) {
					let priceRange = PRICE_SLIDER_INPUT.val().split(',');
					$('#price-min').val(priceRange[0]);
					$('#price-max').val(priceRange[1]);
				}

				//Восстановление значений к инпутах слайдера длительности
				if (TIME_SLIDER_INPUT.length) {
					let timeRange = TIME_SLIDER_INPUT.val().split(',');
					$('#time-min').val(timeRange[0]);
					$('#time-max').val(timeRange[1]);
				}

				$('.tag').click(function () {
					if ($(this).hasClass('active')) {
						$('#searchform-citytag').val('');
						$('#searchform-page').val(1);
					} else {
						$('#searchform-citytag').val($(this).data('id'));
						$('#searchform-page').val(1);
					}

					SEARCH_FORM.submit();

					return false;
				});

				$('#search-button').click(function () {
					$('#searchform-page').val(1);
					$('#searchform-citytag').val('');

					return true;
				});

				//Нажатие на тип сортировки
				SORT_TYPE_CONTROLS.click(function () {
					SORT_TYPE_HIDDEN_INPUT.val($(this).data('id'));
					PAGE_INPUT.val(1);
					SEARCH_FORM.submit();
				});

				SHOW_MORE_BUTTON.unbind('click');

				SHOW_MORE_BUTTON.click(function () {
					SHOW_MORE_BUTTON.hide();
					LOADING_WIDGET.show();
					BLOCK_PANEL.addClass('process');
					PAGE_INPUT.val(parseInt(PAGE_INPUT.val(), 10) + 1);
					$.ajax({
						url: LOAD_URL,
						method: "POST",
						async: true,
						data: SEARCH_FORM.serialize()
					}).done(function (data) {
						LOADING_WIDGET.hide();
						BLOCK_PANEL.removeClass('process');
						if (data !== false) {
							SHOW_MORE_BUTTON.show();
							$('.excursion-list').append(data);
							$('.excursion-block').fadeIn();
							$('input[name="rating"]').rating(
								{
									"displayOnly": true,
									'showCaption': false,
									"size": "xs"
								}
							);
						}
					});
				});
			}
		};
		methods.init();
	};
})(jQuery);