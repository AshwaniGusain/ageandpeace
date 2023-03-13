<script>
Vue.component('snap-resource-compare', {

    mounted: function() {

    },

    data: function() {
        return {

        }
    },

    methods: {
        restore: function(e){
            if ( ! confirm('Are you sure you want to replace the current data with the archived version?')){
                e.preventDefault();
            }
        }
    }
});
</script>