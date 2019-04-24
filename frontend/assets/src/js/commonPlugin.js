(function ($) {
	$.fn.commonPlugin = function () {
		var methods = {
			init: function () {
				alert('common plugin init');
				$(document).on('pjax:send', function() {
					$('.loading-widget').show();
				});

				$(document).on('pjax:complete', function() {
					$('.loading-widget').hide();
				});
			}
		};
		methods.init();
	};
})(jQuery);