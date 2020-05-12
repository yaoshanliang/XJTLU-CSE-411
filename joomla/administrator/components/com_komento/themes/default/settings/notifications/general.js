Komento.require()
.done(function($) {

	$('[data-custom-email-logo]').on('change', function() {
		var enabled = $(this).val() == 1;

		if (enabled) {
			$('[data-email-logo-wrapper]').removeClass('t-hidden');
			return;
		}

		$('[data-email-logo-wrapper]').addClass('t-hidden');
	});

	$('[data-email-logo-restore-default-button]').on('click', function() {

		var wrapper = $(this).parents('[data-email-logo]');
		var imageWrapper = $('[data-email-logo-image]');

		Komento.dialog({
			content: Komento.ajax('admin/views/settings/confirmRestorelogos'),
			bindings: {
				'{restoreButton} click': function() {

					Komento.ajax('admin/controllers/settings/restoreEmailLogo').done(function() {
						var buttonArea = wrapper.find('[data-email-logo-restore-default-wrap]');
						var defaultThumbnail = wrapper.data('defaultEmailLogo');

						buttonArea.hide();
						imageWrapper.attr('src', defaultThumbnail);

						Komento.dialog().close();
					});
				}
			}
		});
	});	
})