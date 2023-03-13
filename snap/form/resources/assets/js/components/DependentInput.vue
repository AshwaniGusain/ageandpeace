<script>
Vue.component('snap-dependent-input', {
    template: '<div><div v-if="options"><select :name="name" :id="id" class="form-control"><option v-if="placeholder" value="">{{ placeholder}}</option><option v-for="(label, val) in options" :value="val" :selected="isSelected(val)">{{ label }}</option></select></div>\
    <div v-else v-html="content" class="form-group-inputs"></div></div>',
    props: {
        'source': {
            type: String,
            required: true
        },
        'url': {
            type: String,
            required: false,
        },
        'urlParams': {
            // default: function(){
            //     return {}
            // }
        },
        'placeholder': {
            default: 'Select one...'
        },
        'value': {

        },
        'name': {

        },
        'id': {

        },
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

    created: function(){

    },

    mounted: function(e){
        var self = this;
        var firstChar = this.source.substr(0, 1);

        var sourceSelector = (firstChar != '.' || firstChar != '#') ? '#' + this.source : this.source;
        var key = $(sourceSelector).attr('id') || $(sourceSelector).attr('name');

        // @TODO... multiple references to an ID will cause unnecessary multiple triggers.
        var trigger = 'change.' + $(this).attr('id');

        $(sourceSelector).off(trigger).on(trigger, function(e){
            self.retrieve(key, $(this).val());
        });

        this.retrieve(key, $(sourceSelector).val());


        // if (this.value) {
        //$(sourceSelector).trigger(trigger);
        // }

        //this.createSelect2();

    },

    data: function(){
        return {
            options: null,
            content: '',
            type: ''
        };
    },

    methods: {
        createSelect2: function(){
            var self = this;

            var $select = $('[name="' + this.name + '"]', this.$el);

            if ($select.hasClass('select2-applied')) {
                $select.select2('destroy');
            }

            $select
                // This is necessary for multi-selects not working correctly and only showing the last selected item.
                .val(this.value)
                .select2(this.getConfig())
                .addClass('select2-applied')
                .on('change', function(e) {

                    // Placeholders don't have an empty value so they take on the label value
                    // if ($(self.$el).attr('placeholder') === self.value) {
                    //     self.value = null;
                    // }

                    self.$emit('input', self.value)
                });
        },

        retrieve: function(key, val){
            var self = this;

            var urlParams = this.urlParams;
            if (typeof(urlParams) === 'string'){
                urlParams = JSON.parse('{"' + decodeURI(this.urlParams).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}')
            }

            if (!urlParams) {
                urlParams = $('.form').serializeArray();
            }

            urlParams[key] = val;

            $.get(this.url, urlParams, function(data){
                if (typeof(data) === 'string') {
                    // self.content = data;
                    var EmbeddedComponent = Vue.extend({
                        template: '<div class="dependent-input-wrapper-inner">' + data + '</div>'
                    });

                    $fields = $(self.$el).find('.form-group-inputs:first');

                    $fields.empty().append('<div class="dependent-input-wrapper-inner"></div>');
                    $wrapper = $fields.find('.dependent-input-wrapper-inner:first')[0];

                    var component = new EmbeddedComponent().$mount($wrapper);
                } else {
                    self.options = data;
                    self.$nextTick(function(){
                        self.createSelect2();
                    })

                }
            })
        },

        isSelected: function(val){
            return (val === this.value);
        },

        getConfig: function(){
            var config = this.config;
            config['theme'] = 'bootstrap4';
            config['tags'] = true;
            config['width'] = this.width;
            return config;
        }
    }

});
</script>