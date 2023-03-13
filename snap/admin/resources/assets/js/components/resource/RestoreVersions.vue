<script>
Vue.component('snap-restore-versions', {
    props: {
        'restoreId': {
            type: Number,
            required: true
        }
    },

    mounted: function() {

    },

    data: function() {
        return {
            version: ''
        }
    },

    methods: {
        go: function(){
            SNAP.event.$emit('removeCheckSave');
            var url = SNAP.paths.get('module') + '/' + this.restoreId + '/compare/' + this.version;
            window.location = url;
        }
    }
});
</script>