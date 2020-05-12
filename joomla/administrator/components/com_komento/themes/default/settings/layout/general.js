Komento.ready(function($) {
	// When indentation toggled
	$('[data-comment-indent]').on('change', function() {
		var enabled = $(this).val() == 1;

		if (enabled) {
			$('[data-comment-indent-level]').removeClass('t-hidden');
			return;
		} 

		$('[data-comment-indent-level]').addClass('t-hidden');
	});

	// When lapsed time is toggled
	$('[data-comment-date-lapsed]').on('change', function() {
		var enabled = $(this).is(':checked');
		
		if (!enabled) {
			$('[data-comment-date-format]').removeClass('hidden');
			return;
		} 

		$('[data-comment-date-format]').addClass('hidden');
	});

	// When media max width toggled
	$('[data-comment-media-maxwidth]').on('change', function() {
		var enabled = $(this).val() == 1;

		if (enabled) {
			$('[data-comment-media-size]').addClass('t-hidden');
			return;
		} 

		$('[data-comment-media-size]').removeClass('t-hidden');
	});
});