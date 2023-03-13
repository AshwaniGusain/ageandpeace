<script>
    Vue.component('snap-resource-preview', {
        template: '<iframe v-if="isShown()" :src="previewUrl" style="border: 0px; width: 70%;" :style="{height: height}" name="module-preview" id="module-preview"></iframe>',
        timeout: null,
        props: {
            url: {
                type: String,
                required: true
            },
            loadingUrl: {
                type: String,
                required: false
            },
            slugInput: {
                type: String,
                required: true,
                default: 'slug',
            },
            debounce: {
                type: Number,
                required: false,
                default: 500
            }
        },

        created: function(){
            this.xhr = null;
            SNAP.event.$on('data-changed', this.preview);
        },
        mounted: function(){
            var self = this;
            this.displayLoader();
            this.$slug = $('[name="' + self.slugInput + '"]');
            // $('#module-edit-btn-preview').on('click', function(e){
            // 	e.preventDefault();
            // 	self.preview();
            // })

            $('#btn-visit').on('click', function(e){
                window.open(self.assembleUrl(false));
            });

            $('#btn-preview').on('click', function(e){
                if (!self.$slug.val()) {
                    self.alert();
                } else {
                    self.toggle();
                    self.preview();
                    if (self.state === 'hidden') {
                        $(this).text('Preview');
                    } else {
                        $(this).text('Close Preview');
                    }


                    // $('#sidebar').hide();
                    // $('#panels-col').hide();
                    // $('#form-col').attr('class', 'col-md-4');
                    // $('#preview-col').attr('class', 'col-md-8');
                    // self.preview();
                }
            });



            this.$nextTick(function(){
                var $form = $('#snap-form');
                $form.on('keyup', 'input, textarea', function(){
                    // self.preview();
                    SNAP.event.$emit('data-changed');
                });
                $form.on('change input', 'input, textarea, select, [contenteditable="true"]', function(){
                    // self.preview();
                    SNAP.event.$emit('data-changed');
                });
                // self.preview();
            });

            $(window).on('resize', function(){
                self.height = self.getPreviewHeight();
            })

        },

        data: function(){
            return {
                state: 'hidden',
                height: '500px',
                previewUrl: ''
            };
        },

        methods: {
            preview: function(){

                if (!this.isShown()) return;

                var self = this;
                clearTimeout(this.timeout);
                this.$slug.prop('disabled', true);

                var url = this.assembleUrl(true);

                this.displayLoader();
                this.timeout = setTimeout(function(){
                    var target = 'module-preview';
                    $form = $('#snap-form :input:not([name="_method"])');

                    var data = $form.serialize();

                    // kill any current requests
                    if (this.xhr) {
                        this.xhr.abort();
                    }
                    this.xhr = $.post(url, data).done(function(html){

                        // https://stackoverflow.com/questions/5784638/replace-entire-content-of-iframe
                        var iframe = $('#snap-resource-preview')[0];
                        var doc = iframe.contentWindow.document;
                        doc.open();
                        doc.write(html);
                        doc.close();
                        //$('#snap-resource-preview').contents().find('html').replace(html);
                    })


                }, self.debounce);

                this.height = this.getPreviewHeight();
            },

            displayLoader: function(){
                this.previewUrl = this.loadingUrl;
            },

            toggle: function(){
                if (this.state === 'shown') {
                    this.hide();
                } else {
                    this.show();
                }
            },

            getPreviewHeight: function(){
                // var docHeight = $('#form-col').outerHeight() - $('#module-preview').offset().top;
                // var docHeight = $('#form-col').outerHeight();
                // var previewHeight = $('#snap-resource-preview').contents().outerHeight();
                var titleHeight = $('#snap-module-title').outerHeight();
                // console.log(docHeight + " " + previewHeight)
                return (window.innerHeight - titleHeight)+ 'px';
                // return (previewHeight < docHeight) ? docHeight + 'px' : (previewHeight + 10) + 'px';
            },

            isShown: function(){
                return this.state === 'shown';
            },

            show: function(){
                //$('#snap-admin').addClass('snap-preview-mode');
                this.state = 'shown';
                $('.form-page .field-uri, .form-page .field-name').hide();
                SNAP.event.$emit('preview-mode-shown');
            },

            hide: function() {
                //$('#snap-admin').removeClass('snap-preview-mode');
                this.state = 'hidden';
                this.$slug.prop('disabled', false);
                $('.form-page .field-uri, .form-page .field-name').show();
                SNAP.event.$emit('preview-mode-hidden');
            },

            alert: function(){
                alert('You must first enter in a value for ' + this.slugInput);
            },

            assembleUrl: function(cacheBuster){

                var prefix = this.$slug.closest('.input-group').find('.input-group-text').text();
                var slug = this.$slug.val();

                if (!slug) {
                    self.alert();
                    return;
                }

                var url = this.url + '/' +prefix + slug;

                if (cacheBuster) {
                    url += '?c=' + new Date().getTime();
                }

                return url;
            }
        }
    });
</script>