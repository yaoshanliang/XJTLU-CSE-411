
Komento.require()
.library('raty')
.done(function($) {

	$('[data-kt-ratings-reset]').on('click', function() {
		$('[data-kt-ratings-star]').raty('cancel');

		$('[data-kt-rating-input]').val('');
	});

	$('[data-kt-ratings-star]').raty($.extend({}, kt.ratings.options, {
		"cancel": false,
		"click": function(value, event) {
			value = value * 2;

			$('[data-kt-rating-input]').val(value);
		}
	}));
});