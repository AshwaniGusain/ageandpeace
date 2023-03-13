<script>
    Vue.component('snap-modal-alert-messages', {

        template : '<div class="snap-alert-messages"><slot></slot></div>',
        props: {
            'displayTime': {
                type: Number,
                required: false,
                default: null
            }
        },

        mounted: function(){
            if (this.displayTime) {
                setTimeout(function(){
                    $('.alert-success', this.$el).slideUp('slow');
                }, this.displayTime)
            }
        },

        data: function(){
            return {

            };
        },

        methods: {

        }

    });
</script>