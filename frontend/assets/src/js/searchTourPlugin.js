(function ($) {
	$.fn.searchTourPlugin = function () {
		var FORM = $('#w0');
		var WAY_POINT_FILTER = $('.way-point-tag');
		var WAY_POINT_INPUT = $('#searchform-tourto');

		var methods = {
			init: function () {
				//Клик по точке маршрута(флаг, страна, город)
				WAY_POINT_FILTER.click(function () {
					WAY_POINT_INPUT.val($(this).data('value'));
					FORM.submit();
				});
			}
		};
		methods.init();
	};
})(jQuery);