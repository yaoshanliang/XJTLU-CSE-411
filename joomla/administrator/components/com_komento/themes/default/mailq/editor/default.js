
Komento.ready(function($) {

	$('[data-mailer-list]').implement(Komento.Controller.Mailer)

	// Handle submit button.
	$.Joomla('submitbutton' , function(action) {
		var selected = [];

		if (action == 'reset') {

			$('[data-kt-table]').find('input[name=cid\\[\\]]:checked').each(function(i , el ) {
				selected.push($(el).val());
			});

			Komento.dialog({
				"content": Komento.ajax('admin/views/mailq/confirmReset', {"files": selected})
			});

			return false;
		}

		$.Joomla('submitform', [action]);
	});

});
