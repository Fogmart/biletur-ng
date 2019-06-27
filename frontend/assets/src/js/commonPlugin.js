(function ($) {
	$.fn.commonPlugin = function () {
		var methods = {
			init: function () {
				$(document).on('pjax:send', function() {
					$('.loading-widget').show();
					$('.block-panel .result .list').html('');
					$('.block-panel').addClass('process');
				});

				$(document).on('pjax:complete', function() {
					$('.loading-widget').hide();
					$('.block-panel').removeClass('process');
				});
			}
		};
		methods.init();
	};
})(jQuery);