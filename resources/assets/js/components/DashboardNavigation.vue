<template>
    <div class="dashboard-navigation dashboard-navigation--mobile">
        <button @click="toggle"
                class="dashboard-navigation__toggle">Go To...
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 8 20" height="1.1em" class="toggle-icon">
                <g fill="currentColor" fill-rule="evenodd" transform="translate(-.036)">
                    <polygon points="3.963 0 7.924 8 .002 8"/>
                    <polygon points="3.963 20 .002 12 7.924 12"/>
                </g>
            </svg>
        </button>

        <div ref="dropdown-menu dashboard-navigation__dropdown" v-show="open">
            <slot></slot>
        </div>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        open: false,
        selected: null,
      }
    },

    // Commented for now while we decide if the navigation dropdown will show the active item text or not
    // mounted() {
    //   this.selected = this.$refs['dropdown-menu'].querySelector('.active');
    //   console.log(this.$refs);
    // },

    methods: {
      toggle() {
        this.open = !this.open;
      },
    },
  };
</script>
