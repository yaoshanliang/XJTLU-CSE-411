Komento.require()
.done(function($) {
	$.Joomla('submitbutton', function(action) {

		if (action == 'cancel') {
			window.location = '<?php echo JURI::base();?>index.php?option=com_komento&view=backgrounds';
			return;
		}

		Joomla.submitform([action]);
	});
});