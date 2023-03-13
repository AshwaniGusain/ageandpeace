<script>
    Vue.component('snap-date-input', {
        template:   '<div class="input-group date">\
                    <input type="text" :name="name + \'_formatted\'" :id="computedId" :value="dateFormatted" class="form-control" :placeholder="placeholder">\
                    <input type="hidden" :name="name" :value="date">\
                    <div class="input-group-append">\
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>\
                    </div>\
                </div>',
        props: {
            'name': {
                type: String,
                // required: true,
                default: ''
            },
            'id': {
                type: String,
                default: function(){
                    return this.name;
                }
            },
            'value': {
                type: String,
                required: false,
                default: ''
            },
            'placeholder': {
                type: String,
                required: false,
                default: 'mm/dd/yyyy'
            },
            'options': {
                type: Object,
                required: false,
                default: function () {
                    return {};
                }
            },
            'attrs': {
                type: Object,
                required: false,
                default: function () {
                    return {};
                }
            }
        },

        // http://www.daterangepicker.com/?ref=wireddots#ex5
        mounted: function(){
            var self = this;

            // Whoa... this is ugly. We need to be able to pass the "class" parameter and "add" it on the input not replace
            if (this.attrs['class']) {
                var $input = $(this.$el).find('input');
                $input.addClass(this.attrs['class']);
                delete this.attrs['class'];
            }

            delete this.attrs['id'];
            this.copyAttrs(this.attrs, $(this.$el).find('input'));

            var defaultOpts = {
                // parentEl: this.$el,
                locale: {
                    format: 'MM/DD/YYYY'
                },
                singleDatePicker: true,
                timePicker: false,
                autoUpdateInput: true
            };
            /*if (this.min) {
                opts.startDate = this.min;
            }
            if (this.max) {
                opts.endDate = this.max;
            }*/

            var opts = $.extend(defaultOpts, this.options);
            if (this.value) {
                opts.startDate = this.value;
                opts.endDate = this.value;
                this.date = moment(new Date(this.value)).format('YYYY-MM-DD');
                this.dateFormatted = moment(new Date(this.value)).format(opts.locale.format);
            }

            var $elem = $(this.$el);
            $elem.daterangepicker(opts);

            $elem.on('apply.daterangepicker', function(e, picker){
                // For some reason... dateFormatted doesn't update
                self.dateFormatted = picker.startDate.format(opts.locale.format);
                //self.pickerInput().val(picker.startDate.format(opts.locale.format));
                self.date = picker.startDate.format('YYYY-MM-DD');
            });

            // Causes issues when manually typing in date
            /*$elem.find('input[type="text"]').on('change', function(e){
                if ($(this).val()) {
                    self.date = moment(new Date($(this).val())).format('YYYY-MM-DD');
                }
            });*/

        },

        data: function(){
            return {
                date: null,
                dateFormatted: null,
            };
        },

        computed: {
            computedId: function(){
                if (!this.id) return this.name;
                return this.id;
            }
        },

        methods: {

            pickerInput: function(){
                return $(this.$el).find('input[type="text"]:first')
            },

            // initValue: function(value){
            //  var parts = value.split(' ');
            //  var datePart = parts[0];
            //  var $input = this.pickerInput();
            //  $input.datepicker('setDate', moment(value).toDate());
            //  this.formatValue(datePart);
            // },

            // formatValue: function(){
            //  var $input = this.pickerInput();
            //  var date = $input.datepicker('getDate');
            //  if (date) {
            //      this.date = moment(date).format('YYYY-MM-DD');
            //  }

            //  this.$emit('changed', this.date);
            // }
        }

    });
</script>