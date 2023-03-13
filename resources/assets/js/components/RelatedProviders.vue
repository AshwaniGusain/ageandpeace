<template>
    <div class="pl-5 pl-md-8 pb-8 bg-white">
        <div v-if="providers && providers.length">
            <div class="d-flex justify-content-between align-items-center mb-4 pr-8">
                <h4>Our Featured Providers</h4>
                <div>
                    <div class="button-prev">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30.5 30.5"><g transform="translate(.33 .256)">
                            <circle cx="14.9" cy="15" r="15" fill="currentColor"/>
                            <path fill="#fff" d="M11.4 12.8l5-5 2.2 2.2-5 5 5 5-2.2 2.2L9.2 15z"/></g></svg>
                    </div>
                    <div class="button-next">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 31 31" class="icon">
                            <g fill="none" fill-rule="evenodd" transform="translate(.33 .256)">
                                <circle cx="15.039" cy="15" r="15" fill="currentColor"/>
                                <polygon fill="#FFF" points="13.533 12.818 18.546 7.806 20.727 9.987 15.714 15 20.727 20.013 18.546 22.194 11.351 15" transform="rotate(180 16.04 15)"/>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>


            <swiper :global-options="swiperOptions">
                <swiper-slide v-for="(provider, i) in providers" :key="i">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <a :href="'/providers/' + provider.slug" target="_blank"><img :src="provider.display_image" :alt="provider.name" style="height: 120px;" class="d-block mb-2"></a>
                        </div>

                        <div class="col-12">
                            <ratings :rating="provider.rating"></ratings>
                        </div>

                        <div class="col-12">
                            <h5 class="mb-0"><a :href="'/providers/' + provider.slug" class="text-inherit">{{ provider.name }}</a></h5>
                            <p class="small">{{ provider.description_stripped }}</p>
                        </div>
                    </div>
                </swiper-slide>
            </swiper>
        </div>
    </div>
</template>

<script>
  import ratings from './Ratings.vue';
  import fetcher from './Fetcher.vue';
  import { swiper, swiperSlide } from 'vue-awesome-swiper';

  export default {
    name: 'related-providers',

    props: ['providers'],

    components: {
      fetcher,
      swiper,
      swiperSlide,
      ratings
    },
    mounted(){
        console.log(this.providers)
    },
    data() {
      return {
        swiperOptions: {
          slidesPerView: 'auto',
          navigation: {
            nextEl: '.button-next',
            prevEl: '.button-prev',
          }
        },
      }
    }
  };
</script>

<style scoped>

</style>
