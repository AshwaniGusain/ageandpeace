<script>
// @TODO THIS IS INCOMPLETE BECAUSE IT WAS NOT DEEMED NEEDED AT THE MOMENT
Vue.component('snap-keyvalue-input', {
    template: '<div>\
                    <div>\
                        <div v-for="(row, index) in newValue" :key="index" class="mb-1 row">\
                            <div class="col-5"><input @keyup="updateValue()" type="text" class="form-control keyvalue-key" placeholder="Key..." :value="row.key"></div>\
                            <div class="col-5"><input @keyup="updateValue()" type="text" class="form-control keyvalue-value" placeholder="Value..." :value="row.value"></div>\
                            <div class="col-2"><a href="javascript:;" v-if="newValue.length != 1" class="btn btn-sm btn-secondary" @click="remove(index)"><i class="fa fa-trash"></i></i></a></div>\
                        </div>\
                        <div>\
                            <a href="javascript:;" @click="add()" class="mt-2 btn btn-sm btn-secondary">Add</a></div><input v-for="(row, index) in newValue" type="hidden" :name="name + \'[\' + row.key + \']\'" :value="row.value" :key="\'input\' + index">\
                        </div>\
                    </div>\
                </div>',
    props: {
        'name': {
            type: String,
            // required: true,
            default: ''
        },
        'value': {
            //type: Object,
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
        this.newValue = (this.value) ? this.convertFromObject(this.value) : [{key:'',value:''}];
    },

    data: function(){
        return {
            newValue: []
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
                let regEx = new RegExp(this.delimiter);
                let vals = oldVal.split(regEx);
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

        remove: function(index) {
            this.newValue.splice(index, 1);
            // $(this.$el).find('.keyvalue-key:eq(' + index + ')').remove();
            // this.updateValue();
        },

        updateValue: function(){
            var keys = $(this.$el).find('.keyvalue-key');
            var values = $(this.$el).find('.keyvalue-value');
            newValue = [];
            keys.each(function(i){
                let val = $(values.get(i)).val();
                //if (val) {
                let keyval = {key: $(this).val(), value: val};
                    newValue.push(keyval);
               // }
            });

            this.newValue = newValue;
        },

        convertFromObject: function(data){
            let obj = [];
            for (var n in data) {
                obj.push({'key': n, 'value': data[n]})
            }

            return obj;
        }

    }

});
</script>