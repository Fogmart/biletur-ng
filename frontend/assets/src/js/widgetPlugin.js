(function ($) {
	$.fn.widgetPlugin = function (method) {
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Метод "' + method + '" не найден в плагине jQuery.mySimplePlugin');
		}
	};

	// наши публичные методы
	var methods = {
		// инициализация плагина
		init: function (params) {

		},
		byCity: function (cityName, containerSelector) {
			$.ajax({
				url: 'http://so.biletur.ru/excursion/widget/' + cityName + '/0/',
				success: function (data) {
					$(containerSelector).html('');
					$(containerSelector).html(data);
				}
			});
		}
	};
})(jQuery);