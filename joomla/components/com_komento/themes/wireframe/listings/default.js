<?php if ($this->config->get('layout_frontpage_ratings') && $this->config->get('enable_ratings')) { ?>
Komento.require()
.library('raty')
.done(function($) {

	var item = $('[data-kt-ratings="<?php echo $cid;?>"]');

	item.raty($.extend({}, kt.ratings.options, {
		"readOnly": true,
		"score": item.data('score')
		})
	);
});
<?php } ?>
