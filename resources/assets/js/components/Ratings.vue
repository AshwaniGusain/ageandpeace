<template>
    <div>
        <span v-for="i in 5">
            <svg v-if="i - rating == .5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="icon-rating" :class="{lg}">
              <g fill="none" fill-rule="evenodd" transform="translate(.68 -.116)">
                <polygon fill="#ECA941" points="7.695 0 5.839 5.885 0 5.885 4.98 9.506 2.716 15.39 7.695 11.769"/>
                <polygon fill="#B4BCC5" points="7.695 0 7.695 11.769 12.674 15.39 10.411 9.505 15.391 5.885 9.551 5.885"/>
              </g>
            </svg>

            <svg v-else-if="rating < i"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="icon-rating" :class="{lg}">
              <polygon fill="#B4BCC5" fill-rule="evenodd" points="136.302 9.668 127.499 9.668 124.7 .796 121.902 9.668 113.098 9.668 120.606 15.127 117.193 24 124.7 18.54 132.208 24 128.795 15.127" transform="translate(-113.088 -.48)"/>
            </svg>

            <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="icon-rating" :class="{lg}">
                <polygon fill="#ECA941" fill-rule="evenodd" points="108.049 9.668 99.245 9.668 96.447 .796 93.648 9.668 84.845 9.668 92.352 15.127 88.939 24 96.447 18.54 103.954 24 100.542 15.127" transform="translate(-84.088 -.48)"/>
            </svg>
        </span>
    </div>
</template>

<script>
  export default {
    name: 'ratings',
    props: {
      rating: {
        required: true
      },

      lg: {
        default: false
      }
    },
  };
</script>
