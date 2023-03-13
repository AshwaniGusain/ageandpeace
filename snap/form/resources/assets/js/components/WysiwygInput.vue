<script>
// https://github.com/Haixing-Hu/vue-html-editor
Vue.component('snap-wysiwyg-input', {
    template: 	'<textarea v-model="content" class=""></textarea>',
	props: {
        'options': {
            type: Object,
            required: false,
            default: function () {
                return {};
            }
        },
        'collection': {
            type: String,
            required: false,
            default: 'default'
        },
        'value': {
            type: String
        }
	},

	mounted: function() {
		var self = this;
		var options = {
            imageResizable: true,
            mediaResourceId: $('#id').val(),
			// snaplinks: ModuleUtils.url('links/default'),
            minHeight: '200px',
            maxHeight: '800px',
			plugins: [
			    // 'filemanager',
                // 'imagemanager',
                //'textexpander',
                // 'definedlinks',
                // 'limiter',
                //'textexpander',
                //'variable'
            ],
            fileUpload: '/admin/media/upload',
            fileManagerJson: '/admin/media/files.json',
            imageUpload: '/admin/media/upload',
            mediaManagerJson: '/admin/media/images.json',
            definedlinks: []
		};

        options = $.extend(options, this.options);


        if (this.collection) {
            options.mediaCollection = this.collection;
        }

        // To make sure the image is associated with the proper resource we must replace the {id} placeholder.
        // options.imageUpload = options.imageUpload.replace(/\{\w+\}/, options.mediaResourceId);
        // options.imageUpload += '?collection=default&_token=' + jQuery('meta[name="csrf-token"]').attr('content');

        options.imageUpload = SNAP.paths.get('module') + '/' + options.mediaResourceId + '/upload?collection=' + options.mediaCollection + '&_token=' + SNAP.http.token;

        // options.callbacks = {
         //    started: function(e){
		// 		self.content = self.value;
         //        this.insertion.set(self.value);
		// 		self.$emit('input', self.value);
		// 		$(self.$el).trigger('change');
		// 	}
		// };

        // console.log(options)
        this.$nextTick(function(){
            // This must be set here for validation purposes
            //this.content = self.value;

            self.content = (self.$slots['default']) ? self.$slots['default'][0].text : '';

            // Put in try/catch block because it sometimes throws a mysterious error I haven't figured out yet.
            try{

                // This is needed for plugins like "count" that need a little more time to get the values setup.
                setTimeout(function(){
                    var redactor = $R(self.$el, options);
                    //$(self.$el).redactor(options);
                    if (self.content) {
                        redactor.insertion.set(self.content);
                    }
                }, 0);
            } catch(e) {

            }
        });

	},

	data: function(){
		return {
			content: ''
		};
	},

	methods: {

		// getValue: function(){
		// 	return $(this.$el).val();
		// }
	}

	// Needed for js validation
	// watch: {
	// 	content : function(value) {
	// 		this.$emit('input', value);
	// 	}
	// }
});
</script>
