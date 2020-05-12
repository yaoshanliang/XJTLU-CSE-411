jQuery(document).ready(function($) {

	if(jwPagefactoryEnabled) {
		$(jwIntergationElement).hide();
		$('.jw-pagefactory-admin').show();
		$('.btn-action-jwpagefactory').addClass('jw-pagefactory-btn-success active');
	} else {
		$('.jw-pagefactory-admin').hide();
		$(jwIntergationElement).show();
		$('.btn-action-editor').addClass('jw-pagefactory-btn-success active');
	}

	$('.jw-pagefactory-btn-switcher').on('click',function(event){
		event.preventDefault();

		$('.jw-pagefactory-btn-switcher').removeClass('active jw-pagefactory-btn-success');
		$(this).addClass('active').addClass('jw-pagefactory-btn-success');

		var action = $(this).data('action');

		// get shared parent container
		var $container = $(this).parent('.jw-pagefactory-btn-group').parent();

		if (action === 'editor') {
			$('.jw-pagefactory-admin').hide();
			$(jwIntergationElement).show();
			$('#jform_attribs_jwpagefactory_active').val('0');

			if (typeof WFEditor !== 'undefined') {
				$('.wf-editor', $container).each(function() {
					var value = this.nodeName === "TEXTAREA" ? this.value : this.innerHTML;

					// pass content from textarea to editor
					Joomla.editors.instances[this.id].setValue(value);

					// show editor and tabs
					$(this).parent('.wf-editor-container').show();
				});

			}

		} else {

			if (typeof WFEditor !== 'undefined') {
				$('.wf-editor', $container).each(function() {
					// pass content to textarea
					Joomla.editors.instances[this.id].getValue();

					// hide editor and tabs
					$(this).parent('.wf-editor-container').hide();
				});
			}

			$(jwIntergationElement).hide();
			$('.jw-pagefactory-admin').show();
			$('#jform_attribs_jwpagefactory_active').val('1');
		}
	});
});
