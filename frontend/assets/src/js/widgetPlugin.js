(function ($) {
	var methods = {
		init: function (params) {
			if (window.addEventListener) {
				window.addEventListener('load', $(this).widgetPlugin('getDocHeight', $(this)), false);
			} else if (window.attachEvent) { // ie8
				window.attachEvent('onload', $(this).widgetPlugin('sendDocHeightMsg'));
			}
		},
		getDocHeight: function () {
			return $('.inner-container').height() + 50;
		},
		sendDocHeightMsg: function () {
			setTimeout(function () {
				var ht = $(this).widgetPlugin('getDocHeight');
				parent.postMessage(JSON.stringify({'docHeight': ht}), '*');
			}, 1500);
		}
	};

	$.fn.widgetPlugin = function (method) {
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Метод "' + method + '" не найден в плагине jQuery.widgetPlugin');
		}
	};
})(jQuery);
