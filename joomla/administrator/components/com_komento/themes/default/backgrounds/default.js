Komento.require()
.done(function($) {
	$.Joomla('submitbutton', function(action) {

		if (action == 'add') {
			window.location.href = '<?php echo JURI::root();?>administrator/index.php?option=com_komento&view=backgrounds&layout=form';
			return;
		}

		$.Joomla('submitform', [action]);
	});
});