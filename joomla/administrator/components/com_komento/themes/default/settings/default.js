
Komento.ready( function($){
	$.Joomla("submitbutton", function(task) {

		$('#submenu li').children().each( function(){
			if( $(this).hasClass( 'active' ) )
			{
				$( '#active' ).val( $(this).attr('id') );
			}
		});

		$('dl#subtabs').children().each(function(){
			if( $(this).hasClass( 'open' ) )
			{
				$( '#activechild' ).val( $(this).attr('class').split(" ")[0] );
			}
		});

		$.Joomla("submitform", [task]);
	});

	$('[data-bs-toggle]').on('click', function() {
		var key = $(this).attr('href');
		key = key.replace('#tab-', '');

		$('[data-active-layout]').val(key);
	});
});