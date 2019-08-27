(function ($) {
	$.fn.searchTourPlugin = function () {
		var LOADING_WIDGET = $('.loading-widget');
		var BLOCK_PANEL = $('.block-panel');
		var BLOCK_PANEL_RESULT_LIST = $('.block-panel .result .list');
		var FORM = $('#w0');
		var WAY_POINT_FILTER = $('.way-point-tag');
		var WAY_POINT_INPUT = $('#searchform-tourto');
		var COUNT_INPUT = $('#searchform-count');
		var LOAD_TOUR_URL = $('.load-tour-url').data('url');
		var RESULT_BLOCK = $('.result');
		var TOUR_SEARCH_FORM = $('#w0');
		var IS_AJAX = false;
		var SORT_PRICE = $('.sort.price');
		var SORT_DAYS = $('.sort.days');
		var SORT_PRICE_INPUT = $('#searchform-sortby');
		var SORT_DAYS_INPUT = $('#searchform-sortdaysby');
		var LOADING_BOTTOM = $('.loading-bottom');

		var methods = {
			init: function () {
				$('.tour-block').fadeIn();
				//$('body,html').animate({scrollTop: 0}, 500);
				//Отображение крутилки подгрузки ajax'ом
				$(document).on('pjax:send', function () {

					LOADING_WIDGET.show();
					BLOCK_PANEL_RESULT_LIST.html('');
					BLOCK_PANEL.addClass('process');
				});

				//Клик по точке маршрута(флаг, страна, город)
				WAY_POINT_FILTER.click(function () {
					WAY_POINT_INPUT.val($(this).data('value'));
					FORM.submit();
					$('body,html').animate({scrollTop: 90}, 500);
				});

				//Клик по сортировке по цене
				SORT_PRICE.click(function () {
					SORT_PRICE_INPUT.val($(this).data('value'));
					FORM.submit();
				});

				//Клик по сортировке по дням
				SORT_DAYS.click(function () {
					SORT_DAYS_INPUT.val($(this).data('value'));
					FORM.submit();
				});

				COUNT_INPUT.val($('.tour-block').length);

				//Подгрузка данных
				$(window).scroll(function () {
					if (($(window).scrollTop() + $(window).height() >= $(document).height() - 300) && true !== IS_AJAX) {
						//LOADING_BOTTOM.show();
						$.ajax({
							url: LOAD_TOUR_URL,
							method: "POST",
							async: false,
							data: TOUR_SEARCH_FORM.serialize(),
							beforeSend: function () {
								IS_AJAX = true;
							}
						}).done(function (data) {
							RESULT_BLOCK.append(data);
							COUNT_INPUT.val($('.tour-block').length);
							$('.tour-block').fadeIn();
							//LOADING_BOTTOM.hide();
						});
					}
				});
			}
		};
		methods.init();
	};
})(jQuery);