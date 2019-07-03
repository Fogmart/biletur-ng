(function ($) {
	$.fn.searchTourPlugin = function () {
		var FORM = $('#w0');
		var WAY_POINT_FILTER = $('.way-point');
		var WAY_POINT_INPUT = $('#searchform-cityinwaypoint');

		var methods = {
			init: function () {
				//Клик по точке маршрута
				WAY_POINT_FILTER.click(function () {
					WAY_POINT_INPUT.val($(this).data('city-id'));
					FORM.submit();
				});
			}
		};
		methods.init();
	};
})(jQuery);