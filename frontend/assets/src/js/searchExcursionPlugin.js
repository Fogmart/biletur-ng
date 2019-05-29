(function ($) {
	$.fn.searchExcursionPlugin = function () {
		var methods = {
			init: function () {
				$('.page-num').click(function () {
					$('#searchform-page').val($(this).data('num'));
					$('form').submit();

					return false;
				});

				$('.tag').click(function () {
					if ($(this).hasClass('active')) {
						$('#searchform-citytag').val('');
						$('#searchform-page').val(1);
					}else {
						$('#searchform-citytag').val($(this).data('id'));
						$('#searchform-page').val(1);
					}

					$('form').submit();

					return false;
				});

				$('#search-button').click(function () {
					$('#searchform-page').val(1);
					$('#searchform-citytag').val('');

					return true;
				});
			}
		};
		methods.init();
	};
})(jQuery);