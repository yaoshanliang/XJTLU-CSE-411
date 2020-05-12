Komento.ready(function($) {
	$('[data-reset-css]').on('click', function() {
		$('[data-custom-css]').each(function() {
			var original = $(this).data('original');
			$(this).val(original);
		});
	});
});