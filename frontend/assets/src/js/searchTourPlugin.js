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
		var methods = {
			init: function () {
				//Клик по точке маршрута(флаг, страна, город)
				WAY_POINT_FILTER.click(function () {
					WAY_POINT_INPUT.val($(this).data('value'));
					FORM.submit();
				});
				COUNT_INPUT.val($('.tour-block').length);

				$(window).scroll(function () {
					if (($(window).scrollTop() + $(window).height() >= $(document).height() - 200) && true !== IS_AJAX) {
						IS_AJAX = true;
						//$(this).searchTourPlugin('load');
						$.ajax({
							url: LOAD_TOUR_URL,
							method: "POST",
							async: false,
							data: TOUR_SEARCH_FORM.serialize(),
							beforeSend: function () {

							}
						}).done(function (data) {
							RESULT_BLOCK.append(data);
							COUNT_INPUT.val($('.tour-block').length);

							IS_AJAX = false;
						});
					}
				});
			},
			//Подгрузка туров при скролле
			load: function () {
				$.ajax({
					url: LOAD_TOUR_URL,
					method: "POST",
					async: false,
					data: TOUR_SEARCH_FORM.serialize(),
					beforeSend: function () {

					}
				}).done(function (data) {
					RESULT_BLOCK.append(data);
					COUNT_INPUT.val($('.tour-block').length);

					IS_AJAX = false;
				});
			}
		};
		methods.init();
	};
})(jQuery);