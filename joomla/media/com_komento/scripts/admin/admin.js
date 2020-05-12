Komento
.require()
.script(
	'shared/elements',
	'admin/api/table'
)
.library(
	'dialog',
	'select2'
).done(function($){

	// Implement grid on tables
	var table = $('[data-kt-table]');

	$('[data-table-grid-filter]').select2({
		'theme': 'backend',
		'width': 'resolve',
		'minimumResultsForSearch': Infinity
	});

	$('[data-table-grid-filter]').on('select2:open', function() {
		$('body').addClass('has-select2-dropdown');
	});

	$('[data-table-grid-filter]').on('select2:close', function() {
		$('body').removeClass('has-select2-dropdown');
	});

	Komento.compareVersion = function(version1, version2) {
		var nRes = 0;
		var parts1 = version1.split('.');
		var parts2 = version2.split('.');
		var nLen = Math.max(parts1.length, parts2.length);

		for (var i = 0; i < nLen; i++) {
			var nP1 = (i < parts1.length) ? parseInt(parts1[i], 10) : 0;
			var nP2 = (i < parts2.length) ? parseInt(parts2[i], 10) : 0;

			if (isNaN(nP1)) { 
				nP1 = 0; 
			}
			
			if (isNaN(nP2)) { 
				nP2 = 0; 
			}

			if (nP1 != nP2) {
				nRes = (nP1 > nP2) ? 1 : -1;
				break;
			}
		}

		return nRes;
	}

	if (table.length > 0) {
		table.implement(Komento.Controller.Api.Table);
	}
});
