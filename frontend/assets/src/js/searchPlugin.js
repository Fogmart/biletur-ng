(function ($) {
	$.fn.searchPlugin = function () {
		var methods = {
			init: function () {
				$('.page-num').click(function () {
					$('#searchform-page').val($(this).data('num'));
					$('form').submit();

					return false;
				});

				$('.tag').click(function () {
					$('#searchform-citytag').val($(this).data('id'));
					$('form').submit();

					return false;
				});


				$('#search-button').click(function () {
					$('#searchform-page').val(1);

					return true;
				});
			}
		};
		methods.init();
	};
})(jQuery);