    // 'vendor/vue/vue-color',
    // 'vendor/spectrum/spectrum',
    // 'cssplugin!cssvendor/spectrum/spectrum.css',


<script>
//    'vendor/jscolor/jscolor',
    Vue.component('snap-color-input', {
        //template: '<div><input type="text" size="8" value="#CC3300"></div>',
        template: '<input value="ffcc00" class="jscolor">',
        props: {
            'config': {
                type: Object,
                required: false,
                default: function () {
                    return {};
                }
            },
        // props: {
        // 	'color': {
        // 		type: String,
        // 		required: false,
        // 		default: '#000000'
        // 	},
        // 	'flat': {
        // 		type: Boolean,
        // 		required: false,
        // 		default: false
        // 	},
        // 	'showInput': {
        // 		type: Boolean,
        // 		required: false,
        // 		default: true
        // 	},
        // 	'showInitial': {
        // 		type: Boolean,
        // 		required: false,
        // 		default: true
        // 	},
        // 	'allowEmpty': {
        // 		type: Boolean,
        // 		required: false,
        // 		default: true
        // 	},
        // 	'disabled': {
        // 		type: Boolean,
        // 		required: false,
        // 		default: true
        // 	},
        // 	'localStorageKey': {
        // 		type: String,
        // 		required: false,
        // 		default: null
        // 	},
        // 	'showAlpha': {
        // 		type: Boolean,
        // 		required: false,
        // 		default: true
        // 	},
        // 	'showPalette': {
        // 		type: Boolean,
        // 		required: false,
        // 		default: false
        // 	},
        // 	'showPaletteOnly': {
        // 		type: Boolean,
        // 		required: false,
        // 		default: false
        // 	},
        // 	'clickoutFiresChange': {
        // 		type: Boolean,
        // 		required: false,
        // 		default: true
        // 	},
        // 	'cancelText': {
        // 		type: String,
        // 		required: false,
        // 		default: 'Cancel'
        // 	},
        // 	'chooseText': {
        // 		type: String,
        // 		required: false,
        // 		default: 'Choose'
        // 	},
        // 	'togglePaletteMoreText': {
        // 		type: String,
        // 		required: false,
        // 		default: 'More'
        // 	},
        // 	'togglePaletteLessText': {
        // 		type: String,
        // 		required: false,
        // 		default: 'Less'
        // 	},
        // 	'containerClassName': {
        // 		type: String,
        // 		required: false,
        // 		default: null,
        // 	},
        // 	'replacerClassName': {
        // 		type: String,
        // 		required: false,
        // 		default: null,
        // 	},
        // 	'preferredFormat': {
        // 		type: String,
        // 		required: false,
        // 		default: 'hex',
        // 	},
        // 	'maxSelectionSize': {
        // 		type: Number,
        // 		required: false,
        // 		default: null,
        // 	},
        // 	'palette': {
        // 		type: Array,
        // 		required: false,
        // 		default: function(){
        // 			return [];
        // 		}
        // 	},
        // 	'selectionPalette': {
        // 		type: Array,
        // 		required: false,
        // 		default: function(){
        // 			return [];
        // 		}
        // 	}
        // },

        data: function(){
            return {  };
        },

        mounted: function(){
            var self = this;
            new jscolor(this.$el);
            // setTimeout(function(){
            // 	$(self.$el).find('input').spectrum(self.getDefaultOptions()).show();

            // }, 20); // TODO... Need to figure out why setTimeout is needed here for Safari
        },
        methods: {
            getDefaultOptions: function(){
                var o = {};
                for (var n in this.$options.props){
                    o[n] = this[n];
                }
                return o;
            }
        },


    /*console.log(VueColor)

    var defaultProps = {
  hex: '#194d33',
  hsl: {
    h: 150,
    s: 0.5,
    l: 0.2,
    a: 1
  },
  hsv: {
    h: 150,
    s: 0.66,
    v: 0.30,
    a: 1
  },
  rgba: {
    r: 25,
    g: 77,
    b: 51,
    a: 1
  },
  a: 1
}
    var ColorField = Vue.extend({
        // template : '<input type="text" v-model="color"><div class="colorpicker-preview" v-bind:style="{background-color: \'#\' + color}" style="display: inline-block; width: 22px; height: 22px; margin: 0  0 -8px 3px; border: 2px solid #ddd;"></div>',
        template: '<compact-picker></compact-picker>',
        props: defaultProps,

        data: function(){
            return {  };
        },

        methods: {

        },
        components: {
            'material-picker': VueColor.Material,
            'compact-picker': VueColor.Compact,
            'swatches-picker': VueColor.Swatches,
            'slider-picker': VueColor.Slider,
            'sketch-picker': VueColor.Sketch,
            'chrome-picker': VueColor.Chrome,
            'photoshop-picker': VueColor.Photoshop
        },
    });

    Vue.component('color-field', ColorField);*/

});
</script>