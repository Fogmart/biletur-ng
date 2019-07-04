(function ($) {
	$.fn.commonPlugin = function () {

		var LOADING_WIDGET = $('.loading-widget');
		var BLOCK_PANEL = $('.block-panel');
		var BLOCK_PANEL_RESULT_LIST = $('.block-panel .result .list');
		var LEFT_MENU = $('.left-menu');
		var TOWN_INPUT = $('.town-input');
		var TOWN_SELECT_MODAL = $('#modal-towns');

		var methods = {
			init: function () {
				//Отображение крутилки подгрузки ajax'ом
				$(document).on('pjax:send', function() {
					LOADING_WIDGET.show();
					BLOCK_PANEL_RESULT_LIST.html('');
					BLOCK_PANEL.addClass('process');
				});

				$(document).on('pjax:complete', function() {
					LOADING_WIDGET.hide();
					BLOCK_PANEL.removeClass('process');
				});

				//Фиксация меню
				$(window).scroll(function () {
					if ($(this).scrollTop() > 70) {
						LEFT_MENU.addClass("fixed");
					} else {
						LEFT_MENU.removeClass("fixed");
					}
				});

				//Открытие модального окна выбора города
				TOWN_INPUT.click(function () {
					TOWN_SELECT_MODAL.modal('show');
				});
			}
		};
		methods.init();
	};
})(jQuery);