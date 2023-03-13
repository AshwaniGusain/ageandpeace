<script>
    Vue.component('snap-template-input', {
        // template: '<div></div>',
        template: '<div>\
                <div class="form-group">\
                    <select :name="name" class="form-control" v-model="template" :data-ajax-param="name" ref="selector">\
                        <option v-if="placeholder" value="">{{ placeholder}}</option>\
                        <option v-for="(label, val) in templates" :value="val" :selected="isSelected(val)">{{ label }}</option>\
                    </select>\
                    <input v-if="nested" type="hidden" :name="getTemplateFieldName()" v-model="template">\
                </div>\
                <div ref="fields" class="form-group form-group-fields">\
                <div v-if="loading" class="snap-loader"></div>\
                </div>\
            </div>',

        props: {
            'templateUrl': {
                type: String,
                required: true,
                default: null
            },
            'templates': {
                // type: Object,
                required: true,
                default: {}
            },

            'value': {

            },
            'placeholder': {
                type: String,
                default: 'Select one'
            },

            'name': {
                type: String
            },

            'scopeKey': {
                type: String
            },

            'resourceId': {
                type: String,
                required: false,
                default: 'id'
            },

            'scope': {
                type: String,
                required: false,
                default: ''
            }
        },

        data: function(){
            return {
                template: '',
                nested: false,
                fieldName: '',
                loading: false
            };
        },

        created: function(){
            SNAP.event.$on('snap-template-changed', function(json, vue){
                if (json.uriPrefix && $('#uri').length) {
                    var prefix = (json.uriPrefix) ? '/' + json.uriPrefix + '/' : '/';
                    //$('#uri').closest('.input-group').find('.input-group-text').text(prefix);
                    if (!$('#uri').val()) {
                        $('#uri').val(prefix);
                    }
                }
            });
        },

        mounted: function(){
            var self = this;
            this.$nextTick(function(){
                self.template = self.value;
            })

            setTimeout(function(){
                self.fieldName = $(self.$refs['selector']).attr('name');
                self.nested = self.fieldName.indexOf('[') !== -1 ? true : false;
                // console.log($(self.$refs['selector']).attr('name'))
            }, 0)
        },

        watch: {
            'template': function(){
                this.load();
            }
        },
        methods: {
            load: function(){
                if (this.template) {
                    var self = this;

                    var url = this.templateUrl + '/' + this.template;

                    if (this.resourceId && (value = $('#' + this.resourceId).val())) {
                        url = url + '/' + value;
                    }

                    var params = '';
                    params += 'scope=' + this.getScope();
                    // params += '&key=' + this.scopeKey;
                    params += '&noajax=1&'; // For AjaxableTrait inputs like repeatable to display the non-ajax view initially.
                    params += $(this.$el).closest('form').serialize();

                    this.loading = true;
                    $.ajax({
                        'type': 'get',
                        'url': url,
                        'data': params,
                        'dataType': 'json'
                    }).done(function(json){
                            self.$nextTick(function(){
                                var html = json.form;
                                var EmbeddedComponent = Vue.extend({
                                    name: 'template-field',
                                    template: '<div class="template-field-wrapper-inner">' + html + '</div>'
                                });

                                $fields = $(self.$el).find('.form-group-fields:first');
                                $fields.empty().append('<div class="template-field-wrapper-inner"></div>');
                                $wrapper = $fields.find('.template-field-wrapper-inner:first')[0];
                                // $(self.$refs['fields']).empty().append('<div class="template-field-wrapper-inner"></div>');

                                var component = new EmbeddedComponent().$mount($wrapper);

                                SNAP.event.$emit('snap-template-changed', json, self);
                                self.loading = false;
                            });
                    });
                    // $.getJSON(url, {}, function (json) {
                    //     //var scripts = json.scripts;
                    //     //self.injectScripts(scripts);
                    //
                    //     self.$nextTick(function(){
                    //         var html = json.form;
                    //         var EmbeddedComponent = Vue.extend({
                    //             name: 'template-field',
                    //             template: '<div class="template-field-wrapper-inner">' + html + '</div>'
                    //         });
                    //
                    //         $fields = $(self.$el).find('.form-group-fields:first');
                    //         $fields.empty().append('<div class="template-field-wrapper-inner"></div>');
                    //         $wrapper = $fields.find('.template-field-wrapper-inner:first')[0];
                    //         // $(self.$refs['fields']).empty().append('<div class="template-field-wrapper-inner"></div>');
                    //
                    //         var component = new EmbeddedComponent().$mount($wrapper);
                    //
                    //         SNAP.event.$emit('snap-template-changed', json, self);
                    //     });
                    // });
                } else {
                    $(this.$refs['fields']).empty();
                }
            },

            isSelected: function(val){
                return (val == this.value);
            },

            getScope: function(){
                return (this.scope.length) ? this.scope : $(this.$refs['selector']).attr('name');
            },

            getTemplateFieldName: function(){
                return this.fieldName + '[__value__]';
            },

            // isNested: function(){
            //     var self = this;
            //     this.$nextTick(function(){
            //         console.log($(self.$refs['selector']).attr('name'));
            //         var name = (self.$refs['selector']).attr('name');
            //         name && name.indexOf('[') !== -1 ? true : false
            //     })
            //     // var name = (self.$refs['selector']).attr('name');
            //     // return name && name.indexOf('[') !== -1 ? true : false;
            //     //return (this.scopeKey.indexOf('.') !== -1) ? true : false;
            // }



            /*injectScripts: function(files){

                var used = this.getLoadedScripts();
                // Loop through the files and if they do not already exist in head, inject them.
                for (var n in files){
                    var src = files[n];

                    if (used.indexOf(src) === -1){
                        var elem = document.createElement('script');
                        elem.setAttribute('src', src);
                        elem.setAttribute('type', 'text/javascript');
                    }
                    document.getElementsByTagName("head")[0].appendChild(elem);
                }
            },

            getLoadedScripts: function() {
                var used = [];
                var scripts = document.getElementsByTagName('scripts');
                if (scripts.length){
                    for (var i = 0; i < scripts.length; i++){
                        var tag = scripts[i];
                        if (tag && tag.getAttribute("src")){
                            used.push(tag.getAttribute(src));
                        }
                    }
                }

                return used;
            }*/
        }


    });
</script>