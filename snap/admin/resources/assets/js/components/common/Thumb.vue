<script>
Vue.component('snap-thumb', {
    template: '<div style="position: relative;"><div v-if="!error"><div v-if="!loaded" class="snap-loader" ref="loader"></div><a :href="thumbUrl" target="_blank" :style="{ minHeight: \'50px\'}"><img v-on:load="hideLoader()" :src="thumbUrl" alt="" :style="{width: width, maxHeight: height}"/></a></div><div v-if="error" v-html="errorMessage"></div></div>',

    props: {
        'url': {
            type: String,
            required: false,
            default: 'line'
        },
        'width': {
            required: false,
            default: '100%'
        },
        'height': {
            required: false,
            default: 'auto'
        },
        timeout: {
            required: false,
            default: 20000, // 20 seconds
        },
        errorMessage: {
            required: false,
            default: 'Error loading thumbnail.'
        }
    },

    mounted: function(){
        this.thumbUrl = this.url + '?c=' + (new Date()).getTime();

        var self = this;
        setTimeout(function(){
            if (!self.loaded) {
                self.error = true;
            }
        }, this.timeout);
    },

    data: function(){
        return {
            thumbUrl: '',
            loaded: false,
            error: false,
        };
    },

    methods: {
        hideLoader: function(){
            this.loaded = true;
        }
    }

});
</script>