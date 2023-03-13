<script>
Vue.component('snap-hierarchical-listing', {
    props: {
        'updateUrl': {
            type: String,
            required: true
        },
        'sortable': {
            type: Boolean,
            required: false,
            default: false
        },
        'nestingDepth': {
            type: Number,
            required: false
        }
    },

    mounted: function(){
        var self = this;
        this.container = $(self.$el).find('.resource-list-items');
        this.initSorting();
    },

    data: function(){
        return {
            name: 'list',
            showDelete: true,
            itemsSelected: [],
            sorting: false,
        };
    },
    methods: {

        loadResource: function(id, event){
            this.$parent.editResource(id);
        },

        initSorting: function(){
            if (!this.hasSorting()) {
                return;
            }

            var self = this;

            $('.list-group-item', this.$el).each(function(i){
                $(this).data('precedence', i + 1);
            });

            this.container.nestedSortable({
                handle: '.grabber',
                items: 'li',
                // toleranceElement: '> div',
                listType: 'ul',
                isTree: false,
                maxLevels: self.nestingDepth,
                // disabled: true, // Disable it at first
                update: function( event, ui ) {
                    self.updateOrder();
                }
            });
        },

        hasSorting: function(){
            return this.sortable;
        },

        toggleOrdering: function(){
            if (this.ordering){
                this.disableSorting();
            } else {
                this.enableSorting();
            }
        },

        enableSorting: function(){
            this.container.sortable("enable");
            this.sorting = true;
        },

        disableSorting: function(){
            this.container.sortable("disable");
            this.sorting = false;
        },

        updateOrder: function(){
            var listData = this.container.nestedSortable('toArray', {startDepthCount: 0});
            var params = { data: listData };

            if (confirm('Are you sure you want to change the order of this item?')) {
                $.post(this.updateUrl, params, function(html){

                })
            } else {
                this.container.sortable('cancel');
            }
        }
    }
});
</script>