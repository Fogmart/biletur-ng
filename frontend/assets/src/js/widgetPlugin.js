(function ($) {
	$.fn.widgetPlugin = function () {
		var methods = {
			init: function (param) {
				console.log(param);
			}
		};
		methods.init(param);
	};
})(jQuery);