<template>
    <button class="btn btn-sm btn-outline-secondary mr-1" ref="button">
        {{value ? dateFormat(value) : 'Set Due Date'}}
        <input :value="value" type="text" class="form-control d-none"  ref="input" placeholder="">
    </button>
</template>

<script>
  import Pikaday from 'pikaday';
  import 'pikaday/css/pikaday.css';

  export default {
    name: 'datebutton',

    props: {
      value: {required: true},
      options: {default: () => ({})},
    },

    data() {
      return {
        picker: null,
      }
    },

    mounted() {
      this.picker = new Pikaday({
        field: this.$refs.input,
        trigger: this.$refs.button,
        container: this.$parent.$refs.detail,
        format: 'MM/DD/YYYY',
        onSelect: () => {
          this.$emit('input', this.picker.toString());
        },
        onOpen: () => {
          //prevent absolute positioned date picker from scrolling because it references a fixed pos element
          window.addEventListener('scroll', this.close);
        },
        ...this.options,
      });
    },

    methods: {
      close() {
        this.picker.hide();
        window.removeEventListener('scroll', this.close);
      }
    }
  };
</script>

