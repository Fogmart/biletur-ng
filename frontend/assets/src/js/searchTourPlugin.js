(function ($) {
	$.fn.searchTourPlugin = function () {
		const LOADING_WIDGET = $('.loading-widget');
		const BLOCK_PANEL = $('.block-panel');
		const BLOCK_PANEL_RESULT_LIST = $('.block-panel .result .list');
		const FORM = $('#w0');
		const WAY_POINT_FILTER = $('.way-point-tag');
		const WAY_POINT_INPUT = $('#searchform-tourto');
		const COUNT_INPUT = $('#searchform-count');
		const LOAD_TOUR_URL = $('.load-tour-url').data('url');
		const RESULT_BLOCK = $('.result');
		const TOUR_SEARCH_FORM = $('#w0');
		const IS_AJAX = false;
		const SORT_PRICE = $('.sort.price');
		const SORT_DAYS = $('.sort.days');
		const SORT_PRICE_INPUT = $('#searchform-sortby');
		const SORT_DAYS_INPUT = $('#searchform-sortdaysby');
		const LOADING_BOTTOM = $('.loading-bottom');

		const methods = {
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