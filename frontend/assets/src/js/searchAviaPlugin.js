(function ($) {
	'use strict';
	$.fn.searchAviaPlugin = function () {
		const methods = {
			init: function () {
				//Запускаем проверки результата после отправки запроса к ETM
				$(document).on('pjax:complete', function () {
					var checkResultTimer = setTimeout(function run() {
						$(this).searchAviaPlugin('getOffers', {"timer": checkResultTimer});
						setTimeout(run, 4000);
					}, 4000);
				});
			},
			//Метод получения предложений после поиска
			getOffers: function (params) {
				let url = $('.load-offers-url').data('url');
				let requestId = $('.offer-request-id').data('id');

				$.ajax({
					url: url,
					method: "POST",
					async: true,
					data: {'requestId': requestId}
				}).done(function (data) {
					if (data !== false) {
						clearTimeout(params.timer);
					}
				});
			}
		};
		methods.init();
	};
})(jQuery);