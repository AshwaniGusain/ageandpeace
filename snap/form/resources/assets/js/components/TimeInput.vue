<script>
    Vue.component('snap-time-input', {
        template: '<div><input type="text" :id="id" v-model="hh" :placeholder="placeholderHr" size="3" maxlength="2" class="form-control" style="width: auto; display: inline;"> : <input type="text" v-model="mm" :placeholder="placeholderMin" @blur="mm = pad(mm, 2)" size="3" maxlength="2" class="form-control" style="width: auto; display: inline;"><span v-if="displaySeconds"> : <input type="text" v-model="ss" :placeholder="placeholderSec" @blur="ss = pad(ss, 2)" size="3" maxlength="2" class="form-control" style="width: auto; display: inline;"></span><input :name="name" type="hidden" v-model="time"></div>',
        props: {
            'name': {
                type: String,
                // required: true,
                default: ''
            },
            'id': {
                type: String,
                default: ''
            },
            value: {
                type: String,
                required: false
                // default: '00:00:00'
            },
            'placeholderHr': {
                type: String,
                required: false,
                default: 'hh'
            },
            'placeholderMin': {
                type: String,
                required: false,
                default: 'mm'
            },
            'placeholderSec': {
                type: String,
                required: false,
                default: 'ss'
            },
            'displaySeconds': {
                type: Boolean,
                default: false
            }
        },

        mounted: function(){
            if (this.value) {
                var parts = this.value.split(':');
                this.hh = parts[0];
                this.mm = (parts.length > 1) ? parts[1] : '';
                this.ss = (parts.length > 2) ? parts[2] : '';
            }
        },

        data: function(){
            return {
                'hour': '',
                'min': '',
                'sec': ''
            };
        },

        computed: {
            hh: {
                get: function(){
                    return this.hour;
                },
                set: function(newHour){
                    this.hour = newHour;
                }
            },
            mm: {
                get: function(){
                    return this.min;
                },
                set: function(newMin){
                    if (parseInt(newMin) > 0 && parseInt(newMin) < 59) {
                        this.min = newMin;
                    } else {
                        this.min = '00';
                    }
                }
            },
            ss: {
                get: function(){
                    return this.sec;
                },
                set: function(newSec){
                    if (parseInt(newSec) > 0 && parseInt(newSec) < 59) {
                        this.sec = newSec;
                    } else {
                        this.sec = '00';
                    }
                }
            },
            time: function(){
                var time = '';
                time += (this.hour) ? this.pad(this.hour, 2) : '00';
                time += ':';
                time += (this.min) ? this.pad(this.min, 2) : '00';
                time += ':';
                time += (this.sec) ? this.pad(this.sec, 2) : '00';
                return time;
            }
        },

        methods: {
            pad : function(num, size) {
                var s = num+"";
                while (s.length < size) s = "0" + s;
                return s;
            }
        }

    });
</script>