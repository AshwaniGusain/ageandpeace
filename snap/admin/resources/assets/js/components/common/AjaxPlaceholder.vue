<script>
Vue.component('snap-ajax-placeholder', {
    template: '<div class="snap-ajax-placeholder"><div class="snap-loader"></div></div>',

    props: {
        'url': {
            type: String,
            required: true
        },
        params: {
            type: Object,
            required: false
        }
    },

    mounted: function(){
        this.load(this.url, this.params);
    },

    data: function(){
        return {

        };
    },

    methods: {

        load: function(url){
            let self = this;
            $.get(url, this.params, function (html) {

                self.$nextTick(function(){
                    var EmbeddedComponent = Vue.extend({
                        name: 'ajax-placeholder',
                        template: '<div>' + html + '</div>'
                    });

                    new EmbeddedComponent().$mount(self.$el);
                });
            });
        }
    }

});

</script>

