(function($)
{
	$.Redactor.prototype.filemanager = function()
	{
		return {
			init: function()
			{
				var button = this.button.add('assets', 'Asset');
				this.button.addCallback(button, this.filemanager.load);


				this.modal.addCallback('filemanager', $.proxy(this.filemanager.show, this));

			},
			load: function()
			{
				var self = this;
				$(document).off('filemanager-selected').on('filemanager-selected', function(e, file, target){
					e.preventDefault();
					if (self.modal) {
						var $img = $('<img src="' + file.ref_url + '" alt="' + file.title + '">');
						self.insert.node($img);
						self.buffer.set();
						self.modal.close();
					}
				})

				// build modal
				this.modal.load('filemanager', 'Assets', '90%');
				var url = this.opts.snapfiles;
				var html = '<iframe id="modal-iframe" src="' + url + '" style="width: 100%; height: 500px; background-color: #fff; border: none;"></iframe>';
				this.modal.getModal().prepend(html);
				this.modal.show();
				console.log(this.modal.getModal())
				// var $section = $('<section />');
				// var $select = $('<select id="redactor-defined-links" />');

				// $section.append($select);
				// this.modal.getModal().prepend($section);

				// this.filemanager.storage = {};

				// var url = (this.opts.filemanager) ? this.opts.filemanager : this.opts.filemanager;
				// $.getJSON(url, $.proxy(function(data)
				// {
				// 	$.each(data, $.proxy(function(key, val)
				// 	{
				// 		this.filemanager.storage[key] = val;
				// 		$select.append($('<option>').val(key).html(val.name));

				// 	}, this));

				// 	$select.on('change', $.proxy(this.filemanager.select, this));

				// }, this));

			},
			// select: function(e)
			// {
			// 	var oldText = $.trim($('#redactor-link-url-text').val());

			// 	var key = $(e.target).val();
			// 	var name = '', url = '';
			// 	if (key !== 0)
			// 	{
			// 		name = this.filemanager.storage[key].name;
			// 		url = this.filemanager.storage[key].url;
			// 	}

			// 	$('#redactor-link-url').val(url);

			// 	if (oldText === '')
			// 	{
			// 		$('#redactor-link-url-text').val(name);
			// 	}

			// }
		};
	};
})(jQuery);