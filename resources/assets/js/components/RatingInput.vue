<template>
    <div class="d-flex w-100 justify-content-around">
        <div v-for="n in 5" class="rating-button" :class="{'active': rating >= n, 'possible': indicator >= n && n > rating }" @click="setRating(n)" @mouseover="indicator = n" @mouseleave="indicator = null">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                      d="M23.961 9.188h-8.804L12.359.316 9.56 9.188H.757l7.507 5.459-3.413 8.873 7.508-5.46 7.507 5.46-3.412-8.873z"/>
            </svg>
        </div>
        <input type="hidden" value="" v-model="rating" name="rating" required>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        rating: null,
        indicator: null,
      }
    },

    methods: {
      setRating(i) {
        this.rating = i;
      }
    }
  };
</script>

<style lang="scss">
    .rating-button {
        svg {
            height: 1.6rem;
        }
        svg path {
            fill: #B4BCC5;
            transition: 0.2s;
        }
        :hover {
            cursor: pointer;
        }
    }
    .rating-button.active {
        svg path {
            fill: #ECA941
        }
    }

    .rating-button.possible {
        svg path {
            fill: #ebecad
        }
    }
</style>

