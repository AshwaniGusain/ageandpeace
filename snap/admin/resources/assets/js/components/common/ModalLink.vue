<script>
    Vue.component('snap-modal-link', {

        template : '<a href="#" @click.prevent="showModal()"><slot></slot></a>',
        props: {
            'href': {
                type: String,
                required: false,
                default: null
            },

            refreshSelector: {
                type: String,
                required: false
            }
        },

        mounted: function(){
            let self = this;
            SNAP.event.$on('snap-modal-saved', function(contentDoc){
                self.active = false;
                let $elem = $(self.refreshSelector);
                let refreshInput = $(contentDoc).find($elem.attr('data-refresh-selector'));
                $elem.html(refreshInput.val());
            });
        },

        data: function(){
            return {
                active: false,
            };
        },

        methods: {
            showModal: function(){
                let url = this.href;
                SNAP.ui.modal.load(url).show();
                this.active = true;
            }
        }

    });
</script>