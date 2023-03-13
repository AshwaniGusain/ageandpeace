<template>
    <transition name="pop-in">
        <div class="display-message d-flex justify-content-center" v-if="active">
            <div class="alert col-10 col-lg-4" :class="'alert-' + type" role="alert">
                {{ message }}
                <button type="button" class="close" aria-label="Close" @click="active = false">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </transition>
</template>

<script>
    //TODO make this component flexible to handle error messaging as well
  export default {
    props: {
      message: {
        type: String,
      },
      type: {
        type: String,
        default: 'success'
      }
    },

    data() {
      return {
        active: false
      }
    },

    mounted() {
      setTimeout(() => {
        this.active = true;

        setTimeout(()=> {
          //this.active = false;
        }, 2500)
      }, 500);
    }
  }
</script>

<style lang="scss" scoped>
    .display-message {
        position: fixed;
        top: 20px;
        width: 100%;
        z-index: 9999;
        transition: 0.3s opacity;
    }
    .pop-in {
        transition: all 0.5s;
    }
    .pop-in-enter,
    .pop-in-leave-to {
        transition: all 0.25s;
        opacity: 0;
        transform: translateY(-10px);
    }

</style>
