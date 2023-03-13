<script>
//require('fullcalendar');

Vue.component('snap-calendar', {
    template: '<div class="calendar-container" ref="calendar"></div>',
    props: {
        'options': {
            type: Object
        }
    },

    mounted: function () {
        var self = this;
        this.options['eventClick'] = function(e){
            window.location = SNAP.paths.get('module') + '/' + e.id + '/edit';
        };

        this.ui = $(this.$el).fullCalendar(this.options);

    },

    data: function(){
        return {
        };
    }
})
</script>