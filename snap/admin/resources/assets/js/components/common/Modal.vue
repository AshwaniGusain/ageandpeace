<script>
    Vue.component('snap-modal', {

        template : '#snap-modal-template',

        props: {

        },

        mounted: function(){
            let self = this;

            $(window).on('resize', function(){
                let iframe = $(self.$el).find('iframe')[0];
                if (iframe) {
                    let contentDoc = iframe.contentDocument;
                    self.resize(contentDoc);
                }
            })
        },

        data: function(){
            return {
                content: '',
                loading: false
            };
        },

        methods: {

            load: function(url, onShowCallback, onHideCallback){

                let self = this;

                if (!this.content) {
                    this.loading = true;
                }

                this.content = '<iframe src="' + url + '" frameborder="0" style="border: none; width: 100%; height: ' + this.viewPortHeight() + 'px"></iframe>';

                this.$nextTick(function(){
                    let iframe = $(self.$el).find('iframe');

                    $(iframe).off().on('load', function(){
                        self.loading = false;
                        let contentDoc = this.contentDocument;

                        SNAP.event.$emit('snap-modal-loaded', contentDoc);
                        if (onShowCallback) onShowCallback(self);

                        // if ($('#__FUEL_SAVED__', contentDoc).length) {
                        if ($('.alerts .alert-success', contentDoc).length) {
                            SNAP.event.$emit('snap-modal-saved', contentDoc);
                        }

                        $('[data-dismiss="modal"]', contentDoc).on('click', function(e){
                            e.preventDefault();
                            if (onHideCallback) onHideCallback(self);
                            self.hide();
                        });

                        if ($('#__close__', contentDoc).length) {
                            if (onHideCallback) onHideCallback(self);
                            self.hide();
                        }

                        // $('[data-dismiss="modal-delay"]', contentDoc).on('click', function(e){
                        //
                        //     // @TODO... have a proper save callback
                        //     setTimeout(function(){
                        //         if (onHideCallback) onHideCallback(self);
                        //         self.hide();
                        //     }, 2000);
                        // });


                        $(window).trigger('resize');
                        // self.resize(contentDoc);
                    });

                });

                $('[data-toggle="modal"]').on('click', function(e){
                    e.preventDefault();
                    self.show();
                    // $(this.$el).modal('show');
                });

                return this;

            },

            loadVue: function(template, data, onShowCallback, onHideCallback){
                let EmbeddedComponent = Vue.extend({
                    template: template,
                    data: function(){
                        return data;
                    },
                    computed: {

                        // For computing content in a vue template
                        htmlContent: function(){
                            let ContentComponent = Vue.extend({
                                template: this.content
                            })
                            let component = new ContentComponent().$mount();
                            return $(component.$el).html();
                        }
                    }
                });


                $('#snap-modal-content').empty().append('<div id="snap-modal-content-inner"></div>');
                let component = new EmbeddedComponent().$mount('#snap-modal-content-inner');

                $(this.$el).one('show.bs.modal', function(e) {
                    if (onShowCallback) onShowCallback();
                });

                $(this.$el).one('hide.bs.modal', function(e) {
                    if (onHideCallback) onHideCallback();
                });

                return this;
            },

            resize: function(contentDoc){
                let iframe = $(this.$el).find('iframe');
                let height = this.calcHeight(contentDoc);
                $('.modal-body', iframe.contents()).outerHeight(height);
                $(iframe).outerHeight(this.viewPortHeight());
            },

            viewPortHeight: function(){
                let offset = parseInt($('.modal-dialog').css('margin-top'));
                return Math.max(document.documentElement.clientHeight, window.innerHeight || 0) - (offset * 2); // 30 is the top margin
            },

            calcHeight: function(context){
                let height = 0;
                let elems = '.modal-header, .modal-footer';

                $(elems, context).each(function(i){
                    // must use false to get around bug with jQuery 1.8
                    let outerHeight = parseInt($(this).outerHeight(false));
                    if (outerHeight) height += outerHeight;
                });

                return this.viewPortHeight() - height;
            },

            setContent: function(content){
                this.content = content;
                return this;
            },

            show: function(callback){
                // this.options(opts);
                SNAP.event.$emit('snap-modal-before-show');
                $(this.$el).modal('show');
                SNAP.event.$emit('snap-modal-after-show');
                if (callback) callback();
                return this;
            },

            hide: function(callback){
                // this.options(opts);
                SNAP.event.$emit('snap-modal-before-hide');
                $(this.$el).modal('hide');
                SNAP.event.$emit('snap-modal-after-hide');
                if (callback) callback();
                return this;
            }

        },
        computed: {

        }

    });
</script>