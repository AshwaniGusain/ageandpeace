<template>
    <li class="nav-item nav-item--accordion" @click="toggle" :class="{'submenu-open': this.open}">
        <slot></slot>
    </li>
</template>

<script>
  import EventBus from "../event-bus";

  export default {
    props: {
      group: {
        type: String,
        required: false
      }
    },

    data() {
      return {
        open: false
      }
    },

    mounted() {
      EventBus.$on('accordion:toggle', (accordion) => {
        if (accordion !== this) {
          this.open = false;
        }
      })
    },

    methods: {
      toggle() {
        this.open = !this.open;
        EventBus.$emit('accordion:toggle', this);
      },
    },
  };
</script>

