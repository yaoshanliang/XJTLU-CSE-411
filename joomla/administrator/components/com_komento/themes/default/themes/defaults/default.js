Komento.ready(function($) {

	$.Joomla('submitbutton', function(task) {

		// Redirect to edit form
		if (task == 'edit') {
			var element = $('input[name=cid\\[\\]]:checked').val() || '';

			// Do not proceed if there are no theme selected
			if (!element) {
				return;
			}
			
			window.location = '<?php echo JURI::base();?>index.php?option=com_komento&view=themes&layout=edit&element=' + element;
			return;
		}

		$.Joomla('submitform', [task]);
	});
});