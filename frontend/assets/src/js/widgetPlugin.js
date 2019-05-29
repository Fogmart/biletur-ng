(function ($) {
	$.fn.widgetPlugin = function () {
		var methods = {
			init: function (param) {
				console.log(param);
			},
			byCity: function (param) {
				console.log(param);
			}
		};
		methods.init();
	};
})(jQuery);