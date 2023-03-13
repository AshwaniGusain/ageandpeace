<template>
    <modal-trigger :start-open="modalOnLoad">
        <div slot="openButton" slot-scope="{show}" >
            <div :class="{'alert alert-danger': !zipIsValid}">

                <span class="small alert-link" v-if="value">
                    <span v-if="!zipIsValid">Invalid zip code {{value}} specified</span>
                    <span v-else>Searching zip code {{value}}</span>
                </span>

                <a href="#" @click.prevent="show" class="tiny-link">Change Zip Code</a>
            </div>
        </div>

        <div class="container" v-cloak>
            <div class="row justify-content-center">
                <div class="col-sm-8 col-lg-6">
                    <div class="card card-modal border-0">
                        <div class="card-body">
                            <div class="text-center">
                                <h3>Zero in on your location</h3>
                                <p>Enter your zip code below so we can connect you with providers in your area.</p>
                            </div>

                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="zip">Zip Code</label>

                                    <input id="zip" type="text" maxlength="5" :value="value" v-on:keyup.enter="$emit('update-zip')" @change="$emit('input', $event.target.value)" class="form-control" name="zip" required>

                                    <span class="invalid-feedback">
                                    <strong></strong>
                                    </span>

                                </div>
                            </div>
                        </div>

                        <div class="card-footer p-0">
                            <button @click="$emit('update-zip')" class="btn btn-arrow btn-block btn-primary">
                                Show me my Providers
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </modal-trigger>
</template>

<script>
  import modalTrigger from './ModalTrigger.vue';

  export default {
    props: ['value', 'valid'],

    components: {modalTrigger},
    mounted() {
      this.zipIsValid = this.valid;
    },

    data() {
      return {
        zipIsValid: false,
        modalOnLoad: false,
      }
    },

    created() {
      if (! this.value) {
        this.modalOnLoad = true;
      }
    },

    methods: {
      //TODO ajax check on zip code validity?
      //TODO limit this field to 5 characters?
      //TODO limit this field to numbers?
      //TODO bigger input size?
    }
  };
</script>
