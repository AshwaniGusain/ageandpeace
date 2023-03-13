<script>
Vue.component('snap-currency-input', {
    //template : '<div><input type="text" :name="name + \'_formatted\'" v-model="formattedAmount" v-on:blur="money" :id="id" class="form-control"><input type="hidden" :name="name" v-model="amount" v-on:blur="money"></div>',
    template: '<div class="input-group">\
    <div class="input-group-prepend">\
        <span class="input-group-text">{{ symbol }}</span>\
    </div>\
        <input type="hidden" :name="name" v-model="amount" v-on:blur="money">\
        <input type="text" :name="name + \'_formatted\'" ref="amount" v-model="formattedAmount" v-on:blur="money" :id="computedId" class="form-control">\
    </div>',

props: {
        'name': {
            type: String,
            // required: true,
            default: ''
        },
        'symbol': {
            type: String,
            // required: true,
            default: ''
        },
        'min': {
            type: Number,
            required: false,
            default: 0
        },
        'max': {
            type: Number,
            required: false,
            default: null
        },
        'id': {
            type: String,
            // required: true,
            default: ''
        },
        'value': {
            //type: Number,
                // required: true,
                default: 0
            }

    },

    mounted: function(){
        $(this.$refs['amount']).val(this.value);
        this.money();
    },

    data: function(){
        return {
            formattedAmount: null,
            amount: null
        }
    },

    // Needed for js validation
    watch: {
        formattedAmount : function(value) {
            this.$emit('input', value);
        }
    },

    computed: {
        computedId: function(){
            if (!this.id) return this.name;
            return this.id;
        }
    },

    methods: {
        money: function () {
            var number = $(this.$refs['amount']).val().replace(/[^\d.-]/g, '');
            number = (!number.length || isNaN(number)) ? 0 : parseFloat(number);
            number = this.restrict(number);

            this.formattedAmount = this.format(number);
            this.amount = number;
            return number;
        },

        restrict: function(number){
            if (this.min && this.min < this.max && number < this.min) {
                number = this.min;
            }
            if (this.max && number > this.max){
                number = this.max;
            }
            return number;
        },

        format: function(val){
            return parseFloat(val).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
    }
});
</script>