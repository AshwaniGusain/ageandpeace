<script>
Vue.component('snap-resource-index', {
	props: {
		'moduleUrl': {
			type: String,
			required: true
		},
		'moduleUri': {
			type: String,
			required: true
		}
	},

	mounted: function() {
		var self = this;

		// @TODO... make this more Vuey...
		this.deleteLink = $('.dropdown-action-delete-selected-items', this.$el);
		this.deleteLink.on('click', function(e){
			if ( ! self.multiselected) {
				alert('Please select from the items below an item to delete.');
				e.preventDefault();
			} else {
				var ids = [];
				self.getMultiSelected().each(function(i){
					ids.push($(this).val());
				});

				$(this).attr('href', $(this).attr('href') + '/' + ids.join(',') + '/delete');
			}
		});
		
		this.deleteLink.addClass('text-muted');

		SNAP.event.$on('submit-form', function(action){
			var $form = $('#snap-form');
			if (action) {
				$form.attr('action', action);
			}
			$form.submit();
		})
	},

	data: function() {
		return {
			multiselected: false
		}
	},

	methods: {

		toggleMultiSelect: function(){
			this.multiselected = this.getMultiSelected().length ? true : false;
			this.deleteLink.toggleClass('text-muted', !this.multiselected);	
		},

		getMultiSelected: function(){
			return $('input.multiselect:checked', this.$el);
		},

		getStorageKey: function(key){
			return this.moduleUri + '#' + key;
		}

	}

});
</script>