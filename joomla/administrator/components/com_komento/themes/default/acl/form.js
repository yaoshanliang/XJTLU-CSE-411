Komento.ready(function($) {

	$('[data-kt-tab]').on('click', function() {
		var current = $(this).data('kt-tab');
		
		$('[data-current]').val(current);
	});

	$('[data-acl-toggle]').on('change', function() {
		
		var parent = $(this).parents('[data-acl-wrap]');
		var enabled = $(this).is(':checked');
		var data = enabled ? 'on' : 'off';
		
		parent.find('[data-info]')
			.addClass('t-hidden');

		parent.find('[data-' + data + ']')
			.removeClass('t-hidden');

	});
});