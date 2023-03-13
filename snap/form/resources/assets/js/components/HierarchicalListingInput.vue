<script>
    Vue.component('snap-hierarchical-listing-input', {
        template: '<div><div v-if="createButton"><a v-if="createUrl" v-bind:href="createUrl" @click.prevent="showModal(createUrl)" class="mt-2 btn btn-sm btn-secondary">{{ createButton }}</a></div><div class="listing-wrapper"></div></div>',

        props: {
            'listingUrl': {
                type: String,
                required: true,
                default: null
            },
            'createUrl': {
                type: String,
                required: false,
                default: null
            },
            'createButton': {
                required: false,
                default: 'Create'
            }
        },

        data: function(){
            return {
                content: '',
                module: null,
            };
        },

        mounted: function(){
            var self = this;

            if (!this.listingUrl) {
                alert('You must specify an AJAX url to pull in the listing information');
            }

            // this.tableUrl = 'http://age-and-peace.local/admin/post/table?limit=3&sort=-title&fields=name,title';
            this.load();

            $(this.$el).on('click', '.listing-action', function(e){
                e.preventDefault();
                var url = $(this).attr('href');
                self.showModal(url);
            });
        },

        methods: {
            load: function(){
                var self = this;
                $.get(this.listingUrl, {}, function(html){

                    var EmbeddedComponent = Vue.extend({
                        template: '<div class="listing-wrapper-inner">' + html + '</div>'
                    });
                    $('.listing-wrapper', self.$el).empty().append('<div class="listing-wrapper-inner"></div>');
                    var component = new EmbeddedComponent().$mount('.listing-wrapper-inner');

                });
            },
            showModal: function showModal(url) {
                SNAP.ui.modal.load(url, null, this.load).show();
            }
        }


    });
</script>