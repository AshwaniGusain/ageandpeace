<script>
    Vue.component('snap-mirror-input', {
        template : '<input type="text" v-model="match" v-on:change="changed" />',

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
            mask: {
                type: String,
                required: false,
                default: '[^A-Za-z0-9\/]'
            },

            maskReplacer: {
                type: String,
                required: false,
                default: ''
            }
        },

        mounted: function(e){
            let self = this;
            let $boundTo;
            if (this.context) {
                let $context = $(this.$el).closest(this.context);

                $boundTo = $context.find('input[name="' + this.boundTo + '"]');

                // if (this.boundTo.substr(0, 1) === '#' || this.boundTo.substr(0, 1) === '.' || this.boundTo.substr(0, 1) === '[') {
                //     var $boundTo = $context.find(this.boundTo);
                // } else {
                //     var $boundTo = $context.find('[data-id="' + this.boundTo + '"]');
                // }
            } else {
                $boundTo = $('input[name="' + this.boundTo + '"]');
            }

            this.touched = false;
            this.transform = $(this.$el).val() ? false : true;
            $boundTo.on('keyup', function(e){

                let val = $(this).val();
                if (!val.length && !$(self.$el).val().length) {
                    self.makeChangeable();
                }

                if (self.transform && !self.touched){
                    self.match = val;
                }
            });

            $(this.$el).on("blur", function() {
                self.$emit('input', $(this).val());
            });
        },

        data: function(){
            return {
                mirror: '',
                touched: false,
                transform: false
            };
        },

        computed: {
            match: {
                // getter
                get: function () {
                    return this.mirror;
                },
                // setter
                set: function (newValue) {
                    // var output = newValue.toString().toLowerCase()
                    //     .replace(/ /g,'-')
                    //     .replace(/[^\w-]+/g,'');
                    // // $(this.$el).val(output); // needed to set immediately
                    if (this.mask) {
                        let maskRegExp = new RegExp(this.mask, 'g');
                        newValue = newValue.replace(maskRegExp, this.maskReplacer);
                    }

                    this.mirror = newValue;
                }
            }
        },

        methods: {

            makeChangeable: function(){
                this.touched = false;
                this.transform = true;
            },

            changed: function(){
                if (!this.mirror.length && !$(this.boundTo).val()) {
                    this.makeChangeable();
                } else {
                    this.touched = (this.mirror.length) ? true : false;
                }
            }
        }

    });
</script>