Komento.module('admin/api/table' , function($) {

var module = this;

Komento.require()
// .script('admin/grid/sort' , 'admin/grid/publishing')
.done(function($) {

Komento.Controller('Api.Table', {
	defaultOptions : {

		"{task}": "[data-kt-table-task]",

		// Search on the table
		"{search}": "[data-kt-table-search]",
		"{searchInput}": "[data-kt-table-search-input]",
		"{resetSearch}": "[data-kt-table-search-reset]",

		// Checkall on table
		"{checkAll}": "[data-kt-table-checkall]",
		"{checkboxes}": "input[type=checkbox][data-kt-table-id]",

		// Publish buttons
		"{stateButton}": "[data-kt-table-publishing]",

		// Table rows
		"{row}": "tr",

		// Hidden inputs
		"{boxChecked}": "[data-kt-table-boxchecked]",

		// Filters
		"{filters}": "[data-kt-table-filter]",

		// Pagination
		"{paginationLink}": '[data-kt-pagination-link]',
		"{limitstart}": "[data-kt-limitstart-value]",

		"{sortButton}": "[data-table-grid-sort]",
		"{ordering}": "[data-kt-table-ordering]",
		"{direction}": "[data-kt-table-direction]"
	}
},
function(self, opts) { return {

	init : function() {

		// // Implement sortable items.
		// self.implementSortable();
	},
	
	'{paginationLink} click': function(link, event) {
		
		event.preventDefault();
		event.stopPropagation();

		var limitstart = link.data('limitstart');

		if (link.hasClass('active') || link.hasClass('disabled')) {
			return;
		}

		self.limitstart().val(limitstart);
		self.submitForm();
	},

	"{stateButton} click": function(button, event) {
		var row = button.parents(self.row.selector);
		var task = button.data('task');
		
		self.selectRow(row);
		self.setTask(task);
		self.submitForm();
	},

	"{filters} change" : function() {
		// Always reset the task before submitting.
		self.setTask('');

		self.submitForm();
	},

	"{search} click" : function() {
		self.submitForm();
	},

	"{resetSearch} click" : function(el, event) {
		self.searchInput().val('');
		self.submitForm();
	},

	submitForm: function() {
		self.element.submit();
	},

	setTask: function(task) {
		self.task().val( task );
	},

	setOrdering: function( ordering )
	{
		self.ordering().val( ordering );
	},

	setDirection: function(direction) {
		self.direction().val( direction );
	},

	updateBoxChecked: function() {
		var totalChecked = self.checkboxes(':checked').length;

		self.boxChecked().val(totalChecked);
	},

	toggleSelectRow: function(row) {
		var item = row.find('input[name=cid\\[\\]]');
		var checked = item.prop('checked') == true;

		if (checked) {
			item.prop('checked', false);
			return;
		}
		item.prop('checked', true);
		return;
	},

	'{sortButton} click': function(ele) {

		var column = ele.data('sort');
		var direction = ele.data('direction');

		self.setOrdering(column);
		self.setDirection(direction);

		self.submitForm();
	},

	selectRow: function(row) {
		var input = row.find('input[name=cid\\[\\]]');

		// If we cannot find an input, it could be a single selection rather than multiple
		if (input.length == 0) {
			input = row.find('input[name=cid]');
			
		}

		input.prop('checked', true);
	},

	"{checkboxes} click": function(checkbox, event) {
		event.stopPropagation();
	},

	"{row} click": function(row, event) {
		var checkbox = row.find(self.checkboxes.selector);

		checkbox.prop("checked", !checkbox.is(':checked'))
			.trigger('change');
	},

	"{checkboxes} change": function(checkbox, event) {
		var checked = checkbox.is(':checked');
		var row = checkbox.closest(self.row.selector);

		// Get a list of checked items
		var total = self.checkboxes().is(':checked').length;

		self.updateBoxChecked();

		row.toggleClass('is-checked', checked);
	},

	"{checkAll} change": function(element, event) {

		// Find all checkboxes in the grid.
		self.checkboxes()
			.prop('checked', element.is(':checked'))
			.trigger('change');

		// Update the total number of checkboxes checked.
		var total = element.is(':checked') ? self.checkboxes().length : 0;

		self.updateBoxChecked();
	}
}});

module.resolve();
});


});
