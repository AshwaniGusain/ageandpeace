<template>
    <div class="media mb-4 align-items-center">
        <div class="icon-holder mr-3">
            <svg viewBox="0 0 80 80" height="79px" class="svg-progress">

                <path stroke="black" stroke-width="3" fill="transparent"
                      :style="{'stroke-dashoffset': strokeDashoffset}"
                      d=" M 0, 0 m 40, 78 a -37, -37 0 1 1 0, -74 a 37, 37 0 1 1 0, 74 "></path>
            </svg>
            <slot></slot>
        </div>

        <div class="media-body">
            <h2 class="category-progress-title mt-0 mb-0">
                {{ title }}

                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 19 29"><path fill="currentColor" fill-rule="evenodd" d="M14.612 18.912L4.524 28.998.136 24.61l10.087-10.087L.136 4.437 4.524.047l14.477 14.476z"/></svg>
            </h2>
            <a href="#" class="text-inherit category-progress-link">
                {{ linkText }}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 13" class="icon"><path fill-rule="evenodd" d="M14.258 5.852l-.836-.812L9.247.98a.605.605 0 0 0-.835 0l-.57.553a.565.565 0 0 0 0 .812l3.075 2.99c.23.223.151.406-.173.406H.591A.584.584 0 0 0 0 6.315v.993c0 .316.266.575.59.575h10.153c.325 0 .403.182.173.405l-3.074 2.99a.565.565 0 0 0 0 .813l.57.553c.23.224.605.224.835 0l4.176-4.06.836-.813.569-.553a.564.564 0 0 0 0-.811l-.57-.555z"/></svg>
            </a>
        </div>
    </div>
</template>

<script>
  export default {
    props: ['progress', 'title'],

    computed: {
      strokeDashoffset() {
        return 240 - (240 * this.progress);
      },
      linkText() {
        if (this.progress === 1) {
          return 'Review Completed Tasks';
        }
        if (this.progress > 0) {
          return 'Continue';
        }

        return 'Get started';
      }
    }
  };
</script>
