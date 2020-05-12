
Komento.ready(function($){

	// Get the current version of EasySocial
	$.ajax({
		url: "<?php echo KOMENTO_SERVICE_VERSION;?>",
		jsonp: "callback",
		dataType: "jsonp",
		data: {
			"apikey": "<?php echo $this->config->get('main_apikey');?>",
			"current": "<?php echo $currentVersion;?>"
		},
		success: function(data) {

			if (data.error) {
				$('#kt.kt-backend').prepend('<div style="margin-bottom: 0;padding: 15px 24px;font-size: 12px;" class="app-alert o-alert o-alert--danger"><div class="row-table"><div class="col-cell cell-tight"><i class="app-alert__icon fa fa-bolt"></i></div><div class="col-cell alert-message">' + data.error + '</div></div></div>');
			}
			
			// Update the latest version
			$('[data-latest-version]').html(data.version);

			var versionSection = $('[data-version-status]');
			var currentVersion = $('[data-current-version]');
			var installedSection = $('[data-version-installed]');

			var version = {
				"latest": data.version,
				"installed": "<?php echo $currentVersion;?>"
			};

			var outdated = Komento.compareVersion(version.installed, version.latest) === -1;

			if (versionSection.length > 0) {
				currentVersion.html(version.installed);
				installedSection.removeClass('hide');
				versionSection.removeClass('is-loading');

				// Update version checking
				if (outdated) {
					versionSection.addClass('is-outdated');
				} else {
					versionSection.addClass('is-updated');
				}
			}

			// Update with banner
			var banner = $('[data-outdated-banner]');

			if (banner.length > 0 && outdated) {
				banner.removeClass('t-hidden');
			}
		}
	});

	$('[data-preview-comment]').on('click', function() {
		var parent = $(this).parents('[data-comment-wrapper]');
		var content = parent.find('[data-comment-content]');

		content.toggleClass('t-hidden');
	});
});