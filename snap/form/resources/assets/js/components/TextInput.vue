<script>
Vue.component('snap-text-input', {

    // template : '<div><input v-model="inputValue" @input=\'$emit("input", inputValue)\' class="form-control"><span>Remaining: {{ remaining }}</span></div>',
    // model: {
    // 	prop: 'inputValue',
    // 	event: 'change',
    // },

    props: {
        inputValue: {
            type: String,
            required: false,
            default: null
        },
        max: {
            type: Number,
            required: false,
            default: null
        }
    },

    mounted: function(){
        this.val = this.inputValue;
    },

    data: function(){
        return {
            val: ''
        };
    },

    computed: {
        remainingClass: function() {
            if (this.remainingPercentage <= 5) {
                return 'text-warning';
            } else if (this.remaining < 0) {
                return 'text-danger';
            }
        },

        remaining : function(){
            if (!this.val) {
                return this.max;
            }
            return this.max - this.val.length;
        },

        remainingPercentage: function(){
            return (this.remaining/this.max) * 100;
        }
    }

});
</script>
