Komento.require()
<?php if ($this->config->get('enable_ratings')) { ?>
.library('raty')
<?php } ?>
.script('site/dashboard/default')
.done(function($) {

	$('[data-kt-dashboard]').implement(Komento.Controller.Dashboard, {
		"return": "<?php echo $returnURL;?>"
	});

	<?php if ($this->config->get('enable_ratings')) { ?>
	$('[data-kt-ratings-item]').each(function() {
		var item = $(this);
		var score = item.data('score');

		item.raty($.extend({}, kt.ratings.options, {
			"readOnly": true,
			"score": score
			})
		);
	});
	<?php } ?>
});
