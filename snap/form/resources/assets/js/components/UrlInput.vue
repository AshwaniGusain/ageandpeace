<script>
// @TODO THIS IS INCOMPLETE BECAUSE IT WAS NOT DEEMED NEEDED AT THE MOMENT
Vue.component('snap-url-input', {
    //template: '<div>\<div v-for="(row, index) in newValue" :key="index" class="mb-1 row"><div class="col-6"><input @keyup="updateValue()" type="text" class="form-control keyvalue-key" placeholder="Key..." :value="row.key"></div> <div class="col-6"><input @keyup="updateValue()" type="text" class="form-control keyvalue-value" placeholder="Value..." :value="row.value"></div></div><div><a href="javascript:;" @click="add()" class="mt-2 btn btn-sm btn-secondary">Add</a></div><input v-for="(row, index) in newValue" type="hidden" :name="name + \'[\' + row.key + \']\'" :value="row.value" :key="\'input\' + index"></div></div>',
    props: {
        'name': {
            type: String,
            // required: true,
            default: ''
        },
        'value': {
            type: Object,
            required: false,
            default: function() {
                return [{key:'',value:''}];
            }
        },
        'placeholder': {
            type: String,
            required: false,
            default: 'Enter in a value on each line...'
        }
    },

    mounted: function(){
        //this.newValue = (this.value) ? this.value : this.newValue = [{key:'',value:''}];
    },

    data: function(){
        return {
            //newValue: [],
        };
    },

    computed: {
        inputName: function(key){
            return this.name + '[' + key + ']';
        },
        displayValue: {
            get: function() {
                if (this.newValue) {
                    return this.newValue.join("\n");
                }
            },
            set: function(oldVal){
                var regEx = new RegExp(this.delimiter);
                var vals = oldVal.split(regEx);
                this.newValue = vals;
            }
        }
    },

    destroyed: function () {

    },

    methods: {

        add: function(){
            this.newValue.push([{key:'', value:''}]);
        },

        updateValue: function(){
            var keys = $(this.$el).find('.keyvalue-key');

            var values = $(this.$el).find('.keyvalue-value');
            newValue = [];
            keys.each(function(i){
                var keyval = {key: $(this).val(), value: $(values.get(i)).val()};
                newValue.push(keyval);
            });


            this.newValue = newValue;
        }

    }

});
</script>