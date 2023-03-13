<script>
Vue.component('snap-number-input', {
    template : '<input type="text" v-on:change="changed" />',

    props: {
        max: {
            type: Number,
            required: false
        },
        min: {
            type: Number,
            required: false
        }
    },

    mounted: function(e){
        var self = this;
    },

    data: function(){
        return {
            oldValue: 0
        };
    },

    methods: {
        changed: function(e){
            var value = e.target.value;

            if (value > this.max) {
                e.target.value = this.max;
            } else if (value < this.min) {
                e.target.value = this.min;
            }
        }
    }

});
</script>