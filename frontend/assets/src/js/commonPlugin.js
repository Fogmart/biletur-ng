(function ($) {
	$.fn.commonPlugin = function () {
		var methods = {
			init: function () {
				$(document).on('pjax:send', function() {
					$('.loading-widget').show();
					$('.block-panel .result .list').html('');
				});

				$(document).on('pjax:complete', function() {
					$(this).widgetPlugin("sendDocHeightMsg");
					$('.loading-widget').hide();
				});
			}
		};
		methods.init();
	};
})(jQuery);