<script>
Vue.component('snap-data-table', {
    // template: '<div class="datatable-container"></div>',
    props: {
        'url': {
            type: String,
            required: false
        },
        'inline': {
            required: false,
            default: false
        }
    },
    mounted: function () {
        let self = this;
        let $elem = $(this.$el);
        this.ref = this;

        // this.ajaxUrl = $elem.find('.data-table-url').val();
        this.ajaxUrl = this.url;

        // Wrap it so we can get a parent and replace it with AJAX calls
        // $elem.wrap('<div class="datatable-container"></div>');
        // this.$parentElem = $elem.parent();
        $(document).on('click', 'th.sortable', function(e){
            self.sortBy(this);
        });

        $(document).on('click', "td[class^='table-col']", function(e){
            if ($(this).find('a').length === 0) {
                e.preventDefault();
                let actionsCol = $(this).parent().find('td.actions');
                if (actionsCol.length) {
                    if (self.inline) {
                        $('a:first', actionsCol[0]).trigger('click');
                    } else {
                        let firstLink = $('a:first', actionsCol[0]).attr('href');
                        if (firstLink){
                            window.location = firstLink;
                        }
                    }
                }
            }
        });

        // Using jQuery events was more reliable than using @click
        $(document).on('click', ".column-toggle", function(e){
            e.stopImmediatePropagation();
            self.toggleColumnDisplay($(this).val());
        });

        $('#limit').on('change', function(e){
            SNAP.event.$emit('submit-form');
        });

        SNAP.event.$on('datatable-refresh', function(){
            self.loadData({ sort: self.sort });
        });

        this.$nextTick(function(){

            let hiddenColumns = SNAP.storage.getLocal(SNAP.ui.getStorageKey('hidden_columns'));

            if (hiddenColumns) {
                hiddenColumns.forEach(function(value){
                    $('.dropdown input[data-column="' + value + '"]', self.$el).click();
                })
            }
        })
    },

    data: function(){
        return {
            id: null,
            order: 'asc',
            col: null,
            sort: null,
            columns: [],
            ref: null,
        };
    },

    methods: {

        sortBy: function (th) {
            let self = this;
            let $th = $(th);
            let $elem = $(this.$el);
            this.col = $th.data('column');

            this.order = ($th.hasClass('asc')) ? 'desc' : 'asc';
            let o = (this.order === 'desc') ? '-' : '';
            this.sort = o + this.col;

            // remove all active states
            $elem.find('.active').removeClass('active');

            // set active state to only this column
            $th.addClass("active");
            let params = 'sort=' + this.sort + '&' + window.location.search.substring(1);
            SNAP.event.$emit('datatable-data-requested', self.el);

            this.loadData(params, function(){
                $th.removeClass('asc desc').addClass(self.order + ' active');

                // Update pagination with sort parameter
                $('.pagination .page-item > a').each(function(){
                    let search = window.location.search.substring(1);
                    let params = JSON.parse('{"' + decodeURI(search).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}')
                    params.sort = self.sort;
                    this.search = '?' + $.param(params);
                })
            });
        },

        setTHClasses: function(th, order){
            $(th).removeClass("asc desc").addClass(order + ' active');
        },

        toggleColumnDisplay: function(column){
            let selector = 'th[data-column="' + column + '"], .table-col-' + column;
            if ($(selector).is(':hidden')) {
                $(selector).show();
            } else {
                $(selector).hide();
            }

            // Persist
            let hidden = [];
            $('.column-toggle', this.$el).each(function(i){
                if (!$(this).prop('checked')){
                    hidden.push($(this).data('column'));
                }
            });

            SNAP.storage.setLocal(SNAP.ui.getStorageKey('hidden_columns'), hidden);
        },

        loadData: function(params, callback){
            let self = this;
            //let $elem = $(this.$el);
            // @TODO Loading image
            // $elem.prepend('LOADING...');

            $.get(this.ajaxUrl, params, function(html){
                SNAP.event.$emit('datatable-data-received', self.$el);
                $(self.$el).empty();

                // This is to instantiate Vue.js components that may be used
                // in the table as formatters.
                var EmbeddedComponent = Vue.extend({
                    template: html
                });

                // Need to setup new reference here because it when mounted it gets lost
                self.ref = new EmbeddedComponent().$mount(self.ref.$el);

                //$(self.$el).html(html);
                self.show = true;
                // self.$forceUpdate();

                if (callback) callback();
            })
        }
    }
})
</script>