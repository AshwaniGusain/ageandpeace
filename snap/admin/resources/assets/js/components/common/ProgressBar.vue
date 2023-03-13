<script>
    Vue.component('snap-progressbar', {
        template: '<div class="progress" v-if="displayed"><div class="progress-bar" :class="{\'progress-bar-striped\': striped, \'progress-bar-animated\': animated}" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div>',

        props: {
            'striped': {
                type: Boolean,
                default: true,
                required: false
            },
            'animated': {
                type: Boolean,
                default: true,
                required: false
            },
        },

        mounted: function(){

        },

        data: function(){
            return {
                displayed: false
            };
        },

        methods: {
            show: function(){
                this.displayed = true;
                SNAP.event.$emit('snap-progressbar-show');
            },
            hide: function(){
                this.displayed = false;
                SNAP.event.$emit('snap-progressbar-hide');
            }

        }

    });
</script>