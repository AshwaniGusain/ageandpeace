<script>
Vue.component('snap-inline', {

    props: {

    },

    mounted: function(){
        var self = this;
        SNAP.event.$on('modal-close', function(){
            self.closeModal();
        });

        if ($('#__close__').length) {
            self.closeModal();
        }
    },

    data: function(){
        return {
        };
    },

    methods: {
        closeModal: function(){
            window.parent.SNAP.ui.$refs['modal'].hide();

            //<input type="hidden" name="__redirect__" id="__redirect__" value="{{ $module->url('edit_inline', $resource) }}">
        },

        saveAndClose: function(){
            var val = $('#__redirect__').val();
            $('#__redirect__').val(val + '?__close__=1');
        }
    }
});
</script>