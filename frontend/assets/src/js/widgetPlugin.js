(function ($) {
	$.fn.widgetPlugin = function (method) {
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Метод "' + method + '" не найден в плагине jQuery.widgetPlugin');
		}
	};

	// наши публичные методы
	var methods = {
		// инициализация плагина
		init: function (params) {
			if (window.addEventListener) {
				window.addEventListener('load', $(this).widgetPlugin('getDocHeight', $(this)), false);
			} else if (window.attachEvent) { // ie8
				window.attachEvent('onload', $(this).widgetPlugin('sendDocHeightMsg'));
			}
		},
		getDocHeight: function (doc) {
			doc = document;
			var body = doc.body, html = doc.documentElement;

			var height = $('.inner-container').height() + 50;

			return height;
		},
		sendDocHeightMsg: function () {
			setTimeout(function () {
				var ht = $(this).widgetPlugin('getDocHeight');
				parent.postMessage(JSON.stringify({'docHeight': ht}), '*');
			}, 500);
		}
	};
})(jQuery);
