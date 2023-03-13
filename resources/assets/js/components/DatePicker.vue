<template>
    <input :value="value" type="text" class="form-control"  ref="input" placeholder="">
</template>

<script>
  import Pikaday from 'pikaday';
  import 'pikaday/css/pikaday.css';

  export default {
    name: 'datepicker',

    props: {
      value: {required: true},
      options: {default: {}},
    },

    mounted() {
      const picker = new Pikaday({
        field: this.$refs.input,
        onSelect: () => {
          this.$emit('input', picker.toString());
        },
        ...this.options,
      });
    },
  };
</script>

