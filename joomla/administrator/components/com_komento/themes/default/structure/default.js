
Komento.ready(function($){

	var header = $('.header');
	var mobileNavigation = $('[data-komento-mobile-nav]');

	// When the page is loaded, ensure that the mobile navigation works
	mobileNavigation.appendTo(header);
	mobileNavigation.css('visibility', 'visible');

	$('[data-sidebar-parent]').click(function() {
		var parent = $(this).parent();

		parent.toggleClass('open active');
	});

	$('.nav-sidebar-toggle').click(function(){
		$('body').toggleClass('show-sidebar');
	});
});

Komento.ready(function($){

    // Sidebar menu functions
    $('[data-sidebar-parent]').on('click', function() {
        var parent = $(this).parent();

        // Disable all open states
        $('[data-sidebar-item]').removeClass('active open');

        parent.toggleClass('active open');
    });


    // Fix the header for mobile view
    $('.container-nav').appendTo($('.header'));

    $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
            $('.header').addClass('header-stick');
        } else if ($(this).scrollTop() < 50) {
            $('.header').removeClass('header-stick');
        }
    });

    $('.nav-sidebar-toggle').click(function(){
        $('html').toggleClass('show-komento-sidebar');
        $('.subhead-collapse').removeClass('in').css('height', 0);
    });

    // Bind tabs for settings
    $('[data-form-tabs]').on('click', function() {
        var active = $(this).attr('href');

        active = active.replace('#', '');

        var hiddenInput = $('[data-settings-active]');

        if (hiddenInput.length > 0) {
            hiddenInput.val(active);
        }
    });

});