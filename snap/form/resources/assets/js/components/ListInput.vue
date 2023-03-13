<script>
    Vue.component('snap-list-input', {
        template: '<div><textarea class="form-control" v-model="displayValue" :placeholder="placeholder" style="min-height: 100px;"></textarea><div v-if="cleanedNewValue.length"><input v-for="(item, index) in cleanedNewValue" type="hidden" :name="inputName" :value="item" :key="index"></div><input v-else type="hidden" :name="inputName" value=""></div>',
        // template : '<select v-model="value"><option v-for="(option, index) in opts" v-bind:value="index">{{ option }}</option></select>',
        props: {
            'name': {
                type: String,
                // required: true,
                default: ''
            },
            'value': {
                type: Array,
                required: false
            },
            'placeholder': {
                type: String,
                required: false,
                default: 'Enter in a value on each line...'
            },
            'delimiter': {
                type: String,
                required: false,
                default: "\s*\n|,\s*"
            }

        },

        mounted: function(e){
            this.newValue = this.value;
        },

        data: function(){
            return {
                newValue: [],
            };
        },

        computed: {
            inputName: function(){
                return this.name + '[]';
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
            },
            cleanedNewValue: function(){
                if (this.newValue) {
                    return this.newValue.filter(function(value, index, array) {
                        if (value != '') {
                            return true;
                        }
                        return false;
                    });
                }

                return [];
            }
        },

        destroyed: function () {

        },

        methods: {


        }

    });
</script>