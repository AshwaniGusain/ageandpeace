<script>
Vue.component('snap-range-input', {
    template : '<div class="row">\
                    <div class="col-9">\
                        <input :name="name" :id="id" type="range" :max="max" :min="min" v-model="newValue" :step="step" class="form-control" :list="id + \'-list\'"  v-on:change="changed">\
                    </div>\
                    <div class="col-3">\
                        <div class="input-group">\
                            <div class="input-group-prepend" v-if="prefix">\
                                <span class="input-group-text">{{ prefix }}</span>\
                            </div>\
                            <input type="number" :max="max" :min="min" :step="step" v-model="newValue" class="form-control" v-on:change="changed">\
                            <div class="input-group-append" v-if="suffix">\
                                <span class="input-group-text">{{ suffix }}</span>\
                            </div>\
                        </div>\
                    </div>\
                    <datalist :id="id + \'-list\'">\
                        <option v-for="(value, index) in options">{{ value }}</option>\
                    </datalist>\
                </div>',
    inheritAttrs: false,

    props: {
        name: {
            type: String,
            required: true
        },
        value: {
            type: String,
            required: false
        },
        id: {
            type: String,
            required: false
        },
        max: {
            type: Number,
            required: false,
            default: 10
        },
        min: {
            type: Number,
            required: false,
            default: 0
        },
        step: {
            type: Number,
            required: false,
            default: 1
        },
        prefix: {
            type: String,
            required: false,
            default: ''
        },
        suffix: {
            type: String,
            required: false,
            default: ''
        }
    },

    mounted: function(e){
        var self = this;
        this.newValue = this.value;

        for (var i = this.min; i <= this.max; i = i + this.step) {
            this.options.push(i);
        }
    },

    data: function(){
        return {
            className: '',
            options: [],
            newValue: null,
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