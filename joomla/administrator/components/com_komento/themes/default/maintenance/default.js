
Komento.ready( function($){
	$.Joomla("submitbutton", function(task) {

		if (task == 'form') {
            document.adminForm.layout.value = 'form';
		}

		$.Joomla("submitform", [task]);
	});

	$('[data-bs-toggle]').on('click', function() {
		var key = $(this).attr('href');
		key = key.replace('#tab-', '');

		$('[data-active-layout]').val(key);
	});
});
