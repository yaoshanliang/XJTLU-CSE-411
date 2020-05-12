Komento.require()
// .script('admin/users/form', 'shared/fields/validate', 'admin/clusters/users')
.done(function($) {

	$('[data-akismet-submit-spam]').on('click', function() {

		if(document.adminForm.boxchecked.value == 0) {
		    alert('<?php echo JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST', true)?>');
		} else {
			document.adminForm.action.value = 'spam';
		    $.Joomla('submitform', ['trainAkismet']);
		}
	});

	$('[data-akismet-submit-ham]').on('click', function() {

		if(document.adminForm.boxchecked.value == 0) {
		    alert('<?php echo JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST', true)?>');
		} else {
			document.adminForm.action.value = 'ham';
		    $.Joomla('submitform', ['trainAkismet']);
		}
	});

	$('[data-akismet-button]').on('click', function() {

		button = $(this);
		id = button.data('id');
		type = button.data('type');

		button.html('<i class="fa fa-cog fa-spin"></i>');

		Komento.ajax('admin/views/comments/akismet', {
			action: type,
			id: id
		})
		.done(function(message){
			button.html(message);
		});
	});

	// Insert a new dropdown button on the toolbar
	<?php if ($this->config->get('antispam_akismet_key')) { ?>
	$('[data-akismet-dropdown]')
		.removeClass('t-hidden')
		.appendTo('#toolbar');
	<?php } ?>
});
