<script>
    Vue.component('snap-slug-input', {
        template: '<div class="row">\
                    <div class="col-md-10">\
                        <div  v-if="prefix" class="input-group">\
                            <div class="input-group-prepend">\
                                <span class="input-group-text">{{ prefix }}</span>\
                            </div>\
                            <input type="text" v-model="slugify" v-on:change="changed" />\
                        </div>\
                        <div v-else><input type="text" v-model="slugify" v-on:change="changed" /></div>\
                    </div>\
                    <div class="col-md-1">\
                        <button type="button" class="btn btn-sm btn-secondary mt-1"><i class="fa fa-refresh" @click="update(getBoundTo().val())"></i></button>\
                    </div>\
                </div>',
        //template : '<input type="text" v-model="slugify" v-on:change="changed" />',
        //inheritAttrs: false,
        props: {
            boundTo: {
                type: String,
                required: true,
                default: 'title'
            },
            context: {
                type: String,
                required: false,
                default: null
            },
            prefix: {
                type: String,
                required: false,
                default: null
            },
            attrs: {
                type: Object,
                required: true,
                default: {}
            }
        },

        mounted: function(e){
            this.touched = false;
            this.transform = $(this.$el).val() ? false : true;

            var self = this;
            var $input = this.getInput();
            var $boundTo = this.getBoundTo();

            // https://forum.vuejs.org/t/assign-props-attributes-to-sub-element-in-component/6259/6
            // https://jsfiddle.net/th3l0nius/3xknzjvv/3/
            // @TODO... create a mixin for this...
            Object.entries(this.attrs).forEach(function([key,val]){
                $input.attr(key, val);
            });

            // Bind events
            $boundTo.on('keyup', function(e){
                var val = $(this).val();
                if (!val.length && !this.getInput().val().length) {
                    this.makeChangeable();
                }

                if (self.transform && !self.touched){
                    self.update(val);
                }
            });

            $(this.$el).on("blur", function() {
                self.$emit('input', $input.val());
            });
        },

        data: function(){
            return {
                slug: '',
                touched: false,
                transform: false,
            };
        },

        computed: {
            slugify: {
                // getter
                get: function () {
                    return this.slug;
                },
                // setter
                set: function (newValue) {
                    return this.slugIt(newValue);
                }
            }
        },

        methods: {

            makeChangeable: function(){
                this.touched = false;
                this.transform = true;
            },

            changed: function(){
                if (!this.slug.length && !$(this.boundTo).val()) {
                    this.makeChangeable();
                } else {
                    this.touched = (this.slug.length) ? true : false;
                }
            },

            getBoundTo: function(){
                var $boundTo = null;
                var selector = (this.boundTo.substr(0, 1) != '#' && this.boundTo.substr(0, 1) != '.') ? 'input[name="' + this.boundTo + '"]' : this.boundTo;
                if (this.context) {
                    var $context = $(this.$el).closest(this.context);
                    $boundTo = $context.find(selector);

                } else {
                    $boundTo = $(selector);
                }

                return $boundTo;
            },

            getInput: function(){
                return $(this.$el).find('input');
            },

            update: function(val){
                this.slugIt(val);
            },

            slugIt: function(newValue){
                var output = newValue.toString().toLowerCase()
                    .replace(/[^\w-]+/g, ' ')
                    .replace(/\s+/g,'-')
                ;
                // $(this.$el).val(output); // needed to set immediately
                this.slug = output;
                return output;
            }
        }

    });
</script>