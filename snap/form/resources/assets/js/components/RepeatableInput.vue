<script>
Vue.component('snap-repeatable-input', {
    props: {
        'name': {
            type: String,
            required: true,
            default: ''
        },
        'depthInit': {
            type: Number,
            required: false,
            default: 0
        },
        'url': {
            type: String,
            required: false,
            default: ''
        },
        'sortable': {
            type: Boolean,
            required: false,
            default: true
        },
        'min': {
            type: Number,
            required: false,
            default: null
        },
        'max': {
            type: Number,
            required: false,
            default: null
        },
        'warn': {
            type: String,
            required: false,
            default: 'Are you sure you want to remove this item?'
        },
        'collapse': {
            type: Boolean,
            required: false,
            default: false
        },
        'containerClass': {
            type: String,
            required: false,
            default: 'repeatable'
        },
        'rowClass': {
            type: String,
            required: false,
            default: 'repeatable-row'
        },
        'ghostClass': {
            type: String,
            required: false,
            default: 'sort-ghost'
        },
        'draggableSelector': {
            type: String,
            required: false,
            default: '.repeatable-row'
        },
        'handleSelector': {
            type: String,
            required: false,
            default: '.grabber'
        },
        'removeable': {
        	type: Boolean,
            required: false,
        	default: true
        }
    },

    mounted: function(){

        let self = this;
        this.scope = this.name;
        this.depth = this.depthInit;
        this.rows = this.$children;

        this.$nextTick(function(){
            if (this.depth == 0) {
                self.updateInputIndexes();
            }
        });

        if (this.sortable){
            // @TODO... put .repeatable-rows as a prop
            $(this.$el).find('> .repeatable-rows').sortable({
                helper: 'clone',
                handle: this.handleSelector,
                appendTo: 'body',
                start : function(e, ui) {
                    ui.item.startIndex = ui.item.index();
                },
                update: function(e, ui) {
                    let newIndex = ui.item.index();
                    let oldIndex = ui.item.startIndex;
                    self.reorder(oldIndex, newIndex);
                }
            });
        }

        this.checkMaxAndMin();

        this.$on('remove-row', function(row){
            self.remove(row);
        });
    },

    // template: html,
    data: function(){
        return {
            depth: 0,
            indexValue: 0,
            scope: '',
            removable: false,
            prefix: '',
            values: {},
            displayed: true,
            canAdd: true,
            canRemove: true
        };
    },

    computed: {
        index: {
            get: function(){
                return this.indexValue;
            },
            set: function(newValue){
                let self = this;

                this.indexValue = newValue;
                this.$nextTick(function(){
                    // self.updateInputIndexes();
                });
            }
        }
    },

    methods: {
        reorder: function(oldIndex, newIndex){
            this.rows.splice(newIndex, 0, this.rows.splice(oldIndex, 1)[0]);
            this.updateInputIndexes();
            this.$emit('repeatable-sorted', this);
            this.$emit('repeatable-updated', self);
        },

        updateInputIndexes: function(){

            let self = this;

            if (!self.prefix.length) {
                self.prefix = self.scope;
            }

            this.rows.forEach(function(row, i){
                row.index = i;

                // First, we need to loop through all inputs and labels and assign a depth data attribute.
                // Nested RepeatableFields will simply overwrite their children's depths to the new depth values.
                $(row.$el).find(':input, label').each(function(){
                    $(this).attr('data-input-depth', self.depth);
                });

                // The depth property is crucial for grabbing only those
                // input elements at a specific depth to alter their names.
                $(row.$el).find(':input[data-input-depth=' + self.depth +'], label[data-input-depth=' + self.depth +']').each(function(index){
                    self.updateInputAttributes(this, i);
                });

                // If the row is a "nested" Repeatable input, then we
                // set the new prefix and update the names on the child
                // inputs to have the correct nested array indexes.
                let $nestedRepeatables = $(row.$el).find('.repeatable');
                if ($nestedRepeatables.length) {
                    $nestedRepeatables.each(function(j){
                        // Can't use $refs because of them being dynamically mounted and,
                        // if added via AJAX and mounted with add method, they won't
                        // have a reference. Instead we grab the __vue__ reference
                        // hidden on the DOM node... Let me know if you can come up
                        // with a better way.
                        // https://stackoverflow.com/questions/26915193/dom-element-to-corresponding-vue-js-component
                        let repeatable = this.__vue__;
                        let prefix = $(this).attr('data-prefix');

                        repeatable.prefix = self.scope +  '[' + i + '][' + repeatable.scope + ']';
                        repeatable.updateInputIndexes();
                    });
                }
            })

        },

        updateInputAttributes: function(elem, i){
            let $elem = $(elem);

            if (!$elem.data('orig')) {
                if ($elem.is('label') && !$elem.data('orig')){
                    $elem.data('orig', this.extractOriginalInputName($elem.attr('for')));
                } else {
                    $elem.data('orig', this.extractOriginalInputName($elem.attr('name')));
                }
            }

            let id = this.prefix.replace(/\[/g, '-').replace(/\]/g, '') + '-' + i + '-' + $elem.data('orig');
            if ($elem.is('label')){
                $elem.attr('for', id);
            } else {
                $elem.attr('id', id);
                $elem.attr('name', this.prefix + '[' + i + '][' + $elem.data('orig') + ']');
            }

            // We need to re-trigger the checked state on inputs that are checked
            // because the browser will visually check the last one with the same name
            // and multiple rows will all have the same name initially.
            if ($elem.attr('checked')) {
                $elem.prop('checked', true);
            }

        },

        extractOriginalInputName: function(name){
            let lastBeginBracket = name.lastIndexOf('[') + 1;
            if (lastBeginBracket > 0) {
                let lastEndBracket = name.lastIndexOf(']');
                return name.substring(lastBeginBracket, lastEndBracket);
            }

            return name;
        },

        isNested: function(row){
            return !!$(row.$el).find('.' + this.containerClass).length;
        },

        checkMaxAndMin: function(){
            this.checkMax();
            this.checkMin();
        },

        checkMax: function(){
            if (this.max) {
                this.canAdd = (this.rows.length < this.max);
            }
        },

        checkMin: function(){
            if (this.rows.length <= 1) {
                this.removable = false;
            } else if (this.min) {
                this.removable = (this.rows.length > this.min);
            } else {
                this.removable = true;
            }
        },

        getRepeatableInput: function(elem){
            let input = $(elem).data('input');
            this.inputs.push(input);
            let $parent = $(elem).parents('.' + this.containerClass + ':first');
            if ($parent.length) {
                return this.getRepeatableInput($parent);
            } else {
                return this.inputs.reverse().join('.');
            }
        },

        add: function(e){
            e.preventDefault();
            let self = this;
            if (this.canAdd){

                this.inputs = [];
                let input = this.getRepeatableInput(this.$el);
                let url = this.url + '/' + input;

                let params = $(this.$el).closest('form').find('[data-ajax-param]').serialize();

                $.get(url, params, function(html){

                    // $.get(this.url, params, function(html){
                    $(self.$refs['repeatable-rows']).append('<div></div>');
                    let repeatable = $(self.$refs['repeatable-rows']).children(':last')[0];
                    //html = '<snap-repeatable-row name="' + self.name + '" :depth-init="' + self.depth + '" ref="row" v-on:removed="remove" class="list-group-item repeatable-row" :data-depth="depth" inline-template>' + html + '</snap-repeatable-row>';
                    let component = new RepeatableRow({ el: repeatable, template: html, data: { prefix: self.prefix, depth: self.depth, indexValue: self.rows.length, scope: self.scope }});
                    self.rows.push(component.$children[0]);

                    // component.$mount();
                    // $(self.$refs['repeatable-rows']).append(component.$el);
                    // self.$nextTick(function(){
                        self.updateInputIndexes();
                        self.checkMaxAndMin();
                        self.$emit('repeatable-added', self, self.rows.length);
                        self.$emit('repeatable-updated', self);
                    // })
                });
            }
        },

        remove: function(row){
            //if (this.removable) {
                if (confirm(this.warn)){
                    let self = this;
                    //
                    // console.log(index)
                    this.rows.splice(row.index, 1);
                    row.$destroy(); // must be after splice
                    // console.log(this.rows)

                    // this.$nextTick(function(){
                    $(row.$el).remove();
                    self.updateInputIndexes();
                    self.checkMaxAndMin();
                    self.$emit('repeatable-removed', row.index, self);
                    self.$emit('repeatable-updated', self);
                    // })
                }
            //}
        }
    }
});

let RepeatableRow = Vue.extend({

    mounted: function(){
        let self = this;

        this.$nextTick(function(){
            self.values = self.getRowValues();
        });

        if (this.parentElem) {
            this.displayed = !this.parentVue.collapse;
        }
    },

    data: function(){
        return {
            values: true,
            depth: 0,
            rowToggle: [],
            index: 0,
            scope: '',
            prefix: '',
            displayed: true
        };
    },

    computed: {

        // So ugly!!! But this seems to be the most reliable way to get a
        // reference to the $parent Vue object with items being AJAXed in.
        parentVue: function() {
            return this.parentElem.__vue__;
        },

        parentElem: function() {
            return $(this.$el).closest('.repeatable')[0];
        },

        num: function(){
            return this.index + 1;
        }

    },

    methods: {

        toggleRowDisplay: function(){
            this.displayed = ! this.displayed;
        },

        isRowDisplayed: function() {
            return this.displayed !== 0;
        },

        getRowValues: function(){
            let values = {};
            $(this.parentElem).find(':input').each(function(i){
                let $elem = $(this);
                values[$elem.attr('name')] = $elem.val();
            });
            return values;
        },

        remove: function(){
            this.parentVue.$emit('remove-row', this);
            //this.parentVue.$emit('remove-row', this);
            // Not sure why I need to use parentVue...
            // Please let me know if you figure it out...
            // The console clearly shows that this is a SnapRepeatableRow
            // that is a child of a SnapRepeatableField
            //this.parentVue.remove(this);
        }

        // hasNested: function(){
        //     console.log(this.$parent.containerClass)
        //     return $(this.$el).find('.' + this.$parent.containerClass).length;
        // }
    }

});

Vue.component('snap-repeatable-row', RepeatableRow);


</script>