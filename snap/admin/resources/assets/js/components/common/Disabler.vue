<script>
Vue.component('snap-disabler', {
    template: '<div v-if="displayed" style="position: absolute; top: 0px; left: 0px; right: 0px; bottom: 0px; background-color: #fff; opacity: .5; z-index: 100000;"></div>',

    props: {

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
            SNAP.event.$emit('snap-disabler-show');
        },
        hide: function(){
            this.displayed = false;
            SNAP.event.$emit('snap-disabler-hide');
        }

    }

});
</script>