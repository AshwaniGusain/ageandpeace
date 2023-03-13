<script>
Vue.component('snap-dual-multi-select-input', {
    template: '<div><input type="hidden" :name="name" value=""><div class="container"><div class="row align-items-center">' +
       '<div class="col-5 p-0 border"><div :style="{height: height + \'px\'}"style="overflow: auto;"><ul class="list-group list-group-flush"><li v-for="(option, index) in availableDisplay" @click="add(option.value)" style="cursor: pointer" class="list-group-item" :class="{\'border-bottom\': index !=optionsArr.length -1 }" :key="\'values\' + index">{{ option.label }}</li></ul></div></div>' +
       '<div class="col-2 p-0"><div class="text-center p-1"><button type="button" @click="addAll()" class="btn btn-sm btn-secondary">&rarr;</button></div><div class="text-center p-1"><button type="button" @click="removeAll()" class="btn btn-sm btn-secondary">&larr;</button></div></div>' +
       '<div class="col-5 p-0 border"><div :style="{height: height + \'px\'}" style="overflow: auto;"><ul class="list-group list-group-flush"><li v-for="(option, index) in selectedDisplay" @click="remove(option.value)" style="cursor: pointer" class="list-group-item" :class="{\'border-bottom\': index !=selected.length -1 }" :key="\'selected\' + index">{{ option.label }}</li></ul></div></div>' +
       '</div></div>' +
       //'<input v-for="(value, index) in selected" type="hidden" :name="name" :value="value" :key="\'input\' + index">' +
        '<select class="d-none" :name="name" v-model="selected" multiple ref="selectElem"><option v-for="(option, index) in optionsArr" :value="option.value" :key="\'input\' + index">{{ option.label }}</option></select>' +
       '</div>',
    props: {
       'name': {
           type: String,
           // required: true,
           default: ''
       },
       value: {
           type: Array,
           default() {
               return [];
           },
       },
       options: {
           type: Object,
           default() {
               return {};
           },
       },
       height: {
           type: Number,
           default: 200
       }

    },

    created: function(){
        this.optionsArr = (this.options) ? this.convertFromObject(this.options) : [{value:'',label:''}];
        this.selected = (this.value) ? this.value : [];
    },

    mounted: function(e){

    },

    data: function(){
        return {
            optionsArr: [],
            selected: [],
            mapping: {}

        };
    },

    computed: {

        selectedDisplay: function() {
            let values = [];
            for (let i = 0; i < this.selected.length; i++) {
                let o = this.mapping[this.selected[i]];
                if (o && this.inArray(this.selected, o.value)) {
                    values.push(o)
                }

            }

            return values;
        },

        availableDisplay: function() {
            let options = [];

            for (let i = 0; i < this.optionsArr.length; i++) {
                let o = this.optionsArr[i];
                if (o && !this.inArray(this.selected, o.value)) {
                    options.push(o)
                }
            }

            return options;
        }
    },
    methods: {
        add(value){
           if (this.selected.indexOf(value) === -1) {
               this.selected.push(value);
               //SNAP.event.$emit('snap-select-changed', this);
               // https://stackoverflow.com/questions/49260887/v-model-wont-detect-changes-made-by-jquery-trigger-event
               this.$refs['selectElem'].dispatchEvent(new CustomEvent('input'));
           }
        },

        addAll(){
            this.removeAll();
            for (let i = 0; i < this.optionsArr.length; i++) {
                let o = this.optionsArr[i];
                this.selected.push(o.value);
            }
            //SNAP.event.$emit('snap-select-changed', this);
            this.$refs['selectElem'].dispatchEvent(new CustomEvent('input'));
        },

        remove(value) {
           let index = this.selected.indexOf(value);
           this.selected.splice(index, 1);
           //SNAP.event.$emit('snap-select-changed', this);
            this.$refs['selectElem'].dispatchEvent(new CustomEvent('input'));
        },

        removeAll(value) {
            this.selected = [];
            //SNAP.event.$emit('snap-select-changed', this);
            this.$refs['selectElem'].dispatchEvent(new CustomEvent('input'));
        },

        getValueByIndex(index) {
            return this.optionsArr[index].value;
        },

        convertFromObject(data){
           let obj = [];
           for (let n in data) {
               let o ={'value': n, 'label': data[n]};
               obj.push(o);
               this.mapping[n] = o;
           }

           return obj;
        },

        // Needed because indexOf uses strict comparison and that sometimes is too harsh for the values
        inArray(array, value) {
            for (let i = 0; i < array.length; i++) {
                if (array[i] == value) {
                    return true;
                }
            }
            return false;
        }
    }

});
</script>