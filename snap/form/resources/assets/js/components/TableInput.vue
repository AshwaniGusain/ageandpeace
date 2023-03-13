<script>
Vue.component('snap-table-input', {
    template: '<div><div v-if="createButton"><a v-if="createUrl" v-bind:href="createUrl" @click.prevent="showModal(createUrl)" class="mt-2 btn btn-sm btn-secondary">{{ createButton }}</a></div><div class="table-wrapper"></div></div>',

    props: {
        'tableUrl': {
            type: String,
            required: true,
            default: null
        },
        'createUrl': {
            type: String,
            required: false,
            default: null
        },
        'column': {
            type: String,
            required: false,
            default: 'id'
        },
        'order': {
            type: String,
            required: false,
            default: 'asc'
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

        if (!this.tableUrl) {
            alert('You must specify an AJAX url to pull in the table information');
        }

       // this.tableUrl = 'http://age-and-peace.local/admin/post/table?limit=3&sort=-title&fields=name,title';
        this.load();

        $(this.$el).on('click', '.table-action', function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            self.showModal(url);
        });
    },

    methods: {
        load: function(){
            var self = this;
            $.get(this.tableUrl, {}, function(html){

                var EmbeddedComponent = Vue.extend({
                    template: '<div class="table-wrapper-inner">' + html + '</div>'
                });
                $('.table-wrapper', self.$el).empty().append('<div class="table-wrapper-inner"></div>');
                var component = new EmbeddedComponent().$mount('.table-wrapper-inner');

            });
        },
        showModal: function showModal(url) {
            SNAP.ui.modal.load(url, null, this.load).show();
        }
    }


});
</script>