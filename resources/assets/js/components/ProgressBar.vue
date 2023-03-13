<template>
    <div class="progress">
        <div v-for="bar in bars"
             v-tooltip.top="bar.name + ': ' + (Math.round(bar.progress * 100)) + '%'"
             :class="bar.slug"
             class="progress-bar"
             role="progressbar"
             :style="{width: bar.progress * (100/Object.keys(bars).length) + '%'}"
             aria-valuenow="25"
             aria-valuemin="0"
             aria-valuemax="25"
        ></div>
    </div>
</template>

<script>
  export default {
    props: ['bars'],
  };
</script>
