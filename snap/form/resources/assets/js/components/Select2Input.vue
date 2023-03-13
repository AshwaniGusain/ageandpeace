<script>
    require('select2');

    Vue.component('snap-select2-input', {
        template: '<select :style="{width: width}"><slot></slot></select>',
        // template : '<select v-model="value"><option v-for="(option, index) in opts" v-bind:value="index">{{ option }}</option></select>',
        props: {
            'width': {
                default: '100%'
            },
            'value': {

            },
            placeholder: {

            },
            'config': {
                type: Object,
                required: false,
                default: function () {
                    return {};
                }
            }
        },

        mounted: function(e){
            var self = this;
            this.$nextTick(function(){
                $(self.$el)
                // IMPORTANT: This is necessary for multi-selects not working correctly and only showing the last selected item.
                    .val(this.value)
                    .select2(self.getConfig())
                    .addClass('select2-applied')
                    .on('change', function(e) {

                        // Placeholders don't have an empty value so they take on the label value
                        // if ($(self.$el).attr('placeholder') === self.value) {
                        //     self.value = null;
                        // }

                        self.$emit('input', self.value)
                    });
            });
        },

        data: function(){
            return {

            };
        },

        destroyed: function () {
            $(this.$el).off().select2('destroy');
        },

        methods: {

            getConfig: function(){
                var config = this.config;
                config['theme'] = 'bootstrap4';
                config['placeholder'] = this.placeholder;
                return config;
            }
        }

    });
</script>