(function ($) {
	'use strict';
	$.fn.commonPlugin = function () {
		const LS_PARAM_IS_ADD_FILTER_HIDDEN = 'is-add-filters-hidden';

		const LOADING_WIDGET = $('.loading-widget');
		const BLOCK_PANEL = $('.block-panel');
		const BLOCK_PANEL_RESULT_LIST = $('.block-panel .result .list');
		const LEFT_MENU = $('.left-menu');
		const TOWN_INPUT = $('.town-input');
		const TOWN_SELECT_MODAL = $('#modal-towns');
		const BUTTON_TO_TOP = $('#scrollUp');
		const BUTTON_CLOSE_GEO_MESSAGE = $('.close-geo-message');
		const BUTTON_OPEN_GEO_MODAL = $('.select-geo-city');
		const BUTTON_HIDE_FILTERS = $('.hide-filters-block');
		const GEO_MESSAGE = $('.dropdown-city');
		const ADDITIONAL_FILTERS = $('.additional-filters');

		const POPUP_FILTER_SELECTOR = 'a.popup-filter';

		const methods = {
			init: function () {
				//Проверка не скрывал ли пользователь панель фильтров
				if ('true' === localStorage.getItem(LS_PARAM_IS_ADD_FILTER_HIDDEN)) {
					ADDITIONAL_FILTERS.hide();
					BUTTON_HIDE_FILTERS.html('Показать доп.фильтры');
				}

				//Отображение крутилки подгрузки ajax'ом
				$(document).on('pjax:send', function () {
					LOADING_WIDGET.show();
					BLOCK_PANEL_RESULT_LIST.html('');
					BLOCK_PANEL.addClass('process');
					$('.btn-show-more').hide();
				});

				$(document).on('pjax:complete', function () {
					LOADING_WIDGET.hide();
					BLOCK_PANEL.removeClass('process');
					$('.btn-show-more').show();
				});

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

				//Открытие модального окна выбора города
				TOWN_INPUT.click(function () {
					TOWN_SELECT_MODAL.modal('show');
				});

				BUTTON_TO_TOP.click(function () {
					$('body,html').animate({scrollTop: 0}, 500);
				});

				BUTTON_CLOSE_GEO_MESSAGE.click(function () {
					GEO_MESSAGE.hide();
				});

				//Открытие попап-фильтров
				$(POPUP_FILTER_SELECTOR).click(function () {


					let popupFilter = $(this).find('span');
					if (popupFilter.hasClass('active')) {
						popupFilter.removeClass('active');
					} else {
						$(POPUP_FILTER_SELECTOR).find('span').removeClass('active');
						popupFilter.addClass('active');
					}
				});

				BUTTON_HIDE_FILTERS.click(function () {
					ADDITIONAL_FILTERS.toggle();
					if (ADDITIONAL_FILTERS.is(':visible')) {
						localStorage.setItem(LS_PARAM_IS_ADD_FILTER_HIDDEN, 'false')
						BUTTON_HIDE_FILTERS.html('Скрыть доп.фильтры');
					} else {
						localStorage.setItem(LS_PARAM_IS_ADD_FILTER_HIDDEN, 'true')
						BUTTON_HIDE_FILTERS.html('Показать доп.фильтры');
					}
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