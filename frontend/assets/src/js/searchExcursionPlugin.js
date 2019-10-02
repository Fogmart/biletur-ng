(function ($) {
	$.fn.searchExcursionPlugin = function () {
		const SHOW_MORE_BUTTON = $('.btn-show-more');
		const LOAD_URL = $('.load-excursion-url').data('url');
		const SEARCH_FORM = $('#w0');
		const RESULT_BLOCK = $('.excursion-list');
		const LOADING_WIDGET = $('.loading-widget');
		const BLOCK_PANEL = $('.block-panel');
		var PAGE_INPUT = $('#searchform-page');

		const methods = {
			init: function () {
				$('.tag').click(function () {
					if ($(this).hasClass('active')) {
						$('#searchform-citytag').val('');
						$('#searchform-page').val(1);
					} else {
						$('#searchform-citytag').val($(this).data('id'));
						$('#searchform-page').val(1);
					}

					SEARCH_FORM.submit();

					return false;
				});

				$('#search-button').click(function () {
					$('#searchform-page').val(1);
					$('#searchform-citytag').val('');

					return true;
				});

				SHOW_MORE_BUTTON.click(function () {
					LOADING_WIDGET.show();
					BLOCK_PANEL.addClass('process');
					PAGE_INPUT.val(parseInt(PAGE_INPUT.val(), 10) + 1);
					$.ajax({
						url: LOAD_URL,
						method: "POST",
						async: true,
						data: SEARCH_FORM.serialize()
					}).done(function (data) {
						if (data.length === 0) {

						} else {
							RESULT_BLOCK.append(data);
							LOADING_WIDGET.hide();
							BLOCK_PANEL.removeClass('process');
							$('.excursion-block').fadeIn();

						}
					});
				});
			}
		};
		methods.init();
	};
})(jQuery);