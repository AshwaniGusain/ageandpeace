<script>
    require('select2');

    Vue.component('snap-tag-input', {
        template: '<select :style="{width: width}"><slot></slot></select>',
        // template : '<select v-model="value"><option v-for="(option, index) in opts" v-bind:value="index">{{ option }}</option></select>',
        props: {
            'width': {
                default: '100%'
            },
            'config': {
                type: Object,
                required: false,
                default: function () {
                    return {};
                }
            }
        },

        // created: function(){
        //     this.value = $(this.$el).val();
        // },

        mounted: function(e){
            var self = this;

            this.value = $(this.$el).val();

            $(self.$el)
            // This is necessary for multi-selects not working correctly and only showing the last selected item.
                .val(this.value)
                .select2(this.getConfig())
                .on('change', function(e) {

                    // Placeholders don't have an empty value so they take on the label value
                    if ($(self.$el).attr('placeholder') === self.value) {
                        self.value = null;
                    }

                    self.$emit('input', self.value)
                });

        },

        data: function(){
            return {
                value: '',
            };
        },

        destroyed: function () {
            $(this.$el).off().select2('destroy');
        },

        methods: {

            getConfig: function(){
                var config = this.config;
                config['theme'] = 'bootstrap4';
                config['tags'] = true;
                // config['createTag'] = function (params) {
                //     var term = $.trim(params.term);
                //
                //     if (term === '') {
                //         return null;
                //     }
                //
                //     return {
                //         term: term
                //     }
                // };

                return config;
            }
        }

    });
</script>