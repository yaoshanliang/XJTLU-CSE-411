Komento.ready(function($) {

	$(document)
		.on('change.theme.files', '[data-files-selection]', function() {

			var dropdown = $(this);
			var selected = dropdown.val();

			if (selected == '') {
				return;
			}

			window.location = '<?php echo JURI::base();?>index.php?option=com_komento&view=themes&layout=edit&element=<?php echo $element;?>&id=' + selected;
		});

	<?php if ($id) { ?>
	$.Joomla('submitbutton', function(task) {

		if (task == 'cancel') {
			window.location = '<?php echo JURI::base();?>index.php?option=com_komento&view=themes';
			return;
		}
		
		if (task == 'revert') {
			Komento.dialog({
				"content": Komento.ajax('admin/views/themes/confirmRevert', {"id": "<?php echo $id;?>", "element" : "<?php echo $element;?>"})
			});

			return;
		}

		$.Joomla('submitform', [task]);
	});
	<?php } ?>
});