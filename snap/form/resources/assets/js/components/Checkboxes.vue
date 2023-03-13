<script>
Vue.component('snap-checkboxes-field', {

    // // template : '<div><input v-model="inputValue" @input=\'$emit("input", inputValue)\' class="form-control"><span>Remaining: {{ remaining }}</span></div>',
    // // model: {
    // // 	prop: 'inputValue',
    // // 	event: 'change',
    // // },
    // props: {
    // 	inputValue: '',
    // 	max: {
    // 		type: Number,
    // 		required: false,
    // 		default: null
    // 	}
    // },

    mounted: function () {
        var $elems = $('.form-check input[type="checkbox"]', this.$el);
        $elems.on('click', function (e) {
            $children = $(this).parent().next('.child');
            if ($children.length) {
                if ($children.css('display') == 'none') {
                    $children.slideDown('fast');
                    $inputs = $children.find('input');
                    if (!$(':checked', $inputs).length) {
                        $inputs.prop('checked', true);
                    }
                } else {
                    $children.slideUp('fast');
                    $children.find('input').prop('checked', false);
                }
            }
        });

        $elems.not(':checked').parent().next('.child').hide();

    },

    data: function () {
        return {
            // value: ''
        };
    }


});
</script>