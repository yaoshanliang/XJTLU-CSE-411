Komento.ready(function($) {

	$('#antispam_captcha_type').on('change', function() {
		var value = $(this).val();

		if (value == 1) {

			$('[data-recaptcha-section]').removeClass('hidden');

			return;	
		}

		$('[data-recaptcha-section]').addClass('hidden');

		return;
	});
});