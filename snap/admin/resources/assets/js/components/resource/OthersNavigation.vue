<script>
Vue.component('snap-others-navigation', {

    mounted: function() {

    },

    data: function() {
        return {
            others: ''
        }
    },

    methods: {
        go: function(){
            var id = $(this.$refs['others']).val();
            SNAP.event.$emit('removeCheckSave');
            var url = SNAP.paths.get('module') + '/' + id + '/edit';
            window.location = url;
        }
    }
});
</script>