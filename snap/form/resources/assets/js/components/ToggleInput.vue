<script>
    Vue.component('snap-toggle-input', {
        template: '<input v-if="mode == \'checkbox\'" type="checkbox" :name="name" :value="value" v-model="checked" class="form-check-input" /><select v-else><slot></slot></select>',
        props: {
            'name': {
                type: String,
            },
            'context': {
                type: String,
                default: 'form'
            },
            'selector': {
                type: String,
                required: false,
                default: '.toggle'
            },
            'selectorContainer': {
                type: String,
                required: false,
                default: '.form-group'
            },
            'mode': {
                type: String,
                required: false,
                default: 'select'
            },
            'value': {

            },
        },

        created: function(){

        },

        mounted: function(e){
            let self = this;

            $(this.$el).on('change', function(e){
                if (self.mode === 'checkbox') {
                    if ($(this).is(':checked')) {
                        self.toggle($(this).val());
                    } else {
                        self.toggle(0);
                    }
                } else {
                    self.toggle($(this).val());
                }
            });

            this.$nextTick(function(){
                $(self.$el).trigger('change');
            });

            this.checked = this.value == 1;

            if (this.mode === 'checkbox') {
                $(this.$el).parent().prepend('<input type="hidden" name="' + this.name + '" value="0">');
            }
        },

        data: function(){
            return {
                options: {},
                checked: false
            };
        },

        methods: {
            toggle: function(val){
                let self = this;
                $(this.context).find(this.selector).each(function(i){
                    let toggleVals = $(this).attr('data-toggle-value');
                    if (toggleVals) {
                        toggleVals = toggleVals.split('|');
                        $(this).closest(self.selectorContainer).hide();
                        let show = false;

                        for (let i = 0; i < toggleVals.length; i++) {
                            let v = toggleVals[i];
                            if (v === val) {
                                show = true;
                                break;
                            }
                        }

                        if (show) {
                            $(this).closest(self.selectorContainer).show();
                        } else {
                            $(this).closest(self.selectorContainer).hide();
                        }
                    }
                })
            }

        }

    });
</script>