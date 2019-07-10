(function ($) {
	$.fn.searchTourPlugin = function () {
		var FORM = $('#w0');
		var WAY_POINT_FILTER = $('.way-point-tag');
		var WAY_POINT_INPUT = $('#searchform-tourto');
		var COUNT_INPUT = $('#searchform-count');
		var LOAD_TOUR_URL = $('.load-tour-url').data('url');
		var RESULT_BLOCK = $('.result');
		var TOUR_SEARCH_FORM = $('#w0');
		var IS_AJAX = false;
		var SORT_PRICE = $('.sort-price');
		var SORT_PRICE_INPUT = $('#searchform-sortby');
		var methods = {
			init: function () {
				$('.tour-block').fadeIn();

				//Клик по точке маршрута(флаг, страна, город)
				WAY_POINT_FILTER.click(function () {
					WAY_POINT_INPUT.val($(this).data('value'));
					FORM.submit();
				});

				//Клик по сортировке
				SORT_PRICE.click(function () {
					SORT_PRICE_INPUT.val($(this).data('value'));
					FORM.submit();
				});

				COUNT_INPUT.val($('.tour-block').length);

				//Подгрузка данных
				$(window).scroll(function () {
					if (($(window).scrollTop() + $(window).height() >= $(document).height() - 300) && true !== IS_AJAX) {
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
						});
					}
				});
			}
		};
		methods.init();
	};
})(jQuery);