(function ($) {
	'use strict';
	$.fn.searchAviaPlugin = function () {
		const methods = {
			init: function () {

			},
			//Метод получения предложений после поиска
			getOffers: function () {
				let url = $('.load-offers-url').data('url');
				let requestId = $('.offer-request-id').data('id');


			}

		};
		methods.init();
	};
})(jQuery);