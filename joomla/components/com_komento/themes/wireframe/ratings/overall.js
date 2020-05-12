
Komento.require()
.library('raty')
.done(function($) {
	var item = $('[data-kt-ratings-overall]');

	item.raty($.extend({}, kt.ratings.options, {
		"readOnly": true,
		"score": item.data('score')
	}));
});