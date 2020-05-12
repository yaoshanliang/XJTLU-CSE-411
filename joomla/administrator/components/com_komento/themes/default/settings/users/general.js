Komento.ready(function($) {

	$('[data-avatar-integration]').on('change', function() {
		var value = $(this).val();

		// Hide everything
		$('[data-avatar-option]').addClass('t-hidden');

		// Show selected
		$('[data-avatar-' + value).removeClass('t-hidden');
	});

	$('[data-avatar-character-based]').on('change', function() {
		var value = $(this).val();

		if (value == 1) {
			// Hide integration settings
			$('[data-avatar-option]').addClass('t-hidden');
			$('[data-avatar-integration-option]').addClass('t-hidden');
			return;
		}

		$('[data-avatar-integration-option]').removeClass('t-hidden');

		var avatarValue = $('[data-avatar-integration]').val();

		// Hide everything
		$('[data-avatar-option]').addClass('t-hidden');

		// Show selected
		$('[data-avatar-' + avatarValue).removeClass('t-hidden');
	});
});