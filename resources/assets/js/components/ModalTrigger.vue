<template>
    <div>
        <slot name="openButton" :show="show">
            <button class="btn btn-primary" @click="show">{{buttonText}}</button>
        </slot>

        <portal to="bottom">
            <modal v-if="open" :class="{open: open}" @close="hide">
                <slot></slot>
            </modal>
        </portal>
    </div>
</template>

<script>
    import modal from './Modal.vue';

  export default {
    components: {modal},

    props: {
      buttonText: {
        type: String,
        default: 'Open',
      },

      startOpen: {
        type: Boolean,
        default: null,
      }
    },

    data() {
      return {
        open: this.startOpen ? this.startOpen : false,
      };
    },

    mounted() {
      if (this.open) {
        document.body.addEventListener('keyup', this.onEscape);
      }
    },

    methods: {
      show() {
        this.$emit('shown');
        this.open = true;
        document.body.classList.add('modal-open');
        document.body.addEventListener('keyup', this.onEscape);
      },

      onEscape(e) {
        if (e.keyCode === 27) {
          this.hide();
        }
      },

      hide() {
        this.open = false;
        document.body.classList.remove('modal-open');
        document.body.removeEventListener('keyup', this.onEscape);
      },
    },
  };
</script>

