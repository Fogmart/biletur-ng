//JS, общий для всего шаблона, инициализируем в лэйауте
(function ($) {
	'use strict';
	$.fn.layoutPlugin = function () {
		const LEFT_MENU = $('.left-menu');
		const BUTTON_TO_TOP = $('#scrollUp');
		const TOWN_INPUT = $('.town-input');
		const TOWN_SELECT_MODAL = $('#modal-towns');
		const BUTTON_CLOSE_GEO_MESSAGE = $('.close-geo-message');
		const GEO_MESSAGE = $('.dropdown-city');
		const BUTTON_OPEN_GEO_MODAL = $('.select-geo-city');

		const methods = {
			init: function () {
				//Фиксация меню, скрытие/показ кнопки "наверх"
				$(window).scroll(function () {
					if ($(this).scrollTop() > 70) {
						LEFT_MENU.addClass("fixed");
					} else {
						LEFT_MENU.removeClass("fixed");
					}

					if ($(this).scrollTop() > 100) {
						BUTTON_TO_TOP.fadeIn();
					} else {
						BUTTON_TO_TOP.fadeOut();
					}
				});

				BUTTON_TO_TOP.click(function () {
					$('body,html').animate({scrollTop: 0}, 500);
				});

				//Открытие модального окна выбора города
				TOWN_INPUT.click(function () {
					TOWN_SELECT_MODAL.modal('show');
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