<script>
Vue.component('snap-resource-filters', {

    mounted: function() {
        var self = this;

        this.$nextTick(function(){
            if (SNAP.storage.getLocal(this.getStorageKey())) {
                $(this.$el).addClass('show');
                this.show();
            }
        });

        $(this.$el).on('show.bs.collapse', function(e){
            self.show();
        });

        $(this.$el).on('hide.bs.collapse', function(e){
            self.hide();
        });
    },

    data: function() {
        return {
            showFilters: false
        }
    },

    methods: {

        show: function(){
            var id = $(this.$el).attr('id');
            self.showFilters = 1;
            SNAP.storage.setLocal(this.getStorageKey(), 1);
            $('[href="#' + id + '"]').addClass('bg-dark');
            $('#snap-main-display').animate({
                scrollTop: $("#snap-main-display").offset().top - 114
            }, 300);
        },

        hide: function(){
            var id = $(this.$el).attr('id');
            self.showFilters = 0;
            SNAP.storage.setLocal(this.getStorageKey(), 0);
            $('[href="#' + id + '"]').removeClass('bg-dark');
        },

        getStorageKey: function(){
            return SNAP.paths.get('module') + '/' + SNAP.ui.getStorageKey('show_filters');
        }

    }
});
</script>