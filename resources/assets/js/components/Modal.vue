<template>
        <transition name="scale">
            <div class="modal-overlay">
                <div class="clearfix">
                    <slot name="closeButton">
                        <button type="button" class="close text-white" @click.prevent="close">
                            Close
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="icon icon-close">
                                <polygon points="82.484 2.121 80.748 .385 70.869 10.264 60.99 .385 59.254 2.121 69.133 12 59.254 21.879 60.99 23.615 70.869 13.736 80.748 23.615 82.484 21.879 72.605 12" transform="translate(-59)"/>
                            </svg>
                        </button>
                    </slot>
                </div>

                <div class="pt-lg-8 pt-2">
                    <slot></slot>
                </div>
            </div>
        </transition>
</template>

<script>
  export default {
    name: 'modal',
    methods: {
      close() {
        this.$emit('close');
      }
    }
  };
</script>


