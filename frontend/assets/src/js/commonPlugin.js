(function ($) {
	$.fn.commonPlugin = function () {

		var LOADING_WIDGET = $('.loading-widget');
		var BLOCK_PANEL = $('.block-panel');
		var BLOCK_PANEL_RESULT_LIST = $('.block-panel .result .list');
		var LEFT_MENU = $('.left-menu');
		var TOWN_INPUT = $('.town-input');
		var TOWN_SELECT_MODAL = $('#modal-towns');
		var BUTTON_TO_TOP = $('#scrollUp');
		var BUTTON_CLOSE_GEO_MESSAGE = $('.close-geo-message');
		var BUTTON_OPEN_GEO_MODAL = $('.select-geo-city');
		var GEO_MESSAGE = $('.dropdown-city');

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

				//Фиксация меню, скрытие/показ кнопки "наверх"
				$(window).scroll(function () {
					if ($(this).scrollTop() > 70) {
						LEFT_MENU.addClass("fixed");
					} else {
						LEFT_MENU.removeClass("fixed");
					}

					if($(this).scrollTop() > 100) {
						BUTTON_TO_TOP.fadeIn();
					} else {
						BUTTON_TO_TOP.fadeOut();
					}
				});

				//Открытие модального окна выбора города
				TOWN_INPUT.click(function () {
					TOWN_SELECT_MODAL.modal('show');
				});

				BUTTON_TO_TOP.click(function() {
					$('body,html').animate({scrollTop:0},500);
				});

				BUTTON_CLOSE_GEO_MESSAGE.click(function () {
					GEO_MESSAGE.hide();
				});

				BUTTON_OPEN_GEO_MODAL.click(function () {
					GEO_MESSAGE.hide();
					TOWN_SELECT_MODAL.modal('show');
				});
			}
		};
		methods.init();
	};
})(jQuery);