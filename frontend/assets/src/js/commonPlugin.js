(function ($) {
	$.fn.commonPlugin = function () {
		var LEFT_MENU = $('.left-menu');
		var methods = {
			init: function () {

				//Отображение крутилки подгрузки ajax'ом
				$(document).on('pjax:send', function() {
					$('.loading-widget').show();
					$('.block-panel .result .list').html('');
					$('.block-panel').addClass('process');
				});

				$(document).on('pjax:complete', function() {
					$('.loading-widget').hide();
					$('.block-panel').removeClass('process');
				});

				//Фиксация меню
				$(window).scroll(function () {
					if ($(this).scrollTop() > 70) {
						LEFT_MENU.addClass("fixed");
					} else {
						LEFT_MENU.removeClass("fixed");
					}
				});
			}
		};
		methods.init();
	};
})(jQuery);