(function($)
{
	$.Redactor.prototype.snaplink = function()
	{
		return {
			init: function()
			{

				var dropdown = {};

				dropdown.linkToContent = { title: 'Link to content', func: this.snaplink.linkToContentCallback };
				dropdown.linkToAsset = { title: 'Link to asset', func: this.snaplink.linkToAssetCallback };
				dropdown.insertLink = { title: 'Insert link', func: this.snaplink.insertLinkCallback };
				dropdown.unlink = { title: 'Unlink', func: this.snaplink.unlinkCallback };

				var button = this.button.add('snaplink', 'Link');
				this.button.addDropdown(button, dropdown);

				// this.observe.images();

				this.modal.addCallback('link', $.proxy(this.snaplink.load, this));

			},
			linkToContentCallback: function(buttonName)
			{
				this.modal.load('snaplink', 'Link to content', '500');

				var $section = $('<section />');
				var $select = $('<select id="snap-links" />');

				$section.append($select);
				this.modal.getModal().prepend($section);

				this.snaplink.storage = {};

				var url = (this.opts.snaplinks) ? this.opts.snaplinks : this.opts.snapLinks;
				$.getJSON(url, $.proxy(function(data)
				{
					$select.append($('<option>').val('').html('Select a content page...'));

					if (Object.keys(data)[0] == '0') {
						$.each(data, $.proxy(function(key, val)
						{
							this.snaplink.storage[key] = val;
							$select.append($('<option>').val(key).html(val.name));

						}, this));
					} else {
						$.each(data, $.proxy(function(key, val)
						{
							$optgroup = $('<optgroup label="' + key + '"">');
							$.each(val, function(k, v)
							{
								$optgroup.append($('<option>').val(v.url).html(v.name));
								$select.append($optgroup);
							});

						}, this));
					}
					
					$select.on('change', $.proxy(this.snaplink.select, this));

				}, this));

				this.modal.show();
			},
			linkToAssetCallback: function(buttonName)
			{
				var self = this;
				$(document).off('filemanager-selected').on('filemanager-selected', function(e, file, target){
					e.preventDefault();
					if (self.modal) {
						var url = file.ref_url;
						var link = self.snaplink.buildLink(url);
						self.link.insert(link, true);
						self.buffer.set();
						self.modal.close();
					}
				})

				this.modal.load('filemanager', 'Assets', '90%');
				var url = this.opts.snapfiles;
				var html = '<iframe id="modal-iframe" src="' + url + '" style="width: 100%; height: 500px; background-color: #fff; border: none;"></iframe>';
				this.modal.getModal().prepend(html);
				this.modal.show();
			},
			insertLinkCallback: function(buttonName)
			{
				this.link.show();
			},
			unlinkCallback: function(buttonName)
			{
				this.link.unlink();
			},
			
			select: function(e)
			{
				var url = $('#snap-links').val();
				var link = this.snaplink.buildLink(url);

				this.link.insert(link, true);
				this.modal.close();
			},

			buildLink: function(url){
				var link = {};

				// url
				link.url = this.link.cleanUrl(url);
				
				link.text = (this.selection.is()) ? this.selection.text() : this.link.cleanText(url);

				link.target = false;

				return link;
			}
		};
	};
})(jQuery);